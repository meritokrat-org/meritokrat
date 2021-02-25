<form id="work_form" class="form mt10 hidden">
<? if (session::has_credential('admin') || $access) { ?>
	<input type="hidden" name="id" value="<?= $user_data['user_id'] ?>">
<? } ?>
<input type="hidden" name="type" value="work">
<table width="100%" class="fs12">
	<tr>
		<td class="aright"></td>
		<td><b><?= t('Текущее') ?> <?= t('место работы') ?></b></td>
		<!--td class="acenter" colspan="2"><b><?= t('Текущее') ?> <?= t('место работы') ?></b></td-->
	</tr>
	<tr>
		<td class="aright"><?= t('Страна') ?></td>
		<td>
			<input name="work_country" rel="<?= t('') ?>" class="text" type="text"
			       value="<?= stripslashes(htmlspecialchars($user_work['work_country'])) ?>"/>
		</td>
	</tr>
	<tr>
		<td class="aright"><?= t('Город') ?></td>
		<td>
			<input name="work_city" rel="<?= t('') ?>" class="text" type="text"
			       value="<?= stripslashes(htmlspecialchars($user_work['work_city'])) ?>"/>
		</td>
	</tr>
	<tr>
		<td class="aright"><?= t('Название организации') ?></td>
		<td>
			<input name="work_name" rel="<?= t('') ?>" class="text" type="text"
			       value="<?= stripslashes(htmlspecialchars($user_work['work_name'])) ?>"/>
		</td>
	</tr>
	<tr>
		<td class="aright"><?= t('Должность') ?></td>
		<td>
			<input name="position" rel="<?= t('') ?>" class="text" type="text"
			       value="<?= stripslashes(htmlspecialchars($user_work['position'])) ?>"/>
		</td>
	</tr>
	<tr>
		<td class="aright"><?= t('Год начала') ?></td>
		<td>
			<input name="work_admission" rel="<?= t('') ?>" class="text" type="text"
			       value="<?= stripslashes(htmlspecialchars($user_work['work_admission'])) ?>"/>
		</td>
	</tr>
</table>



<div style="text-align: center" id="else_works">
	<a id="add_work" style="cursor: pointer"><?=t("Добавить место работы")?></a>
</div>

<table width="100%" class="fs12">
	<tr>
		<td class="aright" width="320"></td>
		<td><b><?= t('Поиск работы') ?></b></td>
	</tr>
	<tr>
		<td class="aright"><?= t('Работаю') ?></td>
		<td><input <?= ($user_work['work_jobsearch'] == 0) ? 'checked' : '' ?> type="radio" name="jobsearch"
		                                                                       value="0"/></td>
	</tr>
	<tr>
		<td class="aright"><?= t('Активно ищу работу') ?></td>
		<td><input <?= ($user_work['work_jobsearch'] == 1) ? 'checked' : '' ?> type="radio" name="jobsearch"
		                                                                       value="1"/></td>
	</tr>
	<tr>
		<td class="aright"><?= t('Работаю, но открыт для предложений') ?></td>
		<td><input <?= ($user_work['work_jobsearch'] == 2) ? 'checked' : '' ?> type="radio" name="jobsearch"
		                                                                       value="2"/></td>
	</tr>
	<tr>
		<td></td>
		<td>
			<input type="submit" name="submit" class="button" value=" <?= t('Сохранить') ?> ">
			<input onclick="history.go(-1);" type="button" name="cancel" class="button_gray"
			       value=" <?= t('Отмена') ?> ">
			<?= tag_helper::wait_panel('work_wait') ?>
			<div class="success hidden mr10 mt10"><?= t('Изменения сохранены') ?></div>
		</td>
	</tr>
</table>
</form>