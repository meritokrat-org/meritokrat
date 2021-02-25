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
$("a.reject").click(function() {
    var uid = $(this).parent('div').parent('div').attr('rel');
    $.post('/invites/delete?tp=2&oid=<?=$data['id']?>&uid='+uid);
    $(this).parent('div').parent('div').remove();
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

<div class="popup_header" rel="<?=t('Спасибо, Ваше сообщение было отправлено!')?>">
	<h2><?=t('Внутренняя рассылка')?></h2>
</div>

<form rel="<?=t('Выберите получателей сообщения')?>" id="share_form" action="/admin/internallisti" method="post" onsubmit="return Application.doMessage();">

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
                                </div>
                        </div>
                <? } ?>

            </div>
            <div class="clear"></div>
        <div id="user_pager" class="mt10 mb5 ml5 left pager"><?=pager_helper::get_full_ajax($userpager,null,12,18,$q)?></div>
        <div class="clear pb10"></div>
        </div>

        <div class="left">
            <div id="friends_checker">
            </div>
                <div class="mb5 mt10 quiet left"><?=t('Cообщение')?>: </div>
                <div class="mt5 right">
                    <div class="quiet bold" style="text-transform:uppercase"><?=t('Выбрано')?>: <span id="friend_counter" class="fs16">0</span></div>
                </div>
                <textarea name="message" style="height: 100px; width: 545px;"><?=$message?> <?=$name?></textarea>

                <div class="mt10">
                        <input type="submit" class="button" value=" <?=t('Отправить')?> ">
                        <input type="button" class="button_gray" onclick="Application.invitedFriends=[];Popup.close();" value=" <?=t('Отмена')?> ">
                        <? if (session::has_credential('admin') && $item_type==2) { ?>
                                <input type="button"  name="jusers" id="jusers" class="button" value=" Приєднати " onclick="return Application.dotoGroup();">
                                <!--a id="join_users" class="button ml15" style="padding:2px;"> Приєднати </a-->
                        <? } ?>
                </div>
        </div>
        
        <div class="clear pb5"></div>
    </div>
</form>

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