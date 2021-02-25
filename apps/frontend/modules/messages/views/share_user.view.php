<? foreach ( $users as $user_id ) { ?>
        <input type="checkbox" class="friend_check hidden" name="fs[]" id="friend_check_<?=$user_id?>" value="<?=$user_id?>">
        <div id="friend_<?=$user_id?>" rel="<?=$user_id?>" class="item friend" onclick="Application.thisfriendSelect(this);"  style="height:50px;">
                <?=user_helper::photo($user_id, 's', array('class' => 'left', 'width' => '40'), false)?>
                <div style="margin-left: 50px;">
                    <?=user_helper::full_name($user_id, true, array('class'=>'dotted','target'=>'_blank'),false)?>
                    <?=(is_array($invited) && in_array($user_id,$invited))?'<br/><span style="color:green;">'.(($reestr)?t('Член МПУ'):t('Уже приглашен')).'</span>':''?>
                </div>
        </div>
<? } ?>
<div class="clear pb10"></div>
<div class="mt10 mb5 ml5 left pager">
<? $srch=0;if($q!='')$srch=1; ?>
<?=pager_helper::get_full_ajax($pager,$page,12,16,$q,$srch)?></div>
<div class="clear pb10"></div>