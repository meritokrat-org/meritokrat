<div id="friend_<?=$v?>" style="width:340px;height:32px;" class="<?=($i==1)?'right':'left'?> p5">
	<?=user_helper::photo($v, 'sm', array('class' => 'border1 left mr10','style'=>'width:30px;'), true)?>
	<div class="ml10 fs12">
		<div>
			<b><?=user_helper::full_name($v)?></b>,
			<? $member = user_auth_peer::instance()->get_item($v);
                        $memberdata = user_data_peer::instance()->get_item($v);?>
                        <? $city = geo_peer::instance()->get_region($memberdata['region_id']);
                        echo $city['name_' . translate::get_lang()]?>,
			<?=user_auth_peer::get_status($member['status'])?><?=($user['leads']>0)?' <span class="green fs11">+'.$user['leads'].'</span>':''?>
		</div>
	</div>
</div>