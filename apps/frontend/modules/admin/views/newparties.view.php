<div class="left mt10" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10 mt10" style="width: 62%;">
	<h1 class="column_head"><?=t('Новые партии')?></h1>

<table class="fs11">
	<tr class="box_content">
		<th>ID</th>
		<th><?=t('Имя') ?></th>
		<th><?=t('Действие') ?></th>
	</tr>
	<? $count = 0 ?>
	<? foreach ($parties as $party) { ?>
		<? $count++ ?>
		<tr<? echo $count%2 == 0 ? " class=\"p10 box_content\"" : "" ?>>
			<td><?=$party['id'] ?></td>
			<td>
				<a href="/party<?=$party['id'] ?>">
					<?=htmlspecialchars($party['title']) ?>
				</a><br />
				<?=t('Пользователь') ?>:&nbsp;
				<a href="/profile-<?=$party['user_id'] ?>">
					<?=htmlspecialchars($party['user']['first_name']) ?>&nbsp;<?=htmlspecialchars($party['user']['last_name']) ?>
				</a>
			</td>
			<td>
				<a href="/admin/newparties?key=<?=$party['id'] ?>&state=old"><?=tag_helper::image('common/up.gif')?></a>
				<a href="/admin/newparties?key=<?=$party['id'] ?>&state=bad"><?=tag_helper::image('common/down.gif')?></a>
			</td>
		</tr>
	<? } ?>
</table>

</div>