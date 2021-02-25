<? if ( !$child_comment = ideas_comments_peer::instance()->get_item($child_id) ) { return; } ?>
<div id="comment<?=$child_comment['id']?>" class="fs11 ml60 mt10 mb15 p5" style="border: 1px solid #E4E4E4; background: #F9F9F9;">
	<?=user_helper::photo($child_comment['user_id'], 's', array('class' => 'border1 left'))?>
	<div style="margin-left: 60px;">
                <div class="left quiet">
                    <?=user_helper::full_name($child_comment['user_id'])?>
                    <span class="quiet ml10"><?=user_helper::com_date(date($child_comment['created_ts']))?></span> &nbsp;
                    <?=($child_comment['edit'])?t('Отредактировано').': '.strip_tags(user_helper::full_name($child_comment['edit']),'<a>').(($child_comment['edit_ts'])?' '.user_helper::com_date(date($child_comment['edit_ts'])):''):''?>
                </div>
                <br />
		<div class="combody mt10 comment_hdata<?=$child_comment['id']?> fs12 <?=$do_hide_comment ? 'hidden' : ''?>"><?=user_helper::get_links($child_comment['text'])?></div>
		<? if ( session::has_credential('moderator') ||
			( $child_comment['user_id'] == session::get_user_id() ) ||
			( session::has_credential('selfmoderator') && $idea['user_id'] == session::get_user_id() ) ) { ?>
			<br />
                        <a href="javascript:;" rel="<?=$child_comment['id']?>" class="dotted comment_update" onClick="Application.<?=($child_comment['user_id']==session::get_user_id()) ? 'initComUpdUser' : 'initComUpd'?>('<?=$child_comment['id']?>')"><?=t('Редактировать')?></a>
                        <a href="javascript:;" onclick="Application.delCom('<?=$child_comment['id']?>','ideas/delete_comment',<?=($child_comment['user_id']==session::get_user_id())?1:0?>)" class="dotted ml10"><?=t('Удалить')?></a>
		<? } ?>
	</div>
	<div class="clear"></div>
</div>