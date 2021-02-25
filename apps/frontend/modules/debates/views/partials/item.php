<? $data = debates_peer::instance()->get_item($id) ?>

<div class="mb10">
	<?=user_helper::photo($data['user_id'], 's', array('class' => 'left border1'))?>
	<div class="ml10 left" style="width: 360px;">
		<div><a href="/debate<?=$id?>"><?=htmlspecialchars(text_helper::smart_trim($data['text'], 128))?></a></div>
		<div class="mt5 mb5 fs11 quiet">
			<?=user_helper::full_name($data['user_id'])?> &nbsp;
			<?=date_helper::human($data['created_ts'], ', ')?>
		</div>
		<div class="fs11 mt10">
			<div class="left" style="width: 100px;"><b><?=$data['for']?></b> За</div>
			<div class="left mt5" style="width: <?=ceil($data['for']/max($data['for'], $data['against'], 1)*250)?>px; background: #DDEEDD; border: 1px solid #AACCAA; height: 5px;"></div>
			<div class="clear"></div>
		</div>
		<div class="fs11 mt5">
			<div class="left" style="width: 100px;"><b><?=$data['against']?></b> <?=t('Против')?></div>
			<div class="left mt5" style="width: <?=ceil($data['against']/max($data['for'], $data['against'], 1)*250)?>px; background: #EEDDDD; border: 1px solid #CCAAAA; height: 5px;"></div>
			<div class="clear"></div>
		</div>
		<? if ( $arguments = debates_arguments_peer::instance()->get_by_debate($data['id']) ) { ?>
			<? $argument = debates_arguments_peer::instance()->get_item( array_pop($arguments) ) ?>
			<div class="fs11 mt10 p5" style="border: 1px solid #E4E4E4; background: #F9F9F9;">
				<div class="mb5 quiet">
					<?=t('Последний аргумент')?>
					(<?=user_helper::full_name($argument['user_id'])?>, <?=date('H:i', $argument['created_ts'])?>):
				</div>
				<?=tag_helper::image('common/' . ( $argument['agree'] ? 'up' : 'down' ) . '.gif', array('class' => 'vcenter', 'height' => 15))?>
				<?= htmlspecialchars($argument['text']) ?>
			</div>
		<? } ?>
		<div class="mt5">
			<a class="fs11" href="/debate<?=$data['id']?>"><?=t('Все аргументы')?> &rarr;</a>
		</div>
	</div>
	<div class="clear"></div>
</div>
<br />