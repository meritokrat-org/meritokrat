<h1 class="column_head mt10 mr10">
	<span class="left"><?= t('Cообщения') ?></span>
	<a class="right fs11" href="/messages/compose" style="color:white"><?= t('Написать сообщение') ?></a>

	<div class="clear"></div>
</h1>

<?php if ($list) { ?>
	<div class="mb5">
		<a class="dotted fs11 ml5" href="javascript:" id="check_all"><?= t('Выбрать все') ?></a>
		<a class="dotted fs11 ml10" href="javascript:"
           onclick="messagesController.markAsRead();"><?= t('Отметить прочитанными') ?></a>
		<a class="dotted fs11 ml5" href="javascript:" id="bulk_delete" onclick="messagesController.bulkDelete();"
           rel="<?= t('Вы уверены?') ?>"><?= t('Удалить') ?></a>
        <?php /* <input id="bulk_delete" rel="<?=t('Вы уверены?')?>" class="button" type="button" onclick="messagesController.bulkDelete();" value="<?=t('Удалить')?>"/>*/ ?>

	</div>
<?php } ?>
<div>

	<form id="messages_form">
        <?php foreach ($list as $id) {
			include 'partials/thread.php';
		} ?>
	</form>
</div>
<div class="bottom_line_d mb10 mr10"></div>
<div class="right mr10 pager"><?= pager_helper::get_full($pager) ?></div>

<script type="text/javascript">
	jQuery(document).ready(function () {
		$('div.thread').click(function (e) {
			// document.location = $(this).attr('title');
		});
		$('#check_all').click(function (e) {
            e.stopPropagation();
			if ($(this).attr('rel') === 1) {
				$('.messages_checkbox').attr('checked', false);
				$('#check_all').attr('rel', 0);
			}
			else {
				$('.messages_checkbox').attr('checked', true);
				$('#check_all').attr('rel', 1);
			}
		});
	});
</script>