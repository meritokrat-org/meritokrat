<script type="text/javascript">
	jQuery(document).ready(function ($) {
		$(document).keypress(function (event) {
			if (event.keyCode == '13') {
				event.preventDefault();
				$("#button").click();
			}
		});
		$("#button").click(function () {
			search();
		});
		$("a.reject").click(function () {
			var uid = $(this).parent('div').parent('div').attr('rel');
			$.post('/invites/delete?tp=2&oid=<?=$data['id']?>&uid=' + uid);
			$(this).parent('div').parent('div').remove();
		});
		$('#ac')
			.focus(function () {
				if ($(this).val() == '<?=t('Введите фамилию человека')?>...') {
					$(this).val('');
				}
			})
			.blur(function () {
				if ($(this).val() == '') {
					$(this).val('<?=t('Введите фамилию человека')?>...');
				}
			})
	});
	function search() {
		var zelenoye = $('#ac').val();
		$.get('/messages/share_user', {q: zelenoye},
			function (data) {
				$('#search').html(data).show();
				for (var i in Application.invitedFriends) {
					if (!Application.invitedFriends.hasOwnProperty(i)) continue;
					$('#search').find('#friend_' + Application.invitedFriends[i]).addClass('selected');
				}
				$('#result').hide();
				$('#user_pager').hide();
			});
	}
</script>

<div class="popup_header" rel="<?= t('Спасибо, Ваше сообщение было отправлено!') ?>">
	<h2><?= $header ?></h2>
</div>

<? if ($error) { ?>
	<div class="m10 fs12 acenter maroon">
		<?= $error ?><br/><br/>
		<input type="button" class="button_gray" onclick="Popup.close();" value=" <?= t('ОК') ?> ">
	</div>
<? } else { ?>
	<form rel="<?= t('Выберите людей из списка') ?>" id="share_form" action="/projects/add_to_project" method="post"
	      onsubmit="return Application.addToProject();">

		<input type="hidden" name="project_id" value="<?=$projectId?>"/>

		<div class="m10 fs11 aleft" style="width: 550px;">

			<div class="friend_selector" id="user_selector">
				<input type="text" autocomplete="off" id="ac" name="auto" class="cgray ml10 mb5 mt10"
				       value="<?= t('Введите фамилию человека') ?>..." style="text-decoration: none; width:440px;">
				<input type="button" class="button" id="button" value=" <?= t('Поиск') ?> ">
				<input type="hidden" name="item_id" value="<?= $item_id ?>"/>
				<input type="hidden" name="item_type" value="<?= $item_type ?>"/>

				<div id="search" style="display:none;"></div>

				<div id="result">

					<? foreach ($pager as $user_id) { ?>
						<input type="checkbox" class="friend_check hidden" name="fs[]" id="friend_check_<?= $user_id ?>"
						       value="<?= $user_id ?>">
						<div id="friend_<?= $user_id ?>" rel="<?= $user_id ?>" class="item friend"
						     onclick="Application.thisfriendSelect(this);" style="height:50px;">
							<?= user_helper::photo($user_id, 's', array('class' => 'left', 'width' => '40'), false) ?>
							<div style="margin-left: 50px;">
								<?= user_helper::full_name($user_id, true, array('class' => 'dotted', 'target' => '_blank'), false) ?>
								<?= (is_array($invited) && in_array($user_id, $invited)) ? '<br/><span style="color:green;">' . t('Уже приглашен') . '</span>' : '' ?>
							</div>
						</div>
					<? } ?>

				</div>
				<div class="clear"></div>
				<div id="user_pager"
				     class="mt10 mb5 ml5 left pager"><?= pager_helper::get_full_ajax($pager, 1, 12, 18, $q) ?></div>
				<div class="clear pb10"></div>
			</div>

			<div class="left">
				<div id="friends_checker">
				</div>

				<div class="mt10">
					<input type="submit" class="button" value=" <?= t('Ок') ?> ">
					<input type="button" class="button_gray" onclick="Popup.close();" value=" <?= t('Отмена') ?> ">
				</div>
			</div>
			<div style="margin-left: 225px;">
				<div class="mt5 quiet"><?= t('Выбрано') ?>: <span id="friend_counter" class="fs18">0</span></div>
				<?= $html ?>
			</div>
			<div class="clear pb5"></div>
		</div>
	</form>
<? } ?>

<script type="text/javascript">
	$(document).ready(function(){
		Application.goToPage('', 1);
	});
</script>