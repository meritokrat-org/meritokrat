<h1 class="column_head"><?=t('Важные вопросы')?></h1>

<? foreach ( $promoted as $id ) { ?>
	<? $poll = polls_peer::instance()->get_item($id) ?>
	<div class="box_content fs12 p10 mb10">
		<a href="/poll<?=$id?>"><?=stripslashes(htmlspecialchars($poll['question']))?></a>
		<div class="fs11 mt5"><?=user_helper::full_name($poll['user_id'])?></div>
		<div class="fs11 mb5 quiet"><?=date_helper::human($poll['created_ts'], ', ')?></div>
	</div>
<? } ?>