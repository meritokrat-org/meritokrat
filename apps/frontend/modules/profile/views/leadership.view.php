<h1 class="column_head mt10 mr10">
	<?=user_helper::full_name($user['id'])?> &rarr;
	<?=t('Люди')?>
</h1>

<? foreach ( $user_people as $id ) { ?>
	<div id="friend_<?=$id?>" class="box_empty box_content left p10 mb10 mr10" style="width: 45.2%;">
		<?=user_helper::photo($id, 't', array('class' => 'border1 left'), true)?>
		<div class="ml10" style="margin-left: 90px;">
			<div style="height: 55px;">
				<b><?=user_helper::full_name($id)?></b><br />
				<? $friend = user_auth_peer::instance()->get_item($id) ?>
				<span class="fs11 quiet"><?=user_auth_peer::get_status($friend['status'])?></span>
			</div>
			<div class="fs11">
				<a href="/messages/compose?user_id=<?=$id?>"><?=t('Написать')?></a>
			</div>
		</div>
		<div class="clear"></div>
	</div>
<? } ?>