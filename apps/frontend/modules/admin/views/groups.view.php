<div class="left mt10" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10 mt10" style="width: 62%;">
	<h1 class="column_head"><?=t('Группы')?></h1>

	<div class="box_content acenter p10">
		<form>
			<label class="fs10 quiet"><?=t('ID группы');?></label>
			<input type="text" class="text" name="key" value="<?=$group_key?>" />
			<input type="submit" class="button" value="<?=t('Искать')?>" />
		</form>
	</div>
	<? if ( $group_key ) { ?>
		<? if ( !$group ) { ?>
			<div class="acenter screen_message acenter"><?=t('Группа не найдена')?></div>
		<? } else { ?>
			<form method="post">
				<table class="fs12 mt10">
					<tr>
						<td width="30%"><?=t('Название')?></td>
						<td>
							<?=group_helper::title($group['id'])?><br />
							<?=group_helper::photo($group['id'], 't', true, array('class' => 'mt5'))?>
						</td>
					</tr>
					<tr>
						<td><?=t('Рейтинг')?></td>
						<td>
							<input type="text" class="text" name="rate" value="<?=$group['rate']?>" />
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<? if ( $saved ) { ?>
								<div class="success"><?=t('Данные сохранены')?></div>
							<? } ?>
							<input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
						</td>
					</tr>
				</table>
			</form>
		<? } ?>
	<? } ?>
</div>
