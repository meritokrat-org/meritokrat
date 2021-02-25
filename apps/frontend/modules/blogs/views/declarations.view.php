<? $sub_menu = '/blogs/news';
load::view_helper('image');/*?>

<div class="left" style="width: 35%;"><? include 'partials/left.php' ?></div>
*/?>
<div class="left ml10 mt10" style="width: 97%;">
			<h1 class="column_head"><?=t('Объявления')?></h1>

	<? foreach ( $list as $id ) { ?>
		<? $post_data = blogs_posts_peer::instance()->get_item($id) ?>
		<? include 'partials/post.php'; ?>
	<? } ?>

	<div class="right pager"><?=pager_helper::get_short($pager)?></div>

</div>