<div class="content_pane hide" id="pane_polls">
	<div class="pl5 pt5 mb5 pb5 fs11" style="background: #F7F7F7">
		<a style="color:gray;" href="/polls-<?=$user_data['user_id']?>"><?=t('Все вопросы')?> &rarr;</a>
		<? if ( session::get_user_id() == $user['id'] ) { ?><a class="ml10" style="color:gray;" href="/polls/create"><?=t('Создать вопрос')?></a><? } ?>
	</div>
	<? if ( !$polls ) { ?>
		<div class="screen_message quiet acenter"><?=t('Нет опросов')?></div>
	<? } else { ?>
		<? foreach ( $polls as $id ) { ?>
			<? include dirname(__FILE__) . '/../../../../polls/views/partials/poll.php'; ?>
		<? } ?>
	<? } ?>
</div>