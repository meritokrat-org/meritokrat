<script type="text/javascript">
	jQuery(document).ready(function ($) {
		$("a.del").click(function () {
			var lid = $(this).attr('rel');
			$.post('/lists/delete_user', {'uid':<?=$id?>, 'lid': lid});
			$('#inv_' + lid).remove();
		});
	});
</script>

<div class="popup_header" rel="<?= t('Участник добавлен в список!') ?>">
	<h2><?= t('Списки') ?></h2>
</div>

<? if ($error) { ?>
	<div class="m10 fs12 acenter maroon">
		<?= $error ?><br/><br/>
		<input type="button" class="button_gray" onclick="Popup.close();" value=" <?= t('ОК') ?> ">
	</div>
<? } else { ?>
	<form rel="<?= t('Выберите список') ?>" id="share_form" action="/lists/add_lists" method="post"
	      onsubmit="return Application.transferData('lists/add_lists');">

		<input type="hidden" name="id" value="<?= $id ?>"/>

		<div class="m10 fs11 aleft" style="width: 550px;">
			<div id="user" class="tab fs18 p5 mr5 quiet left border1">
				<a style="color: black; text-decoration: none;"
				   onclick="$('#friend_selector').hide();$('#user_selector').show();$('#friend').addClass('unselected');$('#user').removeClass('unselected');"
				   href="javascript:;"><?= t('Все') ?></a>
			</div>
			<div id="friend" class="tab fs18 p5 mr5 quiet unselected left border1">
				<a style="color: black; text-decoration: none;"
				   onclick="$('#friend_selector').show();$('#user_selector').hide();$('#user').addClass('unselected');$('#friend').removeClass('unselected');"
				   href="javascript:;"><?= t('В списке') ?></a>
			</div>

			<div class="clear"></div>

			<div class="friend_selector" id="user_selector" style="height:370px;overflow:auto">
				<? foreach ($lists as $list_id) { ?>
					<input type="checkbox" class="friend_check hidden" name="fs[]" id="friend_check_<?= $list_id ?>"
					       value="<?= $list_id ?>">
					<div id="friend_<?= $list_id ?>" rel="<?= $list_id ?>" class="item friend"
					     onclick="Application.thisfriendSelect(this);" style="height:50px;">
						<div style="margin-left: 50px;">
							<? $item = lists_peer::instance()->get_item($list_id) ?>
							<a class="dotted"><?= $item['title'] ?></a>
						</div>
					</div>
				<? } ?>

				<div class="clear"></div>
			</div>

			<div id="friend_selector" class="friend_selector" style="height:370px;overflow:auto;display:none">
				<? foreach ($invited as $friend_id) { ?>
					<div id="inv_<?= $friend_id ?>" rel="<?= $friend_id ?>" class="item friend"
					     style="height:50px;cursor:default">
						<div style="margin-left: 50px;">
							<? $item = lists_peer::instance()->get_item($friend_id) ?>
							<a class="dotted" href="javascript:;"><?= $item['title'] ?></a><br/>
							<a class="del" href="javascript:;" rel="<?= $friend_id ?>"><?= t('Удалить из списка') ?></a>
						</div>
					</div>
				<? } ?>
				<div class="clear"></div>
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