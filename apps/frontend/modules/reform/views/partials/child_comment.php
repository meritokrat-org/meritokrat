<? if ( !$child_comment = ppo_photo_comments_peer::instance()->get_item($child_id) ) { return; } ?>
<div class="fs11 mt10 p5" style="border: 1px solid #E4E4E4; background: #F9F9F9;">
	<?=user_helper::photo($child_comment['user_id'], 's', array('class' => 'border1 left'))?>
	<div style="margin-left: 60px;">
		<div class="left quiet">
			<?=user_helper::full_name($child_comment['user_id'])?> &nbsp;
			<?=user_helper::com_date(date($child_comment['created_ts']))?>
                <div class="right">
			<? if ( session::is_authenticated() && !blogs_comments_peer::instance()->has_rated($child_comment['id'], session::get_user_id()) ) { ?>
				<span>
					<a href="javascript:;" onclick="ppoController.rateComment(this, <?=$child_comment['id']?>, true);"><?=tag_helper::image('common/up.gif', array('height' => 16, 'class' => 'vcenter'))?></a>
					<a href="javascript:;" onclick="ppoController.rateComment(this, <?=$child_comment['id']?>, false);"><?=tag_helper::image('common/down.gif', array('height' => 16, 'class' => 'vcenter'))?></a>
				</span>
			<? } ?>
			<span class="bold mr10" style="color:<?=$child_comment['rate'] >= 0 ? $child_comment['rate'] > 0 ? 'green' : '#999' : 'red' ?>"><?=$child_comment['rate'] > 0 ? '+' : ''?><?=$child_comment['rate']?></span>
		</div>
		</div>
		<br />
		<div class="mt10">
			<?=user_helper::get_links($child_comment['text'])?>
		</div>
		<? if ( session::has_credential('moderator') || ( $child_comment['user_id'] == session::get_user_id() ) ) { ?>
			<br /><a href="javascript:;" onclick="if ( confirm('Точно?') ) { $(this).parent().parent().hide(); $.get('/ppo/delete_photo_comment?id=<?=$child_comment['id']?>') } " class="dotted"><?=t('Удалить')?></a>
		<? } ?>
	</div>
	<div class="clear"></div>
</div>