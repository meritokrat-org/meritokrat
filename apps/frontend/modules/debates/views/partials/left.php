<h1 class="column_head"><?=t('Облако меток')?></h1>
<div style="background:#F7F7F7;" class="p10 acenter">
	<? if ( !$top_tags ) echo '<div class="fs11 quiet">' . t('Меток нет') . '</div>'; ?>
	<? foreach ( $top_tags as $tag_data ) { ?>
		<? $name = debates_tags_peer::instance()->get_name($tag_data['id']); ?>
		<a href="/debates/hot?tag=<?=htmlspecialchars($name)?>" style="<?= $name==$tag ? 'color: #772f23; text-decoration: none;' : ''?>font-size: <?=9+$tag_data['weight']?>px; margin: 1px;"><?=$name?></a>
	<? } ?>
</div>

<br />
<h1 class="column_head"><?=t('Лента аргументов')?></h1>
<div style="width: 239px; overflow: hidden;">
<? foreach ( $newest_arguments as $id ) { ?>
	<? $argument = debates_arguments_peer::instance()->get_item($id) ?>
	<? $debate = debates_peer::instance()->get_item($argument['debate_id']) ?>
		<div style="background:#F7F7F7;" class="p10 mb10">
			<div class="left fs11" style="margin-top: 3px;"><?=date('H:i', $argument['created_ts'])?></div>
			<div class="left ml10" style="width: 165px;">
				<?=tag_helper::image('common/' . ( $argument['agree'] ? 'up' : 'down' ) . '.gif', array('class' => 'vcenter ml5', 'height' => 15))?>
				<a class="fs12" href="/debate<?=$debate['id']?>"><?=htmlspecialchars(text_helper::smart_trim($debate['text'], 128))?></a>
				&rarr;
				<span class="fs12"><?=htmlspecialchars(text_helper::smart_trim($argument['text'], 128))?></span>
				<div class="fs11"><?=user_helper::full_name($argument['user_id'], true, array('class' => 'quiet'))?></div>
			</div>
			<div class="clear"></div>
		</div>
<? } ?>
</div>