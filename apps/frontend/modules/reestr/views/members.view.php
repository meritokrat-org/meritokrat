<script type="text/javascript">
jQuery(document).ready(function($) {
  $(document).keypress(function(event) {
  if (event.keyCode == '13') {
     event.preventDefault();
     $("#button").click();
   }
   });
$("#button").click(function() {
    search();
});
$('#ac')
  .focus(function(){if ($(this).val() == '<?=t('Введите фамилию человека')?>...') {$(this).val('');} })
  .blur(function(){if ($(this).val() == '') {$(this).val('<?=t('Введите фамилию человека')?>...');} })
});
function search(){
    var zelenoye=$('#ac').val();
    $.get('/messages/share_user',  {q: zelenoye},
    function(data) {
    $('#search').html(data).show();
    for(var i in Application.invitedFriends) {
        if (!Application.invitedFriends.hasOwnProperty(i)) continue;
            $('#search').find('#friend_'+Application.invitedFriends[i]).addClass('selected');
    }
    $('#result').hide();
    $('#user_pager').hide();
    });
}
</script>

<div class="popup_header" rel="<?=t('Спасибо, решение сохранено!')?>">
	<h2><?=t('Решение о принятии')?></h2>
</div>

<? if ( $error ) { ?>
	<div class="m10 fs12 acenter maroon">
		<?=$error?><br /><br/>
		<input type="button" class="button_gray" onclick="Popup.close();" value=" <?=t('ОК')?> ">
	</div>
<? } else { ?>
<form rel="<?=t('Выберите людей из списка')?>" id="share_form" action="/reestr" method="post" onsubmit="return Application.doMembers();">

    <div class="m10 fs11 aleft" style="width: 550px;">

        <div class="friend_selector" id="user_selector">
            <input type="text"  autocomplete="off" id="ac" name="auto" class="cgray ml10 mb5 mt10" value="<?=t('Введите фамилию человека')?>..." style="text-decoration: none; width:440px;">
            <input type="button" class="button" id="button" value=" <?=t('Поиск')?> ">

            <div id="search" style="display:none;"></div>

            <div id="result">

                <? foreach ( $users as $user_id ) { ?>
                        <input type="checkbox" class="friend_check hidden" name="fs[]" id="friend_check_<?=$user_id?>" value="<?=$user_id?>">
                        <div id="friend_<?=$user_id?>" rel="<?=$user_id?>" class="item friend" onclick="Application.thisfriendSelect(this);"  style="height:50px;">
                                <?=user_helper::photo($user_id, 's', array('class' => 'left', 'width' => '40'), false)?>
                                <div style="margin-left: 50px;">
                                    <?=user_helper::full_name($user_id, true, array('class'=>'dotted','target'=>'_blank'),false)?>
                                    <?=(is_array($invited) && in_array($user_id,$invited))?'<br/><span style="color:green;">'.t('Член МПУ').'</span>':''?>
                                </div>
                        </div>
                <? } ?>

            </div>
            <div class="clear"></div>
        <div id="user_pager" class="mt10 mb5 ml5 left pager"><?=pager_helper::get_full_ajax($userpager,null,12,18,$q)?></div>
        <div class="clear pb10"></div>
        </div>

        <div class="left mt10 mb10">
            <div id="friends_checker">
            </div>
                <span class="quiet" style="padding-left:5px"><?=t('Номер решения')?>: </span>
                <input type="text" class="text" name="invnumber" id="invnumber" style="width:50px" />
                <span class="quiet ml5"><?=t('Дата')?>: </span>
                <?=tag_helper::select('day',array_combine(range(1,31),range(1,31)),array('value'=>date('j')))?>
                <?=tag_helper::select('month',array_combine(range(1,12),range(1,12)),array('value'=>date('n')))?>
                <?=tag_helper::select('year',array_combine(range(2011,date('Y')),range(2011,date('Y'))),array('value'=>date('Y')))?>
                <br class="mb5" />
                <input type="checkbox" style="" name="kvmake" id="kvmake" value="1" />
                <span class="quiet"><?=t('Партбилет изготовлен')?> </span>
                <br class="mb5" />
                <input type="checkbox" style="" name="kvgive" id="kvgive" value="1" />
                <span class="quiet"><?=t('Партбилет вручен')?> </span>
                <br class="mb5" />
                <input type="checkbox" style="" name="msg_photo" id="msg_photo" value="1" />
                <span class="quiet"><?=t('Сообщение о том, что фото не соответствует стандартам')?> </span>
                <br class="mb5" />
                <input type="checkbox" style="" name="msg_ukr" id="msg_ukr" value="1" />
                <span class="quiet"><?=t('Сообщение о необходимости заполнить свои данные на украинском')?> </span>

                <div class="mt10">
                        <input type="submit" class="button" value=" <?=t('Сохранить')?> ">
                        <input type="button" class="button_gray" onclick="Application.invitedFriends=[];Popup.close();" value=" <?=t('Отмена')?> ">
                </div>
        </div>
        <div class="right mr10 ml10">
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
        $('#search, #users_selector, .friend_selector').hide();
        $(this).removeClass('unselected');
        $('input[name="stype"]').val($(this).attr('id'));
        $('#'+$(this).attr('id')+'_selector').show();
        $('#friend_counter').html('0');
        $('friend_check').attr('checked',false);
        $('.item').removeClass('selected');
        Application.invitedFriends = [];
    });
</script>