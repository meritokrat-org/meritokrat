<script type="text/javascript" src="/static/javascript/library/form/form.js"></script>
<script type="text/javascript" src="/static/javascript/library/form/validators.js"></script>
<div class="mt10 mr10 form_bg">
	<h1 class="column_head">
		<? if ($group["symlink"] != "") { ?>
			<a href="/project/<?= $group['symlink'] ?>"><?= stripslashes(htmlspecialchars($group['title'])) ?></a>
		<? } else { ?>
			<a href="/project<?= $group['id'] ?>/<?= $group['number'] ?>"><?= stripslashes(htmlspecialchars($group['title'])) ?></a>
		<? } ?> &rarr; <?= t('Редактирование') ?>
	</h1>

	<div class="tab_pane_gray mb10">
		<? if (!session::has_credential('designer')) { ?>
			<a href="javascript:;"
			   class="tab_menu <?= (!in_array(request::get('tab'), array('photo', 'more', 'leadership', 'inventory', 'vidatki'))) ? 'selected' : '' ?>"
			   rel="common"><?= t('Основные сведения') ?></a>
		<? } ?>
		<? if (($group['user_id'] == session::get_user_id()) ||
			session::has_credential('admin')
		) { ?>
			<a href="javascript:;" class="tab_menu <?= (request::get('tab') == 'more') ? 'selected' : '' ?>" rel="more">*<?= t('Служебная информация') ?></a>
			<a href="javascript:;" class="tab_menu <?= (request::get('tab') == 'inventory') ? 'selected' : '' ?>"
			   rel="inventory">*<?= t('Партийный инвентарь') ?></a>
		<? } ?>
		<? if ($allow_edit) { ?>
			<a href="javascript:;" class="tab_menu <?= (request::get('tab') == 'leadership') ? 'selected' : '' ?>"
			   rel="leadership">
				<?= t('Руководство') ?></a>
		<? } ?>
		<a href="javascript:;" class="tab_menu <?= (request::get('tab') == 'photo') ? 'selected' : '' ?>"
		   rel="photo"><?= t('Лого') ?></a>

		<? if (($group['user_id'] == session::get_user_id()) ||
			reform_peer::instance()->is_moderator($group['id'], session::get_user_id())
		) { ?>

			<a href="javascript:;" class="tab_menu" rel="applicants">
				<?= t('Заявки') ?>
				<span id="new_applicants" class="green fs10">
                            <?= $applicants ? '+' . count($applicants) : '' ?></span>
			</a>

		<? } ?>
		<? if (session::has_credential('admin') && $group['category'] == 3) { ?>
			<a href="javascript:;" class="tab_menu <?= (request::get('tab') == 'vidatki') ? 'selected' : '' ?>"
			   rel="vidatki"><?= t('Финансы') ?></a>
		<? } ?>
		<div class="clear"></div>
	</div>

	<form id="common_form" action="/projects/edit?type=common&submit=1&id=<?= $group['id'] ?>"
	      class="form mt10 <?= (session::has_credential('designer')) ? 'hidden' : '' ?>">
		<table width="100%" class="fs12">
			<tr>
				<td width="18%" class="aright"><?= t('Название') ?></td>
				<td><input rel="<?= t('Введите название') ?>" style="width: 350px;"
				           value="<?= $group['title'] ?>" name="title" type="text" class="text"/></td>
			</tr>
			<? if ($group['category'] == 3) { ?>
				<tr>
					<td width="18%" class="aright"><?= t('Символьная ссылка') ?></td>
					<td><input rel="<?= t('Введите название') ?>" style="width: 350px;"
					           value="<?= $group['symlink'] ?>" name="symlink" type="text" class="text"/></td>
				</tr>
			<? } ?>
			<? if ($group['category'] == 1) { ?>
				<tr>
					<td width="22%" class="aright"><?= t('Тип') ?></td>
					<td>
						<select name="ptype">
							<?
							foreach (reform_peer::get_ptypes() as $type => $title) { ?>
								<option <?= $type == $group['ptype'] ? 'selected' : '' ?>
									value="<?= $type ?>"><?= $title ?></option>
							<? } ?>
						</select>
					</td>
				</tr>
			<? } ?>
			<? if (session::has_credential('admin')) { ?>
				<tr>
				<td class="aright">* <?= t('Уровень') ?></td>
				<td>
					<select name="category">
						<?
						foreach (reform_peer::get_levels() as $level => $title) {
							if ($level == 0) continue; ?>
							<option <?= $level == $group['category'] ? 'selected' : '' ?>
								value="<?= $level ?>"><?= $title ?></option>
						<? } ?>
					</select>
				</td>
				</tr><? } ?>
			<tr id="region_row">
				<td class="aright"><?= t('Регион') ?></td>
				<td>
					<input name="country" id="country" type="hidden" value="1"/>
					<input type="text" id="region_txt" name="region_txt" style="display: none">
					<? $regions = geo_peer::instance()->get_regions(1); ?>
					<?= tag_helper::select('region_id', $regions,
						array('use_values' => false,
							'value' => $group['region_id'],
							'id' => 'region')); ?>
				</td>
			</tr>
			<tr id="scity" class="<?= ($group['category'] == 3) ? 'hidden' : '' ?>">
				<td class="aright"><?= t('Город/Район') ?></td>
				<td>
					<input type="text" id="city_txt" name="city_txt" style="display: none">
					<?
					$cities = geo_peer::instance()->get_cities($group['region_id']);
					if (count($cities) == 0) $cities[''] = '&mdash;';
					?>
					<?= tag_helper::select('city_id', $cities, array('use_values' => false, 'value' => $group['city_id'], 'id' => 'city')); ?>
				</td>
			</tr>
			<tr class="<?= ($group['city_id'] >= 700 || $group['region_id'] == 13) ? 'hidden' : '' ?>">
				<td class="aright"><?= t('Населенный пункт') ?>
				</td>
				<td><input name="location"
				           class="text" type="text"
				           value="<?= $group['location'] ?>"/></td>
			</tr>
			<tr>
				<td class="aright"><?= t('Территория деятельности') ?></td>
				<td id="teritorytd"><a id="teritory" href="javascript:;"><?= t('Изменить') ?></a></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="hidden" id="latbox" name="map_lat" value="<?= $group['map_lat'] ?>"/>
					<input type="hidden" id="lonbox" name="map_lon" value="<?= $group['map_lon'] ?>"/>
					<input type="hidden" id="zoom" name="map_zoom" value="<?= $group['map_zoom'] ?>"/>
					<input type="hidden" id="coords" name="coords" value="<?= $group['coords'] ?>"/>
					<input type="submit" name="submit" class="button" value=" <?= t('Сохранить') ?> ">
					<input onclick="history.go(-1);" type="button" name="cancel" class="button_gray"
					       value=" <?= t('Отмена') ?> ">
					<?= tag_helper::wait_panel() ?>
					<div class="success hidden mr10 mt10"><?= t('Изменения сохранены') ?>...</div>
				</td>
			</tr>
		</table>
	</form>
	<? if (session::has_credential('admin')) { ?>
		<? include 'partials/more_info.php' ?>
		<? include 'partials/inventory.php' ?>
		<? if ($group['category'] == 3) { ?>
			<? include 'partials/vidatki.php' ?>
		<? } ?>
	<? } ?>
	<form id="photo_form" action="/projects/edit?type=photo&submit=1&id=<?= $group['id'] ?>"
	      class="form mt10 <?= (session::has_credential('designer')) ? '' : 'hidden' ?>" enctype="multipart/form-data">
		<div class="left acenter" style="width: 250px;">
			<? if ($group['photo_salt']) { ?>
				<?= user_helper::reform_photo(user_helper::reform_photo_path($group['id'], 'p', $group['photo_salt'])) ?>
			<? } else {
				load::view_helper('group');
				load::model('groups/groups'); ?>
				<?= group_helper::photo(0, 'p', array('class' => 'border1', 'id' => 'photo')) ?>
				<?
			} ?>
		</div>
		<table class="left fs12" style="width: 400px;">
			<tr>
				<td class="cgray fs11" colspan="2">
					<?= t('Загрузите изображение, символизирующее территорию деятельности Вашей партийной организации (памятник культуры, пейзаж, фото улиц, площадей, любых других объектов, расположенных в пределах территории деятельности организации и являются известными или хотя бы могут помочь отличить Вашу партийную организацию от других).') ?>
					<br/><br/>
					<?= t('Вы можете загрузить фотографии в формате JPG, PNG или GIF размером') ?>
					<b><?= t('не более 2 Мб.') ?></b> <?= t('Не используйте посторонних изображений.') ?><br/><br/>
					<?= t('При возникновении проблем попробуйте загрузить фотографию меньшего размера или') ?> <a
						href="/messages/compose?user_id=10599"><?= t('отправьте сообщение Администрации сайта "Меритократ"') ?></a><br/><br/>

					<br/><br/><br/>
				</td>
			</tr>
			<tr>
				<td class="aright" width="150"><?= t('Выберите файл') ?></td>
				<td><input type="file" name="file" rel="<?= t('Картинка неверная либо слишком большая') ?>"/></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" name="submit" class="button" value=" <?= t('Сохранить') ?> ">
					<input onclick="history.go(-1);" type="button" name="cancel" class="button_gray"
					       value=" <?= t('Отмена') ?> ">
					<?= tag_helper::wait_panel('photo_wait') ?>
					<div class="success hidden mr10 mt10"><?= t('Изменения сохранены') ?></div>
				</td>
			</tr>
		</table>
		<div class="clear"></div>
	</form>
	<? if ($allow_edit) { ?>
		<? include 'partials/leadership.php' ?>
	<? } ?>
	<? if (($group['user_id'] == session::get_user_id()) || reform_peer::instance()->is_moderator($group['id'], session::get_user_id())) { ?>
		<div id="applicants_form" class="hidden form p10">
			<div class="fs12">
				<div class="bold mb5"><?= t('Список заявок на вступление') ?></div>
				<? if (!$applicants) { ?>
					<div id="no_applicants" class="quiet fs11 mb10"><?= t('Список пуст') ?></div>
				<? } else { ?>
					<div id="applicants">
						<? foreach ($applicants as $applicant_id) {
							include 'partials/applicant.php';
						} ?>
					</div>
				<? } ?>
			</div>
			<br/>
		</div>
	<? } ?>

