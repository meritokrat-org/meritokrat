<? $sub_menu = '/blogs/favorites'; ?>
<? include 'partials/sub_menu.php' ?>

<div class="left" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10" style="width: 62%;">

		<h1 class="column_head"><?=t('Любимые авторы')?></h1>
		<a class="right" href="/blogs/rss?type=favorites"><?=tag_helper::image('icons/rss.gif', array('class' => 'mt10', 'title' => 'RSS'))?></a>

	<? if(count($list)>0){ ?>
    <? foreach ( $list as $id ) { ?>
		<? $post_data = blogs_posts_peer::instance()->get_item($id) ?>
		<? include 'partials/post.php'; ?>
	<? } ?>
    <? }else{ ?>
        <div class="fs12 mt10" style="text-align:center;color:grey;"><?=t('Тут еще нет записей')?></div>
    <? } ?>

	<div class="right pager"><?=pager_helper::get_short($pager)?></div>

</div>