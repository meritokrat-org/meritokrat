<div class="form_bg">
	<h1 class="column_head mt10"><a href="/messages"><?= t('Cообщения') ?></a> &rarr; <?= t('Новое сообщение') ?></h1>
	<?
	if (request::get_int('group') > 0)
		$receiver_id = request::get_int('group');
	elseif (request::get_int('team') > 0)
		$receiver_id = request::get_int('team');
	else $receiver_id = $user['user_id'];
	?>
	<form id="compose_form" name="compose_form" class="form mt10" rel="<?= t('Начинайте вводить имя друга') ?>..."
	      onsubmit="return false;">
		<input type="hidden" name="receiver_id" value="<?= $receiver_id ?>"/>
		<table width="100%" class="fs12">
			<tr>
				<td class="aright"><?= t('Имя получателя') ?></td>
				<td>
					<input type="hidden" name="sender_id" value="<?= request::get_int('sender_id') ?>">
					<?= $group_title ?>
					<input type="hidden" name="team" value="<?= request::get_int('team') ?>"/>
					<input type="hidden" value="0" rel="<?= t('Выберите получателя') ?>" style="width: 500px;"
					       name="receiver"/>
				</td>
			</tr>
			<tr>
				<td class="aright"><?= t('Сообщение') ?></td>
				<td><textarea id="body" rel="<?= t('Введите текст сообщения') ?>" name="body"
				              style="width: 500px; height:150px;"><?= (request::get('body')) ? stripslashes(htmlspecialchars(request::get('body'))) : (($message_data['body']) ? "\n\n\n" . t('Переадресованное сообщение') . ':' . $message_data['body'] : '') ?></textarea
					<? if (request::get_int('group') || request::get_int('team')) { ?>
						<br/>
						<div class="fs10">* <b>NAME</b> - для зазначення імені в листі</div>
					<? } ?>
				</td>
			</tr>
			<? if (!request::get_int('group') && !request::get_int('team')) { ?>
				<tr>
					<td></td>
					<td style="cursor: pointer; width: 600px;">
						<? $smiles = messages_peer::get_smiles();
						foreach ($smiles as $code => $value) { ?>
							<a class="ml5 mr5" onclick='InsertSmile("<?= $code ?>")'><?= $value ?></a>
						<? } ?>
				</tr>
			<? } ?>
			<tr>
				<td></td>
				<td>
					<input type="submit" name="submit" class="button" value=" <?= t('Отправить') ?> ">
					<input onclick="history.go(-1);" type="button" name="cancel" class="button_gray"
					       value=" <?= t('Отмена') ?> ">
					<?= tag_helper::wait_panel() ?>
					<div class="success hidden mr10 mt10"><?= t('Сообщение отправлено') ?></div>
				</td>
			</tr>

		</table>
	</form>
</div>
<script language="javascript" type="text/javascript">
	<!--
	var ie = document.all ? 1 : 0;
	var ns = document.getElementById && !document.all ? 1 : 0;

	function InsertSmile(SmileId) {
		if (ie) {
			document.all.body.focus();
			document.all.body.value += " " + SmileId + " ";
		}

		else if (ns) {
			document.forms['compose_form'].elements['body'].focus();
			document.forms['compose_form'].elements['body'].value += " " + SmileId + " ";
		}
	}
	// -->
</script>