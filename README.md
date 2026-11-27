# Mint · Discuz! X5.0 主题

一套面向 Discuz! X5.0（MitFrame 架构）的浅色/深色双主题模板套系。视觉参考 klpbbs.com：低饱和中性底色、蓝白主色、细描边卡片、克制的阴影与圆角。

## 目录结构

```
upload/                              → 直接覆盖到 Discuz! 根目录
└── template/
    └── cloudrail/                        模板套系目录（唯一标识符 cloudrail）
        ├── discuz_style_cloudrail.json   风格配置文件（后台导入用）
        ├── common/
        │   ├── header.php           PC 全局头部（侧栏 + 顶栏）
        │   ├── footer.php           PC 全局尾部（右栏 + 页脚）
        │   ├── mint_init.htm        主题初始化（防闪、读写本地偏好）
        │   ├── mint_js.htm          主题切换 / Ctrl K / 悬浮名片
        │   ├── header_diy.php       前台 DIY 工具条（含配色覆盖）
        │   ├── header_diynav.php    DIY 入口按钮
        │   ├── extend_common.css    设计系统（令牌 + 骨架 + 组件）
        │   └── extend_module.css    模块层（版块/列表/帖子/登录/弹窗/DIY/表单）
        ├── forum/
        │   ├── discuz.php           论坛首页
        │   ├── forumdisplay.php     版块列表
        │   ├── viewthread.php       帖子详情
        │   └── post.php             发帖页
        ├── member/
        │   ├── login.php            登录
        │   ├── register.php         注册
        │   └── getpasswd.php        找回 / 重置密码
        ├── search/
        │   ├── header.php           改为复用 Mint 骨架
        │   ├── footer.php           同上
        │   └── forum.php            搜索结果页
        ├── portal/
        │   └── index.php            门户首页（DIY 容器）
        ├── home/
        │   └── space_index.php      个人空间首页
        ├── cells/
        │   └── common/
        │       └── mintrail.htm     右侧栏细胞模板（可后台 DIY）
        ├── touch/
        │   └── common/
        │       ├── header.php       手机端骨架（Mint 顶栏 + 抽屉侧栏）
        │       ├── footer.php       手机端底栏 + 抽屉交互
        │       └── extend_common.css 手机端样式层
        └── i18n/
            └── SC_UTF8/
                └── lang_template.php 模板语言包扩展
```

## 安装

### 第 1 步：放文件

解压后只看 `upload/` 这一层，**不要把 `upload` 整个文件夹传上去**，取它里面的 `template/cloudrail`：

```
把  upload/template/cloudrail/    放成    你的站点根目录/template/cloudrail/
```

传完确认这几个路径存在：

```
/template/cloudrail/discuz_style_cloudrail.json
/template/cloudrail/discuz_style_cloudrail.xml
/template/cloudrail/common/header.php
/template/cloudrail/common/extend_common.css
```

`preview/` 和 `README.md` 是给你看的，不需要上传。

### 第 2 步：导入风格

后台 → **界面 → 风格管理** → 顶部「导入」。

| 情况 | 用哪个文件 |
|---|---|
| 后台接受 `.json` | `discuz_style_cloudrail.json` |
| 只认 `.xml`（多数 X3.5 升级上来的站） | `discuz_style_cloudrail.xml` |

两个文件内容等价，任选其一，不用都导。

### 第 3 步：挂上模板套系

导入成功后，风格列表里会出现「CloudRail BBS」。点「编辑」：

- **匹配模板** → 选 `CloudRail BBS Theme`（目录 `./template/cloudrail`）
- 如果没有这个选项，说明第 1 步目录放错了，回上一步检查

保存。

### 第 4 步：启用

- 想全站生效：把「CloudRail BBS」设为**默认风格**
- 想自己先看：右上角切换风格，或访问 `?styleid=新风格ID`

### 第 5 步：刷新缓存

后台 → **工具 → 更新缓存**，勾选这两项：

- CSS 缓存
- 模板缓存

不刷这两项，页面会加载旧的 `data/cache/style_x_common.css`，看起来像没生效。

### 开发期小技巧

在 `config/config_global.php` 末尾加一行：

```php
$_config['plugindeveloper'] = 1;
```

改模板后刷新即可看到，不用反复清缓存。改完 CSS 仍建议清一次 CSS 缓存。

### 装完自检

