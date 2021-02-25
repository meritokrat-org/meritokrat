<? $sub_menu = '/groups/create'; ?>
<? include 'partials/sub_menu.php' ?>

<? if (!$allow_create_group) { ?>
	<div class="screen_message acenter"><?= t('У вас недостаточно прав') ?></div>
<? } elseif ($succes or request::get('success')) { ?>
	<div style="width: 500px;" class="screen_message"><?= t('Спасибо за инициативу!<br/>
<br/>
Ваша заявка на создание сообщества отправлена на рассмотрение Секретариата МПУ. Мы проинформируем Вас как только заявка будет одобрена.<br/>
<br/>
С уважением<br/>
Секретариат МПУ') ?></div>
<? } else { ?>

	<form <? /* id="add_form" */ ?> class="form_bg mt10">
		<h1 class="column_head"><?= t('Новое сообщество') ?></h1>
		<table width="100%" class="fs12">

			<tr>
				<td width="18%" class="aright"><?= t('Категория') ?> <b>*</b></td>
				<td><?= tag_helper::select('category', groups_peer::get_categories(), array('id' => 'category')) ?></td>
			</tr>
			<tr class="programhide">
				<td width="18%" class="aright"><?= t('Название') ?> <b>*</b></td>
				<td><input rel="<?= t('Введите название сообщества') ?>" style="width: 350px;"
				           value="<?= request::get('title') ?>" name="title" type="text" class="text"/></td>
			</tr>

			<tr>
				<td width="18%" class="aright">
					<?=t("Создать проект")?> :
				</td>
				<td>
					<input type="checkbox" name="create_project" id="create_project">
				</td>
			</tr>

			<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker.js"></script>
			<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker-uk.js"></script>
			<script>
				jQuery(document).ready(function ($) {
					$("#create_project").click(function() {
						if($(this).is(":checked") === true)
							$(".create_project_fields").show();
						else
							$(".create_project_fields").hide();
					});

					var settings = {
						changeMonth: true,
						changeYear: true,
						autoSize: true,
						showOptions: {direction: 'left'},
						dateFormat: 'dd-mm-yy',
						yearRange: '2010:2025',
						firstDay: true
					};

					$('#dzbori').datepicker(settings);
					$("#ui-datepicker-div").delay(250).css("background-color", "#fff");
				});
			</script>

			<tr class="create_project_fields" style="display:none;">
				<td class="aright"><?= t('Адрес') ?> <b>*</b></td>
				<td><input name="adres" rel="<?= t('Адрес') ?>" class="text adres" type="text"/></td>
			</tr>
			<tr class="create_project_fields" style="display:none;">
				<td class="aright"><?= t('Дата учредительного собрания') ?> <b>*</b></td>
				<td>
					<input name="dzbori" id="dzbori" rel="<?= t('Дата учредительного собрания') ?>"
					       value="<?= request::get_string('dzbori') ?>" class="text" type="text"/>
				</td>
			</tr>

			<tr id="sfera-tr" class="hidden">
				<td width="18%" class="aright"><?= t('Тема') ?></td>
				<? /*$group_types=groups_peer::get_types();
                        $group_types['']='&mdash;';
                        ksort($group_types); */ ?>
				<td><?= tag_helper::select('type', user_helper::get_program_types()) ?></td>
			</tr>
			<tr id="level-tr" class="hidden">
				<td width="18%" class="aright"><?= t('Уровень') ?></td>
				<td><?= tag_helper::select('level', groups_peer::get_levels()) ?></td>
			</tr>
			<!--tr>
			<td width="18%" class="aright"><?= t('Территория') ?></td>
			<td><?= tag_helper::select('teritory', groups_peer::get_teritories()) ?></td>
		</tr-->
			<? if (session::has_credential('admin')) { ?>
				<tr>
					<td class="aright"><?= t('Приватность') ?></td>
					<td>
						<input type="checkbox" id="hidden_1" name="hidden" value="1"/><label for="hidden_1">*Скрита
							спільнота</label> <span class="quiet fs11">(не бачить ніхто крім запрошених)</span>
						<br/>
						<input type="checkbox" id="private_1" name="private" value="1"/><label for="private_1">*Персональна
							спільнота</label> <span
							class="quiet fs11">(матеріали додають лише власник та модератори)</span>
						<br/>
						<input type="radio" checked id="privacy_<?= groups_peer::PRIVACY_PUBLIC ?>" name="privacy"
						       value="<?= groups_peer::PRIVACY_PUBLIC ?>"/>
						<label for="privacy_<?= groups_peer::PRIVACY_PUBLIC ?>"><?= t('Открытая') ?></label>

						<input type="radio" id="privacy_<?= groups_peer::PRIVACY_PRIVATE ?>" name="privacy"
						       value="<?= groups_peer::PRIVACY_PRIVATE ?>"/>
						<label for="privacy_<?= groups_peer::PRIVACY_PRIVATE ?>"><?= t('Закрытая') ?></label>

						<div
							class="mt5 quiet fs11"><?= t('Закрытые сообщества будут доступны только вступившим в них учасникам.') ?></div>
					</td>
				</tr>
			<? } ?>

			<tr>
				<td class="aright"><?=t("Руководитель")?></td>
				<td><input type="text" class="text" rel="Координатор 1" disabled="disabled" name="glava"/>
					<input type="hidden" name="glavaid"/>
					<a class="one_add_function" rel="glava" id="select_glava" href="javascript:;"><?= t('Выбрать') ?></a></td>
			</tr>

			<tr class="programhide">
				<td class="aright"><?= t('Описание') ?></td>
				<td><textarea name="aims" rel="<?= t('Введите краткое описание') ?>"
				              style="width: 350px;"><?= request::get('aims') ?></textarea></td>
			</tr>
			<tr>
				<td class="aright"><b>*</b></td>
				<td><?= t('поля обязательны для заполнения') ?></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" name="submit" class="button" value=" <?= t('Отправить') ?> ">
					<input onclick="history.go(-1);" type="button" name="cancel" class="button_gray"
					       value=" <?= t('Отмена') ?> ">
					<?= tag_helper::wait_panel() ?>
					<div class="success hidden mr10 mt10"><?= t('Сообщество создано, ожидайте...') ?></div>
				</td>
			</tr>
		</table>
	</form>
<? } ?>
<script type="text/javascript">
	jQuery(document).ready(function ($) {
		<? /*$.datepicker.setDefaults($.extend(
		$.datepicker.regional["uk"])
	  );*/ ?>

		$('#category').change(function () {
			$('.programhide').show();
			if ($(this).val() == 2) {
				$('.programhide').hide();
				$("#sfera-tr").show();
				$("#level-tr").hide();
				$("#hidden_1").removeAttr('checked');
			}
			else if ($(this).val() == 3) {
				$("#sfera-tr").hide();
				$("#level-tr").show();
				$("#hidden_1").removeAttr('checked');
			}
			else {
				$("#sfera-tr").hide();
				$("#level-tr").hide();
				$("#hidden_1").removeAttr('checked');
				if ($(this).val() == 4) {
					$("#hidden_1").attr('checked', 'checked');
					$("#privacy_2").attr('checked', 'checked');
					$("#privacy_1").removeAttr('checked');
				}
			}
		});
	});
</script>

<script type="text/javascript">
	jQuery(document).ready(function ($) {
		$("#select_glava").click(function () {
			Application.groupsShowUsers('glava');
			return false;
		});
	});
</script>