<div class="left" style="width: 60px;"><?=party_helper::photo($party['id'], 's')?></div>
<div style="margin-left: 60px;">
	<div class="fs11 quiet mb5">
		<?=tag_helper::image('/menu/parties.png', array('class' => 'vcenter'))?>
		[[action]] "<?=party_helper::title($party['id'])?>"
	</div>
	<a href="/parties/talk_topic?id=<?=$id?>"><?=htmlspecialchars($topic)?></a>
</div><div class="clear"></div>