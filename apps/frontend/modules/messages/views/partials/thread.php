<? $message = messages_peer::instance()->get_item($id) ?>

<? $is_mine = $message['sender_id'] == session::get_user_id(); ?>

<div id="message_<?= $id ?>" title="http://<?= conf::get('server') ?>/messages/view?id=<?= $message['thread_id'] ?>"
     onmouseover="document.getElementById('del_message_<?= $id ?>').style.display ='block'"
     onmouseout="document.getElementById('del_message_<?= $id ?>').style.display ='none'"
     class="d-flex thread box_empty p10 mb10 mr10 <?= !$message['is_read'] ? 'highlight' : 'box_content' ?>">
	<div class="mr10">
		<input type="checkbox" name="messages[]" value="<?= $message['thread_id'] ?>" class="messages_checkbox"/>
	</div>

	<a href="/messages/view?id=<?= $message['thread_id'] ?>">
		<?= user_helper::photo($is_mine ? $message['receiver_id'] : $message['sender_id'], 's', array('class' => 'border1 left')) ?>
	</a>

	<div class="pointer flex-fill ml-3">
		<div class="bold left">
			<a href="/messages/view?id=<?= $message['thread_id'] ?>" style="color:black;text-decoration:none">
				<? if ($is_mine) { ?>
					<?= t('Я') ?> &rarr; <?= user_helper::full_name($message['receiver_id'], false) ?>
				<? } else { ?>
					<?= user_helper::full_name($message['sender_id'], false) ?>
				<? } ?>
				<?// $num = db::get_scalar('SELECT COUNT(*) FROM messages WHERE thread_id = ' . $message['thread_id'] . ' AND owner != ' . session::get_user_id()) ?>
				<? $num = 0 ?>
				<? if ($num > 1) { ?>
					<?= ' (' . ($num) . ')' ?>
				<? } ?>
			</a>
		</div>
		<div class="right quiet fs11">
			<?= date('H:i d/m/y', $message['created_ts']) ?><br/>

			<div class="fs11 quiet hidden mt10" id="del_message_<?= $id ?>">
				<a href="/messages/compose?resend=<?= $message['id'] ?>"><?= t('Переслать') ?></a><br/>
				<a href="/messages/delete?id=<?= $message['thread_id'] ?>"
				   onclick="return confirm('<?= t('Удалить сообщение?') ?>');"><?= t('Удалить') ?></a>
			</div>
		</div>
		<br/>

		<div class="fs11 quiet" style="width:550px">
			<? $new_num = ''; ?>
			<a style="color:black;text-decoration:none" href="/messages/view?id=<?= $message['thread_id'] ?>">
				<? if (!$message['is_read']) { ?>
					<?// $num = db::get_scalar('SELECT COUNT(id) FROM messages WHERE thread_id = ' . $message['thread_id'] . ' AND is_read = false LIMIT 11') ?>
					<? $num = 0; ?>
					<? if ($num > 1) { ?>
						<? $new_num = ' (' . ($num > 10 ? '10+' : $num) . ')' ?>
					<? } ?>
				<? } ?>
				<?= $message['is_read'] ? '' : '<b style="color:green;">' . t('Новое сообщение') . $new_num . ':</b>' ?>
				<?= strip_tags(html_entity_decode(stripslashes(strtr(text_helper::smart_trim(strip_tags($message['body'], '<br><br />'), 320), array('<p>' => '', '</p>' => ''))))) ?>
			</a>
		</div>
		<div class="fs11 quiet hidden right" id="del_message_<?= $id ?>">
			<a href="/messages/delete?id=<?= $message['thread_id'] ?>"
			   onclick="return confirm('<?= t('Удалить сообщение?') ?>');"><?= t('Удалить') ?></a>
		</div>
	</div>
</div>