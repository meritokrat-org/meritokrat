<style type="text/css">
	.box_content_dark {
		background-color: #e6e6e6;
	}
</style>
<div class="box_content mb10">
	<div class="left ml5" style="width: 60px;">
		<? $group = team_peer::instance()->get_item($post_data['team_id']);
		if (!$post_data['photo']) {
			echo group_helper::photo($post_data['team_id'], 's', true, array('class' => 'border1'));
		} else
			echo image_helper::photo($post_data['photo'], 's', 'groupnews', array('class' => 'border1'));
		?>
		<div class="quiet fs11 mb10 acenter"
		     style="line-height: 1.2;"><?= date_helper::human($post_data['created_ts']) ?></div>

	</div>
	<div class="mt5" style="margin-left: 70px;">
		<? if (request::get('bookmark')) { ?>
			<? $bkm = bookmarks_peer::instance()->is_bookmarked(session::get_user_id(), 1, $post_data['id']); ?>
			<a class="bookmark mb10 ml5 right" style="<?= ($bkm) ? 'display:none;' : 'display:block;' ?>"
			   href="#add_bookmark"
			   onclick="Application.bookmarkThisItem(this,'1','<?= $post_data['id'] ?>');return false;"><span><?= t('В закладки') ?></span></a>
			<a class="unbkmrk mb10 ml5 right" style="<?= ($bkm) ? 'display:block;' : 'display:none;' ?>"
			   href="#del_bookmark"
			   onclick="Application.unbookmarkThisItem(this,'1','<?= $post_data['id'] ?>');return false;"><span><?= t('Удалить из закладок') ?></span></a>
		<? } ?>
		<h5 class="mb5">
			<a href="/team/newsread?id=<?= $post_data['id'] ?>" style="font-weight:normal;"
			   class="fs18"><?= stripslashes(htmlspecialchars($post_data['title'])) ?></a>
		</h5>

		<div class="fs12">
			<div
				class="mb5"><?= /*in_array($post_data['mission_type'],array(2,3)) ? '' : strlen($post_data['anounces'])>6 ? */
				stripslashes(htmlspecialchars($post_data['anounces']))// : tag_helper::get_short(stripslashes(htmlspecialchars(strip_tags($post_data['body']))),250) ?></div>
			<div
				class="p5  box_content<?= ((request::get('action') == 'declarations' || request::get('action') == 'news') && $post_data['favorite']) ? '_dark' : '' ?>">
				<?
				load::model('user/user_data');
				$post_user = user_data_peer::instance()->get_item($post_data['user_id']);
				?>
				<a class="fs11 mr15"
				   href="/team<?= $post_data['team_id'] ?>"><?= stripslashes(htmlspecialchars($group['title'])) ?></a>
				<a href="/team/newsread?id=<?= $post_data['id'] ?>"
				   class="fs11 cgray mr15"><?= t('Читать дальше') ?> &rarr;</a>
				<a href="/team/newsread?id=<?= $post_data['id'] ?>" class="fs11 cgray"><?= t('Просмотров') ?>
					: <?= (int)$post_data['views'] ?></a>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>