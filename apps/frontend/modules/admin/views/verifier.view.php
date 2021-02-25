<table>
	<? $flag = false; ?>
	<? foreach($diffs as $user => $diff){ ?>
		<? $flag = $flag ? false : true; ?>
		<tr style="background: #<?= $flag ? "EEE" : "FFF" ?>">
			<td width="64px">UID: <?=$user?></td>
			<td width="96px">Diff count: <?=count($diff)?></td>
			<td width="256px">Not viewed: <?=count($from_redis[$user])?>:[R] <?=count($from_db[$user])?>:[D]</td>
			<td>&nbsp;</td>
		</tr>
	<? } ?>
</table>
