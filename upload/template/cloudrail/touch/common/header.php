<?php exit('Access Denied');?>
<!--{subtemplate common/header_common}-->
{cells common/header/css}
{cells common/header/js}
<style>
	body {
		--dz-BG-body:{if $_G['style']['touch_style_bodybg']}$_G['style']['touch_style_bodybg']{else}#EEEEEE{/if};
		--dz-BG-color:{if $_G['style']['touch_style_color']}$_G['style']['touch_style_color']{else}#2B7ACD{/if};
		--dz-BG-0:{if $_G['style']['touch_style_bg0']}$_G['style']['touch_style_bg0']{else}#FFFFFF{/if};
		--dz-BG-1:{if $_G['style']['touch_style_bg1']}$_G['style']['touch_style_bg1']{else}#333333{/if};
		--dz-BG-2:{if $_G['style']['touch_style_bg2']}$_G['style']['touch_style_bg2']{else}#FF5656{/if};
		--dz-BG-3:{if $_G['style']['touch_style_bg3']}$_G['style']['touch_style_bg3']{else}#FF9C00{/if};
		--dz-BG-4:{if $_G['style']['touch_style_bg4']}$_G['style']['touch_style_bg4']{else}#B3CC0D{/if};
		--dz-BG-5:{if $_G['style']['touch_style_bg5']}$_G['style']['touch_style_bg5']{else}#F3F3F3{/if};
		--dz-BG-6:{if $_G['style']['touch_style_bg6']}$_G['style']['touch_style_bg6']{else}#CCCCCC{/if};
		--dz-BG-n:{if $_G['style']['touch_style_bgn']}$_G['style']['touch_style_bgn']{else}#A0C8EA{/if};
		--dz-FC-color:{if $_G['style']['touch_style_color']}$_G['style']['touch_style_color']{else}#2B7ACD{/if};
		--dz-FC-fff:{if $_G['style']['touch_style_tfff']}$_G['style']['touch_style_tfff']{else}#FFFFFF{/if};
		--dz-FC-333:{if $_G['style']['touch_style_t333']}$_G['style']['touch_style_t333']{else}#333333{/if};
		--dz-FC-666:{if $_G['style']['touch_style_t666']}$_G['style']['touch_style_t666']{else}#666666{/if};
		--dz-FC-777:{if $_G['style']['touch_style_t777']}$_G['style']['touch_style_t777']{else}#777777{/if};
		--dz-FC-888:{if $_G['style']['touch_style_t888']}$_G['style']['touch_style_t888']{else}#888888{/if};
		--dz-FC-999:{if $_G['style']['touch_style_t999']}$_G['style']['touch_style_t999']{else}#999999{/if};
		--dz-FC-aaa:{if $_G['style']['touch_style_taaa']}$_G['style']['touch_style_taaa']{else}#AAAAAA{/if};
		--dz-FC-bbb:{if $_G['style']['touch_style_tbbb']}$_G['style']['touch_style_tbbb']{else}#BBBBBB{/if};
		--dz-FC-ccc:{if $_G['style']['touch_style_tccc']}$_G['style']['touch_style_tccc']{else}#CCCCCC{/if};
		--dz-FC-ddd:{if $_G['style']['touch_style_tddd']}$_G['style']['touch_style_tddd']{else}#DDDDDD{/if};
		--dz-FC-nnn:{if $_G['style']['touch_style_tnnn']}$_G['style']['touch_style_tnnn']{else}#7DA0CC{/if};
		--dz-FC-light:{if $_G['style']['touch_style_tlight']}$_G['style']['touch_style_tlight']{else}#FF9C00{/if};
		--dz-FC-a:{if $_G['style']['touch_style_ta']}$_G['style']['touch_style_ta']{else}#F26C4F{/if};
		--dz-FC-v:{if $_G['style']['touch_style_tv']}$_G['style']['touch_style_tv']{else}#7CBE00{/if};		
		--dz-FC-t:transparent;		
		--dz-BOR-ed:{if $_G['style']['touch_style_border']}$_G['style']['touch_style_border']{else}#EDEDED{/if};
	}
	<!--{if $_GET['diy'] != 'yes' && $_GET['mobilediy'] != 'yes'}-->.discuz_diy .area {background:none;min-height:0}<!--{/if}-->
	<!--{if $_G['style']['touch_style_addcss']}-->$_G['style']['touch_style_addcss']<!--{/if}-->
</style>
</head>
<body id="{$_G['basescript']}" class="pg_{CURMODULE} discuz_diy mint-m">
<!--{hook/global_header_mobile}-->
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>

<!-- Mint 手机端顶栏 -->
<header id="mint-m-top">
	<button type="button" class="mint-m-btn" id="mint-m-back" aria-label="{lang back}">
		<svg viewBox="0 0 24 24"><path d="m14.5 5-7 7 7 7"/></svg>
	</button>
	<div class="mint-m-title">
		<!--{if !empty($navtitle)}-->$navtitle<!--{else}-->{$_G['setting']['bbname']}<!--{/if}-->
	</div>
	<button type="button" class="mint-m-btn" id="mint-m-open" aria-label="{lang my_nav}">
		<svg viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h10"/></svg>
	</button>
</header>

<!-- Mint 手机端：抽屉侧栏 -->
<div id="mint-m-scrim"></div>

<aside id="mint-m-drawer" aria-hidden="true">
	<div class="mint-m-dr-head">
		<span class="mint-m-dr-logo">{$_G['style']['boardlogo']}</span>
		<span class="mint-m-dr-name">{$_G['setting']['bbname']}</span>
	</div>

	<nav class="mint-m-dr-nav">
		<a href="index.php" class="mint-m-dr-item">
			<svg viewBox="0 0 24 24"><path d="M3.5 10.5 12 3.5l8.5 7"/><path d="M5.5 9.5v10h13v-10"/></svg>
			<span>{lang home}</span>
		</a>
		<a href="forum.php" class="mint-m-dr-item">
			<svg viewBox="0 0 24 24"><path d="M4 5.5h7v13H4z"/><path d="M13 5.5h7v13h-7z"/></svg>
			<span>{lang forum}</span>
		</a>
		<!--{loop $_G['setting']['navs'] $nav}-->
			<!--{if is_array($nav) && $nav['available'] && (!$nav['level'] || ($nav['level'] == 1 && $_G['uid']) || ($nav['level'] == 2 && $_G['adminid'] > 0) || ($nav['level'] == 3 && $_G['adminid'] == 1))}-->
				<a href="$nav[filename]" class="mint-m-dr-item">
					<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8"/><path d="M12 8.5v7M8.5 12h7"/></svg>
					<span>$nav[name]</span>
				</a>
			<!--{/if}-->
		<!--{/loop}-->
	</nav>

	<div class="mint-m-dr-foot">
		<!--{if $_G['uid']}-->
			<a href="home.php?mod=space&uid=$_G[uid]" class="mint-m-dr-user">
				<img class="_avt" data-uid="$_G[uid]" data-size="middle" alt="$_G[member][username]" />
				<span>{$_G['member']['username']}</span>
			</a>
			<a href="member.php?mod=logging&action=logout&formhash={FORMHASH}" class="mint-m-dr-out">{lang logout}</a>
		<!--{else}-->
			<a href="member.php?mod=logging&action=login" class="mint-m-dr-login">{lang login}</a>
			<!--{if $_G['setting']['regstatus']}-->
				<a href="member.php?mod={$_G['setting']['regname']}" class="mint-m-dr-reg">{lang register}</a>
			<!--{/if}-->
		<!--{/if}-->
	</div>
</aside>
