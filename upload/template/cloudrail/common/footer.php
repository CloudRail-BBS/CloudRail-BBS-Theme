<?php exit('Access Denied');?>
			</div><!-- /#wp -->

		</div><!-- /#mint-main -->
	</div><!-- /#mint-app -->

<!--{if empty($topic) || ($topic[usefooter])}-->
	{cells common/footer/focus}

	<!--{ad/footerbanner/wp a_f/1}--><!--{ad/footerbanner/wp a_f/2}--><!--{ad/footerbanner/wp a_f/3}-->
	<!--{ad/float/a_fl/1}--><!--{ad/float/a_fr/2}-->
	<!--{ad/couplebanner/a_fl a_cb/1}--><!--{ad/couplebanner/a_fr a_cb/2}-->
	<!--{ad/cornerbanner/a_cn}-->

	<!--{hook/global_footer}-->
	<footer id="ft" class="mint-ft">
		<div class="mint-ft-in">
			<div class="mint-ft-main">
				<!--{loop $_G['setting']['footernavs'] $nav}--><!--{if is_array($nav) && $nav['available'] && ($nav['type'] && (!$nav['level'] || ($nav['level'] == 1 && $_G['uid']) || ($nav['level'] == 2 && $_G['adminid'] > 0) || ($nav['level'] == 3 && $_G['adminid'] == 1)) ||
						!$nav['type'] && ($nav['id'] == 'stat' && $_G['group']['allowstatdata'] || $nav['id'] == 'report' && $_G['uid'] || $nav['id'] == 'archiver' || $nav['id'] == 'mobile' || $nav['id'] == 'darkroom'))}-->$nav[code]<!--{/if}--><!--{/loop}-->
				<a href="{$_G['setting']['siteurl']}" target="_blank"><strong>{$_G['setting']['sitename']}</strong></a>
				<!--{if $_G['setting']['icp'] || !empty($_G['setting']['mps'])}-->
				<span class="mint-ft-sep">·</span>
				<!--{if $_G['setting']['icp']}--><a href="https://beian.miit.gov.cn/" target="_blank">{$_G['setting']['icp']}</a><!--{/if}-->
				<!--{if !empty($_G['setting']['mps'])}--><!--{if $_G['setting']['icp']}--><span class="mint-ft-sep">·</span><!--{/if}--><a href="https://beian.mps.gov.cn/#/query/webSearch?code={$_G['setting']['mpsid']}" target="_blank"><img width="14" height="14" src="{IMGDIR}/ico_mps.png" />{$_G['setting']['mps']}</a><!--{/if}-->
				<!--{/if}-->
			</div>
			<div class="mint-ft-sub">
				{cells common/footer/copyright}
				<span class="mint-ft-sep">·</span>
				{lang time_now}
				<span id="debuginfo"><!--{if debuginfo()}-->, Processed in $_G[debuginfo][time] second(s), $_G[debuginfo][queries] queries<!--{if $_G['gzipcompress']}-->, Gzip On<!--{/if}--><!--{if C::memory()->type}-->, <!--{echo C::memory()->type}--> On<!--{/if}-->.<!--{/if}--></span>
			</div>
		</div>
		<!--{eval updatesession();}-->
	</footer>
<!--{/if}-->

<div id="scrolltop">
	<!--{if $_G[fid] && $_G['mod'] == 'viewthread'}-->
	<span><a href="forum.php?mod=post&action=reply&fid=$_G[fid]&tid=$_G[tid]&extra=$_GET[extra]&page=$page{if $_GET[from]}&from=$_GET[from]{/if}" onclick="showWindow('reply', this.href)" class="replyfast" title="{lang fastreply}"><b>{lang fastreply}</b></a></span>
	<!--{/if}-->
	<span hidefocus="true"><a title="{lang scrolltop}" onclick="window.scrollTo('0','0')" class="scrolltopa"><b>{lang scrolltop}</b></a></span>
</div>

{cells common/footer/js}
{subtemplate common/mint_js}

<!--{eval output();}-->
</body>
</html>
