<div  style="border: 1px solid #E4E4E4; background: #F9F9F9;" class="mt10 p5 fs11" id="reply_<?=$question['id']?>">
	<b><?=user_helper::full_name($question['profile_id'], false)?>:</b> <?=stripslashes(htmlspecialchars($question['reply']))?>
</div>