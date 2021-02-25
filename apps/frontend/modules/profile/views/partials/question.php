<? $question = user_questions_peer::instance()->get_item($id) ?>

<div class="fs11 p10 ml10 mb10" style="background: #F7F7F7;">
    <b><?=user_helper::full_name($question['user_id'], true)?></b>:
	<?=stripslashes(nl2br(htmlspecialchars($question['text'])))?>
        <br/>
        				<? if ( session::has_credential('moderator') ||
					( ( $question['user_id'] == session::get_user_id() ) ) ||
					( $user['id'] == session::get_user_id() ) ) { ?>
					<a href="javascript:;" style="color:gray;" "class="ml10" onclick=" if ( confirm('Точно?') ) { $(this).parent().hide(); $.get('/profile/delete_question?id=<?=$question['id']?>') } " class="dotted ml10"><?=t('Удалить')?></a>
				<? } ?>

	<? if ( $question['reply'] ) { include 'question_reply.php'; } ?>
</div>