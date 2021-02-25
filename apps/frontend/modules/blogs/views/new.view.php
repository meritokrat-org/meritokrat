<? $sub_menu = '/blogs/new'; ?>
<? include 'partials/sub_menu.php' ?>

<div class="left" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10" style="width: 62%;">

	<h1 class="column_head"><?=t('Все свежие записи')?></h1>

	<? foreach ( $list as $id ) { ?>
		<? $post_data = blogs_posts_peer::instance()->get_item($id) ?>
		<? include 'partials/post.php'; ?>
	<? } ?>

	<div class="bottom_line_d mb10" style="margin-left: 75px;"></div>
	<div class="right pager"><?=pager_helper::get_short($pager)?></div>

</div>