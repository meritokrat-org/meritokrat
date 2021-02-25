<? include 'partials/sub_menu.php' ?>

<div class="left" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10" style="width: 62%;">
	<div class="column_head">
            <h1 class="left column_head p0" style="background: none"><?=user_helper::full_name($user_id)?> &rarr; <?=t('Опросы')?></h1>
	</div>

	<? foreach ( $list as $id ) include 'partials/poll.php'; ?>
	<div class="bottom_line_d mb10" style="margin-left: 60px;"></div>
	<div class="right pager"><?=pager_helper::get_full($pager)?></div>
</div>