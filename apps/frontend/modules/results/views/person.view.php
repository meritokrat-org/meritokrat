<div class="fs12">
	
	<div class="left mr10" style="width: 200px;">
		<div><?=user_helper::photo($user_id, "p")?></div>
		<div class="mt10 acenter">
			<?=user_helper::full_name($user_id, true, array("class" => "bold"))?>
		</div>
	</div>
	
	<div class="left" style="width: 550px;">
		<table style="border: 1px solid #600" cellpadding="5" cellspacing="2">
			<thead>
				<tr>
					<th>№</th>
					<th><?=t("Дата")?></th>
					<th><?=t("Время")?></th>
				</tr>
			</thead>
			<tbody>
				<? $flag = false; ?>
				<? $cnt = 1; ?>
				<? $total = 0; ?>
				<? foreach($visits_log as $id){ ?>
					<? $visit_log = user_visits_log_peer::instance()->get_item($id); ?>
					<? $time = strtotime($visit_log["time"]); ?>
					<? $time_out = strtotime($visit_log["time_out"]); ?>
					<? $total += ($time_out - $time) ?>
					<? $flag = $flag ? false : true ?>
					<tr style="background: <? if($flag){ ?>#fff<? } else { ?>#eee<? } ?>">
						<td class="acenter"><?=$cnt++?></td>
						<td class="acenter"><?=date("d", $time)?> <?=date_helper::get_month_name(date("m", $time)+1)?> <?=date("Y", $time)?></td>
						<? $tokens = explode(".", number_format(($time_out - $time)/3600, 2)); ?>
						<? $minuts = intval($tokens[1]*60/100); ?>
						<td class="acenter"><?=$tokens[0]?> г. <?=$minuts?> хв.</td>
					</tr>
				<? } ?>
				<tr>
					<? $tokens = explode(".", number_format($total/3600, 2)); ?>
					<? $minuts = intval($tokens[1]*60/100); ?>
					<td colspan="3" class="acenter bold"><?=t("Всего")?>: <?=$tokens[0]?> г. <?=$minuts?> хв.</td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<div class="clear"></div>
	
</div>