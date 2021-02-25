<table>
	<tr>
		<th>
			<a href="/admin/rating?type=criteria"><?= t('Критерий') ?></a>
		</th>
		<th>
			<a href="/admin/rating?type=criteria&direct=<?= (request::get('direct') == 'ASC') ? 'DESC' : 'ASC' ?>"><?= t('Баллы') ?></a>
		</th>
	</tr>
	<?
	if ($by_types)
		foreach ($by_types as $name => $summ) { ?>
			<tr>
				<td class="fs12" style="color:black;">
					<?= $alias2names[$name] ?>
				</td>
				<td class="acenter fs12" style="color:black;">
					<a href="/admin/rating?type=criteria_adv&direct=DESC&field=<?= $name ?>"><b><?= ($summ) ? number_format($summ, 0, '.', ' ') : ' - ' ?></b></a>
				</td>
			</tr>
		<? } ?>
	<tr>
		<td><b><?= t('Вобщем') ?></b></td>
		<td class="acenter"><b><?= ($summary) ? number_format($summary, 0, '.', ' ') : ' - ' ?></b></td>
	</tr>
</table>
