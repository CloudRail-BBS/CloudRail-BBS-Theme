<?php
/**
 * Discuz! X5 模板审查器（轻量版）
 * 用法：
 *   E:/dzdz/.tools/php/php.exe -c E:/dzdz/.tools/php/php.ini tools/dz_audit.php
 *
 * 1. 把 Discuz 模板语法编译成原生 PHP
 * 2. 检查 if / loop / eval 标签是否配对
 * 3. 用 php -l 检查编译结果的 PHP 语法
 * 4. 可选 --run：在 stub 环境中试运行编译后的模板，捕获运行时错误
 */

$ROOT = dirname(__DIR__);
$TPL  = $ROOT . '/upload/template/cloudrail';
$OUT  = $ROOT . '/.audit_tmp/compiled';
@mkdir($OUT, 0777, true);

$RUN = in_array('--run', $argv, true);

function compile_template(string $src): string
{
    // 去掉 .php 模板的防注入头（动态试运行必须去掉）
    $s = preg_replace('/^\s*<\?php\s+exit\([^\n]*\)\?>\s*/s', '', $src, 1);

    // <!--{xxx}--> -> {xxx}
    $s = preg_replace('/<!--\{(.+?)\}-->/s', '{\\1}', $s);

    // 先把 {lang ...} 换成占位符，避免它的 } 截断 {eval ...} 的正则
    $langs = [];
    $s = preg_replace_callback('/\{lang\s+(.+?)\}/is', static function ($m) use (&$langs) {
        $i = count($langs);
        $langs[$i] = trim($m[1]);
        return "\x02{$i}\x03";
    }, $s);

    // {eval ...}（内部的 lang 占位还原为字符串字面量）
    $s = preg_replace_callback('/\{eval\s+(.+?)\}/is', static function ($m) use (&$langs) {
        $body = preg_replace_callback('/\x02(\d+)\x03/', static fn($x) => "'lang:" . $langs[(int)$x[1]] . "'", $m[1]);
        return '<?php ' . trim($body) . ' ?>';
    }, $s);

    // {subtemplate} / {template} / {cells} / {hook} -> 占位（避免递归展开）
    $s = preg_replace('/\{(?:sub)?template\s+[a-z0-9_:\/]+\}/is', '<!-- included -->', $s);
    $s = preg_replace('/\{cells\s+[a-zA-Z0-9_\/\s]+\}/', '<!-- cells -->', $s);
    $s = preg_replace('/\{hook\/[^\}]+\}/i', '', $s);
    $s = preg_replace('/\{ad[^\}]*\}/i', '', $s);
    $s = preg_replace('/\{block[^\}]*\}/i', '', $s);

    // 函数型标签
    $s = preg_replace_callback('/\{avatar\((.+?)\)\}/is', static fn($m) => 'dz_avatar(' . $m[1] . ')', $s);
    $s = preg_replace_callback('/\{date\((.+?)\)\}/is', static fn($m) => 'dzdate(' . $m[1] . ')', $s);

    // 先展开 {$var}，否则 {echo dsign({$var})} 中的 } 会截断 echo 正则
    $s = preg_replace('/\{(\$[a-zA-Z_\x7f-\xff][^\}]*?)\}/s', '<?php echo \\1 ?>', $s);
    $s = preg_replace_callback('/\{echo\s+(.+?)\}/is', static function ($m) {
        // {echo dsign({$a}.{$b})} 内层已被展开成 PHP echo 块，这里收回为表达式
        $body = preg_replace('/<\?php\s+echo\s+(.+?)\s*\?>/s', '($1)', $m[1]);
        return '<?php echo ' . trim($body) . ' ?>';
    }, $s);
    $s = preg_replace('/\{FORMHASH\}/', 'FORMHASH', $s);
    $s = preg_replace('/\{STATICURL\}/', 'STATICURL', $s);

    // 控制结构
    $s = preg_replace_callback('/\{if\s+(.+?)\}/is', static fn($m) => '<?php if(' . $m[1] . ') { ?>', $s);
    $s = preg_replace_callback('/\{elseif\s+(.+?)\}/is', static fn($m) => '<?php } elseif(' . $m[1] . ') { ?>', $s);
    $s = preg_replace('/\{else\}/i', '<?php } else { ?>', $s);
    $s = preg_replace('/\{\/if\}/i', '<?php } ?>', $s);
    $s = preg_replace_callback('/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}/is', static function ($m) {
        return '<?php foreach((' . $m[1] . ') as ' . $m[2] . ' => ' . $m[3] . ') { ?>';
    }, $s);
    $s = preg_replace_callback('/\{loop\s+(\S+)\s+(\S+)\}/is', static fn($m) => '<?php foreach((' . $m[1] . ') as ' . $m[2] . ') { ?>', $s);
    $s = preg_replace('/\{\/loop\}/i', '<?php } ?>', $s);

    // 无引号数组下标 $space[uid] -> $space['uid']（只处理纯标识符下标，避免破坏已有引号）
    $s = preg_replace_callback('/\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)((?:\[[a-zA-Z_][a-zA-Z0-9_]*\])+)/', static function ($m) {
        $idx = preg_replace('/\[([a-zA-Z_][a-zA-Z0-9_]*)\]/', "['\\1']", $m[2]);
        return '$' . $m[1] . $idx;
    }, $s);

    // 还原 HTML 文本中的 lang 占位
    $s = preg_replace_callback('/\x02(\d+)\x03/', static fn($m) => 'lang:' . $langs[(int)$m[1]], $s);

    return $s;
}

