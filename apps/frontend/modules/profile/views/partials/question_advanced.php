<? $question = user_questions_peer::instance()->get_item($id) ?>
<div class="mb10 comment_bg mr10">
	<div class="left"><?=user_helper::photo($question['user_id'], 's', array('class' => 'border1'))?></div>
	<div class="ml10" style="margin-left: 60px;">
		<div class="fs11 pb5">
			<div class="left">
				<?=user_helper::full_name($question['user_id'])?>
				<? if ( session::has_credential('moderator') ||
					( ( $question['user_id'] == session::get_user_id() ) ) ||
					( session::has_credential('selfmoderator') && $user['id'] == session::get_user_id() ) ) { ?>
					<a href="javascript:;" class="ml10" onclick=" if ( confirm('Точно?') ) { $(this).parent().parent().parent().parent().hide(); $.get('/profile/delete_question?id=<?=$question['id']?>') } " class="dotted ml10"><?=t('Удалить')?></a>
				<? } ?>
			</div>
			<div class="right mr10">
				<? if ( session::is_authenticated() && !user_questions_peer::instance()->has_rated($id, session::get_user_id()) ) { ?>
					<span>
						<a href="javascript:;" onclick="profileController.rateQuestion(this, <?=$id?>, true);"><?=tag_helper::image('common/up.gif', array('height' => 16, 'class' => 'vcenter'))?></a>
						<a href="javascript:;" onclick="profileController.rateQuestion(this, <?=$id?>, false);"><?=tag_helper::image('common/down.gif', array('height' => 16, 'class' => 'vcenter'))?></a>
					</span>
				<? } ?>
				<span class="ml10 bold" style="color:<?=$question['rate'] >= 0 ? $question['rate'] > 0 ? 'green' : '#999' : 'red' ?>"><?=$question['rate'] > 0 ? '+' : ''?><?=$question['rate']?></span>
			</div>
			<br />
		</div>
		<div class="fs12"><?=stripslashes(nl2br(htmlspecialchars($question['text'])))?></div>
		<? if ( $question['reply'] ) { ?>
			<? include 'question_reply.php'; ?>
		<? } else if ( session::is_authenticated() && ( $user['id'] == session::get_user_id()) ) { ?>
			<div class="fs11 mb5 mt5"><a href="javascript:;" rel="<?=$question['id']?>" class="dotted question_reply"><?=t('Ответить')?></a></div>
		<? } ?>
	</div>
	<div class="clear"></div>
</div>