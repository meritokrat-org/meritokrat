<div class="left" style="width: 60px;"><?=user_helper::photo(session::get_user_id(), 's')?></div>
<div style="margin-left: 60px;">
	<div class="fs11 quiet mb5">
		<?=tag_helper::image('/menu/polls.png', array('class' => 'vcenter'))?>
		[[action]]: &nbsp;
		<?=user_helper::full_name(session::get_user_id()) . ', ' . date_helper::human(time(), ', ')?>
	</div>
	<a href="/poll<?=$poll_id?>"><?=htmlspecialchars($data['question'])?></a>
</div><div class="clear"></div>