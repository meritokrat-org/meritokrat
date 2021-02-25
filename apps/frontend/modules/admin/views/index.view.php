<div class="left mt10" style="width: 35%;"><? include 'partials/left.php' ?></div>


<div class="left ml10 mt10" id="rmcol" style="width: 62%;">
	<h1 class="column_head"><?=t('Административная панель')?></h1>

	<table class="fs12">
		<? foreach ( $stats as $name => $value ) { ?>
			<tr>
				<td class="aright" width="50%"><?=$name?></td>
				<td>
					<?=$value?>
					<? if ( !empty($subStats[$name]) ) { ?>
						<a href="/admin/newparties">+<?=$subStats[$name] ?></a>
					<? } ?>
				</td>
			</tr>
		<? } ?>
	</table>
        
	<div id="user_activated_stats" style="z-index: 0" class="acenter m10 fs11 quiet">Завантаження...</div>

	<table class="stat">
		<tr>
			<th style="width: 15%;">ID</th>
			<th style="width: 65%;"><?=t('Имя')?></th>
			<th><?= t('Приглашений') ?></th>
		</tr>

		<? foreach ($list as $user) { ?>
			<tr data-id="<?=$user["id"]?>">
				<td>
					<?= $user["id"] ?>
				</td>
				<td>
					<a href="/profile-<?= $user["id"] ?>"><?= $user["first_name"] ?> <?= $user["last_name"] ?></a><br/>
				</td>
				<td style="text-align: center; font-weight: bold">
					<?=$user["invited"]?>
				</td>
			</tr>
		<? } ?>
	</table>

</div>