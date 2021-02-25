<div class="content_pane hide" id="pane_debates">
	<div class="pl5 pt5 mb5 pb5 fs11" style="background: #F7F7F7">
		<a href="/debates-<?=$user_data['user_id']?>"><?=t('Все дебаты')?> &rarr;</a>
		<? if ( session::get_user_id() == $user['id'] ) { ?><a class="ml10" href="/debates/create"><?=t('Начать дебаты')?></a><? } ?>
	</div>
	<? if ( !$debates ) { ?>
		<div class="screen_message quiet acenter"><?=t('Участия в дебатах не замечено')?></div>
	<? } else {?>
		<? foreach ( $debates as $id ) { ?>
			<? include dirname(__FILE__) . '/../../../../debates/views/partials/item.php'; ?>
		<? } ?>
	<? } ?>
</div>