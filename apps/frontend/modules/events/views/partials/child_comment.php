<? if ( !$child_comment = events_comments_peer::instance()->get_item($child_id) ) { return; } ?>
<div id="comment<?=$child_comment['id']?>"  class="fs11 ml60 mb15 mt10 p5" style="border: 1px solid #E4E4E4; background: #F9F9F9;">
	<? //$do_hide_comment = $child_comment['rate'] <= -5; ?>

	<div class="comment_hdata<?=$child_comment['id']?> left <?=$do_hide_comment ? 'hidden' : ''?>">
		<?=user_helper::photo($child_comment['user_id'], 's', array('class' => 'border1'))?>
	</div>

	<div style="margin-left: 60px;">
		<div class="left quiet">
			<?=user_helper::full_name($child_comment['user_id'])?> &nbsp;
			<?=user_helper::com_date(date($child_comment['created_ts']))?> &nbsp;
                        <?=($child_comment['edit'])?t('Отредактировано').': '.strip_tags(user_helper::full_name($child_comment['edit']),'<a>').(($child_comment['edit_ts'])?' '.user_helper::com_date(date($child_comment['edit_ts'])):''):''?>
			<? if ( $do_hide_comment ) { ?>
				<a href="javascript:;" onclick="$('.comment_hdata<?=$child_comment['id']?>').show();" class="dotted ml10"><?=t('Показать')?></a>
			<? } ?>
		</div>
		<br />
		<div class="mt10 combody comment_hdata<?=$child_comment['id']?> fs12 <?=$do_hide_comment ? 'hidden' : ''?>">
			<?=user_helper::get_links($child_comment['text'])?>
		</div>
		
		<? if ( session::has_credential('moderator') ||
			( $child_comment['user_id'] == session::get_user_id() ) ||
			( session::has_credential('selfmoderator') && $post_data['user_id'] == session::get_user_id() ) ) { ?>
                <a href="javascript:;" rel="<?=$child_comment['id']?>" class="dotted comment_update" onClick="Application.<?=($child_comment['user_id']==session::get_user_id()) ? 'initComUpdUser' : 'initComUpd'?>('<?=$child_comment['id']?>')"><?=t('Редактировать')?></a>
		&nbsp;&nbsp;<a href="javascript:;" onclick="Application.delCom('<?=$child_comment['id']?>','events/delete_comment',<?=($child_comment['user_id']==session::get_user_id())?1:0?>)" class="dotted"><?=t('Удалить')?></a>
		<? } ?>
	</div>
	<div class="clear"></div>
</div>