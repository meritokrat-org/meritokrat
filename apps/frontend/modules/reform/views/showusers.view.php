<script type="text/javascript">
jQuery(document).ready(function($) {
    $('#ac').val('');
    search();
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
    $.get('/ppo/share_user',  {q: zelenoye, ppo_id: $('#ppo_id').val(),more_select:<?=request::get_int('more_select')?>},
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

$("#share_form").submit(function() { 
<?if(request::get('more_select')==1){?>
var uchval=''; 
$("#search > .selected").each(function(){
           var thisids=$(this).attr('rel');
           var names = $(this).find("div > .dotted").html();
           uchval+='<tr id="member'+thisids+'"><td></td><td><input type="hidden" value="'+thisids+'" name="members[]"/>'+names+' - <a class="one_delete_function" rel="'+thisids+'" href="javascript:;"><?= t('Удалить') ?></a></td></tr>';
}); 
$(uchval).insertAfter("#uchasniki");
<?}else{?>
var id = $('input#act').val();
var name = $(".selected > div > .dotted").html();
var thisid=$("#search > .selected").attr('rel');   
<?if(request::get('multi')==1){?>      
$('<tr id="member'+thisid+'"><td></td><td><input type="hidden" value="'+thisid+'" name="function4[]"/>'+name+' - <a class="one_delete_function" rel="'+thisid+'" href="javascript:;"><?= t('Удалить') ?></a></td></tr>').insertBefore("."+id);
<?}else{?>       
$("#"+id).val(name);
$("#"+id+"div").html(name);
<?}?>
$("input#"+id+"id").val(thisid);
<?}?> 
$(".one_delete_function").click(function() { 
        $("#member"+$(this).attr('rel')).remove();
        return false; 
}); 
Application.invitedFriends=[];
$("#search").html('');
Popup.close();
return false;
});
</script>

<div class="popup_header">
	<h2><?=t('Выберите пользователя')?></h2>
</div>

<form rel="<?=t('Выберите пользователя')?>" id="share_form" action="/ppo/showusers" method="post">
    <input type="hidden" name="act" id="act" />
    <input type="hidden" name="ppo_id" id="ppo_id" value="<?=request::get_int('ppo_id')?>" />
    <div class="m10 fs11 aleft" style="width: 550px;">

        <div class="friend_selector" id="user_selector">
            <input type="text"  autocomplete="off" id="ac" name="auto" class="cgray ml10 mb5 mt10" value="<?=t('Введите фамилию человека')?>..." style="text-decoration: none; width:440px;">
            <input type="button" class="button" id="button" value=" <?=t('Поиск')?> ">

            <div id="search" style="display:none;"></div>

            <div id="result">

                <? foreach ( $users as $user_id ) { ?>
                        <input type="checkbox" class="friend_check hidden" name="fs[]" id="friend_check_<?=$user_id?>" value="<?=$user_id?>">
                        <div id="friend_<?=$user_id?>" rel="<?=$user_id?>" class="item friend" onclick="Application.thisOneUserSelect(this);"  style="height:50px;">
                                <?=user_helper::photo($user_id, 's', array('class' => 'left', 'width' => '40'), false)?>
                                <div style="margin-left: 50px;">
                                    <a class="dotted"><?=user_helper::full_name($user_id, false, array(), false)?></a>
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
                <div class="mt10">
                        <input type="submit" class="button" value=" <?=t('Отправить')?> ">
                        <input type="button" class="button_gray" onclick="Application.invitedFriends=[];Popup.close();" value=" <?=t('Отмена')?> ">
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