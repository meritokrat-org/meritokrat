<div id="divainfo" class="hide">
	<div class="pt5 fs11 tab_pane_gray">
		<a rel="hist" href="javascript:;" class="cgray ml10 selected"><?= t('История контактов') ?></a>
		<? if (!$show_contact_info) { ?> <a rel="info" href="javascript:;"
		                                    class="cgray ml10"><?= t('Инфо') ?></a><? } ?>
		<a rel="cont" href="javascript:;" class="cgray ml10"><?= t('Контакты') ?></a>
		<a rel="no_viewed_blogs" href="javascript:;" class="cgray ml10"><?= t('Непрочитанные блоги') ?></a>

		<div class="clear"></div>
	</div>
	<? if (!$show_contact_info) { ?>
		<div id="pane_info" class="hide content_pane">
			<table class="fs12 mt10">

				<tr>
					<td width="35%;" class="bold aright p0"></td>
					<td class="aright p0">
						<? if (session::has_credential('admin')) { ?>
							<a class="fs11 cgray"
							   href="/profile/edit?id=<?= $user['id'] ?>&tab=admin_info"><?= t('Редактировать') ?></a>
						<? } ?>
					</td>
				</tr>
				<?
				//    echo "<pre>";
				//    var_dump($user_info);
				//    echo "</pre>";
				?>
				<? if ($user_info['fname']) { ?>
					<tr>
						<td class="bold aright" width="35%;"><?= t('Имя') ?></td>
						<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_info['fname'])) ?></td>
					</tr>
				<? } ?>
				<? if ($user_info['fathername']) { ?>
					<tr>
						<td class="bold aright" width="35%;"><?= t('Отчество') ?></td>
						<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_info['fathername'])) ?></td>
					</tr>
				<? } ?>
				<? if ($user_info['sname']) { ?>
					<tr>
						<td class="bold aright" width="35%;"><?= t('Фамилия') ?></td>
						<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_info['sname'])) ?></td>
					</tr>
				<? } ?>
				<? if ($user_info['country']) { ?>
					<? $country = geo_peer::instance()->get_country($user_info['country']) ?>
					<tr>
						<td class="bold aright" width="35%;"><?= t('Страна') ?></td>
						<td style="color:#333333"><?= $country['name_' . translate::get_lang()] ?></td>
					</tr>
				<? } ?>
				<? if ($user_info['region']) { ?>
					<? $region = geo_peer::instance()->get_region($user_info['region']) ?>
					<tr>
						<td class="bold aright" width="35%;"><?= t('Регион') ?></td>
						<td style="color:#333333"><?= $region['name_' . translate::get_lang()] ?></td>
					</tr>
				<? } ?>
				<? if ($user_info['district']) { ?>
					<? $city = geo_peer::instance()->get_city($user_info['district']) ?>
					<tr>
						<td class="bold aright" width="35%;"><?= t('Город областного подчинения/Район области') ?></td>
						<td style="color:#333333"><?= $city['name_' . translate::get_lang()] ?></td>
					</tr>
				<? } ?>
				<? if ($user_info['location']) { ?>
					<tr>
						<td class="bold aright" width="35%;"><?= t('Место проживания') ?></td>
						<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_info['location'])) ?></td>
					</tr>
				<? } ?>
				<? if ($user_info['age']) { ?>
					<tr>
						<td class="bold aright" width="35%;"><?= t('Дата рождения') ?></td>
						<td style="color:#333333"><?= $user_info['age'] ?></td>
					</tr>
				<? } ?>
				<? if ($user_info['sex']) { ?>
					<tr>
						<td class="bold aright" width="35%;"><?= t('Пол') ?></td>
						<td style="color:#333333"><?= ($user_info['sex'] == 2) ? t('Мужской') : t('Женский') ?></td>
					</tr>
				<? } ?>
				<? if ($user_info['sfera']) { ?>
					<tr>
						<td class="bold aright" width="35%;"><?= t('Род занятий') ?></td>
						<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_info['sfera'])) ?></td>
					</tr>
				<? } ?>
				<? if ($user_info['activity']) { ?>
					<? $sferas = user_auth_peer::instance()->get_segments(); ?>
					<? $sferas[''] = "&mdash;"; ?>
					<? ksort($sferas); ?>
					<tr>
						<td class="bold aright" width="35%;"><?= t('Сфера деятельности') ?></td>
						<td style="color:#333333"><?= user_auth_peer::get_segment($user_info['activity']) ?></td>
					</tr>
				<? } ?>
				<? if ($user_info['activitya']) { ?>
					<tr>
						<td class="bold aright" width="35%;"><?= t('Другая') ?></td>
						<td style="color:#333333"><?= $user_info['activitya'] ?></td>
					</tr>
				<? } ?>
				<? if ($user_info['activity2']) { ?>
					<tr>
						<td class="bold aright" width="35%;"><?= t('Дополнительная сфера деятельности') ?></td>
						<td style="color:#333333"><?= user_auth_peer::get_segment($user_info['activity2']) ?></td>
					</tr>
				<? } ?>
				<? if ($user_info['activitya2']) { ?>
					<tr>
						<td class="bold aright" width="35%;"><?= t('Другая') ?></td>
						<td style="color:#333333"><?= $user_info['activitya2'] ?></td>
					</tr>
				<? } ?>
				<? if ($user_info['about']) { ?>
					<tr>
						<td class="bold aright" width="35%;"><?= t('Коротко о себе') ?></td>
						<td style="color:#333333"><?= $user_info['about'] ?></td>
					</tr>
				<? } ?>
				<? if ($user_data['why_join']) { ?>
					<tr>
						<td class="bold aright" width="35%;">
							<?= t('Чому приєднались до мережі "Мерітократ"') ?>
						</td>
						<td style="color:#333333">
							<?= $user_data['why_join'] ?>
						</td>
					</tr>
				<? } ?>
				<? $user_can_do = unserialize($user_data['can_do']) ?>
				<? if (is_array($user_can_do) && count($user_can_do) > 0) { ?>
					<tr>
						<td class="bold aright" width="35%;">
							<?= t('Чем Вы можете помочь Меритократическому движению') ?>
						</td>
						<td style="color:#333333">
							<? $can_so_arr = array(1 => t('готов заниматься интернет агитацией'), 2 => t('готов заниматься уличной агитацией'), 3 => t('могу помогать финансово (каждая гривня имеет значение)'), 4 => t('другое')) ?>
							<? foreach ($user_can_do as $can) { ?>
								<?= $can_so_arr[$can] ?>
								<?= ($can == 4) ? ' - ' . stripslashes($user_data['can_do_text']) : '' ?>
								<br/>
							<? } ?>
						</td>
					</tr>
				<? } ?>
				<? if ($user_data['additional_info']) { ?>
					<tr>
						<td class="bold aright" width="35%;"><?= t('Дополнительная информация') ?></td>
						<td style="color:#333333"><?= $user_data['additional_info'] ?></td>
					</tr>
				<? } ?>
				<? if ($user_info['why_join']) { ?>
					<tr>
						<td class="bold aright" width="35%;"><?= t('Почему присоединились к сети "Меритократ"') ?></td>
						<td style="color:#333333"><?= $user_info['why_join'] ?></td>
					</tr>
				<? } ?>
				<? if ($user_info['can_do']) { ?>
					<tr>
						<td class="bold aright" width="35%;"><?= t('Чем можете помочь') ?></td>
						<td style="color:#333333"><?= $user_info['can_do'] ?></td>
					</tr>
				<? } ?>
				<? if ($user_info['influence']) { ?>
					<tr>
						<td class="bold aright"
						    width="35%;"><?= t('Кто повлиял на Ваше решение присоединиться к команде?') ?></td>
						<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_info['influence'])) ?></td>
					</tr>
				<? } ?>
				<? if ($user_info['email_lang']) { ?>
					<tr>
						<td class="bold aright"
						    width="35%;"><?= t('На каком языке хотели бы получать сообщения от Игоря Шевченко?') ?></td>
						<td style="color:#333333"><?= ($user_info['email_lang'] == 1) ? tag_helper::image('/icons/ru.png', array('alt' => "ru")) : tag_helper::image('/icons/ua.png', array('alt' => "ua")) ?></td>
					</tr>
				<? } ?>

				<? if (session::has_credential('admin')) { ?>

					<? if ($user_info['is_public']) { ?>
						<tr>
							<td class="bold aright" width="35%;"><?= t('Публичное присоединение?') ?></td>
							<td style="color:#333333"><?= ($user_info['is_public']) ? t('Да') : t('Нет') ?></td>
						</tr>
					<? } ?>
					<? if ($user_info['referer']) { ?>
						<? $arr = array('', '', '', t('С рекламы в соц. сетях'), '', t('От Игоря Шевченко'), t('Другое'), t('От родственников'), t('От друзей/знакомых/колег')) ?>
						<tr>
							<td class="bold aright" width="35%;"><?= t('Откуда Вы узнали про Игоря Шевченко') ?></td>
							<td style="color:#333333"><?= ($user_info['referer'] != 6) ? $arr[$user_info['referer']] : $user_info['ranother'] ?></td>
						</tr>
						<? if ($user_info['referer'] == 3) { ?>
							<tr>
								<td class="bold aright" width="35%;"><?= t('Какой?') ?></td>
								<td style="color:#333333">
									<?
									switch ($user_info['rsocial']) {
										case 'facebook':
											echo tag_helper::image('/logos/' . 3 . '.png', array('class' => 'vcenter mr5', 'title' => user_data_peer::get_contact_type(3)));
											break;
										case 'vkontakte':
											echo tag_helper::image('/logos/' . 1 . '.png', array('class' => 'vcenter mr5', 'title' => user_data_peer::get_contact_type(1)));
											break;
										default:
											echo $user_info['rsocial'];
											break;
									}
									?>
								</td>
							</tr>
						<? } ?>
					<? } ?>
					<? if ($user_novasys['notates']) { ?>
						<tr>
							<td class="bold aright" width="35%;">Примітки</td>
							<td style="color:#333333"><?= $user_novasys['notates'] ?></td>
						</tr>
					<? } ?>
					<? if ($user_novasys['status'] > 0) { ?>
						<tr>
							<td class="bold aright" width="35%;"><?= t('Статус') ?></td>
							<td style="color:#333333"><?= user_novasys_data_peer::get_status($user_novasys['status']); ?></td>
						</tr>
					<? } ?>

					<? load::model('library/files'); ?>
					<tr>
						<td class="bold aright" width="35%;">Досьє</td>
						<td style="color:#333333">
							<? $admin_dos = library_files_peer::instance()->get_list(
								array("object_id" => $user_data['user_id'], "files" => "a:0:{}", "type" => 9), array(),
								array('position ASC'));
							if ($admin_dos[0]) {
								foreach ($admin_dos as $id) {
									$counter++;

									$file = library_files_peer::instance()->get_item($id);
									if (isset($file['files']))
										$arr = unserialize($file['files']);

									include conf::get('project_root') . '/apps/frontend/modules/profile/views/partials/desktop/admin_file.php';
								}
							} else echo "Немае";
							?>
						</td>
					</tr>
					<tr>
						<td class="bold aright" width="35%;">Адмiн файли</td>
						<td style="color:#333333">
							<? $counter = 0;
							$admin_files = library_files_peer::instance()->get_list(
								array("object_id" => $user_data['user_id'], "type" => 9), array(),
								array('position ASC'));

							if ($admin_files[0]) {
								foreach ($admin_files as $id) {


									$file = library_files_peer::instance()->get_item($id);
									if (isset($file['files']))
										$arr = unserialize($file['files']);
									if ($arr[0]) {
										$counter++;
										include conf::get('project_root') . '/apps/frontend/modules/profile/views/partials/desktop/admin_file.php';
									}
								}
							} else echo "Немае"; ?>
							<div class="aright"><a href="/profile/admin_file?id=<?= $user_data['user_id']; ?>&add=1">Додати</a>
							</div>
						</td>
					</tr>
				<? } ?>

			</table>
		</div>
	<? } ?>
	<!-- DIV CONT -->
	<div id="pane_cont" class="<?= session::get_user_id() == 4 ? '' : 'hide' ?> content_pane">
		<table class="fs12 mt10">
			<tr>
				<td width="35%;" class="bold aright p0"></td>
				<td class="aright p0">
					<a class="fs11 cgray"
					   href="/profile/edit?id=<?= $user['id'] ?>&tab=admin_contact"><?= t('Редактировать') ?></a>
				</td>
			</tr>
			<!-- приоритетные -->
			<? if ($user_novasys['phone'] || $user_novasys['email0'] || $user_novasys['site'] || $user_info['site'] || $user_info['email'] || $user_info['phone'] || $user_data['mobile']) { ?>
				<tr>
					<td class="bold aright" width="35%;"></td>
					<td style="color:#333333;font-weight:bold;"><?= t('Приоритетные') ?></td>
				</tr>
			<? } ?>
			<? if ($user_novasys['phone'] || $user_info['phone'] || $user_data['mobile']) { ?>
				<tr>
					<td class="bold aright" width="35%;"><?= t('Телефон') ?></td>
					<td style="color:#333333"><?= $user_novasys['phone'] ? stripslashes(htmlspecialchars($user_novasys['phone'])) : ($user_data['mobile'] ? $user_data['mobile'] : stripslashes(htmlspecialchars($user_info['phone']))) ?></td>
				</tr>
			<? } ?>
			<? if ($user_novasys['email0'] || $user_info['email']) { ?>
				<tr>
					<td class="bold aright" width="35%;"><?= t('Электронная почта') ?></td>
					<td style="color:#333333"><?= $user_novasys['email0'] ? stripslashes(htmlspecialchars($user_novasys['email0'])) : stripslashes(htmlspecialchars($user_info['email'])) ?></td>
				</tr>
			<? } ?>
			<? if ($user_novasys['site'] || $user_info['site']) { ?>
				<tr>
					<td class="bold aright" width="35%;"><?= t('Веб-сайт') ?></td>
					<td style="color:#333333"><?= $user_novasys['site'] ? stripslashes(htmlspecialchars($user_novasys['site'])) : stripslashes(htmlspecialchars($user_info['site'])) ?></td>
				</tr>
			<? } ?>

			<!-- личные -->
			<? /*if ( $user_novasys['site'] || $user_novasys['mphone'] || $user_novasys['mphone1a'] || $user_novasys['fax1'] || $user_novasys['email1']
                || $user_novasys['email1a'] || $user_novasys['site1'] || $user_novasys['skype1'] || $user_novasys['icq1']) {*/ ?>
			<tr>
				<td class="bold aright" width="35%;"></td>
				<td style="color:#333333;font-weight:bold;"><?= t('Личные') ?></td>
			</tr>
			<? /*}*/ ?>
			<? if ($user_novasys['mphone']) { ?>
				<tr>
					<td class="bold aright" width="35%;"><?= t('Мобильный телефон') ?></td>
					<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_novasys['mphone'])) ?></td>
				</tr>
			<? } ?>
			<? if ($user_novasys['mphone1a']) { ?>
				<tr>
					<td class="bold aright" width="35%;"><?= t('Альтернативный моб.тел.') ?></td>
					<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_novasys['mphone1a'])) ?></td>
				</tr>
			<? } ?>
			<? if ($user_novasys['fax1']) { ?>
				<tr>
					<td class="bold aright" width="35%;"><?= t('Факс') ?></td>
					<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_novasys['fax1'])) ?></td>
				</tr>
			<? } ?>

			<tr>
				<td class="bold aright" width="35%;"><?= t('Электронная почта') ?></td>
				<td style="color:#333333"><?= ($user_novasys['email1']) ? stripslashes(htmlspecialchars($user_novasys['email1'])) : $user['email'] ?></td>
			</tr>

			<? if ($user_novasys['email1a']) { ?>
				<tr>
					<td class="bold aright" width="35%;"><?= t('Альтернативная эл.почта') ?></td>
					<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_novasys['email1a'])) ?></td>
				</tr>
			<? } ?>
			<? if ($user_novasys['site1']) { ?>
				<tr>
					<td class="bold aright" width="35%;"><?= t('Веб-сайт') ?></td>
					<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_novasys['site1'])) ?></td>
				</tr>
			<? } ?>
			<? if ($user_novasys['skype1']) { ?>
				<tr>
					<td class="bold aright" width="35%;">Skype</td>
					<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_novasys['skype1'])) ?></td>
				</tr>
			<? } ?>
			<? if ($user_novasys['icq1']) { ?>
				<tr>
					<td class="bold aright" width="35%;">ICQ</td>
					<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_novasys['icq1'])) ?></td>
				</tr>
			<? } ?>

			<!-- рабочие -->
			<? if ($user_novasys['phone2'] || $user_novasys['fax2'] || $user_novasys['email2'] || $user_novasys['site2']) { ?>
				<tr>
					<td class="bold aright" width="35%;"></td>
					<td style="color:#333333;font-weight:bold;"><?= t('Рабочие') ?></td>
				</tr>
			<? } ?>
			<? if ($user_novasys['phone2']) { ?>
				<tr>
					<td class="bold aright" width="35%;"><?= t('Рабочий телефон') ?></td>
					<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_novasys['phone2'])) ?></td>
				</tr>
			<? } ?>
			<? if ($user_novasys['fax2']) { ?>
				<tr>
					<td class="bold aright" width="35%;"><?= t('Рабочий факс') ?></td>
					<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_novasys['fax2'])) ?></td>
				</tr>
			<? } ?>
			<? if ($user_novasys['email2']) { ?>
				<tr>
					<td class="bold aright" width="35%;"><?= t('Рабочая эл.почта') ?></td>
					<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_novasys['email2'])) ?></td>
				</tr>
			<? } ?>
			<? if ($user_novasys['site2']) { ?>
				<tr>
					<td class="bold aright" width="35%;"><?= t('Веб-сайт организации') ?></td>
					<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_novasys['site2'])) ?></td>
				</tr>
			<? } ?>

			<!-- контакты помощника -->
			<? if ($user_novasys['name3'] || $user_novasys['lname3'] || $user_novasys['mname3'] || $user_novasys['phone3'] || $user_novasys['hphone3']
				|| $user_novasys['mphone3'] || $user_novasys['email3'] || $user_novasys['skype3'] || $user_novasys['icq3']
			) { ?>
				<tr>
					<td class="bold aright" width="35%;"></td>
					<td style="color:#333333;font-weight:bold;"><?= t('Контакты помощника') ?></td>
				</tr>
			<? } ?>
			<? if ($user_novasys['name3']) { ?>
				<tr>
					<td class="bold aright" width="35%;"><?= t('Имя') ?></td>
					<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_novasys['name3'])) ?></td>
				</tr>
			<? } ?>
			<? if ($user_novasys['lname3']) { ?>
				<tr>
					<td class="bold aright" width="35%;"><?= t('Фамилия') ?></td>
					<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_novasys['lname3'])) ?></td>
				</tr>
			<? } ?>
			<? if ($user_novasys['mname3']) { ?>
				<tr>
					<td class="bold aright" width="35%;"><?= t('Отчество') ?></td>
					<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_novasys['mname3'])) ?></td>
				</tr>
			<? } ?>
			<? if ($user_novasys['phone3']) { ?>
				<tr>
					<td class="bold aright" width="35%;"><?= t('Рабочий телефон') ?></td>
					<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_novasys['phone3'])) ?></td>
				</tr>
			<? } ?>
			<? if ($user_novasys['hphone3']) { ?>
				<tr>
					<td class="bold aright" width="35%;"><?= t('Домашний телефон') ?></td>
					<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_novasys['hphone3'])) ?></td>
				</tr>
			<? } ?>
			<? if ($user_novasys['mphone3']) { ?>
				<tr>
					<td class="aright" width="35%;"><?= t('Мобильный телефон') ?></td>
					<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_novasys['mphone3'])) ?></td>
				</tr>
			<? } ?>
			<? if ($user_novasys['email3']) { ?>
				<tr>
					<td class="bold aright" width="35%;"><?= t('Электронная почта') ?></td>
					<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_novasys['email3'])) ?></td>
				</tr>
			<? } ?>
			<? if ($user_novasys['skype3']) { ?>
				<tr>
					<td class="bold aright" width="35%;">Skype</td>
					<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_novasys['skype3'])) ?></td>
				</tr>
			<? } ?>
			<? if ($user_novasys['icq3']) { ?>
				<tr>
					<td class="bold aright" width="35%;">ICQ</td>
					<td style="color:#333333"><?= stripslashes(htmlspecialchars($user_novasys['icq3'])) ?></td>
				</tr>
			<? } ?>

		</table>
	</div>
	<!-- DIV HIST -->

	<div id="pane_hist" class="<?= session::get_user_id() != 4 ? '' : 'hide' ?> content_pane">
		<table class="fs12 mt10">

			<? if (session::has_credential('admin')) { ?>
				<tr>
					<td width="35%;" class="bold aright p0"></td>
					<td class="aright p0">
						<a class="fs11 cgray"
						   href="/profile/edit?id=<?= $user['id'] ?>&tab=contact_info"><?= t('Редактировать') ?></a>
					</td>
				</tr>
			<? } ?>
			<? foreach ($user_contact as $cont) { ?>
				<? $res = user_contact_peer::instance()->get_item($cont);
				if (session::has_credential('admin') || ($user['id'] != session::get_user_id() && ($res['who'] == 4 || $res['who'] == 5 || $res['who'] == 11 || $res['who'] == 12 || $res['who'] == 13 || $res['who'] == 14))) {
					?>
					<tr id="tr_<?= $res['id'] ?>">

						<td class="bold aright" width="35%;"><?= user_helper::com_date($res['date']) ?>:</td>
						<td style="color:#333333" id="td_<?= $res['id'] ?>">
							<div class="right">
								<a href="javascript:;" onClick="$('#edit_form_<?= $res['id'] ?>').slideToggle(100);"
								   class="contact_edit dib icoedt mr5"></a>
								<a href="javascript:;" class="contact_delete dib icodel" id="<?= $res['id'] ?>"></a>

							</div>
							<?= user_helper::get_contact_type($res['type']) ?>
							, <?= user_novasys_data_peer::get_who_contact($res['who']) ?>
							(<?= user_helper::full_name($res['contacter_id'], true, array(), false) ?>)
							<!-- <? var_dump($res); ?> --><br/>
							<?= stripslashes(htmlspecialchars($res['description'])) ?></td>
					</tr>

					<?//if(session::get_user_id()==5968) {?>
					<tr>
						<td colspan="2">

							<form action="/profile/contact_add" id="edit_form_<?= $res['id'] ?>" class="hidden form_bg">
								<table>
									<tr class="itemi">
										<td class="aright" width="35%"><?= t('Дата') ?></td>
										<td>
											<input name="idate" id="tdate" class="text tdate" type="text"
											       style="width: 250px" value="<?= date("m/d/Y", $res['date']) ?>"/>
										</td>
									</tr>
									<tr class="itemi">
										<td class="aright"><?= t('Тип') ?></td>
										<td>
											<?= tag_helper::select('types', user_helper::get_contact_types(), array('use_values' => false, 'value' => $res['type'], 'style' => 'width: 250px;')) ?>
										</td>
									</tr>
									<tr class="<?= session::has_credential('admin') ? 'itemi' : '' ?>">
										<td class="aright"><?= t('Кто') ?></td>
										<td>
											<?= tag_helper::select('who', user_novasys_data_peer::get_who_contacts(), array('use_values' => false, 'value' => $res['who'], 'style' => 'width: 250px;')) ?>
										</td>
									</tr>
									<tr class="itemi">
										<td class="aright"><?= t('Содержание контакта') ?></td>
										<td>
											<textarea name="description"
											          style="width:250px;height:60px;"><?= $res['description'] ?></textarea>
										</td>
									</tr>
									<tr class="itemi">
										<td class="aright"></td>
										<td>
											<input type="hidden" name="user_id" value="<?= $user['id'] ?>">
											<input type="hidden" name="contact_id" value="<?= $res['id'] ?>">
											<input type="submit" value="Зберегти" class="button" name="submit"/>
											<input type="button" value="Скасувати" class="button_gray" name="cancel"
											       onClick="$('#edit_form_<?= $res['id'] ?>').slideToggle(100);"/>
										</td>
									</tr>
								</table>
							</form>

						</td>
					</tr>
					<?// } ?>
				<?
				}
			} ?>

			<? if (session::has_credential('admin')) { ?>
				<? if ($user_novasys['all_contacts']) { ?>
					<tr>
						<td class="bold aright" width="35%;"><?= t('Контакт') ?></td>
						<td style="color:#333333"><?= $user_novasys['all_contacts'] ?></td>
					</tr>
				<? } ?>
				<? if ($user_novasys['contacted']) { ?>
					<tr>
						<td class="bold aright" width="35%;"><?= t('Контактирует') ?></td>
						<td style="color:#333333"><?= user_novasys_data_peer::get_who_contact($user_novasys['contacted']) ?></td>
					</tr>
				<? } ?>
				<tr>
					<td class="bold aright" width="35%;"><?= t('Статус контакта') ?></td>
					<td style="color:#333333">
						<? if (session::has_credential('admin')) { ?>
							<?= tag_helper::select('contact_status', user_helper::get_statuses(), array('use_values' => false, 'value' => $user_data['contact_status'], 'id' => 'status_sel')) ?>
							<input type="button" class="button ml10" id="change_user_status"
							       value="<?= t('Сохранить') ?>" rel="<?= $user_data['user_id']; ?>">
						<? } else { ?>
							<?= user_helper::get_status($user_data['contact_status']) ?>
						<? } ?>
						<?= tag_helper::wait_panel('status_wait') ?>
					</td>
				</tr>

			<? } ?>
			<tr>
				<td class="bold aright"></td>
				<td>
					<a href="javascript:;" id="contact_add" class="button p5">Додати контакт</a>
				</td>
			</tr>

			<form action="/profile/contact_add">
				<tr class="itemi hide">
					<td class="aright"><?= t('Дата') ?></td>
					<td>
						<input name="idate" id="tdate" class="text tdate" type="text" value="<?= date("m/d/Y") ?>"/>
					</td>
				</tr>
				<tr class="itemi hide">
					<td class="aright"><?= t('Тип') ?></td>
					<td>
						<?= tag_helper::select('types', user_helper::get_contact_types(), array('use_values' => false, 'value' => 2)) ?>
					</td>
				</tr>
				<tr class="<?= session::has_credential('admin') ? 'itemi' : '' ?> hide">
					<td class="aright"><?= t('Кто') ?></td>
					<td>
						<? session::get_user_id() == 4 ? $who_contacted = 2 : ($is_regional_coordinator ? $who_contacted = 4 : ($is_raion_coordinator ? $who_contacted = 5 : ($is_predstavitel_vnz ? $who_contacted = 11 : $who_contacted = ''))); ?>
						<? if ($leader_ppo_category == 1) $who_contacted = 12;
						if ($leader_ppo_category == 2) $who_contacted = 13;
						if ($leader_ppo_category == 3) $who_contacted = 14; ?>
						<?= tag_helper::select('who', user_novasys_data_peer::get_who_contacts(), array('use_values' => false, 'value' => $who_contacted)) ?>
					</td>
				</tr>
				<tr class="itemi hide">
					<td class="aright"><?= t('Содержание контакта') ?></td>
					<td>
						<textarea name="description" style="width:150px"></textarea>
					</td>
				</tr>
				<tr class="itemi hide">
					<td class="aright"></td>
					<td>
						<input type="hidden" name="user_id" value="<?= $user['id'] ?>">
						<input type="submit" value="Додати контакт" class="button" name="submit"/>
					</td>
				</tr>

			</form>
		</table>
	</div>

	<div id="pane_no_viewed_blogs" class="hide content_pane">
		<table class="fs12 mt10">
			<? $counter = 1; ?>
			<? $flag = false; ?>
			<? load::model('user/user_data'); ?>
			<? foreach ($posts_id as $post_id) { ?>
				<? $post_desc = blogs_posts_peer::instance()->get_item($post_id); ?>
				<? $flag = $flag ? false : true; ?>
				<tr style="background: #<?= $flag ? "EEE" : "FFF" ?>;">
					<td align="center" width="16px" style="vertical-align: middle;"><?= $counter ?></td>
					<td style="vertical-align: middle;">
						<div>
							<a href="/blogpost<?= $post_desc["id"] ?>"><?= stripslashes(htmlspecialchars($post_desc["title"])) ?></a>
						</div>
						<? $user_data = user_data_peer::instance()->get_item($post_desc['user_id']); ?>
						<div style="font-size:9px;"><a class="fs11 mr15" style="color: #222;"
						                               href="/profile-<?= $post_desc["user_id"] ?>"><?= stripslashes(htmlspecialchars($user_data["first_name"] . " " . $user_data["last_name"])) ?></a>
						</div>
					</td>
					<? $date = $post_desc['created_ts']; ?>
					<td width="128px" align="center" style="vertical-align: middle; color: #888">
						<?= date("d", $date) ?> <?= date_helper::get_month_name((int)date("m", $date)) ?> <?= date("Y", $date) ?>
					</td>
				</tr>
				<? $counter++ ?>
			<? } ?>
		</table>
	</div>

</div>
<script type="text/javascript">
	jQuery(document).ready(function () {
		$('#change_user_status').click(function () {
			$.ajax({
				type: 'post',
				url: "/profile/edit",
				beforeSend: function () {
					$('#status_wait').show();
				},
				data: {
					submit: '1',
					type: 'update_stat',
					id: $(this).attr('rel'),
					status: $('select[id="status_sel"] :selected').val()
				},
				success: function () {
					$('#status_wait').hide();
				}
			});
		});
		$('a.contact_delete').click(function () {
			if (confirm('Точно?')) {
				var id = $(this).attr('id');
				$("#tr_" + id).hide();
				$.get('/profile/contact_delete', {id: id});
			}
			$('a.contact_edit').click(function () {
				var id = $(this).attr('rel');
				alert(id);
				$('#edit_form_' + id).slideToggle(100);
			});

		});
		$('#contact_add').click(function () {
			$('tr.itemi').show();
			$('#contact_add').hide();
		});
	});
</script>
