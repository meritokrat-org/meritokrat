<style type="text/css">
	.lgray {
		background-color: lightgray;
		margin-top: 10px;
		margin-bottom: 10px;
	}

	<?if(request::get_int('map')){?>
	.search-results li {
		width: 32%;
	}

	<?}?>
</style>

<div class="form_bg mt10 mr10 mb15" id="all_content">
	<h1 class="column_head"><?= t('Поиск') ?></h1>
	<a onclick="$('#searchformcontainer').show()"
	   class="srchpanel fs11 ml15 mt5 <?= (request::get_int('status') || request::get_int('segment') || request::get_int('expert') || request::get_string('gender') || request::get_int('contacts') || request::get_int('offline')) || request::get_int('map') || request::get_int('smap') ? '' : 'bold' ?>"
	   href="javascript:;" rel="search">
		<?= t('Основной') ?>
	</a>
	<a onclick="$('#searchformcontainer').show()"
	   class="srchpanel fs11 ml15 mt5 <?= ((request::get_int('status') || request::get_int('segment') || request::get_int('expert') || request::get_string('gender') || request::get_int('offline')) && !request::get_int('contacts')) && !request::get_int('map') && !request::get_int('map') ? 'bold' : '' ?>"
	   href="javascript:;" rel="search_advanced">
		<?= t('Расширенный') ?>
	</a>
	<? if (session::is_authenticated()) { ?>
		<a onclick="$('#searchformcontainer').show()"
		   class="<?= (request::get_int('map')) ? 'srchpanel' : '' ?> fs11 ml15 mt5 <?= (request::get_int('map')) ? 'bold' : '' ?>"
		   href="<?= (!request::get_int('map')) ? '/search?map=1&distance=10&submit=1' : 'javascript:;' ?>" <?= (request::get_int('map')) ? 'rel="search_map"' : '' ?>>
			<?= t('Кто рядом') ?>
		</a>

		<a class="<?= (request::get_int('smap')) ? 'srchpanel' : '' ?> fs11 ml15 mt5 <?= (request::get_int('smap')) ? 'bold' : '' ?>"
		   href="<?= (!request::get_int('smap')) ? '/search?smap=1&distance=10&submit=1' : 'javascript:;' ?>" <?= (request::get_int('smap')) ? 'rel="search_smap"' : '' ?>>
			<?= t('Карта') ?> <?= db::get_scalar("SELECT count(*) FROM user_data
                        JOIN user_auth ON user_data.user_id=user_auth.id 
AND user_auth.active=true WHERE locationlng>0") ?>
			(<?= db::get_scalar("SELECT count(*) FROM user_data WHERE onmap=1 AND locationlng>0") ?>
			+<?= db::get_scalar("SELECT count(*) FROM user_data WHERE onmap=0 AND locationlng>0") ?>)
		</a>
	<? } ?>
	<? if (session::has_credential('admin') || count($is_regional_coordinator) > 0 || count($is_raion_coordinator) > 0) { ?>
		<a onclick="$('#searchformcontainer').show()"
		   class="srchpanel fs11 ml15 mt5 <?= (request::get_int('contacts')) ? 'bold' : '' ?>" href="javascript:;"
		   rel="search_contacts">
			*<?= t('Контакты') ?>
		</a>
	<? } ?>
	<? if ($found) { ?>
		<a class="right fs11 mr10" href="javascript:;" onclick="$('#searchformcontainer').toggle()"
		   rel="search_contacts">
			<?= t('Показать/спрятать форму') ?>
		</a>
	<? } ?>

	<div id="searchformcontainer"
	     class="<?= ($found && (!request::get_int('map') || (request::get_int('map') && request::get_int('page')))) ? 'hide' : '' ?>">
		<!-- SIMPLE -->
		<div id="search"
		     class="mt10 fs11 <?= (request::get_int('status') || request::get_int('segment') || request::get_int('expert') || request::get_string('gender') || request::get_int('contacts') || request::get_int('offline') || request::get_int('map') || request::get_int('smap')) ? 'hide' : '' ?>">
			<form method="get" action="/search" class="mr10 ml10">
				<table>
					<tr>
						<td class="aright" width="250"><?= t('Имя') ?></td>
						<td>
							<input name="first_name" style="width:194px;" class="text" type="text"
							       value="<?= stripslashes(htmlspecialchars(request::get_string('first_name'))) ?>"/>
						</td>
					</tr>
					<tr>
						<td class="aright"><?= t('Фамилия') ?></td>
						<td>
							<input name="last_name" style="width:194px;" class="text" type="text"
							       value="<?= stripslashes(htmlspecialchars(request::get_string('last_name'))) ?>"/>
						</td>
					</tr>
					<? if (session::has_credential('admin')) { ?>
						<tr>
							<td class="aright">* E-mail</td>
							<td>
								<input name="email" style="width:194px;" class="text" type="text"
								       value="<?= stripslashes(htmlspecialchars(request::get_string('email'))) ?>"/>
							</td>
						</tr>
					<? } ?>
					<tr>
						<td class="aright"><?= t('Страна') ?></td>
						<td>
							<? load::model('geo') ?>
							<? $сountries = geo_peer::instance()->get_countries(); ?>
							<?= tag_helper::select('country', $сountries, array('use_values' => false, 'style' => 'width:200px;', 'value' => request::get_int('country', 0), 'id' => 'country', 'rel' => t('Выберите страну'), /*'onchange'=>"getRegions(this,'info','region','/ua/ajax/action/regions/')"*/)) ?>
						</td>
					</tr>
					<tr id="region_row">
						<td class="aright"><?= t('Регион') ?></td>
						<td>
							<?
							$regions = geo_peer::instance()->get_regions(request::get_int('country', 1));
							?>
							<input name='region_txt' type='text' style="display: none" id='region_txt' value="<?=request::get_string("region_txt", "")?>">
							<?= tag_helper::select('region', $regions, array('use_values' => false, 'style' => "width:160px; ".$style_sel , 'value' => request::get_int('region', 0), 'id' => 'region', 'name' => 'region', 'rel' => t('Выберите регион'))); ?>
						</td>
					</tr>
					<tr>
						<td class="aright"><?= t('Город/Район') ?></td>
						<td>
							<?
							$cities[0] = "&mdash;";
							$cities += geo_peer::instance()->get_cities(request::get_int('region', 1), request::get_int("country", 1) > 1 ? true : false);
							?>
							<input name='city_txt' type='text' style="display: none" id='city_txt' value="<?=request::get_string("city_txt", "")?>">
							<?=tag_helper::select('city', $cities, array('use_values' => false, 'value' => request::get_int('city', 0), 'style' => "width:160px; ".$cstyle_sel, 'id' => 'city', 'name' => 'city', 'rel' => t('Выберите город/район'))); ?>
						</td>
					</tr>
					<tr>
						<td class="aright"><?= t('Функции') ?></td>
						<? $functions = user_auth_peer::get_functions();
						$functions[''] = '&mdash;';
						ksort($functions);
						?>
						<td><?= tag_helper::select('function', $functions, array('use_values' => false, 'value' => request::get_int('function'), 'style' => 'width:200px;')) ?></td>
					</tr>
					<tr>
						<td class="aright"><?= t('Статус') ?></td>
						<td>
							<select name="status">
								<option value="">&mdash;</option>
								<? $sts = user_auth_peer::get_statuses() ?>
								<? if (session::has_credential('admin')) $sts[99] = '*' . t('Лише мерітократи'); ?>
								<? foreach ($sts as $k => $v) { ?>
									<option
										value="<?= $k ?>" <?= (request::get('status') === "$k") ? 'selected="selected"' : '' ?>><?= $v ?></option>
								<? } ?>
							</select>
						</td>
					</tr>
					<tr>
						<td class="aright"><?= t('Последнее посещение') ?></td>
						<td>
							<select name="visit_ts" value="" use_values="">
								<option
									value="" <?= request::get('visit_ts') == '' ? 'selected' : '' ?>>&mdash;</option>
								<option
									value="1" <?= request::get('visit_ts') == 1 ? 'selected' : '' ?>><?= t('сегодня') ?></option>
								<option
									value="3" <?= request::get('visit_ts') == 3 ? 'selected' : '' ?>><?= t('3 дня') ?></option>
								<option
									value="7" <?= request::get('visit_ts') == 7 ? 'selected' : '' ?>><?= t('неделя') ?></option>
								<option
									value="30" <?= request::get('visit_ts') == 30 ? 'selected' : '' ?>><?= t('месяц') ?></option>
								<option
									value="-7" <?= request::get('visit_ts') == '-7' ? 'selected' : '' ?>><?= t('больше недели') ?></option>
								<option
									value="-30" <?= request::get('visit_ts') == '-30' ? 'selected' : '' ?>><?= t('больше месяца') ?></option>
								<option
									value="-163" <?= request::get('visit_ts') == '163' ? 'selected' : '' ?>><?= t('больше полгода') ?></option>
								<option
									value="-365" <?= request::get('visit_ts') == '-365' ? 'selected' : '' ?>><?= t('больше года') ?></option>

							</select>
						</td>
					</tr>
					<? load::model('user/user_desktop') ?>
					<? if (session::has_credential('admin') || user_desktop_peer::instance()->is_regional_coordinator(session::get_user_id()) || user_desktop_peer::instance()->is_raion_coordinator(session::get_user_id())) { ?>
						<tr>
							<td class="aright">* Активні</td>
							<td>
								<select name="active" value="" use_values="">
									<option
										value="" <?= request::get('active') == '' ? 'selected' : '' ?>>&mdash;</option>
									<option value="10" <?= request::get('active') == 10 ? 'selected' : '' ?>>ні</option>
									<option value="1" <?= request::get('active') == 1 ? 'selected' : '' ?>>так</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="aright">* <?= t('Создание профиля') ?></td>
							<td>
								<? if (request::get_int('start_begin_day') && request::get_int('start_begin_month') && request::get_int('start_begin_year')) {
									$start_begin = mktime(0, 0, 0, request::get_int('start_begin_month'), request::get_int('start_begin_day'), request::get_int('start_begin_year'));
								} ?>
								<?= user_helper::datefields('start_begin', intval($start_begin), false, array(), true) ?>
								<span class="left mr10">&mdash;</span>
								<? if (request::get_int('start_end_day') && request::get_int('start_end_month') && request::get_int('start_end_year')) {
									$start_end = mktime(0, 0, 0, request::get_int('start_end_month'), request::get_int('start_end_day'), request::get_int('start_end_year'));
								} ?>
								<?= user_helper::datefields('start_end', intval($start_end), false, array(), true) ?>
							</td>
						</tr>
						<tr>
							<td class="aright">* <?= t('Активация профиля') ?></td>
							<td>
								<? if (request::get_int('activation_begin_day') && request::get_int('activation_begin_month') && request::get_int('activation_begin_year')) {
									$activation_begin = mktime(0, 0, 0, request::get_int('activation_begin_month'), request::get_int('activation_begin_day'), request::get_int('activation_begin_year'));
								} ?>
								<?= user_helper::datefields('activation_begin', intval($activation_begin), false, array(), true) ?>
								<span class="left mr10">&mdash;</span>
								<? if (request::get_int('activation_end_day') && request::get_int('activation_end_month') && request::get_int('activation_end_year')) {
									$activation_end = mktime(0, 0, 0, request::get_int('activation_end_month'), request::get_int('activation_end_day'), request::get_int('activation_end_year'));
								} ?>
								<?= user_helper::datefields('activation_end', intval($activation_end), false, array(), true) ?>
							</td>
						</tr>
					<? }
					if (session::has_credential('admin')) { ?>
						<tr>
							<td class="aright">* Мітки</td>
							<td>
								<input type="checkbox" value="1"
								       name="interesting" <?= request::get('interesting') ? 'checked="checked"' : '' ?>>
								Цікава особа
							</td>
						</tr>
						<tr>
							<td class="aright">* Про себе</td>
							<td>
								<input type="radio" value="1"
								       name="about" <?= request::get('about') == 1 ? 'checked="checked"' : '' ?>>
								заповнено
								<input type="radio" value="2"
								       name="about" <?= request::get('about') == 2 ? 'checked="checked"' : '' ?>> не
								заповнено
							</td>
						</tr>
						<tr>
							<td class="aright">* Контактує</td>
							<? $who_contacts = user_novasys_data_peer::get_who_contacts();
							$who_contacts[''] = '&mdash;';
							$who_contacts[100] = t('никто');
							ksort($who_contacts);
							?>
							<td><?= tag_helper::select('contacted', $who_contacts, array('use_values' => false, 'value' => request::get_int('contacted'), 'style' => 'width:200px;')) ?></td>
						</tr>

						<tr>
							<td class="aright">* <?= t('Статус контакта') ?></td>
							<td>
								<select name="contact_status">
									<option value="">&mdash;</option>
									<? foreach (user_helper::get_statuses() as $k => $v) { ?>
										<option
											value="<?= $k ?>" <?= (request::get('contact_status') === "$k") ? 'selected="selected"' : '' ?>><?= $v ?></option>
									<? } ?>
								</select>
							</td>
						</tr>

						<tr>
							<td class="aright"><?= t('Целевая группа') ?></td>
							<td>
								<select name="target">
									<option value="">&mdash;</option>
									<? foreach (user_helper::get_targets() as $k => $v) { ?>
										<option
											value="<?= $k ?>" <?= (request::get('target') === "$k") ? 'selected="selected"' : '' ?>><?= $v ?></option>
									<? } ?>
								</select>
							</td>
						</tr>

						<tr>
							<td class="aright">* <?= t('Целевая группа') ?></td>
							<td>
								<select name="admintarget">
									<option value="">&mdash;</option>
									<? foreach (user_helper::get_targets() as $k => $v) { ?>
										<option
											value="<?= $k ?>" <?= (request::get('admintarget') === "$k") ? 'selected="selected"' : '' ?>><?= $v ?></option>
									<? } ?>
								</select>
							</td>
						</tr>

						<tr>
							<td class="aright">* Формат</td>
							<td>
								<input type="checkbox" value="1"
								       name="outlook" <?= request::get('outlook') ? 'checked="checked"' : '' ?>> Outlook
							</td>
						</tr>

						<tr>
							<td class="aright">* <?= t('Сортування') ?></td>
							<td>
								<select name="sort" value="" use_values="">
									<option value="az" <?= request::get('sort') == 'az' ? 'selected' : '' ?>>За
										прізвищем
									</option>
									<option
										value="last" <?= (request::get('sort') == 'last' or !request::get('sort')) ? 'selected' : '' ?>>
										Останні першими
									</option>
								</select>
							</td>
						</tr>
					<? } ?>
					<tr>
						<td class="aright"></td>
						<td><input type="submit" name="submit" class="button" value=" <?= t('Найти') ?> &raquo; "></td>
					</tr>
				</table>
			</form>
		</div>

		<!-- ADVANCED -->

		<!--input name="keyword" type="text" class="text" value="<?= htmlspecialchars;
		($keyword) ?>" style="width: 470px;"-->
		<div id="search_advanced"
		     class="mt10 fs11 <?= ((request::get_int('status') || request::get_int('segment') || request::get_int('expert') || request::get_string('gender') || request::get_int('offline')) && (!request::get_int('contacts'))) ? '' : 'hide' ?>">
			<form method="get" action="/search" class="mr10 ml10">
				<table>
					<tr>
						<td class="aright" width="250"><?= t('Имя') ?></td>
						<td>
							<input name="first_name" style="width:194px;" class="text" type="text"
							       value="<?= stripslashes(htmlspecialchars(request::get_string('first_name'))) ?>"/>
						</td>
					</tr>
					<tr>
						<td class="aright"><?= t('Фамилия') ?></td>
						<td>
							<input name="last_name" style="width:194px;" class="text" type="text"
							       value="<?= stripslashes(htmlspecialchars(request::get_string('last_name'))) ?>"/>
						</td>
					</tr>
					<? if (session::has_credential('admin')) { ?>
						<tr>
							<td class="aright">* E-mail</td>
							<td>
								<input name="email" style="width:194px;" class="text" type="text"
								       value="<?= stripslashes(htmlspecialchars(request::get_string('email'))) ?>"/>
							</td>
						</tr>
						<tr>
							<td class="aright">* <?= t('Телефон') ?></td>
							<td>
								<input name="phone" style="width:194px;" class="text" type="text"
								       value="<?= stripslashes(htmlspecialchars(request::get_string('phone'))) ?>"/>
							</td>
						</tr>
						<tr>
							<td class="aright">* <?= t('Наличие телефона') ?></td>
							<td>
								<?= tag_helper::select('has_phone', array('&mdash;', t('Да'), t('Нет')), array('use_values' => false, 'style' => 'width:200px;', 'value' => request::get_int('has_phone'), 'id' => 'has_phone')) ?>
							</td>
						</tr>
					<? } ?>
					<tr>
						<td class="aright">
							<?= t('Возраст') ?>:
						</td>
						<td>
							<? $ages = range(15, 107);
							array_unshift($ages, '...') ?>
							<?= tag_helper::select('age_start', $ages, array('use_values' => true, 'style' => 'width:91px;', 'value' => $age_start)) ?>
							&mdash;
							<?= tag_helper::select('age_end', $ages, array('use_values' => true, 'style' => 'width:91px;', 'value' => $age_end)) ?>
						</td>
					</tr>
					<tr>
						<td class="aright"><?= t('Страна') ?></td>
						<td>
							<? load::model('geo') ?>
							<? $сountries = geo_peer::instance()->get_countries();
							ksort($сountries); ?>
							<? // print_r($сountries); ?>
							<?= tag_helper::select('country', $сountries, array('use_values' => false, 'style' => 'width:200px;', 'value' => request::get_int('country', 1), 'id' => 'country', 'rel' => t('Выберите страну'), /*'onchange'=>"getRegions(this,'info','region','/ua/ajax/action/regions/')"*/)) ?>
							<!--input name="country" class="text" type="text" value="<?= $country['name_' . translate::get_lang()] ?>" /-->
						</td>
					</tr>
					<tr id="region_row">
						<td class="aright"><?= t('Регион') ?></td>
						<td>
							<? if (request::get_int('country', 0) < 2) {
								$regions = geo_peer::instance()->get_regions(request::get_int('country', 1));
								$regions[''] = '&mdash;';
								ksort($regions);
							} else   $regions = array("" => "&mdash;"); ?>
							<?= tag_helper::select('region_adv', $regions, array('value' => $region_selected, 'use_values' => false, 'style' => 'width:200px;', 'id' => 'region_adv', 'rel' => t('Выберите регион'),)); ?>
							<!--input name="region" class="text" type="text" value="<?= $region['name_' . translate::get_lang()] ?>" /-->
						</td>
					</tr>
					<tr>
						<td class="aright"><?= t('Город/Район') ?></td>
						<td>
							<select id="city_adv_select" name="city_adv_select" style="width: 200px;">
								<option value="">&mdash;</option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="aright"><?= t('Сфера деятельности') ?></td>
						<? load::model('user/user_auth') ?>
						<? $sferas = user_auth_peer::instance()->get_segments();
						$sferas[''] = '&mdash;';
						ksort($sferas);
						?>
						<td><?= tag_helper::select('segment', $sferas, array('use_values' => false, 'value' => request::get_int('segment'), 'style' => 'width:200px;')) ?></td>
					</tr>
					<? /** ?>
					 * <tr>
					 * <td class="aright"><?=t('Статус')?></td>
					 * <? $types = user_auth_peer::instance()->get_types();
					 * $types['']='&mdash;';
					 * unset($types[10]);
					 * unset($types[0]);
					 * ksort($types);
					 * ?>
					 * <td><?=tag_helper::select('type', $types, array('use_values' => false, 'value' => request::get_int('status'),'style'=>'width:200px;'))?></td>
					 * </tr>
					 * <? */ ?>

					<tr>
						<td class="aright"><?= t('Статус') ?></td>
						<td>
							<select name="status">
								<option value="">&mdash;</option>
								<? $sts = user_auth_peer::get_statuses() ?>
								<? if (session::has_credential('admin')) $sts[99] = '*' . t('Лише мерітократи'); ?>
								<? foreach ($sts as $k => $v) { ?>
									<option
										value="<?= $k ?>" <?= (request::get('status') === "$k") ? 'selected="selected"' : '' ?>><?= $v ?></option>
								<? } ?>
							</select>
						</td>
					</tr>

					<tr>
						<td class="aright"><?= t('Функции') ?></td>
						<? $functions = user_auth_peer::get_functions();
						$functions[''] = '&mdash;';
						ksort($functions);
						?>
						<td><?= tag_helper::select('function', $functions, array('use_values' => false, 'value' => request::get_int('function'), 'style' => 'width:200px;')) ?></td>
					</tr>
					<?
					/*
					<tr>
			<td class="aright"><?=t('Эксперт')?></td>
							<? $expert = user_auth_peer::get_expert_types() ?>
			<td><?=tag_helper::select('expert', $expert, array('use_values' => false, 'value' => request::get_int('expert'),'style'=>'width:200px;'))?></td>
		</tr>
					*/
					?>
					<tr>
						<td class="aright"><?= t('Поиск работы') ?></td>
						<td>
							<input type="checkbox" value="1"
							       name="work_jobsearch1" <?= request::get('work_jobsearch1') == 1 ? 'checked="checked"' : '' ?> /> <?= t('Активно ищу работу') ?>
							<br/>
							<input type="checkbox" value="2"
							       name="work_jobsearch2" <?= request::get('work_jobsearch2') == 2 ? 'checked="checked"' : '' ?> /> <?= t('Работаю, но открыт для предложений') ?>
						</td>
					</tr>
					<tr>
						<td class="aright"><?= t('Последнее посещение') ?></td>
						<td>
							<select name="visit_ts" value="" use_values="">
								<option
									value="" <?= request::get('visit_ts') == '' ? 'selected' : '' ?>>&mdash;</option>
								<option
									value="1" <?= request::get('visit_ts') == 1 ? 'selected' : '' ?>><?= t('сегодня') ?></option>
								<option
									value="3" <?= request::get('visit_ts') == 3 ? 'selected' : '' ?>><?= t('3 дня') ?></option>
								<option
									value="7" <?= request::get('visit_ts') == 7 ? 'selected' : '' ?>><?= t('неделя') ?></option>
								<option
									value="30" <?= request::get('visit_ts') == 30 ? 'selected' : '' ?>><?= t('месяц') ?></option>
								<option
									value="-7" <?= request::get('visit_ts') == '-7' ? 'selected' : '' ?>><?= t('больше недели') ?></option>
								<option
									value="-30" <?= request::get('visit_ts') == '-30' ? 'selected' : '' ?>><?= t('больше месяца') ?></option>
								<option
									value="-163" <?= request::get('visit_ts') == '163' ? 'selected' : '' ?>><?= t('больше полгода') ?></option>
								<option
									value="-365" <?= request::get('visit_ts') == '-365' ? 'selected' : '' ?>><?= t('больше года') ?></option>

							</select>
						</td>
					</tr>
					<tr>
						<td class="aright">
							<?= t('Пол') ?>
						</td>
						<td>
							<?= tag_helper::select('gender', array(
								'' => t('') . '&mdash;',
								'f' => t('Женский пол'),
								'm' => t('Мужской пол'),
							), array('value' => $gender)) ?>
						</td>
					</tr>
					<? load::model('user/user_desktop') ?>
					<? if (session::has_credential('admin') || user_desktop_peer::instance()->is_regional_coordinator(session::get_user_id()) || user_desktop_peer::instance()->is_raion_coordinator(session::get_user_id())) { ?>
						<tr>
							<td class="aright">* Активні</td>
							<td>
								<select name="active" value="" use_values="">
									<option
										value="" <?= request::get('active') == '' ? 'selected' : '' ?>>&mdash;</option>
									<option value="10" <?= request::get('active') == 10 ? 'selected' : '' ?>>ні</option>
									<option value="1" <?= request::get('active') == 1 ? 'selected' : '' ?>>так</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="aright">* <?= t('Создание профиля') ?></td>
							<td>
								<?= user_helper::datefields('start_begin', intval($start_begin), false, array(), true) ?>
								<span class="left mr10">&mdash;</span>
								<?= user_helper::datefields('start_end', intval($start_end), false, array(), true) ?>
							</td>
						</tr>
						<tr>
							<td class="aright">* <?= t('Активация профиля') ?></td>
							<td>
								<?= user_helper::datefields('activation_begin', intval($activation_begin), false, array(), true) ?>
								<span class="left mr10">&mdash;</span>
								<?= user_helper::datefields('activation_end', intval($activation_end), false, array(), true) ?>
							</td>
						</tr>
					<? }
					if (session::has_credential('admin')) { ?>
						<tr>
							<td class="aright">* Мітки</td>
							<td><input type="checkbox" value="1"
							           name="interesting" <?= request::get('interesting') ? 'checked="checked"' : '' ?>>
								Цікава особа
							</td>
						</tr>
						<tr>
							<td class="aright">* Контактує</td>
							<? $who_contacts = user_novasys_data_peer::get_who_contacts();
							$who_contacts[''] = '&mdash;';
							ksort($who_contacts);
							?>
							<td><?= tag_helper::select('contacted', $who_contacts, array('use_values' => false, 'value' => request::get_int('contacted'), 'style' => 'width:200px;')) ?></td>
						</tr>
						<tr>
							<td class="aright">* Про себе</td>
							<td>
								<input type="radio" value="1"
								       name="about" <?= request::get('about') == 1 ? 'checked="checked"' : '' ?>>
								заповнено
								<input type="radio" value="2"
								       name="about" <?= request::get('about') == 2 ? 'checked="checked"' : '' ?>> не
								заповнено
							</td>
						</tr>
						<tr>
							<td class="aright">* Формат</td>
							<td>
								<input type="checkbox" value="1"
								       name="outlook" <?= request::get('outlook') ? 'checked="checked"' : '' ?>> Outlook
							</td>
						</tr>
					<? } ?>
					<? if (session::has_credential('admin') || user_desktop_peer::instance()->is_regional_coordinator(session::get_user_id()) || user_desktop_peer::instance()->is_raion_coordinator(session::get_user_id())) { ?>
						<tr>
							<td class="aright">* <?= t('Тип анкеты') ?></td>
							<td>
								<select name="offline" value="" use_values="">
									<option
										value="1" <?= request::get_int('offline') == 1 ? 'selected' : '' ?>>&mdash;</option>
									<option
										value="2" <?= (request::get_int('offline') == 2 or !request::get_int('offline')) ? 'selected' : '' ?>><?= t('Он-лайн') ?></option>
									<option
										value="3" <?= request::get_int('offline') == 3 ? 'selected' : '' ?>><?= t('Офф-лайн') ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="aright">* <?= t('Сортування') ?></td>
							<td>
								<select name="sort" value="" use_values="">
									<option value="az" <?= request::get('sort') == 'az' ? 'selected' : '' ?>>За
										прізвищем
									</option>
									<option
										value="last" <?= (request::get('sort') == 'last' or !request::get('sort')) ? 'selected' : '' ?>>
										Останні першими
									</option>
								</select>
							</td>
						</tr>
					<? } ?>
					<? if (session::has_credential('admin')) { ?>
						<tr>
							<td class="aright">* <?= t('Cкрытый профиль') ?></td>
							<td>
								<input type="checkbox" <?= request::get('suslik') == 1 ? 'checked' : '' ?> name="suslik"
								       value="1"/>
							</td>
						</tr>
						<tr>
							<td class="aright">* <?= t('Оригинал заявления') ?></td>
							<td>
								<select name="real_app">
									<option value="0">&mdash;</option>
									<option value="2">Так</option>
									<option value="1">Ні</option>
								</select>
							</td>
						</tr>
					<? } ?>
					<tr>
						<td class="aright"></td>
						<td><input type="submit" name="submit" class="button" value=" <?= t('Найти') ?> &raquo; "></td>
					</tr>
				</table>

			</form>
		</div>

		<!-- MAP -->
		<div id="search_map" class="mt10 fs11 <?= (request::get_int('map')) ? '' : 'hide' ?>">
			<form class="mr10 ml10" action="/search" method="get">
				<input type="hidden" value="1" name="map"/>
				<table class="m0" style="margin-left: 85px;">
					<tr>
						<td class="aright pt5" style="padding-right: 0; padding-top: 7px;"><b><?= t('В радиусе') ?>
								(км)</b></td>
						<td class="aleft">
							<input type="text" class="text"
							       value="<?= (request::get_int('distance')) ? request::get_int('distance') : '100' ?>"
							       id="distance" name="distance" size="10">
							<input type="submit" value=" Знайти » " class="button" name="submit"/>
						</td>
					</tr>
				</table>
			</form>
		</div>

		<!-- CONTACTS -->
		<? if (session::has_credential('admin') || count($is_regional_coordinator) > 0 || count($is_raion_coordinator) > 0) { ?>
			<div id="search_contacts" class="mt10 fs11 <?= (request::get_int('contacts')) ? '' : 'hide' ?>">
				<form method="get" action="/search" class="mr10 ml10">
					<input type="hidden" name="contacts" value="1"/>
					<table>
						<tr>
							<td class="aright" width="250"><?= t('Имя') ?></td>
							<td>
								<input name="first_name" style="width:194px;" class="text" type="text"
								       value="<?= stripslashes(htmlspecialchars(request::get_string('first_name'))) ?>"/>
							</td>
						</tr>
						<tr>
							<td class="aright"><?= t('Фамилия') ?></td>
							<td>
								<input name="last_name" style="width:194px;" class="text" type="text"
								       value="<?= stripslashes(htmlspecialchars(request::get_string('last_name'))) ?>"/>
							</td>
						</tr>
						<tr id="region_contact_row">
							<td class="aright"><?= t('Регион') ?></td>
							<td>
								<? if (request::get_int('country', 0) < 2) {
									$regions = geo_peer::instance()->get_regions(request::get_int('country', 1));
									$regions[''] = '&mdash;';
									ksort($regions);
								} else   $regions = array("" => "&mdash;"); ?>
								<?= tag_helper::select('contact_region', $regions, array('value' => $region_selected, 'use_values' => false, 'style' => 'width:200px;', 'id' => 'contact_region', 'rel' => t('Выберите регион'),)); ?>
								<!--input name="region" class="text" type="text" value="<?= $region['name_' . translate::get_lang()] ?>" /-->
							</td>
						</tr>
						<tr>
							<td class="aright"><?= t('Город/Район') ?></td>
							<td id="region_contact_col">
								<select id="region_contact_city_select" name="region_contact_city_select"
								        style="width: 200px;">
									<option value="">&mdash;
									<option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="aright"><?= t('Описание') ?></td>
							<td>
								<input name="description" style="width:194px;" class="text" type="text"
								       value="<?= stripslashes(htmlspecialchars(request::get_string('description'))) ?>"/>
							</td>
						</tr>
						<tr>
							<td class="aright">ID контактуючого</td>
							<td>
								<input name="contact_user_id" style="width:194px;" class="text" type="text"
								       value="<?= stripslashes(htmlspecialchars(request::get_int('contact_user_id'))) ?>"/>
							</td>
						</tr>
						<tr>
							<td class="aright"><?= t('Кто') ?></td>
							<td> <? $who_contacts = user_novasys_data_peer::get_who_contacts();
								$who_contacts[''] = '&mdash;';
								ksort($who_contacts);
								?>
								<?= tag_helper::select('contact_who', $who_contacts, array('use_values' => false, 'value' => request::get_int('contact_who'))) ?>
							</td>
						</tr>
						<tr>
							<td class="aright"><?= t('Тип контакта') ?></td>
							<td>
								<?= tag_helper::select('contact_type', user_helper::get_contact_types(), array('use_values' => false, 'value' => request::get_int('contact_type'))) ?>
							</td>
						</tr>
						<tr>
							<td class="aright"><?= t('Период контакта') ?></td>
							<td>
								<? if (request::get_int('period_begin_day') && request::get_int('period_begin_month') && request::get_int('period_begin_year')) {
									$period_begin = mktime(0, 0, 0, request::get_int('period_begin_month'), request::get_int('period_begin_day'), request::get_int('period_begin_year'));
								} ?>
								<?= user_helper::datefields('period_begin', intval($period_begin), false, array(), true) ?>
								<span class="left mr10">&mdash;</span>
								<? if (request::get_int('period_end_day') && request::get_int('period_end_month') && request::get_int('period_end_year')) {
									$period_end = mktime(0, 0, 0, request::get_int('period_end_month'), request::get_int('period_end_day'), request::get_int('period_end_year'));
								} ?>
								<?= user_helper::datefields('period_end', intval($period_end), false, array(), true) ?>
							</td>
						</tr>
						<tr>
							<td class="aright"></td>
							<td><input type="submit" name="submit" class="button" value=" <?= t('Найти') ?> &raquo; ">
							</td>
						</tr>
					</table>
				</form>
			</div>
		<? } ?>

	</div>
	<? if ($count_found > 0) { ?>
		<table class="fs11 mt15">
			<tr>
				<td class="aright" width="300">
					<?= t('Найдено') ?>:
				</td>
				<td>
					<b><?= $count_found ?></b> <? //=($count_found>=500)?'('.t('Поиск ограничен по количеству результатов, введите более точные условия').')':''?>
					<? if (session::has_credential('admin') || (in_array(request::get('region'), $is_regional_coordinator)) || (in_array(request::get('city'), $is_raion_coordinator))) { ?>
						<a href="javascript:;" class="right icoprint" id="get_print_c"></a>
					<? } ?>
				</td>
			</tr>
		</table>
	<? } ?>

</div>

<div id="search_form_holder" class="hide">
	<table class="srch fs12">
		<tr>
			<td class="aleft"><input type="checkbox" name="ft" value="1"/><?= t('Фото') ?></td>
			<td class="aleft"><input type="checkbox" name="ph" value="1"/><?= t('Телефон') ?></td>
		</tr>
		<tr>
			<td class="aleft"><input type="checkbox" name="nm" value="1" checked="checked"/><?= t('Имя и фамилия') ?>
			</td>
			<td class="aleft"><input type="checkbox" name="fn" value="1"/><?= t('Функции') ?></td>
		</tr>
		<tr>
			<td class="aleft"><input type="checkbox" name="ot" value="1"/><?= t('Отчество') ?></td>
			<td class="aleft"><input type="checkbox" name="st" value="1"/><?= t('Статус') ?></td>
		</tr>
		<tr>
			<td class="aleft"><input type="checkbox" name="rg" value="1"/><?= t('Регион') ?></td>
			<td class="aleft"><input type="checkbox" name="br" value="1"/><?= t('Дата рождения') ?></td>
		</tr>
		<tr>
			<td class="aleft"><input type="checkbox" name="ct" value="1"/><?= t('Район') ?></td>
			<td class="aleft"><input type="checkbox" name="at" value="1"/><?= t('Активность') ?></td>
		</tr>
		<tr>
			<td class="aleft"><input type="checkbox" name="sg" value="1"/><?= t('Место проживания') ?></td>
			<td class="aleft"><input type="checkbox" name="rt" value="1"/><?= t('Активный с ...') ?></td>
		</tr>
		<? if (session::has_credential('admin')) { ?>
			<tr>
				<td class="aleft"><input type="checkbox" name="hd" value="1"/><?= t('Закрытый статус') ?></td>
				<td class="aleft">
					<? if (request::get_int('contacts')) { ?>
						<input type="checkbox" name="cd" value="1"
						       checked="checked"/><?= t('Ограничить по дате контакта') ?>
					<? } else { ?>
						<input type="checkbox" name="rf" value="1"/><?= t('Как попал в Меритократ') ?>
					<? } ?>
				</td>
			</tr>
			<tr>
				<td class="aleft"><input type="checkbox" name="pc" value="1"/><?= t('Профиль создан ...') ?></td>
				<td class="aleft"><? if (request::get_int('contacts')) { ?><input type="checkbox" name="cn" value="1"
				                                                                  checked="checked"/><?= t('Содержание контакта') ?><? } ?>
				</td>
			</tr>
		<? } ?>
		<tr>
			<td class="aleft"><input type="checkbox" name="em" value="1"/><?= t('E-mail') ?></td>
			<td class="aleft"><input type="checkbox" name="all_pages"
			                         value="1"/><b><?= t('Печать всех результатов') ?></b></td>
		</tr>
		<tr>
			<td colspan="2" style="text-align:center">
				<input type="button" class="button" id="print_on" value="<?= t('Печатать') ?>" onclick="printon();"/>
				<input type="button" class="button_gray" id="print_off" value="<?= t('Назад') ?>"
				       onclick="printoff();"/>
			</td>
		</tr>
	</table>
</div>

<!-- MAP -->
<div id="search_smap" class="mt10 fs11 <?= (request::get_int('smap')) ? '' : 'hide' ?>">
	<? if (request::get_int('smap')) include 'partials/map.php'; ?>
</div>

<div class="mr10">
	<? if ($found && request::get_int('outlook')) { ?>
		<div style="width:200px; font-size: 90%;" class="ml10 left mr10">
			<? foreach ($users as $id) { ?>
				<? include 'partials/outlook.php' ?>
			<? } ?>
		</div>
		<div style="width:500px; font-size: 80%;" class="ml10 left mr10">
			<table width="60%" height="100%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td valign="middle" align="center" id="user_outlook_data">
						<- оберіть учасника
					</td>
				</tr>
			</table>
		</div>
		<div class="clear"></div>
		<div class="right mr10 pager"><?= pager_helper::get_full($pager) ?></div>

	<? }elseif ($found) { ?>

	<? if ((context::get_controller()->get_module() == "search") && (session::has_credential('admin'))) { ?>
		<script src="/static/javascript/jquery/jquery-1.10.2.js"></script>
		<script src="/static/javascript/jquery/jquery-ui-1.11.4.js"></script>
	<? } ?>

		<style>
			.placeholder {
				height: 2.5em;
				line-height: 1.2em;
			}

			/*.search-results li { width: auto !important; }*/
		</style>

		<script>
			var j = jQuery.noConflict();
			j(document).ready(function () {
				j(".search-results, #sort_buffer").sortable({
					connectWith: ".connectedSortable",
					placeholder: "placeholder",
					start: function (event, ui) {
						$("div.minimize", ui.item).show();
						$("div.maximize", ui.item).hide();
					},
					stop: function (event, ui) {
						if ($(ui.item).parent().attr("id") == "sort_buffer") {
							$("div.minimize", ui.item).show().parent().removeClass("mb10 ml10").removeAttr("style");
							$("div.maximize", ui.item).hide();
							$("a.del_sort", ui.item).show();
						} else {
							$("div.minimize", ui.item).hide();
							$("div.maximize", ui.item).show().parent().addClass("mb10 ml10");
							$("a.del_sort", ui.item).hide();
						}

						$(ui.item).removeClass("sortable_li");

						var sort_ids = new Array;

						$("li", "#sort_buffer").each(function (n, e) {
							sort_ids.push($(e).attr("data-user-id"));
						});

						$.post("/people", {
							sort: true,
							list: sort_ids
						}, function (response) {
							console.log(response);
						}, "json");
					},
					remove: function (event, ui) {

						var id = $(ui.item).attr("data-user-id");

						if ($("#sort_buffer").children("li[data-user-id=" + id + "]").length > 1) {
							ui.item.remove();
						}

						$($(ui.item).attr("ui-parrent")).children("li[data-user-id=" + id + "]:not(.sortable_li)").remove();

						ui.item.clone().prependTo("#" + $(ui.item).attr("ui-parrent"));

						$("#" + $(ui.item).attr("ui-parrent")).children("li[data-user-id=" + id + "]").children(".minimize").hide();
						$("#" + $(ui.item).attr("ui-parrent")).children("li[data-user-id=" + id + "]").addClass("mb10 ml10");
						$("#" + $(ui.item).attr("ui-parrent")).children("li[data-user-id=" + id + "]").children(".maximize").show();
					}
				}).disableSelection();

				$(".del_sort").live("click", function () {
					$(this).parent().remove();

					var sort_ids = new Array;

					$("li", "#sort_buffer").each(function (n, e) {
						sort_ids.push($(e).attr("data-user-id"));
					});

					$.post("/people", {
						sort: true,
						list: sort_ids
					}, function (response) {
						console.log(response);
					}, "json");
				});
			})
		</script>

		<div class="left" style="width: 35%;"><? include 'partials/left.php' ?></div>

		<div class="left ml10" style="width: 62%;">
			<? if (count($all_coordinators) > 0 && (!request::get_int('page') || request::get_int('page') == 1)) { ?>
				<div class="mb10"
				     style="-moz-border-radius: 4px 4px 4px 4px; border:solid 1px #660000; float:left; padding-top:10px">
					<ul class="search-results connectedSortable" id="results-important" style="width: 100%;">
						<? foreach ($all_coordinators as $id) { ?>
							<li class="mb10 ml10 sortable_li" data-user-id='<?= $id ?>' style="width: 450px"
							    ui-parrent="results-important">
								<? include 'partials/user.php' ?>
							</li>
						<? } ?>
					</ul>
				</div>
				<div class="clear"></div>
			<? } ?>

			<div class="mb10"
			     style="-moz-border-radius: 4px 4px 4px 4px; border:solid 1px #660000; float:left; padding-top:10px">
				<ul class="search-results connectedSortable" id="results-all" style="width: 100%;">
					<? foreach ($users as $id) { ?>
						<li class="mb10 ml10 sortable_li" data-user-id='<?= $id ?>' style="width: 450px"
						    ui-parrent="results-all">
							<? include 'partials/user.php' ?>
						</li>
					<? } ?>
				</ul>
			</div>

			<!--			<ul class="search-results sortable" id="results-all" style="width: 100%;">-->
			<!--				--><? // foreach ( $users as $id ) { ?>
			<!--					<li class="mb10 ml10" style="min-height:91px; width: 98%" ui-parrent="results-all">-->
			<!--						--><? // include 'partials/user.php' ?>
			<!--					</li>-->
			<!--				--><? // } ?>
			<!--			</ul>-->
		</div>

		<!--		<ul id="sort_list" class="sort" style="list-style: none; margin: 0">-->
		<!--			--><? //
	//			foreach ( $list as $id )
	//			{
	//				echo "<li class='sortable_li' data-user-id='$id'>";
	//				include 'partials/person.php';
	//				echo "</li>";
	//			}
	//			?>
		<!--		</ul>-->

		<div class="clear"></div>
		<div class="right mr10 pager"><?= pager_helper::get_full($pager) ?></div>

	<? } elseif (!request::get_int('smap')) { ?>
		<div class="screen_message acenter quiet"><?= t('Ничего не найдено') ?></div>
	<? } ?>
</div>

<script>
	$(function () {
		$cid = eval("(" + <?=$city_selected?> +")");
		$('#all_content').find('select').each(function () {
			$(this).change(function () {
				if ($(this).attr('id') == 'contact_region' || $(this).attr('id') == 'region_adv') {
					if ($(this).attr('id') == 'contact_region')
						$target = '#region_contact_city_select';
					else
						$target = '#city_adv_select';
					$.ajax({
						url: 'search/index',
						data: {
							regId: $(this).val()
						},
						success: function (resp) {
							data = eval("(" + resp + ")");
							$opt = '<option value="">&mdash;</option>';
							for ($key in data.cities) {
								$opt += '<option value="' + data.cities[$key].id + '"';
								if (($cid) && ($cid == data.cities[$key].id))
									$opt += 'selected';
								$opt += '>' + data.cities[$key].title + "</option>";
							}
							$($target).html($opt);
						}
					});
				}
			});
		});
		<?if($region_selected) {?>
		init_sel();
		<?}?>

	});
	function init_sel() {
		$rid = eval("(" + <?=$region_selected?>+")");
		$opt = '';
		$('#all_content').find('select').each(function () {
			if ($(this).attr('id') == 'contact_region' || $(this).attr('id') == 'region_adv') {
				$.ajax({
					url: 'search/index',
					data: {
						regId: $rid
					},
					success: function (resp) {
						//alert(resp);
						data = eval("(" + resp + ")");
						$opt = '<option value="">&mdash;</option>';
						for ($key in data.cities) {
							$opt += '<option value="' + data.cities[$key].id + '"';
							//alert('cid='+$cid+'data_id='+data.cities[$key].id);

							if (($cid != 0) && ($cid == data.cities[$key].id)) {
								$opt += 'selected';
								//alert('ok');
							}
							$opt += '>' + data.cities[$key].title + "</option>";
						}
						if ($(this).attr('id') == 'contact_region') {
							$target = '#region_contact_city_select';
							$($target).html($opt);

						}
						else {
							$target1 = '#city_adv_select';
							$($target1).html($opt);
						}
					}
				});

			}
		});

	}

</script>
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

		$('#start_begin').datepicker(settings);
		$('#start_end').datepicker(settings);
		$('#activation_begin').datepicker(settings);
		$('#activation_end').datepicker(settings);

		$('#start_begin_a').datepicker(settings);
		$('#start_end_a').datepicker(settings);
		$('#activation_begin_a').datepicker(settings);
		$('#activation_end_a').datepicker(settings);

		$('#period_begin').datepicker(settings);
		$('#period_end').datepicker(settings);

		$('#get_print, #get_print_a, #get_print_c').click(function () {
			Popup.show();
			Popup.setHtml($('#search_form_holder').html());
			$('#popup_box').css({'left': parseInt($('#popup_box').css('left')) - 250 + 'px'});
		});
		$('.outlook_user').click(function () {
			var id = $(this).attr('rel');
			$('#user_outlook_data').html("<img src='https://s1.meritokrat.org/common/loaging.gif' style='margin-top:240px;'>");
			$('.outlook_user').removeClass('lgray');
			$(this).addClass('lgray');
			$.get('/people/get_one', {id: id},
				function (data) {
					$('#user_outlook_data').html(data);
				});
		});

		$('.srchpanel').click(function () {
			var id = $(this).attr('rel');
			$('.srchpanel').removeClass('bold');
			$(this).addClass('bold');
			$('#search, #search_advanced, #search_contacts, #search_map, #search_smap').hide();
			$('#' + id).show();
		});

	});
	function printoff() {
		$('#popup_box').hide();
	}

	function printon() {
		var str = '&print=1';
		$('#popup_box').find('input[type="checkbox"]:checked').each(function () {
			str += '&' + $(this).attr('name') + '=1';
		});
		window.location = "<?=$_SERVER['REQUEST_URI']?>" + str;
	}
</script>
