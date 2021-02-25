<? $argument = debates_arguments_peer::instance()->get_item($id) ?>

<div>
	<div class="mr10 p5" style="background: <?=!$argument['agree'] ? '#FFEEEE' : '#EEFFEE'?>; border: 1px solid <?=!$argument['agree'] ? '#EEAAAA' : '#AAEEAA'?>;">
		<div class="left">
			<?=user_helper::photo($argument['user_id'], 's', array('class' => 'border1'))?>
		</div>
		<div class="left ml10" style="width: 600px;">
			<div class="fs11 quiet mb5 left">
				<?=user_helper::full_name($argument['user_id'])?><Br />
				<?=date_helper::human($argument['created_ts'], ', ')?>
			</div>
			<div class=" fs11 right">
				<? if ( session::is_authenticated() && !debates_arguments_peer::instance()->has_rated($id, session::get_user_id()) ) { ?>
					<span>
						<?=tag_helper::image('common/up.gif', array('height' => 16, 'class' => 'vcenter'))?>
						<a href="javascript:;" onclick="debatesController.rateArgument(this, <?=$argument['id']?>, true);" class="dotted" onclick=""><?=t('Сильный аргумент')?></a>

						<?=tag_helper::image('common/down.gif', array('height' => 16, 'class' => 'ml10 vcenter'))?>
						<a href="javascript:;" onclick="debatesController.rateArgument(this, <?=$argument['id']?>, false);" class="dotted" onclick=""><?=t('Слабый аргумент')?></a>
					</span>
				<? } ?>

				<span class="ml10 bold" style="color:<?=$argument['rate'] >= 0 ? $argument['rate'] > 0 ? 'green' : '#999' : 'red' ?>"><?=$argument['rate'] > 0 ? '+' : ''?><?=$argument['rate']?></span>
				<span class="quiet">(<?=t('Всего')?>: <?=(int)$argument['total']?>)</span>

			</div>
			<div class="clear"></div>
			<div class="fs12 mt5"><?=nl2br(htmlspecialchars($argument['text']))?></div>
		</div>
		<div class="clear"></div>
	</div>

	<div class="mb10" style="margin-left: 70px;">

		<div id="child_arguments_<?=$argument['id']?>">
			<? $childs = explode(',', $argument['childs']); foreach ( $childs as $child_id ) { if ( $child_id = (int)$child_id ) { ?>
					<? include dirname(__FILE__) . '/child_argument.php'; ?>
			<? } } ?>
		</div>

		<? if ( session::is_authenticated() ) { ?>
			<div class="reply mt5 fs11">
				<a href="javascript:;" class="dotted" onclick="debatesController.reply(this, <?=$argument['id']?>, false)">+ <?=t('Контраргумент')?></a>
				<? if ( session::has_credential('moderator') ) { ?>
					<a href="javascript:;" onclick=" if ( confirm('Точно?') ) { $(this).parent().parent().parent().hide(); $.get('/debates/delete_argument?id=<?=$argument['id']?>') } " class="dotted ml10"><?=t('Удалить')?></a>
				<? } ?>
			</div>
		<? } ?>

	</div>
</div>