function check_tags(string $src): array
{
    $errors = [];
    $src = preg_replace('/<!--\{(.+?)\}-->/s', '{\\1}', $src);
    preg_match_all('/\{(\/?)(if|loop|eval)\b([^\}]*)\}/is', $src, $m, PREG_SET_ORDER);
    $stack = [];
    foreach ($m as $t) {
        $tag = strtolower($t[2]);
        if ($t[1] === '/') {
            if (!$stack) {
                $errors[] = "多余的 {/$tag}";
            } elseif (array_pop($stack) !== $tag) {
                $errors[] = "闭合标签 {/$tag} 与栈顶不匹配";
            }
        } elseif ($tag !== 'eval') {
            $stack[] = $tag;
        }
    }
    if ($stack) {
        $errors[] = '未闭合: {' . implode(', {', $stack) . '}';
    }
    return $errors;
}

$php = 'E:/dzdz/.tools/php/php.exe';
$ini = 'E:/dzdz/.tools/php/php.ini';
$files = [];
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($TPL, FilesystemIterator::SKIP_DOTS));
foreach ($it as $f) {
    if ($f->isFile() && in_array(strtolower($f->getExtension()), ['php', 'htm'], true)) {
        $files[] = $f->getPathname();
    }
}
sort($files);

$total = count($files);
$tagErrors = 0;
$syntaxErrors = 0;
$runErrors = 0;
$compiled = [];

echo "审查模板数: $total\n\n";

foreach ($files as $file) {
    $rel = substr($file, strlen($TPL) + 1);
    $src = file_get_contents($file);

    foreach (check_tags($src) as $e) {
        echo "  [标签] $rel: $e\n";
        $tagErrors++;
    }

    $code = compile_template($src);
    $out = $OUT . '/' . str_replace(['/', '\\'], '_', $rel) . '.php';
    file_put_contents($out, $code);
    $compiled[$rel] = $out;

    $cmd = escapeshellarg($php) . ' -c ' . escapeshellarg($ini) . ' -l ' . escapeshellarg($out) . ' 2>&1';
    $lines = [];
    exec($cmd, $lines, $rc);
    if ($rc !== 0) {
        echo "  [语法] $rel: " . implode(' ', $lines) . "\n";
        $syntaxErrors++;
    }
}

echo "\n标签错误: $tagErrors, 语法错误: $syntaxErrors\n";