| 检查项 | 正常表现 |
|---|---|
| 首页 | 左侧竖排导航 + 顶部搜索 + 右侧栏，不是默认的蓝白横导航 |
| 帖子页 | 作者信息在标题下方横排（头像 + 名字 + 时间），不是左侧竖栏 |
| 手机端 | 顶部有返回 / 标题 / 菜单，点菜单滑出抽屉 |
| 深色 | 顶栏右侧太阳图标可切换，刷新后保持 |

如果帖子页作者还是竖排，说明 `mint_js.htm` 没加载，看控制台报错。

### 常见问题

**导入报「风格文件格式错误」** → 用了 `.json` 但后台只认 XML，换 `discuz_style_cloudrail.xml`。

**页面样式全乱** → 多半是 CSS 缓存没清，或 `data/cache/` 不可写（`chmod 777 data/cache`）。

**风格列表里没有「CloudRail BBS Theme」可匹配** → `template/cloudrail` 放错层级，实际成了 `/template/template/cloudrail`。

**手机端顶部出现两条** → 页面自带的 `.header` 没被隐藏，说明该页模板用了别的类名，把页面地址发我。

## 在线更新

主题自带版本清单与更新器，三种方式任选。

### 方式一：网页更新器

1. 把仓库根目录的 `update.php` 上传到**站点根目录**，可重命名为 `cloudrail-update.php`
2. 打开该文件，把顶部 `ACCESS_KEY` 改成你自己的随机字符串
3. 访问 `https://你的域名/cloudrail-update.php?key=你的密钥`
4. 页面会显示本地版本与远端版本，点「开始更新」即可
5. 更新完回后台「工具 → 更新缓存」，勾 CSS 缓存与模板缓存

覆盖前会自动备份到 `template/cloudrail.bak`。这个文件等于一个写入口，用完建议删除或改名。

### 方式二：整包覆盖

下载 `https://github.com/CloudRail-BBS/CloudRail-BBS-Theme/archive/refs/heads/main.zip`，
解压后把 `upload/template/cloudrail` 覆盖到站点同名目录即可。

### 方式三：git

站点目录本身是 git 仓库时，直接 `git pull`。

### 版本号怎么改

两个地方必须同步：

| 文件 | 字段 |
|---|---|
| `version.json` | `version`、`released`、`changelog` |
| `upload/template/cloudrail/VERSION` | 纯版本号，如 `1.0.1` |

更新器比对的是 `version.json` 的 `version` 与本地 `VERSION` 文件，不一致且远端更高时才会更新。

## 实现要点

**不做整套复制。** Discuz 的模板查找是回退式的：套系目录里缺哪个文件，就用 `template/default/` 的同名文件。主题只覆盖真正需要改的部分，默认模板升级时不会冲突。

**CSS 走双文件追加机制。** 生成样式缓存时，Discuz 读取 `default/common/*.css`，若套系有同名文件则替换，并**自动追加** `extend_同名.css`：

- `extend_common.css` 追加在 `common.css` 之后 → 全局观感
- `extend_module.css` 追加在 `module.css` 之后 → 版块 / 帖子 / 登录 / 弹窗 / DIY / 表单

不动 Discuz 原生 CSS，升级不会被覆盖。

**版块与帖子列表用纯 CSS 重排。** `table.fl_tb`、`#threadlisttableid` 通过 flex/grid 重排为卡片流，未改写列表模板本身。置顶、精华、排序、权限、AJAX 删除全部保留原生行为。

**帖子详情：单栏大卡片 + 作者横排。** Discuz 默认把作者信息拆在 `td.pls`（头像/统计）和 `td.plc` 的 `.pi`（名字/时间/楼层）两处，纯 CSS 合不成一行。做法分两步：

1. `mint_js.htm` 里做一次 DOM 内联 —— 把 `td.pls` 里的 `.favatar` 移进 `.pi` 开头，然后隐藏 `td.pls`；
2. `.mint-flat` 把 `table.plhin` 整条表格改成 block 流，`.pi` 用 flex 横排，头像 42px + 双环。

移动的是整个 `#favatar{pid}` 容器，ID 不变，Discuz 的悬浮卡与 AJAX 引用仍然有效。JS 未执行时会退化成「96px 窄头像栏」，不会错版。

