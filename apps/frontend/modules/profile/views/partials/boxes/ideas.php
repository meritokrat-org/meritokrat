<div class="content_pane hidden" id="pane_ideas">
	<div class="pl5 pt5 mb5 pb5 fs11" style="background: #F7F7F7">
		<a href="/userideas<?=$user_data['user_id']?>"><?=t('Все идеи')?> &rarr;</a>
		<? if ( session::get_user_id() == $user['id'] ) { ?>
			<a class="ml10" href="/ideas/create"><?=t('Высказать идею')?></a>
		<? } ?>
	</div>

	<? if ( !$ideas_list ) { ?>
		<div class="screen_message quiet acenter"><?=t('У пользователя еще нет идей')?></div>
	<? } ?>

	<? foreach ( $ideas_list as $id ) { ?>
		<? $idea = ideas_peer::instance()->get_item($id) ?>
		<? include dirname(__FILE__) . '/../../../../ideas/views/partials/idea.php'; ?>
	<? } ?>
</div>