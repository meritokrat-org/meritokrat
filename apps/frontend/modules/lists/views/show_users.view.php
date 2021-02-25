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
$("a.del").click(function() {
    var uid = $(this).attr('rel');
    $.post('/lists/delete_user',{'uid':uid,'lid':<?=$data['id']?>});
    $('#inv_'+uid).remove();
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

<div class="popup_header" rel="<?=t('Спасибо, действие выполнено!')?>">
	<h2><?=$data['title']?></h2>
</div>

<? if ( $error ) { ?>
	<div class="m10 fs12 acenter maroon">
		<?=$error?><br /><br/>
		<input type="button" class="button_gray" onclick="Popup.close();" value=" <?=t('ОК')?> ">
	</div>
<? } else { ?>
<form rel="<?=t('Выберите участников из списка')?>" id="share_form" action="/lists/add_users" method="post" onsubmit="return Application.transferData('lists/add_users');">

    <input type="hidden" name="id" value="<?=$id?>" />

    <div class="m10 fs11 aleft" style="width: 550px;">
        <div id="user" class="tab fs18 p5 mr5 quiet left border1">
            <a style="color: black; text-decoration: none;" href="javascript:;" onclick="$('#result, #user_pager').show()"><?=t('Все')?></a>
        </div>
        <div id="friend" class="tab fs18 p5 mr5 quiet unselected left border1">
            <a style="color: black; text-decoration: none;" href="javascript:;"><?=t('В списке')?></a>
        </div>


        <div id="edit" class="tab fs18 p5 mr5 quiet unselected right border1">
            <a style="color: black; text-decoration: none;" href="javascript:;"><?=t('Редактируют')?></a>
        </div>
        <div id="view" class="tab fs18 p5 mr5 quiet unselected right border1">
            <a style="color: black; text-decoration: none;" href="javascript:;"><?=t('Просматривают')?></a>
        </div>


        <div class="clear"></div>

        <div class="friend_selector" id="user_selector" style="height:370px">
            <input type="text"  autocomplete="off" id="ac" name="auto" class="cgray ml10 mb5 mt10" value="<?=t('Введите фамилию человека')?>..." style="text-decoration: none; width:440px;">
            <input type="button" class="button" id="button" value=" <?=t('Поиск')?> ">

            <div id="search" style="display:none"></div>

            <div id="result">

                <? foreach ( $users as $user_id ) { ?>
                        <input type="checkbox" class="friend_check hidden" name="fs[]" id="friend_check_<?=$user_id?>" value="<?=$user_id?>">
                        <div id="friend_<?=$user_id?>" rel="<?=$user_id?>" class="item friend" onclick="Application.thisfriendSelect(this);"  style="height:50px;">
                                <?=user_helper::photo($user_id, 's', array('class' => 'left', 'width' => '40'), false)?>
                                <div style="margin-left: 50px;">
                                    <a class="dotted"><?=user_helper::full_name($user_id, false)?></a>
                                </div>
                        </div>
                <? } ?>

            </div>
            <div class="clear"></div>
        <div id="user_pager" class="mt10 mb5 ml5 left pager"><?=pager_helper::get_full_ajax($userpager,null,12,18,$q)?></div>
        <div class="clear pb10"></div>
        </div>

        <div id="friend_selector" class="friend_selector" style="height:370px;overflow:auto;display:none">
            <? foreach ( $in_list as $friend_id ) { ?>
                    <div id="friend_<?=$friend_id?>" rel="<?=$friend_id?>" class="item friend" onclick="Application.thisfriendSelect(this);" style="height:50px">
                            <?=user_helper::photo($friend_id, 's', array('class' => 'left', 'width' => '40'), false)?>
                            <div style="margin-left: 50px;">
                                <a class="dotted" href="javascript:;"><?=user_helper::full_name($friend_id, false)?></a>
                            </div>
                    </div>
            <? } ?>
            <div class="clear"></div>
        </div>

        <div id="view_selector" class="friend_selector" style="height:370px;overflow:auto;display:none">
            <? foreach ( $viewers as $friend_id ) { ?>
                    <div id="friend_<?=$friend_id?>" rel="<?=$friend_id?>" class="item friend" onclick="Application.thisfriendSelect(this);" style="height:50px">
                            <?=user_helper::photo($friend_id, 's', array('class' => 'left', 'width' => '40'), false)?>
                            <div style="margin-left: 50px;">
                                <a class="dotted" href="javascript:;"><?=user_helper::full_name($friend_id, false)?></a>
                            </div>
                    </div>
            <? } ?>
            <div class="clear"></div>
        </div>

        <div id="edit_selector" class="friend_selector" style="height:370px;overflow:auto;display:none">
            <? foreach ( $editors as $friend_id ) { ?>
                    <div id="friend_<?=$friend_id?>" rel="<?=$friend_id?>" class="item friend" onclick="Application.thisfriendSelect(this);" style="height:50px">
                            <?=user_helper::photo($friend_id, 's', array('class' => 'left', 'width' => '40'), false)?>
                            <div style="margin-left: 50px;">
                                <a class="dotted" href="javascript:;"><?=user_helper::full_name($friend_id, false)?></a>
                            </div>
                    </div>
            <? } ?>
            <div class="clear"></div>
        </div>

        <div class="mt10" id="select_handler">
            <?=t('С выбранными')?>:<br/>
            <select name="act" id="act" style="width:550px">
                <option id="oadd" value="add"><?=t('Внести в список')?></option>
                <option id="odel" style="display:none" value="del"><?=t('Удалить из списка')?></option>
                <option id="oview" value="view"><?=t('Дать полномочия просматривать список')?></option>
                <option id="oedit" value="edit"><?=t('Дать полномочия редактировать список')?></option>
                <option id="oundo" style="display:none" value="undo"><?=t('Лишить полномочий')?></option>
            </select>
        </div>

        <div class="left">
            <div id="friends_checker">
            </div>

                <div class="mt10">
                        <input type="submit" class="button" value=" <?=t('Ок')?> ">
                        <input type="button" class="button_gray" onclick="Popup.close();" value=" <?=t('Отмена')?> ">
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
        var id = $(this).attr('id');
        $('.tab').addClass('unselected');
        $('#search, #users_selector, .friend_selector').hide();
        $(this).removeClass('unselected');
        $('#'+id+'_selector').show();
        $('#friend_counter').html('0');
        $('friend_check').attr('checked',false);
        $('.item').removeClass('selected');
        Application.invitedFriends = [];

        $('#act').children().show();
        if(id=='user'){
            $('#oundo, #odel').hide();
            $('#oadd').attr('selected','selected');
        }else if(id=='friend'){
            $('#oadd, #oundo').hide();
            $('#odel').attr('selected','selected');
        }else if(id=='edit'){
            $('#odel, #oedit').hide();
            $('#oundo').attr('selected','selected');
        }else if(id=='view'){
            $('#odel, #oview').hide();
            $('#oundo').attr('selected','selected');
        }
    });
</script>