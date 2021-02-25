<script type="text/javascript">
jQuery(document).ready(function($) {
  $(document).keypress(function(event) {
  if (event.keyCode == '13') {
     event.preventDefault();
     search();
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
    $('#result').hide();
    $('#user_pager').hide();
    });
}
$(".one_delete_function").click(function() {  
        $("#member"+$(this).attr('rel')).remove();
        return false; 
});
$("#share_form").submit(function() { 
var uchval= ''; 
$(".selected").each(function(){
           var thisids=$(this).attr('rel');
           var names = $(this).find("div > .dotted").html();
           if(thisids>0)uchval+='<tr id="member'+thisids+'"><td><input type="hidden" value="'+thisids+'" name="members[]"/>'+names+' - <a class="one_delete_function" rel="'+thisids+'" href="javascript:;"><?= t('Удалить') ?></a></td></tr>';
}); 
Popup.close();
$("#shareuserstr").show();
$(uchval).insertAfter("#uchasniki");
return false;
}); 
</script>

<div class="popup_header" rel="<?=t('Спасибо, Ваше сообщение было отправлено!')?>">
	<h2><?=$header?></h2>
</div>

<? if ( $error ) { ?>
	<div class="m10 fs12 acenter maroon">
		<?=$error?><br /><br/>
		<input type="button" class="button_gray" onclick="Popup.close();" value=" <?=t('ОК')?> ">
	</div>
<? } else { ?>
<form rel="<?=t('Выберите друзей из списка')?>" id="share_form" action="" method="post">

    <input type="hidden" name="stype" value="" />

    <div class="m10 fs11 aleft" style="width: 550px;">
        <div id="user" class="tab fs18 p5 mr5 quiet left border1">
            <a style="color: black; text-decoration: none;" onclick="$('#search, #friend_selector').hide();$('#user_pager, #users_selector').show();$('#result').show();$('#friend_tab').addClass('unselected');$('#user_tab').removeClass('unselected');" href="javascript:;"><?=t('Все')?></a>
        </div>
        <div id="friend" class="tab fs18 p5 mr5 quiet unselected left border1">
            <a style="color: black; text-decoration: none;" onclick="$('#friend_tab').removeClass('unselected');$('#user_tab').addClass('unselected');" href="javascript:;"><?=t('Друзья')?></a>
        </div>


        <? if(session::has_credential('admin') && $item_type==1){ ?>
        <div id="regions" class="tab fs18 p5 mr5 quiet unselected left border1">
            <a style="color: black; text-decoration: none;" onclick="" href="javascript:;"><?=t('Регион')?></a>
        </div>
        <div id="groups" class="tab fs18 p5 mr5 quiet unselected left border1">
            <a style="color: black; text-decoration: none;" onclick="" href="javascript:;"><?=t('Сообщества')?></a>
        </div>
        <div id="status" class="tab fs18 p5 mr5 quiet unselected left border1">
            <a style="color: black; text-decoration: none;" onclick="" href="javascript:;"><?=t('Статус')?></a>
        </div>
        <div id="functions" class="tab fs18 p5 mr5 quiet unselected left border1">
            <a style="color: black; text-decoration: none;" onclick="" href="javascript:;"><?=t('Функции')?></a>
        </div>
        <? } ?>
        <? load::model('groups/groups') ?>
        <? if($data['type']==1 && groups_peer::instance()->is_moderator($data['content_id'],session::get_user_id()) && $item_type==1){ ?>
        <div class="mb5 quiet right">
                <input type="checkbox" id="select_all_friends" name="all" value="<?=$data['content_id']?>" />
                <label for="select_all_friends"><?=t('Выбрать всех<br/> членов сообщества')?></label>
        </div>
        <? } ?>
        <div class="clear"></div>

        <div class="friend_selector" id="user_selector">
            <input type="text"  autocomplete="off" id="ac" name="auto" class="cgray ml10 mb5 mt10" value="<?=t('Введите фамилию человека')?>..." style="text-decoration: none; width:440px;">
            <input type="button" class="button" id="button" value=" <?=t('Поиск')?> ">
            <input type="hidden" name="item_id" value="<?=$item_id?>" />
            <input type="hidden" name="item_type" value="<?=$item_type?>" />

            <div id="search" style="display:none;"></div>

            <div id="result">

                <? foreach ( $users as $user_id ) { ?>
                        <input type="checkbox" class="friend_check hidden" name="fs[]" id="friend_check_<?=$user_id?>" value="<?=$user_id?>">
                        <div id="friend_<?=$user_id?>" rel="<?=$user_id?>" class="item friend" onclick="Application.thisfriendSelect(this);"  style="height:50px;">
                                <?=user_helper::photo($user_id, 's', array('class' => 'left', 'width' => '40'), false)?>
                                <div style="margin-left: 50px;"><a class="dotted"><?=user_helper::full_name($user_id, false)?></a></div>
                        </div>
                <? } ?>

            </div>
            <div class="clear"></div>
        <div id="user_pager" class="mt10 mb5 ml5 left pager"><?=pager_helper::get_full_ajax($userpager,null,12,18,$q)?></div>
        <div class="clear pb10"></div>
        </div>

        <div id="friend_selector" class="friend_selector" style="height:400px;overflow:auto;display:none">
                <? foreach ( $friends as $friend_id ) { ?>
                        <input type="checkbox" class="friend_check hidden" name="fs[]" id="friend_check_<?=$friend_id?>" value="<?=$friend_id?>">
                        <div id="friend_<?=$friend_id?>" rel="<?=$friend_id?>" class="item friend" onclick="Application.thisfriendSelect(this);"  style="height:50px;">
                                <?=user_helper::photo($friend_id, 's', array('class' => 'left', 'width' => '40'), false)?>
                                <div style="margin-left: 50px;"><a class="dotted"><?=user_helper::full_name($friend_id, false)?></a></div>
                        </div>
                <? } ?>
                        <div class="clear"></div>
        <!--div class="mt10 mb5 ml5 left pager"><?=pager_helper::get_full_ajax($pager,null,12,10,$q)?></div-->
                <div class="clear pb10"></div>
        </div>

        <? if(session::has_credential('admin') && $item_type==1){ ?>

        <div id="regions_selector" class="friend_selector" style="height:400px;overflow:auto;display:none">
                <? foreach ( $regions as $k => $v ) { ?>
                        <input type="checkbox" class="friend_check hidden" name="fs[]" id="friend_check_<?=$k?>" value="<?=$k?>">
                        <div id="friend_<?=$k?>" rel="<?=$k?>" class="item friend" onclick="Application.thisfriendSelect(this);"  style="height:50px;">
                                <?//user_helper::photo(0, 's', array('class' => 'left', 'width' => '40'), false)?>
                                <div style="margin-left: 50px;"><a class="dotted"><?=$v?></a></div>
                        </div>
                <? } ?>
                <div class="clear pb10"></div>
        </div>

        <div id="groups_selector" class="friend_selector" style="height:400px;overflow:auto;display:none">
                <? foreach ( $groups as $gid ) { ?>
                        <input type="checkbox" class="friend_check hidden" name="fs[]" id="friend_check_<?=$gid?>" value="<?=$gid?>">
                        <div id="friend_<?=$gid?>" rel="<?=$gid?>" class="item friend" onclick="Application.thisfriendSelect(this);"  style="height:50px;">
                                <?=group_helper::photo($gid, 'sm', false, array('class' => 'left', 'width' => '40'))?>
                                <div style="margin-left: 50px;"><a class="dotted"><?=group_helper::title($gid, false)?></a></div>
                        </div>
                <? } ?>
                <div class="clear pb10"></div>
        </div>

        <div id="status_selector" class="friend_selector" style="height:400px;overflow:auto;display:none">
                <? foreach ( $statuses as $k => $v ) { ?>
                        <input type="checkbox" class="friend_check hidden" name="fs[]" id="friend_check_<?=$k?>" value="<?=$k?>">
                        <div id="friend_<?=$k?>" rel="<?=$k?>" class="item friend" onclick="Application.thisfriendSelect(this);"  style="height:50px;">
                                <?//user_helper::photo(0, 's', array('class' => 'left', 'width' => '40'), false)?>
                                <div style="margin-left: 50px;"><a class="dotted"><?=$v?></a></div>
                        </div>
                <? } ?>
                <div class="clear pb10"></div>
        </div>

        <div id="functions_selector" class="friend_selector" style="height:400px;overflow:auto;display:none">
                <? foreach (user_auth_peer::get_functions() as $function_id=>$function_title) { ?>
                        <input type="checkbox" class="friend_check hidden" name="fs[]" id="friend_check_<?=$function_id?>" value="<?=$function_id?>">
                        <div id="friend_<?=$function_id?>" rel="<?=$function_id?>" class="item friend" onclick="Application.thisfriendSelect(this);"  style="height:50px;">
                                <?//user_helper::photo(0, 's', array('class' => 'left', 'width' => '40'), false)?>
                                <div style="margin-left: 50px;"><a class="dotted"><?=$function_title?></a></div>
                        </div>
                <? } ?>
                <div class="clear pb10"></div>
        </div>

        <? } ?>

        <div class="left">
            <div id="friends_checker">
            </div>
                <div class="mt10">
                        <input type="submit" class="button" value=" <?=t('Добавить')?> ">
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
        $('.tab').addClass('unselected');
        $('#search, #users_selector, .friend_selector').hide();
        $(this).removeClass('unselected');
        $('input[name="stype"]').val($(this).attr('id'));
        $('#'+$(this).attr('id')+'_selector').show();
        $('#friend_counter').html('0');
        $('friend_check').attr('checked',false);
        $('.item').removeClass('selected');
    });
</script>