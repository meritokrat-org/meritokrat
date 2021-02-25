<? if ( !$child_argument = debates_arguments_peer::instance()->get_item($child_id) ) { return; } ?>

<div class="fs11 mt10 mr10 p5" style="background: #FAFAFA; border: 1px solid #EEE;">
	<?=user_helper::photo($child_argument['user_id'], 's', array('class' => 'border1 left'))?>
	<div class="left ml5" style="width: 530px;">
		<div class="quiet left">
			<?=user_helper::full_name($child_argument['user_id'])?> &nbsp;
			<?=date_helper::human($child_argument['created_ts'], ', ')?>
		</div>
		<div class="fs11 right">
			<? if ( session::is_authenticated() && !debates_arguments_peer::instance()->has_rated($child_argument['id'], session::get_user_id()) ) { ?>
				<span>
					<a href="javascript:;" onclick="debatesController.rateArgument(this, <?=$child_argument['id']?>, true);"><?=tag_helper::image('common/up.gif', array('height' => 16, 'class' => 'vcenter'))?></a>
					<a href="javascript:;" onclick="debatesController.rateArgument(this, <?=$child_argument['id']?>, false);"><?=tag_helper::image('common/down.gif', array('height' => 16, 'class' => 'ml10 vcenter'))?></a>
				</span>
			<? } ?>

			<span class="ml10 bold" style="color:<?=$child_argument['rate'] >= 0 ? $child_argument['rate'] > 0 ? 'green' : '#999' : 'red' ?>"><?=$child_argument['rate'] > 0 ? '+' : ''?><?=$child_argument['rate']?></span>
			<span class="quiet">(<?=t('Всего')?>: <?=(int)$child_argument['total']?>)</span>
		</div>
		<div class="clear"></div>
		<div class="mt5"><?=htmlspecialchars($child_argument['text'])?></div>
		<? if ( session::has_credential('moderator') ) { ?>
			<a href="javascript:;" onclick=" if ( confirm('Точно?') ) { $(this).parent().parent().parent().hide(); $.get('/debates/delete_argument?id=<?=$child_argument['id']?>') } " class="dotted"><?=t('Удалить')?></a>
		<? } ?>
	</div>
	<div class="clear"></div>
</div>