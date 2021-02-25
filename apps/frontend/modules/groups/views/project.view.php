<? /* $sub_menu = '/groups/new'; ?>
<? include 'partials/sub_menu.php' */?>

<div class="left" style="width: 35%;"><?// include 'partials/left.php' ?><h1 class="column_head">Сфера</h1></div>

<div class="left ml10" style="width: 62%;">
	<h1 class="column_head"><?=t('Новые проекты')?></h1>

	<? foreach ( $hot as $id ) { include 'partials/group.php'; } ?>
	<div class="bottom_line_d mb10"></div>
	<div class="right pager"><?=pager_helper::get_full($pager)?></div>

</div>