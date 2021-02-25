<? $id=$user['id'];?>
<div id="friend_<?=$id?>" class="box_empty box_content left p10" style="height:70px;width: 230px;">
	<?=user_helper::photo($id, 'sm', array('class' => 'border1 left'), true)?>
	<div class="ml10" style="margin-left: 65px;">
		<div>
			<b><?=user_helper::full_name($id)?></b><br />
			<? $member = user_auth_peer::instance()->get_item($id) ?>
			<span class="fs11 quiet"><?=user_auth_peer::get_status($member['status'])?></span>
                        <span class="fs11 quiet"><?=geo_peer::instance()->get_region($member['country_id'])?></span>
		</div>
        <? if(session::get_user_id()==$user_id){ ?>
		<div class="fs11">
			<a class="ml10 maroon" onclick="friendsController.deleteFriend(<?=$id?>);" href="javascript:;"><?=t('Удалить')?></a>
		</div>
        <? } ?>
	</div>
	<div class="clear"></div>
</div>