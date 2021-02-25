<? if ( !$child_comment = photo_comments_peer::instance()->get_item($child_id) ) { return; } ?>
<div class="fs11 mt10 p5" style="border: 1px solid #E4E4E4; background: #F9F9F9;">
	<?=user_helper::photo($child_comment['user_id'], 's', array('class' => 'border1 left'))?>
	<div style="margin-left: 60px;">
		<div class="left quiet">
			<?=user_helper::full_name($child_comment['user_id'])?> &nbsp;
			<?=user_helper::com_date(date($child_comment['created_ts']))?>
		</div>
		<br />
		<div class="mt10">
			<?=nl2br(htmlspecialchars($child_comment['text']))?>
		</div>
		<? if ( session::has_credential('moderator') || ( $child_comment['user_id'] == session::get_user_id() ) ) { ?>
			<br /><a href="javascript:;" onclick="if ( confirm('Точно?') ) { $(this).parent().parent().hide(); $.get('/photo/delete_comment?id=<?=$child_comment['id']?>') } " class="dotted"><?=t('Удалить')?></a>
		<? } ?>
	</div>
	<div class="clear"></div>
</div>