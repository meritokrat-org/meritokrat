<div>
	<? $num = 0; ?>
	<? foreach ($list_3 as $item) { ?>
		<? $num++; ?>
		<? $poll = polls_peer::instance()->get_item($item['obj_id']) ?>

		<? if ($big) { ?>

			<div id="invite_item_<?= $item['id'] ?>" class="mb10 p10 box_content">
				<div class="left"><?= user_helper::photo($poll['user_id'], 't') ?></div>
				<div style="margin-left: 85px;" class="ml10">
					<a href="/poll<?= $item['obj_id'] ?>" style="font-weight:normal;"
					   class="fs18"><?= stripslashes(htmlspecialchars($poll['question'])) ?></a>

					<div class="fs11 quiet mb5">
						<?= date_helper::human($poll['created_ts'], ', ') ?> &nbsp;&nbsp;
						<?= user_helper::full_name($poll['user_id']) ?>
						<div class="mt5">
							<?= t('Количество проголосовавших') ?>: <b><?= $poll['count'] ?></b> &nbsp;&nbsp;
						</div>
					</div>
				</div>
				<div class="mt10" style="margin-left:85px;">
					<a class="uline nopromt button p5" style="text-decoration:none;"
					   href="/poll<?= $item['obj_id'] ?>"><?= t('Голосовать') ?></a>&nbsp;&nbsp;
					<a class="uline nopromt button p5" style="text-decoration:none;" href="javascript:;"
					   onclick="invitesController.group('<?= $item['id'] ?>','2');"><?= t('Отказать') ?></a>
				</div>
			</div>

		<? } else { ?>
			<? if ($num > 2) break; ?>
			<div class="p5 fs12 left" style="padding-bottom:0;">
				<?= user_helper::photo($poll['user_id'], 's', array('class' => 'left mr10')) ?>
				<div class="right" style="width:185px">
					<div style="line-height:1.2;">
						<a href="/poll<?= $item['obj_id'] ?>" style="font-weight:normal;"
						   class="fs18"><?= stripslashes(htmlspecialchars($poll['question'])) ?></a>
					</div>

            <span style="color:grey">
                <?= t('Вас приглашает') . ': ' ?><?= user_helper::get_inviters(session::get_user_id(), '3', $poll['id']) ?>
            </span>
				</div>
			</div>
		<? }
	} ?>
</div>