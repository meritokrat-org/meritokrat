<? if ($type=='group') { ?>
<? /*
<script type="text/javascript">
$(function() {
$("#ac").autocomplete("/messages/share_user", {
delay:10,
minChars:3,
matchSubset:1,
autoFill:false,
matchContains:1,
cacheLength:10
}
);
});
</script>
<? */ ?>
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
</script>
<div class="popup_header" rel="<?=t('Спасибо, Ваше сообщение было отправлено!')?>">
	<h2><?=$type=='group' ? t('Пригласить в сообщество') : t('Поделиться с друзьями')?></h2>
</div>

<? if ( $error ) { ?>
	<div class="m10 fs12 acenter maroon">
		<?=$error?><br /><br/>
		<input type="button" class="button_gray" onclick="Popup.close();" value=" <?=t('ОК')?> ">
	</div>
<? } else { ?>
<form rel="<?=t('Выберите друзей из списка')?>" id="share_form" action="/messages/share?process" method="post" onsubmit="return Application.shareItemProcess();"><div class="m10 fs11 aleft" style="width: 550px;">
			<div id="user_tab" class="fs18 p5 mr5 quiet left border1">
                            <a style="color: black; text-decoration: none;" onclick="$('#search, #friend_selector').hide();$('#user_pager, #users_selector').show();$('#result').show();$('#friend_tab').addClass('unselected');$('#user_tab').removeClass('unselected');" href="javascript:;"><?=t('Все')?></a>
                        </div>
			<div id="friend_tab" class="fs18 p5 mr5 quiet unselected left border1">
                            <a style="color: black; text-decoration: none;" onclick="$('#search, #users_selector').hide();$('#friend_selector').show();$('#friend_tab').removeClass('unselected');$('#user_tab').addClass('unselected');" href="javascript:;"><?=t('Друзья')?></a>
                        </div>
			<div class="mb5 quiet right">
				<input type="checkbox" id="select_all_friends" onchange="Application.friendsToggle();">
				<label for="select_all_friends"><?=t('Выбрать всех')?></label>
			</div>
			<div class="clear"></div>

			<div class="friend_selector" id="users_selector">
                            <input type="text"  autocomplete="off" id="ac" name="auto" class="cgray ml10 mb5 mt10" value="<?=t('Введите фамилию человека')?>..." style="text-decoration: none; width:440px;">
                            <input type="button" class="button" id="button" value=" <?=t('Поиск')?> ">
                            <input type="hidden" name="id" value="<?=$data['id']?>" />
                            <input type="hidden" name="type" value="<?=$type?>" />

                            <div id="search" style="display:none;"></div>

                            <div id="result">
				<? if(is_array($users)) foreach ( $users as $user_id ) { ?>
					<input type="checkbox" class="friend_check hidden" name="fs[]" id="friend_check_<?=$user_id?>" value="<?=$user_id?>">
					<div id="friend_<?=$user_id?>" rel="<?=$user_id?>" class="item friend" onclick="Application.friendSelect(<?=$user_id?>);"  style="height:50px;">
						<?=user_helper::photo($user_id, 's', array('class' => 'left', 'width' => '40'), false)?>
						<div style="margin-left: 50px;"><a class="dotted"><?=user_helper::full_name($user_id, false)?></a></div>
					</div>
				<? } ?>

                            </div>
                            <div class="clear"></div>
                        <div id="user_pager" class="mt10 mb5 ml5 left pager"><?=pager_helper::get_full_ajax($userpager,null,12,18,$q)?></div>
                        <div class="clear pb10"></div>
			</div>

                        <div id="friend_selector" class="friend_selector" style="display:none">
				<? if(is_array($friends)) foreach ( $friends as $friend_id ) { ?>
					<input type="checkbox" class="friend_check hidden" name="fs[]" id="friend_check_<?=$friend_id?>" value="<?=$friend_id?>">
					<div id="friend_<?=$friend_id?>" rel="<?=$friend_id?>" class="item friend" onclick="Application.friendSelect(<?=$friend_id?>);"  style="height:50px;">
						<?=user_helper::photo($friend_id, 's', array('class' => 'left', 'width' => '40'), false)?>
						<div style="margin-left: 50px;"><a class="dotted"><?=user_helper::full_name($friend_id, false)?></a></div>
					</div>
				<? } ?>
                                        <div class="clear"></div>
                        <!--div class="mt10 mb5 ml5 left pager"><?=pager_helper::get_full_ajax($pager,null,12,10,$q)?></div-->
				<div class="clear pb10"></div>
			</div>


			<div class="left">
                            <div id="friends_checker">
                            </div>
				<div class="mb5 mt10 quiet"><?=t('Короткое сообщение')?>: </div>
				<textarea name="message" style="height: 50px; width: 200px;"><?=$type=='group' ? t('Здравствуйте, приглашаю вас присоединиться к сообществу') : t('Привет, хочу поделиться с тобой полезной информацией')?>:</textarea>

				<div class="mt10">
					<input type="submit" class="button" value=" <?=t('Отправить')?> ">
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
<? } else { ?>
<div class="popup_header" rel="<?=t('Спасибо, Ваше сообщение было отправлено!')?>">
	<h2><?=$type=='group' ? t('Пригласить в сообщество') : t('Поделиться с друзьями')?></h2>
</div>

<? if ( $error ) { ?>
	<div class="m10 fs12 acenter maroon">
		<?=$error?><br /><br/>
		<input type="button" class="button_gray" onclick="Popup.close();" value=" <?=t('ОК')?> ">
	</div>
<? } else { ?>
	<form rel="<?=t('Выберите друзей из списка')?>" id="share_form" action="/messages/share?process" method="post" onsubmit="return Application.shareItemProcess();">
		<input type="hidden" name="id" value="<?=$data['id']?>" />
		<input type="hidden" name="type" value="<?=$type?>" />

		<div class="m10 fs11 aleft" style="width: 550px;">
			<div class="mb5 quiet left"><?=t('Выберите друзей')?>: </div>
			<div class="mb5 quiet right">
				<input type="checkbox" id="select_all_friends" onchange="Application.friendsToggle();">
				<label for="select_all_friends"><?=t('Выбрать всех')?></label>
			</div>
			<div class="clear"></div>
			<div class="friend_selector" style="height:350px;overflow: auto;">
				<? if(is_array($friends)) foreach ( $friends as $friend_id ) { ?>
					<input type="checkbox" class="friend_check hidden" name="friends[]" id="friend_check_<?=$friend_id?>" value="<?=$friend_id?>">
					<div id="friend_<?=$friend_id?>" rel="<?=$friend_id?>" class="item friend" onclick="Application.friendSelect(<?=$friend_id?>);"  style="height:50px;">
						<?=user_helper::photo($friend_id, 's', array('class' => 'left', 'width' => '40'), false)?>
						<div style="margin-left: 50px;"><a class="dotted"><?=user_helper::full_name($friend_id, false)?></a></div>
					</div>
				<? } ?>
				<div class="clear pb10"></div>
			</div>

			<div class="left">
				<div class="mb5 mt10 quiet"><?=t('Напишите короткое сообщение')?>: </div>
				<textarea name="message" style="height: 50px; width: 200px;"><?=$type=='group' ? t('Здравствуйте, приглашаю вас присоединиться к сообществу') : t('Привет, хочу поделиться с тобой полезной информацией')?>:</textarea>

				<div class="mt10">
					<input type="submit" class="button" value=" <?=t('Отправить')?> ">
					<input type="button" class="button_gray" onclick="Popup.close();" value=" <?=t('Отмена')?> ">
				</div>
			</div>
			<div style="margin-left: 225px;">
				<div class="mb5 mt10 quiet"><?//=t('Вы поделитесь этим')?> </div>
				<?=$html?>
			</div>
			<div class="clear pb5"></div>
		</div>
	</form>
<? } ?>
<? } ?>