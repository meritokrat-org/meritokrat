<div id="friend_<?=$user['user_id']?>" style="width:340px;height:91px;" class="<?=($i==1)?'right':'left'?> p5">
	<?=user_helper::photo($user['user_id'], 'sm', array('class' => 'border1 left mr10'), true)?>
	<div class="ml10 fs12">
		<div class="left" style="width:215px">
			<b><?=user_helper::full_name($user['user_id'])?></b>,
			<? $member = user_auth_peer::instance()->get_item($user['user_id']);
                        $memberdata = user_data_peer::instance()->get_item($user['user_id']);?>
                        <? $city = geo_peer::instance()->get_region($memberdata['region_id']);
                        echo $city['name_' . translate::get_lang()]?>,
			<?=user_auth_peer::get_status($member['status'])?><?=($user['leads']>0)?' <span class="green fs11">+'.$user['leads'].'</span>':''?>
		</div>
                <? if($has_access){ ?>
                    <? if(!$confirm = events_peer::instance()->user_confirm($event['id'],$user['user_id'])){ ?>
                    <a class="right button_gray p5 confirmation" rel="<?=$user['user_id']?>" style="margin-right:0"><?=t('Нет')?></a>
                    <a class="right button p5 mr5 confirmation" rel="<?=$user['user_id']?>"><?=t('Был')?></a>
                    <a class="right confresult p5 hide pointer"  rel="<?=$user['user_id']?>"></a>
                    <? }else{ ?>
                    <a class="right button_gray p5 confirmation hide" rel="<?=$user['user_id']?>" style="margin-right:0"><?=t('Нет')?></a>
                    <a class="right button p5 mr5 confirmation hide" rel="<?=$user['user_id']?>"><?=t('Был')?></a>
                    <a class="right confresult p5 pointer"  rel="<?=$user['user_id']?>"><?=($confirm==1)?t('Был'):t('Нет')?></a>
                    <? } ?>
                <? } ?>
	</div>
</div>