</div>
<?
if ($_SERVER['SERVER_NAME'] == 'meritokrat.org') {
	$key = 'ABQIAAAAeJTsA7ppykO6RHwqXVTnxhRUv1QFGme1wBmmBs0G3PPf8lp1HxSLUl3FK3V4kfgdjiurxjuNdubvAg';
} else {
	//ABQIAAAAeJTsA7ppykO6RHwqXVTnxhS237pdi7AAC2Fq3Ha5pN09SYJt4xRkBNsN6wrom0qaIxq0Haiiaurq6A
	$key = 'ABQIAAAAXi7AtY5jQ4YMZS3uNqaQVhSn51_jLMmjl25B6QxLNt9bnzD_KBRpTuhouSuZjyfhXbGmAM6vx3bLFw';
}

if ($group['map_lat'] == '' || $group['map_lon'] == '') {
	$group['map_lat'] = '50.4599800';
	$group['map_lon'] = '30.4810890';
	$ow = 0;
} else $ow = 1;
if ($group['map_zoom'] == 0) $group['map_zoom'] = '8';
?>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?= $key ?>"
        type="text/javascript"></script>
<script type="text/javascript">
	jQuery(document).ready(function ($) {
		<?if(request::get_int('show_teritory')){?>reformController.showTerritory();
		<?}?>
		$("#teritory").click(function () {
			reformController.showTerritory();
			return false;
		});
		$("#glava").click(function () {
			Application.showUsers('glava');
			return false;
		});
		$("#secretar").click(function () {
			Application.showUsers('secretar');
			return false;
		});
		$('#category').change(function () {
			if ($(this).val() == 2) {
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
		<?if(request::get('tab')){?>
		$('.form').hide();
		$('#<?=request::get('tab')?>_form').show();
		<?}?>

		$("#ui-datepicker-div").delay(250).css("background-color", "#fff");
	});
	$(".info").click(function () {
		if (!$("#applicant_info_" + this.id).is(":visible")) {
			$("#applicant_info_" + this.id).slideDown(100);

		}
		else {
			$("#applicant_info_" + this.id).slideUp(100);
		}
	});
	$('#city').change(function () {
		if ($(this).val() >= 700 || $('#region').val() == 13)$('#nspu').hide();
		else $('#nspu').show();
	});
	$('.ppocategory').change(function () {
		if ($(this).val() == 3)$('#scity').hide();
		else $('#scity').show();
		if ($(this).val() == 1)$('.ptype').show();
		else $('.ptype').hide();
	});

	function initialize2() {
		if (GBrowserIsCompatible()) {
			map = new GMap2(document.getElementById("Map"));

			var gzm = document.getElementById("zoom").value;
			if (gzm == '') gzm = 13;

			map.setCenter(new GLatLng('<?=$group['map_lat']?>', '<?=$group['map_lon']?>'), <?=$group['map_zoom']?>);

			map.addControl(new GLargeMapControl());
			map.addControl(new GMapTypeControl());
			//var reformCoords=new Array();
			<?
	$cord_array=explode('; ', $group['coords']);
	$cord_array = array_unique(array_diff($cord_array, array('')));
	if(count($cord_array)>0) {?>
			<?
			echo "var polyline = new GPolyline([";
			foreach($cord_array as $k=>$c) {
				$coordinates = explode(", ",$c);
				?>
			//var array = [ <?=$coordinates[1] . "," . $coordinates[0]?> ]
			//reformCoords.push(array);
			<?
				echo "new GLatLng(" . $coordinates[1] . "," . $coordinates[0] . "),\n";
			}
			$firstcoordinates = explode(", ",$cord_array[0]);
			echo "new GLatLng(" . $firstcoordinates[1] . "," . $firstcoordinates[0] . ")\n";
			echo "],\"#ff0000\", 5, 1);\n";
			echo "map.addOverlay(polyline);\n\n";
		}
		?>
			map.clearOverlays();
			GEvent.addListener(map, 'click', mapClick);
			featureTable_ = document.getElementById("featuretbody");
			select("hand_b");
		}
	}
</script>