<?php exit('Access Denied');?>
<div class="u_profile mint-prof">

	<!-- ===== 基本资料 ===== -->
	<section class="mint-prof-sec mint-prof-sec--wide">
		<header class="mint-prof-sec-h">
			<h2>{lang memcp_profile}</h2>
			<!--{eval $isfriendinfo = 'home_friend_info_'.$space['uid'].'_'.$_G['uid'];}-->
			<span class="mint-prof-sec-sub">UID {$space[uid]}
				<!--{if !empty($_G[$isfriendinfo][note])}--><span class="mint-prof-note-tag">$_G[$isfriendinfo][note]</span><!--{/if}-->
			</span>
			<div class="mint-prof-meta">
				<!--{if !empty($_G['ols'][$space[uid]])}-->
					<span class="mint-prof-dot is-on">{lang online}</span>
				<!--{else}-->
					<span class="mint-prof-dot">{lang offline}</span>
				<!--{/if}-->
				<!--{if $_G[setting][homepagestyle]}-->
					<span>{lang space_visits} <strong>$space[views]</strong></span>
				<!--{/if}-->
				<span>{lang email_status}
					<!--{if $space[emailstatus] > 0}--><strong class="mint-prof-ok">{lang profile_verified}</strong><!--{else}-->{lang profile_no_verified}<!--{/if}-->
				</span>
				<!--{if in_array($_G['adminid'], array(1, 2))}-->
					<span>Email <strong>$space[email]</strong></span>
				<!--{/if}-->
				<!--{if $_G['setting']['allowspacedomain'] && $_G['setting']['domain']['root']['home'] && checkperm('domainlength') && !empty($space['domain'])}-->
					<!--{eval $spaceurl = $_G['scheme'].'://'.$space['domain'].'.'.$_G['setting']['domain']['root']['home'];}-->
					<span>{lang second_domain} <a href="$spaceurl" onclick="setCopy('$spaceurl', '{lang copy_space_address}');return false;">$spaceurl</a></span>
				<!--{/if}-->
			</div>
		</header>

		<!--{if CURMODULE == 'space'}-->
			<!--{hook/space_profile_baseinfo_top}-->
		<!--{elseif CURMODULE == 'follow'}-->
			<!--{hook/follow_profile_baseinfo_top}-->
		<!--{/if}-->

		<ul class="mint-prof-nums">
			<!--{if helper_access::check_module('friend')}-->
			<li>
				<a href="home.php?mod=space&uid=$space[uid]&do=friend&view=me&from=space" target="_blank">
					<strong>$space[friends]</strong><span>{lang friends_num}</span>
				</a>
			</li>
			<!--{/if}-->
			<!--{if helper_access::check_module('follower')}-->
			<li>
				<a href="home.php?mod=follow&do=following&uid=$space[uid]" target="_blank">
					<strong>$space['following']</strong><span>{lang follow_following_num}</span>
				</a>
			</li>
			<li>
				<a href="home.php?mod=follow&do=follower&uid=$space[uid]" target="_blank">
					<strong>$space['follower']</strong><span>{lang follow_follower_num}</span>
				</a>
			</li>
			<!--{/if}-->
			<!--{if $_G['setting']['allowviewuserthread'] !== false && helper_access::check_module('forum')}-->
				<!--{eval $space['posts'] = $space['posts'] - $space['threads'];}-->
				<li>
					<a href="{if CURMODULE != 'follow'}home.php?mod=space&uid=$space[uid]&do=thread&view=me&type=thread&from=space{else}home.php?mod=space&uid=$space[uid]&view=thread{/if}" target="_blank">
						<strong>$space[threads]</strong><span>{lang threads_num}</span>
					</a>
				</li>
				<li>
					<a href="{if CURMODULE != 'follow'}home.php?mod=space&uid=$space[uid]&do=thread&view=me&type=reply&from=space{else}home.php?mod=space&uid=$space[uid]&view=thread&type=reply{/if}" target="_blank">
						<strong>$space[posts]</strong><span>{lang replay_num}</span>
					</a>
				</li>
			<!--{/if}-->
			<!--{if helper_access::check_module('doing')}-->
			<li>
				<a href="home.php?mod=space&uid=$space[uid]&do=doing&view=me&from=space" target="_blank">
					<strong>$space[doings]</strong><span>{lang doings_num}</span>
				</a>
			</li>
			<!--{/if}-->
			<!--{if helper_access::check_module('blog')}-->
			<li>
				<a href="home.php?mod=space&uid=$space[uid]&do=blog&view=me&from=space" target="_blank">
					<strong>$space[blogs]</strong><span>{lang blogs_num}</span>
				</a>
			</li>
			<!--{/if}-->
			<!--{if helper_access::check_module('album')}-->
			<li>
				<a href="home.php?mod=space&uid=$space[uid]&do=album&view=me&from=space" target="_blank">
					<strong>$space[albums]</strong><span>{lang albums_num}</span>
				</a>
			</li>
			<!--{/if}-->
			<!--{if helper_access::check_module('share')}-->
			<li>
				<a href="home.php?mod=space&uid=$space[uid]&do=share&view=me&from=space" target="_blank">
					<strong>$space[sharings]</strong><span>{lang shares_num}</span>
				</a>
			</li>
			<!--{/if}-->
		</ul>

		<!--{if CURMODULE == 'space'}-->
			<!--{hook/space_profile_baseinfo_middle}-->
		<!--{elseif CURMODULE == 'follow'}-->
			<!--{hook/follow_profile_baseinfo_middle}-->
		<!--{/if}-->

		<!--{if $profiles}-->
		<ul class="pf_l mint-prof-fields">
			<!--{loop $profiles $value}-->
			<li><em>$value[title]</em><div class="mint-prof-val">$value[value]</div></li>
			<!--{/loop}-->
		</ul>
		<!--{/if}-->

		<!--{if CURMODULE == 'space'}-->
			<!--{hook/space_profile_baseinfo_bottom}-->
		<!--{elseif CURMODULE == 'follow'}-->
			<!--{hook/follow_profile_baseinfo_bottom}-->
		<!--{/if}-->

		<!--{if $space[spacenote] || $space[customstatus] || ($space[group][maxsigsize] && $space[sightml])}-->
		<div class="mint-prof-notes">
			<!--{if $space[spacenote]}-->
			<div class="mint-prof-note"><em>{lang spacenote}</em><p>$space[spacenote]</p></div>
			<!--{/if}-->
			<!--{if $space[customstatus]}-->
			<div class="mint-prof-note"><em>{lang permission_basic_status}</em><p>$space[customstatus]</p></div>
			<!--{/if}-->
			<!--{if $space[group][maxsigsize] && $space[sightml]}-->
			<div class="mint-prof-note"><em>{lang personal_signature}</em><div class="mint-prof-sign">$space[sightml]</div></div>
			<!--{/if}-->
		</div>
		<!--{/if}-->
	</section>

	<!-- ===== 活跃信息 ===== -->
	<section class="mint-prof-sec">
		<h2 class="mint-prof-sec-h">{lang active_profile}</h2>
		<ul class="mint-prof-list">
			<!--{if $space[adminid] > 0}-->
			<li><em>{lang management_team}</em><span style="color:{$space[admingroup][color]}"><a href="home.php?mod=spacecp&ac=usergroup&gid=$space[adminid]" target="_blank">{$space[admingroup][grouptitle]}</a></span> {$space[admingroup][icon]}</li>
			<!--{/if}-->
			<li><em>{lang usergroup}</em><span style="color:{$space[group][color]}"{if $upgradecredit !== false} class="xi2" onmouseover="showTip(this)" tip="{lang credits} $space[credits], {lang thread_groupupgrade} $upgradecredit {lang credits}"{/if}><a href="home.php?mod=spacecp&ac=usergroup&gid=$space[groupid]" target="_blank">{$space[group][grouptitle]}</a></span> {$space[group][icon]}<!--{if !empty($space['groupexpiry'])}--><span class="mint-prof-hint">{lang group_useful_life} <!--{date($space['groupexpiry'], 'Y-m-d H:i')}--></span><!--{/if}--></li>
			<!--{if $space[extgroupids]}--><li><em>{lang group_expiry_type_ext}</em><span>$space[extgroupids]</span></li><!--{/if}-->
			<!--{if $space[oltime] && !$_G['setting']['sessionclose']}--><li><em>{lang online_time}</em><span>$space[oltime] {lang hours}</span></li><!--{/if}-->
			<li><em>{lang regdate}</em><span>$space[regdate]</span></li>
			<li><em>{lang last_visit}</em><span>$space[lastvisit]</span></li>
			<!--{if $space[lastactivity]}--><li><em>{lang last_activity_time}</em><span>$space[lastactivity]</span></li><!--{/if}-->
			<!--{if $space[lastpost]}--><li><em>{lang last_post_time}</em><span>$space[lastpost]</span></li><!--{/if}-->
			<!--{if $space[lastsendmail]}--><li><em>{lang last_send_email}</em><span>$space[lastsendmail]</span></li><!--{/if}-->
			<li><em>{lang time_offset}</em><span><!--{eval $timeoffset = array({lang timezone});}-->$timeoffset[$space[timeoffset]]</span></li>
			<!--{if $_G[uid] == $space[uid] || !empty($_G[group][allowviewip])}-->
			<li><em>{lang register_ip}</em><span>$space[regip]:$space[regport] - $space[regip_loc]</span></li>
			<li><em>{lang last_visit_ip}</em><span>$space[lastip]:$space[port] - $space[lastip_loc]</span></li>
			<!--{/if}-->
		</ul>
	</section>

	<!-- ===== 统计信息 ===== -->
	<section class="mint-prof-sec">
		<h2 class="mint-prof-sec-h">{lang stat_info}</h2>
		<ul class="mint-prof-list" id="psts">
			<li><em>{lang used_space}</em><span>$space[attachsize]</span></li>
			<!--{if $space[buyercredit]}-->
			<li><em>{lang eccredit_buyerinfo}</em><span><a href="home.php?mod=space&uid=$space[uid]&do=trade&view=eccredit<!--{if $_G[setting][homepagestyle]}-->&sns=1<!--{/if}-->#sellcredit" target="_blank">$space[buyercredit] <img src="{STATICURL}image/traderank/buyer/$space[buyerrank].gif" border="0" class="vm" /></a></span></li>
			<!--{/if}-->
			<!--{if $space[sellercredit]}-->
			<li><em>{lang eccredit_sellerinfo}</em><span><a href="home.php?mod=space&uid=$space[uid]&do=trade&view=eccredit<!--{if $_G[setting][homepagestyle]}-->&sns=1<!--{/if}-->#buyercredit" target="_blank">$space[sellercredit] <img src="{STATICURL}image/traderank/seller/$space[sellerrank].gif" border="0" class="vm" /></a></span></li>
			<!--{/if}-->
			<li class="mint-prof-credit"><em>{lang credits}</em><strong>$space[credits]</strong></li>
			<!--{loop $_G[setting][extcredits] $key $value}-->
				<!--{if $value[title]}-->
				<li><em>$value[title]</em><strong>{$space["extcredits$key"]}</strong> <span class="mint-prof-unit">$value[unit]</span></li>
				<!--{/if}-->
			<!--{/loop}-->
		</ul>
	</section>

	<!-- ===== 勋章 / 认证 ===== -->
	<!--{if $space['medals'] || $_G['setting']['verify']['enabled']}-->
	<section class="mint-prof-sec mint-prof-sec--wide">
		<h2 class="mint-prof-sec-h">{lang medals} <span class="mint-prof-sec-sub">{lang profile_verify}</span></h2>

		<!--{if $space['medals']}-->
		<div class="mint-prof-medals">
			<!--{loop $space['medals'] $medal}-->
			<a href="home.php?mod=medal" class="mint-prof-medal">
				<img src="$medal['image']" alt="$medal['name']" id="md_{$medal['medalid']}" onmouseover="showMenu({'ctrlid':this.id, 'menuid':'md_{$medal['medalid']}_menu', 'pos':'12!'});" />
				<span>$medal[name]</span>
			</a>
			<!--{/loop}-->
		</div>
		<!--{loop $space['medals'] $medal}-->
			<div id="md_{$medal[medalid]}_menu" class="tip tip_4" style="display: none;">
				<div class="tip_horn"></div>
				<div class="tip_c">
					<h4>$medal[name]</h4>
					<p>$medal[description]</p>
				</div>
			</div>
		<!--{/loop}-->
		<!--{/if}-->

		<!--{if $_G['setting']['verify']['enabled']}-->
			<!--{eval $showverify = true;}-->
			<!--{loop $_G['setting']['verify'] $vid $verify}-->
				<!--{if $verify['available']}-->
					<!--{if $showverify}-->
					<div class="mint-prof-verify">
						<!--{eval $showverify = false;}-->
					<!--{/if}-->
					<!--{if $space['verify'.$vid] == 1}-->
						<a href="home.php?mod=spacecp&ac=profile&op=verify&vid=$vid" target="_blank" class="mint-prof-vitem"><!--{if $verify['icon']}--><img src="$verify['icon']" class="vm" alt="$verify[title]" title="$verify[title]" /><!--{else}-->$verify[title]<!--{/if}--></a>
					<!--{elseif !empty($verify['unverifyicon'])}-->
						<a href="home.php?mod=spacecp&ac=profile&op=verify&vid=$vid" target="_blank" class="mint-prof-vitem is-off"><!--{if $verify['unverifyicon']}--><img src="$verify['unverifyicon']" class="vm" alt="$verify[title]" title="$verify[title]" /><!--{/if}--></a>
					<!--{/if}-->
				<!--{/if}-->
			<!--{/loop}-->
			<!--{if !$showverify}--></div><!--{/if}-->
		<!--{/if}-->
	</section>
	<!--{/if}-->

	<!-- ===== 管理版块 / 群组 ===== -->
	<!--{if $count || !empty($groupcount)}-->
	<section class="mint-prof-sec mint-prof-sec--wide">
		<h2 class="mint-prof-sec-h">{lang manage_forums}</h2>
		<!--{if $count}-->
		<div class="mint-prof-tags">
			<!--{loop $manage_forum $key $value}-->
			<a href="forum.php?mod=forumdisplay&fid=$key" target="_blank">$value</a>
			<!--{/loop}-->
		</div>
		<!--{/if}-->
		<!--{if !empty($groupcount)}-->
		<h3 class="mint-prof-subh">{lang joined_group}</h3>
		<div class="mint-prof-tags mint-prof-tags--group">
			<!--{loop $usergrouplist $key $value}-->
			<a href="forum.php?mod=group&fid={$value['fid']}" target="_blank">$value['name']</a>
			<!--{/loop}-->
		</div>
		<!--{/if}-->
	</section>
	<!--{/if}-->

	<!-- ===== 违规记录 ===== -->
	<!--{if $clist}-->
	<section class="mint-prof-sec mint-prof-sec--wide">
		<h2 class="mint-prof-sec-h">{lang crime_record}</h2>
		<table id="pcr" class="dt mint-prof-table">
			<tr>
				<th width="15%">{lang crime_action}</th>
				<th width="15%">{lang crime_dateline}</th>
				<th>{lang crime_reason}</th>
				<th width="15%">{lang crime_operator}</th>
			</tr>
			<!--{loop $clist $crime}-->
			<tr>
				<td>
					<!--{if $crime[action] == 'crime_delpost'}-->
						{lang crime_delpost}
					<!--{elseif $crime[action] == 'crime_warnpost'}-->
						{lang crime_warnpost}
					<!--{elseif $crime[action] == 'crime_banpost'}-->
						{lang crime_banpost}
					<!--{elseif $crime[action] == 'crime_banspeak'}-->
						{lang crime_banspeak}
					<!--{elseif $crime[action] == 'crime_banvisit'}-->
						{lang crime_banvisit}
					<!--{elseif $crime[action] == 'crime_banstatus'}-->
						{lang crime_banstatus}
					<!--{elseif $crime[action] == 'crime_avatar'}-->
						{lang crime_avatar}
					<!--{elseif $crime[action] == 'crime_sightml'}-->
						{lang crime_sightml}
					<!--{elseif $crime[action] == 'crime_customstatus'}-->
						{lang crime_customstatus}
					<!--{elseif $crime[action] == 'members_ban_none'}-->
						{lang members_ban_none}
					<!--{/if}-->
				</td>
				<td><!--{date($crime['dateline'])}--></td>
				<td>$crime[reason]</td>
				<td><a href="home.php?mod=space&uid=$crime[operatorid]" target="_blank">$crime[operator]</a></td>
			</tr>
			<!--{/loop}-->
		</table>
	</section>
	<!--{/if}-->

	<!--{if CURMODULE == 'space'}-->
		<!--{hook/space_profile_extrainfo}-->
	<!--{elseif CURMODULE == 'follow'}-->
		<!--{hook/follow_profile_extrainfo}-->
	<!--{/if}-->
</div>
