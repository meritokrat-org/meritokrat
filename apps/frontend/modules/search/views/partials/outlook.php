<div style="height:32px; font-size: 90%;" class="outlook_user pointer" rel="<?=$id?>">
                                    <?=user_helper::photo($id, 'sm', array('class' => 'border1 mb5 mr5','style'=>'width:30px;', 'align' => 'left'))?>
                                    <? if (session::has_credential('admin')||($is_regional_coordinator && $is_regional_coordinator==request::get('region'))||($is_raion_coordinator && $is_raion_coordinator==request::get('city'))) {
                                        $user_auth_data=user_auth_peer::instance()->get_item($id); ?>
                                        <?=$user_auth_data['active']==true ? '<b class="cbrown">'.user_helper::full_name($id,false).'</b>' : '<span  class="cbrown">'.user_helper::full_name($id,false).'</span>'?>
                                        <? if(is_array($region_coordinators) && in_array($id,$region_coordinators)){ ?>
                                            <br><span class="fs11"><?=t('Координатор развития региона')?></span>
                                        <? }elseif(is_array($raion_coordinators) && in_array($id,$raion_coordinators)){ ?>
                                            <br><span class="fs11"><?=t('Координатор развития района')?></span>
                                        <? } ?>
                                        <? if($user_auth_data['offline'] || $user_auth_data['del']){ ?>
                                            <br><span class="fs11">*<?=t('Офф-лайн')?></span>
                                            <? if($user_auth_data['del']){ ?>
                                                <? if($user_auth_data['del']==$id){ ?>
                                                <span class="fs11">&nbsp;(<?=t('Самоудаление')?>)</span>
                                                <? }else{ ?>
                                                <span class="fs11">&nbsp;(<?=t('Удален')?>)</span>
                                                <? } ?>
                                            <? } ?>
                                        <? } ?>
                                    <? } else { ?>
                        		<?=user_helper::full_name($id)?>
                                    <? } /* ?>
                                        <br>
					<span class="fs11">
                                                <?=user_helper::geo( $id );?>
					</span> */ ?>
				</div>
		<div class="clear"></div>