**登录页只加容器类。** 覆盖 `member/login.php` 后，仅在 `#ct` 上追加 `mint-auth` 类，DOM 结构一行未动。表单字段、`formhash`、`loginhash`、AJAX 提交、验证码、安全提问、第三方登录 hook 全部原封不动，视觉全靠 CSS 重排（`label` 上置、输入框 46px、`tipcol` 绝对定位到右上角）。

**AJAX 弹窗统一。** `.f_c`、`.f_win`、`[id^="fwin_"]`、`.flb`、`.flbc`、`.mask` 全部改为圆角卡片 + 磨砂遮罩，弹窗内 `.rfm` 同步 label 上置布局。

**DIY 只做配色适配。** 工具条、可编辑区域虚线、框架占位、DIY 弹窗改为主题配色与圆角，**不改任何布局与尺寸** —— DIY 是拖拽操作，改尺寸会直接影响可用性。

**手机端重构了骨架。** 覆盖 `touch/common/header.php`：

- 注入统一顶栏 `#mint-m-top`：返回 / 页面标题（取 `$navtitle`）/ 抽屉按钮，毛玻璃吸顶
- 隐藏页面自带 `.header`，避免双层顶栏
- 注入抽屉侧栏 `#mint-m-drawer`：品牌区 + 全站导航 + 登录态区，82% 宽、滑入动画、遮罩、Esc 关闭
- `footer.php` 保留原生 `#mfoot` 底栏（基于 `$_G['setting']['mnavs']`），仅换配色

**深浅色由令牌驱动。** 颜色集中在 `:root` 的 `--mint-*`，`html[data-mint-theme="dark"]` 整组替换。偏好存 `localStorage`（键 `mint_theme`，PC 与手机端共用），`mint_init.htm` 在 `<head>` 里抢先设属性避免首屏闪白。

**头像用 X5 新接口。** `<img class="_avt" data-uid="..." data-size="middle" />`，不依赖 `avatar()` 与 UCenter，头像不存在也不裂图。

## 自定义

整套配色集中在 `extend_common.css` 的 `:root`：`--mint-brand`（主蓝）、`--mint-brand-ink`（深蓝）、`--mint-brand-soft`（浅蓝底）、`--mint-bg`、`--mint-surface`、`--mint-text-*`、`--mint-line`。换肤只改这一组值即可，所有组件跟着走。风格变量里也预留了 `mint_brand`、`mint_bg`、`mint_radius`，可在后台风格编辑页直接改。

## 预览

`preview/` 下是四页高保真静态预览，共用同一套设计令牌：

| 文件 | 内容 |
|---|---|
| `index.html` | 首页：侧栏 + 顶栏 + 卡片流 + 右栏统计 / 在线 / 排行 / 悬停名片 |
| `forum.html` | 版块页：版块头 + 子版块 + 筛选条 + 行式主题列表 + 分页 |
| `thread.html` | 帖子详情：标题区 + 作者栏 + 正文 + 楼层回复 + 快速回复编辑器 |
| `login.html` | 登录页：居中大卡片 + 表单 + 扫码栏 |

操作方式：

- 右下角控制条切换页面、浅色 / 深色
- 首页点「手机」进入移动端演示，或访问 `index.html#phone`
- 移动端演示里点左上角菜单图标，可看抽屉侧栏
- 右栏「悬停名片示例」处悬停可看用户悬浮卡
- `Ctrl / Cmd + K` 聚焦搜索框

## 环境要求

- Discuz! X5.0 及以上（`version` 字段为 `X5.0`，按 X5 规则即代表 X5.0+）
- PHP 8.0+
- 现代浏览器；深色模式用到 `color-mix()` 与 `backdrop-filter`，老浏览器会退化为纯色底

## 已知边界

- 手机端隐藏了页面自带 `.header`，改由 Mint 顶栏统一承担返回与标题；若某个页面依赖原 header 内的特有按钮（如版块页的筛选），需要在该页模板里单独补回
- DIY 面板只改配色，不改布局 —— 拖拽交互优先于视觉一致
- 注册、找回密码已覆盖模板并复用登录页同一套卡片表单，但字段间的说明文案位置仍由原生结构决定
- 门户首页与个人空间首页是 DIY 容器，只统一了外层间距与区块圆角，不干预站长自己搭的布局
- 搜索页的 `header/footer` 直接改成复用 common 骨架，原搜索页自带的热点推荐区块因此不再显示
- 后台管理模板不在主题范围内
- `preview.jpg` 未附带，后台风格列表会显示占位图；放一张 200×150 截图到 `template/cloudrail/` 即可