if ($RUN) {
    echo "\n--- 试运行（stub 环境）---\n";
    define('FORMHASH', 'stubformhash');
    define('STATICURL', 'static/');
    function dz_avatar($uid, $size = 'middle') { return '<img class="avatar" alt="avatar" />'; }
    function dzdate($t, $f = 'Y-m-d H:i') { return date($f, is_numeric($t) ? (int)$t : 0); }
    function showTip() {}
    function showMenu() {}
    function showWindow() {}
    function getcurrentnav() { return ''; }
    function checkperm($p) { return true; }
    function strexists($h, $n) { return strpos($h, $n) !== false; }
    function helper_access_stub() { return true; }
    class helper_access { public static function check_module($m) { return true; } }
    $space = [
        'uid' => 2, 'username' => '测试用户', 'self' => false, 'views' => 1234,
        'email' => 'test@example.com', 'emailstatus' => 1, 'spacenote' => '这个人很懒',
        'customstatus' => '在线摸鱼', 'sightml' => '这是我的签名',
        'domain' => 'demo', 'group' => ['grouptitle' => '会员', 'color' => '#2563EB', 'maxsigsize' => 200, 'icon' => ''],
        'admingroup' => ['grouptitle' => '管理员', 'color' => '#C9302C', 'icon' => ''],
        'adminid' => 1, 'regdate' => '2026-01-01', 'lastvisit' => '2026-11-27 10:00',
        'regip' => '127.0.0.1', 'regport' => '80', 'regip_loc' => '本地', 'lastip' => '127.0.0.1',
        'port' => '80', 'lastip_loc' => '本地', 'oltime' => 100, 'timeoffset' => 8,
        'credits' => 888, 'attachsize' => '1.2 MB', 'threads' => 12, 'posts' => 34,
        'friends' => 5, 'doing' => 3, 'doings' => 3, 'blogs' => 2, 'albums' => 1,
        'sharings' => 4, 'extcredits1' => 10, 'extcredits2' => 20,
    ];
    $space['following'] = 6; $space['follower'] = 7;
    $_G = [
        'uid' => 1, 'adminid' => 0, 'ols' => [2 => 1], 'setting' => [
            'bbname' => '云轨社区', 'homepagestyle' => 0, 'sessionclose' => 0,
            'allowspacedomain' => 1, 'domain' => ['root' => ['home' => 'test.com']],
            'allowviewuserthread' => true, 'verify' => ['enabled' => 0],
            'extcredits' => [1 => ['title' => '威望', 'unit' => ''], 2 => ['title' => '金钱', 'unit' => '元']],
        ],
        'group' => ['allowviewip' => 1], 'lang' => [], 'member' => ['username' => '访客'],
    ];
    $profiles = [
        ['title' => '性别', 'value' => '男'], ['title' => '生日', 'value' => '2000-01-01'],
        ['title' => '所在地', 'value' => '北京'], ['title' => 'QQ', 'value' => '123456'],
    ];
    $upgradecredit = false;
    $clist = [];
    $count = 0;
    $groupcount = 0;
    $manage_forum = [];
    $usergrouplist = [];
    $do = 'profile';
    $uid = 2;
    define('CURMODULE', 'space');

    error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);
    set_error_handler(static function ($no, $str, $file, $line) {
        throw new ErrorException($str, 0, $no, $file, $line);
    });

    foreach (['home/space_profile.php', 'home/space_profile_body.php'] as $rel) {
        if (!isset($compiled[$rel])) { continue; }
        try {
            ob_start();
            include $compiled[$rel];
            $html = ob_get_clean();
            echo "  [通过] $rel (" . strlen($html) . " bytes)\n";
        } catch (Throwable $e) {
            ob_end_clean();
            echo "  [运行错误] $rel: " . $e->getMessage() . ' @ ' . basename($e->getFile()) . ':' . $e->getLine() . "\n";
            $runErrors++;
        }
    }
    restore_error_handler();
    echo "运行错误: $runErrors\n";
}
