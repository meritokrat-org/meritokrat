<? $sub_menu = '/blogs/discussed'; ?>
<? include 'partials/sub_menu.php' ?>

<div class="left" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10" style="width: 62%;">

		<h1 class="column_head"> <?=t('Обсуждаемые записи')?></h1>
	<? foreach ( $list as $id ) { ?>
		<? $post_data = blogs_posts_peer::instance()->get_item($id) ?>
		<? include 'partials/post.php'; ?>
	<? } ?>

	<div class="right pager"><?=pager_helper::get_short($pager)?></div>

</div>