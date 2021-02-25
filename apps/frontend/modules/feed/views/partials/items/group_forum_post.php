<div class="left" style="width: 60px;"><?=group_helper::photo($group['id'], 's')?></div>
<div style="margin-left: 60px;">
	<div class="fs11 quiet mb5">
		<?=tag_helper::image('/menu/groups.png', array('class' => 'vcenter'))?>
		[[action]] "<?=group_helper::title($group['id'])?>"
	</div>
	<a href="/groups/talk_topic?id=<?=$id?>"><?=htmlspecialchars($topic)?></a>
</div><div class="clear"></div>