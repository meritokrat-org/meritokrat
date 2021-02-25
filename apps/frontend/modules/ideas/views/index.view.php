<? $sub_menu = '/ideas'; ?>
<? include 'partials/sub_menu.php' ?>

<div class="left" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10" style="width: 62%;">
	<? if ( $segment ) { ?>
		<h1 class="column_head"><a href="/ideas"><?=t('Идеология')?></a> &rarr; <?=htmlspecialchars($segment)?></h1>
	<? } else { ?>
		<h1 class="column_head"><?=t('Новые записи')?></h1>
	<? } ?>


	<? foreach ( $list as $id ) { include 'partials/idea.php'; } ?>

	<div class="bottom_line_d mb10"></div>
	<div class="right pager"><?=pager_helper::get_short($pager)?></div>
</div>