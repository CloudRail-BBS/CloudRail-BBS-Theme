<?php exit('Access Denied');?>
<!--{template common/header}-->
<div id="ct" class="wp cl mint-page mint-prof-page">

	<div id="pt" class="bm cl mint-crumb">
		<div class="z">
			<a href="./" class="nvhm" title="{lang homepage}">$_G[setting][bbname]</a>
			<em>&rsaquo;</em>
			<a href="home.php?mod=space&uid=$space[uid]">{$space[username]}</a>
			<em>&rsaquo;</em>
			{lang memcp_profile}
		</div>
		<div class="y">
			<!--{if $space['self']}-->
				<a href="home.php?mod=spacecp&ac=profile" class="mint-crumb-act">{lang setting}</a>
			<!--{/if}-->
		</div>
	</div>

	<!--{template home/space_menu}-->

	<!--[diy=diycontenttop]--><div id="diycontenttop" class="area"></div><!--[/diy]-->

	<!--{subtemplate home/space_profile_body}-->

	<!--[diy=diycontentbottom]--><div id="diycontentbottom" class="area"></div><!--[/diy]-->

</div>
<!--{template common/footer}-->
