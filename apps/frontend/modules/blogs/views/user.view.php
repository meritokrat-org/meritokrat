<? if ( $user_id == session::get_user_id() ) { $sub_menu = '/blog-mine'; } ?>
<? include 'partials/sub_menu.php' ?>

<div class="left" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10" style="width: 62%;">

		<? if ( $user_id == session::get_user_id() ) { ?>
    <h1 class="column_head"><?=t('Мои записи')?></h1>
		<? } else { ?>
			<h1 class="column_head"><?=user_helper::full_name($user_id)?> &rarr; <?=t('Записи')?></h1>
			<a class="right" href="/blogs/rss?user=<?=$user_id?>"><?=tag_helper::image('icons/rss.gif', array('class' => 'mt10', 'title' => 'RSS'))?></a>
		<? } ?>

	<? if ( !$list ) { ?>
		<div class="screen_message acenter"><?=t('Тут еще нет записей')?></div>
	<? } else { ?>
		<? foreach ( $list as $id ) { ?>
			<? $post_data = blogs_posts_peer::instance()->get_item($id) ?>
			<? include 'partials/post.php'; ?>
		<? } ?>
	
		<div class="right pager"><?=pager_helper::get_full($pager, null, null, 20)?></div>
	<? } ?>

</div>