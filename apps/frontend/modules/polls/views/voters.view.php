<h1 class="column_head mt10 mr10">
	<a href="/poll<?=$poll['id']?>"><?=stripslashes(htmlspecialchars($poll['question']))?></a>
	&rarr;
	<?=t('Список голосовавших')?>
</h1>

<div class="mr10">
	<? if ( !$voters ) { ?>
		<div class="acenter fs11 quiet m10 p10"><?=t('Тут еще никого нет')?>...</div>
	<? } else { ?>
		<? foreach ( $voters as $data ) { ?>
			<? if ( is_numeric($data) ) { $id = $data; }
			else { $id = $data['user_id']; }?>
			
			<div class="box_empty box_content p10 mb10">
				<?=user_helper::full_name($id)?><br />
				<?=stripslashes(htmlspecialchars($data['text']))?>
			</div>
		<? } ?>
		<div class="clear bottom_line_d mb10"></div>
	<? } ?>
</div>