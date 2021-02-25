<? $comment = blogs_comments_peer::instance()->get_item($id) ?>
<? $post_data = blogs_posts_peer::instance()->get_item($comment['post_id']) ?>

<div class="left mr10 acenter" style="width: 70px;">
	<?=user_helper::photo($comment['user_id'], 's', array('class' => 'mt5 border1'))?>
	<div class="quiet fs10 mb10 acenter"><?=date_helper::human($comment['created_ts'])?></div>

</div>
<div style="margin-left: 80px;">
	<h5 class="mb5"><a href="/blogpost<?=$post_data['id']?>"><?=stripslashes(htmlspecialchars($post_data['title']))?></a></h5>
	<div class="fs12">
		<div class="mb10"><?=stripslashes(nl2br(htmlspecialchars($comment['text'])))?></div>
		<div class="p5" style="background: #F7F7F7;">
			<?=t('Оставил')?>: <?=user_helper::full_name($comment['user_id'])?><br />
			<a style="margin-right: 25px;" href="/blogpost<?=$post_data['id']?>#comments" class="fs11"><?=t('Все комментарии')?> &rarr;</a>
		</div>
	</div>
</div>
<div class="clear mb5"></div>
<br />