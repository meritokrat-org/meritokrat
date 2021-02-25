<? $sub_menu = '/polls/mine'; ?>
<? include 'partials/sub_menu.php' ?>

<div class="left" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10" style="width: 62%;">
	<h1 class="column_head"><?=t('Мои вопросы')?></h1>

	<? foreach ( $list as $id ) include 'partials/poll.php'; ?>
	<div class="bottom_line_d mb10" style="margin-left: 60px;"></div>
	<div class="right pager"><?=pager_helper::get_full($pager)?></div>
</div>