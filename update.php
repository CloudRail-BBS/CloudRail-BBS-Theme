<?php
/**
 * CloudRail BBS Theme 在线更新器
 *
 * 用法：把本文件放到 Discuz! 站点根目录，浏览器访问
 *   https://你的域名/cloudrail-update.php?key=你的密钥
 *
 * 安全：必须先把下面的 ACCESS_KEY 改成你自己的随机串，
 *       否则任何人都能触发更新。用完建议删掉本文件。
 */

declare(strict_types=1);

const ACCESS_KEY   = 'change-me-to-a-random-string';
const REPO         = 'CloudRail-BBS/CloudRail-BBS-Theme';
const BRANCH       = 'main';
const TARGET_DIR   = 'template/cloudrail';   // 相对于站点根目录
const VERSION_FILE = 'template/cloudrail/VERSION';

header('Content-Type: text/html; charset=utf-8');

if (!isset($_GET['key']) || !hash_equals(ACCESS_KEY, (string)$_GET['key'])) {
    http_response_code(403);
    exit('403 Forbidden：请在脚本里设置 ACCESS_KEY 后用 ?key= 访问。');
}

if (!class_exists('ZipArchive')) {
    exit('缺少 php-zip 扩展，无法在线更新。请改用整包覆盖。');
}

$root      = __DIR__;
$targetAbs = $root . '/' . TARGET_DIR;
$rawBase   = 'https://raw.githubusercontent.com/' . REPO . '/' . BRANCH;
$zipUrl    = 'https://github.com/' . REPO . '/archive/refs/heads/' . BRANCH . '.zip';

function fetch(string $url): ?string
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_USERAGENT      => 'CloudRail-Theme-Updater',
        ]);
        $body = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($body !== false && $code === 200) ? $body : null;
    }
    $ctx = stream_context_create(['http' => ['timeout' => 30, 'user_agent' => 'CloudRail-Theme-Updater']]);
    $body = @file_get_contents($url, false, $ctx);
    return $body === false ? null : $body;
}

function rrmdir(string $dir): void
{
    if (!is_dir($dir)) return;
    foreach (scandir($dir) ?: [] as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = $dir . '/' . $item;
        is_dir($path) ? rrmdir($path) : @unlink($path);
    }
    @rmdir($dir);
}

function rcopy(string $src, string $dst): int
{
    $n = 0;
    if (!is_dir($src)) return 0;
    if (!is_dir($dst)) @mkdir($dst, 0755, true);
    foreach (scandir($src) ?: [] as $item) {
        if ($item === '.' || $item === '..') continue;
        $s = $src . '/' . $item;
        $d = $dst . '/' . $item;
        if (is_dir($s)) {
            $n += rcopy($s, $d);
        } else {
            if (@copy($s, $d)) $n++;
        }
    }
    return $n;
}

$versionPath  = $root . '/' . VERSION_FILE;
$localVersion = is_file($versionPath) ? trim((string)file_get_contents($versionPath)) : '0.0.0';
$remoteJson   = fetch($rawBase . '/version.json');
$remote       = $remoteJson ? json_decode($remoteJson, true) : null;

echo '<!doctype html><meta charset="utf-8"><title>CloudRail 主题更新</title>';
echo '<style>body{font:14px/1.7 -apple-system,"PingFang SC","Microsoft YaHei",sans-serif;max-width:720px;margin:40px auto;padding:0 20px;color:#0f172a;background:#f6f8fb}'
   . 'h1{font-size:20px}code{background:#e8f0fe;padding:2px 6px;border-radius:4px;color:#1d4ed8}'
   . '.box{background:#fff;border:1px solid #e5eaf1;border-radius:12px;padding:18px 20px;margin:16px 0}'
   . 'a.btn{display:inline-block;background:#2563eb;color:#fff;padding:10px 18px;border-radius:10px;text-decoration:none;font-weight:600}'
   . '.ok{color:#1b7a45}.warn{color:#c9302c}</style>';
echo '<h1>CloudRail BBS Theme · 在线更新</h1>';

if (!$remote) {
    echo '<div class="box warn">无法读取远端 <code>version.json</code>，请检查服务器能否访问 GitHub（可能需要代理）。</div>';
    exit;
}

$latest = (string)($remote['version'] ?? '0');
echo '<div class="box">';
echo '本地版本：<code>' . htmlspecialchars($localVersion) . '</code><br>';
echo '远端版本：<code>' . htmlspecialchars($latest) . '</code><br>';
echo '发布时间：' . htmlspecialchars((string)($remote['released'] ?? '-'));
echo '</div>';

if (version_compare($latest, $localVersion, '<=') && empty($_GET['force'])) {
    echo '<div class="box ok">已是最新版本，无需更新。</div>';
    echo '<p><a href="?key=' . urlencode((string)$_GET['key']) . '&force=1">强制重新拉取</a></p>';
    exit;
}

if (empty($_GET['confirm'])) {
    echo '<p>将下载 <code>' . htmlspecialchars($zipUrl) . '</code> 并覆盖到 <code>' . TARGET_DIR . '</code>。</p>';
    echo '<p>覆盖前会备份到 <code>template/cloudrail.bak</code>。</p>';
    echo '<p><a class="btn" href="?key=' . urlencode((string)$_GET['key']) . '&confirm=1">开始更新到 ' . htmlspecialchars($latest) . '</a></p>';
    exit;
}

$tmpZip = $root . '/data/cloudrail-theme.zip';
$tmpDir = $root . '/data/cloudrail-theme-tmp';

$zip = fetch($zipUrl);
if (!$zip) {
    echo '<div class="box warn">下载失败，服务器可能无法访问 GitHub。</div>';
    exit;
}
file_put_contents($tmpZip, $zip);

rrmdir($tmpDir);
@mkdir($tmpDir, 0755, true);

$za = new ZipArchive();
if ($za->open($tmpZip) !== true) {
    echo '<div class="box warn">压缩包解压失败。</div>';
    exit;
}
$za->extractTo($tmpDir);
$za->close();

$inner = $tmpDir . '/CloudRail-BBS-Theme-' . BRANCH . '/' . $remote['tplDir'];
if (!is_dir($inner)) {
    echo '<div class="box warn">压缩包结构不符，未找到 <code>' . htmlspecialchars((string)$remote['tplDir']) . '</code>。</div>';
    exit;
}

$backup = $root . '/template/cloudrail.bak';
rrmdir($backup);
if (is_dir($targetAbs)) {
    rcopy($targetAbs, $backup);
}

$count = rcopy($inner, $targetAbs);
@unlink($tmpZip);
rrmdir($tmpDir);

echo '<div class="box ok">更新完成：写入 ' . $count . ' 个文件，当前版本 <code>' . htmlspecialchars($latest) . '</code>。</div>';
echo '<p>旧版本已备份到 <code>template/cloudrail.bak</code>，确认无误后可删除。</p>';
echo '<p><strong>记得回后台「工具 → 更新缓存」勾选 CSS 缓存与模板缓存。</strong></p>';
