<? foreach ( $invited as $invited_id ) { ?>
        <div id="friend_<?=$invited_id?>" rel="<?=$invited_id?>" class="item friend"  style="height:50px;width:230px;">
                <?=user_helper::photo($invited_id, 's', array('class' => 'left', 'width' => '40'), false)?>
                <div style="margin-left: 50px;">
                    <a class="dotted"><?=user_helper::full_name($invited_id, false, array(), false)?></a>
                    <a class="right reject"><?=t('Отменить')?></a>
                    <br/>
                    <?=t('Пригласил')?>: <?=user_helper::get_inviters($invited_id,'2',$id,true)?>
                </div>
        </div>
<? } ?>
<div class="clear pb10"></div>
<div class="mt10 mb5 ml5 left pager"><?=page_helper::get_full_ajax($invpager,null,12,18,$q,'/invites/invited')?></div>