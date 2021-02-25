<div class="left" style="width: 60px;"><?=group_helper::photo($group['id'], 's')?></div>
<div style="margin-left: 60px;">
	<div class="fs11 quiet mb5">
		<?=tag_helper::image('/menu/groups.png', array('class' => 'vcenter'))?>
		[[action]] "<?=group_helper::title($group['id'])?>"
	</div>
	<a href="/groups/talk_topic?id=<?=$id?>"><?=htmlspecialchars($topic)?></a>
	<div class="m10">
		<? $photos = array_slice($photos, 0, 3) ?>
		<? foreach ( $photos as $photo_id ) { ?>
			<a href="/groups/photo_view?id=<?=$photo_id?>"><?=group_helper::media_photo($photo_id, 't')?></a>
		<? } ?>
	</div>
</div><div class="clear"></div>