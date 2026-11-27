<?php exit('Access Denied');?>
<!--{subtemplate common/header_common}-->
{cells common/header/css}
{cells common/header/js}
{subtemplate common/mint_init}
</head>

<body id="nv_{$_G[basescript]}" class="pg_{CURMODULE}">
	<div id="append_parent"></div><div id="ajaxwaitid"></div>
	<!--{if $_GET['diy'] == 'yes' && check_diy_perm($topic)}-->
		<!--{template common/header_diy}-->
	<!--{/if}-->
	<!--{if check_diy_perm($topic)}-->
		<!--{template common/header_diynav}-->
	<!--{/if}-->

	<div id="mint-app">

		<!--{eval $mnid = getcurrentnav();}-->

		<aside id="mint-side">
			<a href="{if $_G['setting']['domain']['app']['default']}{$_G['scheme']}://{$_G['setting']['domain']['app']['default']}/{else}./{/if}" class="mint-brand" title="{$_G['setting']['bbname']}">
				{$_G['style']['boardlogo']}
				<span class="mint-brand-txt">{$_G['setting']['bbname']}</span>
			</a>

			<ul class="mint-nav" aria-label="{$_G['setting']['bbname']}">
				<!--{loop $_G['setting']['navs'] $nav}-->
					<!--{if is_array($nav) && $nav['available'] && (!$nav['level'] || ($nav['level'] == 1 && $_G['uid']) || ($nav['level'] == 2 && $_G['adminid'] > 0) || ($nav['level'] == 3 && $_G['adminid'] == 1) || ($nav['level'] == 4 && !empty($nav['data']['perm']) && forumperm($nav['data']['perm']) ) )}-->
						<li class="mint-nav-li{if $mnid == $nav[navid]} mint-on{/if}" $nav[nav]></li>
					<!--{/if}-->
				<!--{/loop}-->
			</ul>

			<!--{if $_G['setting']['plugins']['jsmenu']}-->
				<div class="mint-nav-label">{lang plugin}</div>
				<nav class="mint-nav">
					<!--{loop $_G['setting']['plugins']['jsmenu'] $module}-->
						<!--{if in_array($module['adminid'], array(0, -1)) || ($module['adminid'] && $_G['adminid'] > 0 && $module['adminid'] >= $_G['adminid'])}-->
							<a href="javascript:;" onclick="showMenu({'ctrlid':'plugin_menu','pos':'34!'})">$module[name]</a>
						<!--{/if}-->
					<!--{/loop}-->
				</nav>
			<!--{/if}-->

			<!--{if $_G['uid']}-->
			<a class="mint-side-card" href="home.php?mod=space&uid=$_G[uid]">
				<h4>{$_G['member']['username']}<span class="mint-tag">{lang setting}</span></h4>
				<p>{lang my_nav} · UID $_G[uid]</p>
			</a>
			<!--{/if}-->
		</aside>

		<div id="mint-main">
			<header id="mint-top">
				<form class="mint-search" id="mint-scbar" method="post" autocomplete="off" action="search.php?searchsubmit=yes" target="_blank" role="search">
					<input type="hidden" name="formhash" value="{FORMHASH}" />
					<input type="hidden" name="mod" value="forum" />
					<input type="hidden" name="srchfid" value="all" />
					<svg class="mint-search-ico" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m16.5 16.5 4 4"/></svg>
					<input type="text" name="srchtxt" class="mint-search-in" placeholder="{lang search_forum}" />
					<span class="mint-search-kbd">Ctrl K</span>
				</form>

				<div class="mint-top-spacer"></div>

				<button type="button" class="mint-top-btn" id="mint-theme-toggle" aria-label="{lang changestyle}" title="{lang changestyle}">
					<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="4.2"/><path d="M12 2.6v2.2M12 19.2v2.2M4.4 4.4l1.6 1.6M18 18l1.6 1.6M2.6 12h2.2M19.2 12h2.2M4.4 19.6 6 18M18 6l1.6-1.6"/></svg>
				</button>

				<!--{if $_G['uid']}-->
					<a href="home.php?mod=space&uid=$_G[uid]" class="mint-top-user" title="$_G['member']['username']">
						<span class="mint-avatar"><img class="_avt" data-uid="$_G[uid]" data-size="middle" alt="$_G['member']['username']" /></span>
					</a>
				<!--{else}-->
					<a href="member.php?mod=logging&action=login" class="mint-btn-ghost">{lang login}</a>
					<!--{if $_G['setting']['regstatus']}-->
						<a href="member.php?mod={$_G['setting']['regname']}" class="mint-btn-solid">{lang register}</a>
					<!--{/if}-->
				<!--{/if}-->
			</header>

			<div id="wp" class="wp">
