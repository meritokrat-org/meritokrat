<? $members = explode(',', str_replace(array('{', '}'), array('', ''), $group['uchasniki'])); ?>
<form id="more_form" action="/projects/edit?type=more&submit=1&id=<?= $group['id'] ?>" class="form mt10 hidden">
	<table width="100%" class="fs12">
		<tr>
			<td class="aright"><?= t('Номер') ?>:</td>
			<td><input disabled size="8" name="number" id="number" class="text number" value="<?= $group['number'] ?>"
			           type="text"/>
				<input type="button" name="editnumber" onclick="$('#number').removeAttr('disabled');" class="button"
				       value="<?= t('Редактировать') ?>"/></td>
		</tr>
		<tr>
			<td class="aright"><?= t('Адрес') ?>:</td>
			<td><input name="adres" class="text adres" value="<?= $group['adres'] ?>" type="text"/></td>
		</tr>
		<tr>
			<td style="width:40%;" class="aright mr15 bold">Установчi збори:</td>
			<td></td>
		</tr>
		<tr>
			<td class="aright">Дата</td>
			<td>
				<input name="dzbori" id="dzbori" value="<?= $group['dzbori'] ? date("d-m-Y", $group['dzbori']) : '' ?>"
				       class="text date" type="text"/>
			</td>
		</tr>
		<tr id="uchasniki">
			<td class="aright">Учасники</td>
			<td><a onclick="Application.showUsers($(this).attr('rel'),false,false,1)"
			       href="javascript:;"><?= t('Добавить') ?></a>
			</td>
		</tr>
		<? foreach ($members as $m): if (!$m) continue; ?>
			<tr id="member<?= $m ?>">
				<td></td>
				<td>
					<input type="hidden" value="<?= $m ?>" name="members[]"/>
					<?= user_helper::full_name((int)$m, true, array(), false) . ' - ' ?>
					<a class="one_delete_function" rel="<?= $m ?>" href="javascript:;"><?= t('Удалить') ?></a>
				</td>
			</tr>
		<? endforeach; ?>
		<tr>
			<td class="aright mr15 bold">Рiшення Голови про затвердження:</td>
			<td></td>
		</tr>
		<tr>
			<td class="aright">№</td>
			<td>
				<input name="uhvalnum" style="width: 30px" id="uhvalnum" value="<?= $group['uhvalnum'] ?>" class="text"
				       type="text"/>
			</td>
		</tr>
		<tr>
			<td class="aright">Дата</td>
			<td>
				<input name="duhval" id="duhval"
				       value="<?= ($group['duhval']) ? date("d-m-Y", $group['duhval']) : '' ?>" class="text date"
				       type="text"/>
			</td>
		</tr>
		<tr>
			<td class="aright mr15 bold">Легалiзацiя:</td>
			<td></td>
		</tr>
		<tr>
			<td class="aright">№ довiдки</td>
			<td>
				<input name="dovidnum" id="dovidnum" style="width: 30px" value="<?= $group['dovidnum'] ?>" class="text"
				       type="text"/>
			</td>
		</tr>
		<tr>
			<td class="aright">Дата видачi</td>
			<td>
				<input name="doviddate" id="doviddate"
				       value="<?= ($group['doviddate']) ? date("d-m-Y", $group['doviddate']) : '' ?>" class="text date"
				       type="text"/>
			</td>
		</tr>
		<tr>
			<td class="aright">Копія свідоцтва видана:</td>
			<td>
				<input name="svidcopy" id="svidcopy" <?= ($group['svidcopy']) ? 'checked' : '' ?> value="1"
				       type="checkbox"/>
			</td>
		</tr>
		<tr>
			<td class="aright mr15 bold">Отримання документів Секретаріатом:</td>
			<td></td>
		</tr>
		<tr>
			<td class="aright">Протокол</td>
			<td>
				<input name="protokolsdate" id="protokolsdate" <?= ($group['protokolsdate']) ? 'checked' : '' ?>
				       value="1" type="checkbox"/>
			</td>
		</tr>
		<tr>
			<td class="aright">Довiдка / Свiдоцтво:</td>
			<td>
				<input name="dovidsdate" id="dovidsdate" <?= ($group['dovidsdate']) ? 'checked' : '' ?> value="1"
				       type="checkbox"/>
			</td>
		</tr>
		<tr>
			<td class="aright">Заява:</td>
			<td>
				<input name="zayava" id="zayava" <?= ($group['zayava']) ? 'checked' : '' ?> value="1" type="checkbox"/>
			</td>
		</tr>
		<tr>
			<td class="aright mr15 bold">Рiшення Голови про включення в структуру:</td>
			<td></td>
		</tr>
		<tr>
			<td class="aright">№</td>
			<td>
				<input name="vklnumber" id="vklnumber" style="width: 30px" value="<?= $group['vklnumber'] ?>"
				       class="text" type="text"/>
			</td>
		</tr>
		<tr>
			<td class="aright">Дата ухвалення</td>
			<td>
				<input name="vkldate" id="vkldate"
				       value="<?= ($group['vkldate']) ? date("d-m-Y", $group['vkldate']) : '' ?>" class="text date"
				       type="text"/>
			</td>
		</tr>
		<tr>
			<td class="aright mr15 bold">Свідоцтво:</td>
			<td></td>
		</tr>
		<tr>
			<td class="aright">№ Свідоцтва</td>
			<td>
				<input name="svidnum" id="svidnum" style="width: 30px" value="<?= $group['svidnum'] ?>" class="text"
				       type="text"/>
			</td>
		</tr>
		<tr>
			<td class="aright">Виготовлення</td>
			<td>
				<input name="svidvig" id="svidvig" <?= ($group['svidvig']) ? 'checked' : '' ?> value="1"
				       type="checkbox"/>
			</td>
		</tr>
		<tr>
			<td class="aright">Вручення</td>
			<td>
				<input name="svidvruch" id="svidvruch" <?= ($group['svidvruch']) ? 'checked' : '' ?> value="1"
				       type="checkbox"/>
			</td>
		</tr>
		<tr>
			<td class="aright">Коментар</td>
			<td>
				<input name="svidcom" id="svidcom" value="<?= $group['svidcom'] ?>" class="text" type="text"/>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="submit" name="submit" class="button" value=" <?= t('Отправить') ?> ">
				<input onclick="history.go(-1);" type="button" name="cancel" class="button_gray"
				       value=" <?= t('Отмена') ?> ">
				<?= tag_helper::wait_panel() ?>
				<div class="success hidden mr10 mt10"><?= t('Изменения сохранены...') ?></div>
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker-uk.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function ($) {
		var settings = {
			changeMonth: true,
			changeYear: true,
			autoSize: true,
			showOptions: {direction: 'left'},
			dateFormat: 'dd-mm-yy',
			yearRange: '2010:2025',
			firstDay: true
		};
		$('.date').datepicker(settings);
		if ($("input[@name='mu']:checked").val())$('#dmu').show();

	});
</script>