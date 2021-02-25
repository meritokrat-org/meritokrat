<? function yes_no($data){
	return $data != 0 ? t('Да') : t('Нет');
} ?>

<? $flag = true; ?>
<? foreach($table as $id => $arr){ ?>
	<? $flag = $flag ? false : true; ?>
	<tr style="background: <?= $flag ? '#eee' : '#fff' ?>">
		<td><a href="/profile/desktop?id=<?=$id?>"><?=str_replace(' ', '&nbsp;', user_helper::full_name($id, flase))?></a></td>
		<td align="center"><?=yes_no($arr['willVote'])?></td>
		<td align="center"><?=$arr['agitated'] > 0 ? $arr['agitated'] : 0?></td>
		<td align="center"><?=$arr['willVote']+$arr['agitated']?></td>
		<td align="center"><?=yes_no($arr['financialSupport'])?></td>
		<td align="center"><?=$arr['countFinancialSupport'] > 0 ? $arr['countFinancialSupport'] : 0 ?></td>
		<td align="center"><?=yes_no($arr['agitateMyFamily'])?></td>
		<td align="center"><?=yes_no($arr['agitateInInternet'])?></td>
		<td align="center"><?=yes_no($arr['agitateOnStreet'])?></td>
		<td align="center"><?=yes_no($arr['volunteerInKiev'])?></td>
		<td align="center"><?=yes_no($arr['volunteerInRegion'])?></td>
		<td align="center"><?=yes_no($arr['wantRunSingle'])?></td>
		<td align="center"><?=yes_no($arr['wantRunByList'])?></td>
		<td align="center"><?=yes_no($arr['wantToBeObserver'])?></td>
		<td align="center"><?=yes_no($arr['wantToBeCommissioner'])?></td>
		<td align="center"><?=yes_no($arr['wantToBeLawyer'])?></td>
	</tr>
<? } ?>