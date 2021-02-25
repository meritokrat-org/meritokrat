
<div class="popup_header" rel="<?=t('Спасибо, Ваше сообщение было отправлено!')?>">
	<h2><?=t('Пригласить в сообщество')?></h2>
</div>

<? if ( $error ) { ?>
	<div class="m10 fs12 acenter maroon">
		<?=$error?><br /><br/>
		<input type="button" class="button_gray" onclick="Popup.close();" value=" <?=t('ОК')?> ">
	</div>
<? } else { ?>
<form rel="<?=t('Выберите группы из списка')?>" id="share_form" action="/invites" method="post" onsubmit="return Application.doGroupInvite();">
    <input type="hidden" name="user_id" id="user_id" value="<?=$user_id?>">
    <div class="m10 fs11 aleft" style="width: 550px;">

        <? $num=0 ?>
        <? foreach($categories as $k => $v){ ?>
        <div id="cat_<?=$k?>" class="tab fs12 p5 mr5 quiet left border1 <?=($num)?'unselected':''?>">
            <a style="color: black; text-decoration: none;" onclick="" href="javascript:;"><?=t($v)?></a>
        </div>
        <? $num++ ?>
        <? } ?>

        <div class="clear"></div>

        <? $num=0 ?>
        <? foreach($categories as $k => $v){ ?>
        <div id="cat_<?=$k?>_selector" class="friend_selector" style="height:400px;overflow:auto;<?=($num)?'display:none':''?>">
                <? foreach ( $groups[$k] as $gid ) { ?>
                        <input type="checkbox" class="friend_check hidden" name="fs[]" id="friend_check_<?=$gid?>" value="<?=$gid?>">
                        <div id="friend_<?=$gid?>" rel="<?=$gid?>" class="item friend" onclick="Application.thisfriendSelect(this);"  style="height:50px;">
                                <?=group_helper::photo($gid, 'sm', false, array('class' => 'left', 'width' => '40'))?>
                                <div style="margin-left: 50px;line-height:1.2">
                                    <a class="dotted"><?=stripslashes(group_helper::title($gid, false))?></a>
                                    <?//(is_array($invites) && in_array($gid,$invites))?'<br/>'.t('Уже приглашен'):''?>
                                </div>
                        </div>
                <? } ?>
                <div class="clear pb10"></div>
        </div>
        <? $num++ ?>
        <? } ?>

        <div class="left">
            <div id="friends_checker">
            </div>
                <div class="mb5 mt10 quiet"><?=t('Короткое сообщение')?>: </div>
                <textarea name="message" style="height: 50px; width: 200px;">Доброго дня, запрошую Вас приєднатися до спільноти.</textarea>

                <div class="mt10">
                        <input type="submit" class="button" value=" <?=t('Отправить')?> ">
                        <input type="button" class="button_gray" onclick="Popup.close();" value=" <?=t('Отмена')?> ">
                        <input type="button" class="button" value=" <?=t('Присоединить')?> " onclick="Application.doGroupJoin()">
                </div>
        </div>
        <div style="margin-left: 225px;">
                <div class="mt5 quiet"><?=t('Выбрано')?>: <span id="friend_counter" class="fs18">0</span></div>
                <?=$html?>
        </div>
        <div class="clear pb5"></div>
    </div>
</form>
<? } ?>

<script type="text/javascript">
    $('.tab').click(function(){
        $('.tab').addClass('unselected');
        $('.friend_selector').hide();
        $(this).removeClass('unselected');
        $('#'+$(this).attr('id')+'_selector').show();
    });
</script>