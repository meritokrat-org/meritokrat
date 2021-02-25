<div class="left" style="width: 60px;"><?=user_helper::photo(session::get_user_id(), 's')?></div>
<div style="margin-left: 60px;">
	<div class="fs11 quiet mb5">
		<?//=tag_helper::image('/menu/blogs.png', array('class' => 'vcenter'))?>
                <?=user_helper::full_name(session::get_user_id()) ?> 
		[[action]]
		"<a href="/blogpost<?=$post['id']?>"><?=htmlspecialchars($post['title'])?></a>"
	</div>
	<a href="/blogpost<?=$post['id']?>#comment<?=$this->id?>"><?=tag_helper::get_short(htmlspecialchars($data['text']),80)?></a>
</div><div class="clear"></div>