<? $flag = true; ?>

<? if( ! request::get_int('region_id') && ! request::get_int('city_id')){ $locations = array_merge(array(0 => '<b>'.t('Украина').'</b>'), $locations); } ?>

<? foreach($locations as $id => $name){ ?>
	<? $flag = $flag ? false : true; ?>
	<tr <? if($id == 0){ ?>id="total"<? } ?> style="background: <?= $flag ? '#eee' : '#fff' ?>;">
		<td>
			<a href="?<?=$location_key?>=<?=$id?>"><?=str_replace(array('область', ' '), array('обл.', '&nbsp;'), $name)?></a>
		</td>
		<td align="center">
			<? if($table[$id]['willVote'] != '' || $table[$id]['willVote'] != 0){ ?>
				<a href="?<?=$location_key?>=<?=$id?>&filter=willVote"><?=$table[$id]['willVote']?></a>
			<? } else { ?>
				-
			<? } ?>
		</td>
		<td align="center"><?=$table[$id]['agitated'] > 0 ? $table[$id]['agitated'] : 0?></td>
		<td align="center"><?=$table[$id]['willVote']+$table[$id]['agitated']?></td>
		<td align="center">
			<? if($table[$id]['financialSupport'] != '' || $table[$id]['financialSupport'] != 0){ ?>
				<a href="?<?=$location_key?>=<?=$id?>&filter=financialSupport"><?=$table[$id]['financialSupport']?></a>
			<? } else { ?>
				-
			<? } ?>
		</td>
		<td align="center">
			<? if($table[$id]['countFinancialSupport'] != 0){ ?>
				<a href="?<?=$location_key?>=<?=$id?>&filter=countFinancialSupport"><?=$table[$id]['countFinancialSupport']?></a>
			<? } else { ?>
				-
			<? } ?>
		</td>
		<td align="center">
			<? if($table[$id]['agitateMyFamily'] != '' || $table[$id]['agitateMyFamily'] != 0){ ?>
				<a href="?<?=$location_key?>=<?=$id?>&filter=agitateMyFamily"><?=$table[$id]['agitateMyFamily']?></a>
			<? } else { ?>
				-
			<? } ?>
		</td>
		<td align="center">
			<? if($table[$id]['agitateInInternet'] != '' || $table[$id]['agitateInInternet'] != 0){ ?>
				<a href="?<?=$location_key?>=<?=$id?>&filter=agitateInInternet"><?=$table[$id]['agitateInInternet']?></a>
			<? } else { ?>
				-
			<? } ?>
		</td>
		<td align="center">
			<? if($table[$id]['agitateOnStreet'] != '' || $table[$id]['agitateOnStreet'] != 0){ ?>
				<a href="?<?=$location_key?>=<?=$id?>&filter=agitateOnStreet"><?=$table[$id]['agitateOnStreet']?></a>
			<? } else { ?>
				-
			<? } ?>
		</td>
		<td align="center">
			<? if($table[$id]['volunteerInKiev'] != '' || $table[$id]['volunteerInKiev'] != 0){ ?>
				<a href="?<?=$location_key?>=<?=$id?>&filter=volunteerInKiev"><?=$table[$id]['volunteerInKiev']?></a>
			<? } else { ?>
				-
			<? } ?>
		</td>
		<td align="center">
			<? if($table[$id]['volunteerInRegion'] != '' || $table[$id]['volunteerInRegion'] != 0){ ?>
				<a href="?<?=$location_key?>=<?=$id?>&filter=volunteerInRegion"><?=$table[$id]['volunteerInRegion']?></a>
			<? } else { ?>
				-
			<? } ?>
		</td>
		<td align="center">
			<? if($table[$id]['wantRunSingle'] != '' || $table[$id]['wantRunSingle'] != 0){ ?>
				<a href="?<?=$location_key?>=<?=$id?>&filter=wantRunSingle"><?=$table[$id]['wantRunSingle']?></a>
			<? } else { ?>
				-
			<? } ?>
		</td>
		<td align="center">
			<? if($table[$id]['wantRunByList'] != '' || $table[$id]['wantRunByList'] != 0){ ?>
				<a href="?<?=$location_key?>=<?=$id?>&filter=wantRunByList"><?=$table[$id]['wantRunByList']?></a>
			<? } else { ?>
				-
			<? } ?>
		</td>
		<td align="center">
			<? if($table[$id]['wantToBeObserver'] != '' || $table[$id]['wantToBeObserver'] != 0){ ?>
				<a href="?<?=$location_key?>=<?=$id?>&filter=wantToBeObserver"><?=$table[$id]['wantToBeObserver']?></a>
			<? } else { ?>
				-
			<? } ?>
		</td>
		<td align="center">
			<? if($table[$id]['wantToBeCommissioner'] != '' || $table[$id]['wantToBeCommissioner'] != 0){ ?>
				<a href="?<?=$location_key?>=<?=$id?>&filter=wantToBeCommissioner"><?=$table[$id]['wantToBeCommissioner']?></a>
			<? } else { ?>
				-
			<? } ?>
		</td>
		<td align="center">
			<? if($table[$id]['wantToBeLawyer'] != '' || $table[$id]['wantToBeLawyer'] != 0){ ?>
				<a href="?<?=$location_key?>=<?=$id?>&filter=wantToBeLawyer"><?=$table[$id]['wantToBeLawyer']?></a>
			<? } else { ?>
				-
			<? } ?>
		</td>
	</tr>
<? } ?>