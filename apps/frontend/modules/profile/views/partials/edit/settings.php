<form id="settings_form" class="hidden form mt10">
	<? if (session::has_credential('admin')) { ?>
		<input type="hidden" name="id" value="<?= $user_data['user_id'] ?>">
	<? } ?>
	<input type="hidden" name="type" value="settings">
	<table width="100%" class="fs12">
		<tr>
			<td width="30%" class="aright"><?= t('Получать уведомления') ?></td>
			<td>
				<input type="radio" name="notify" value="1" <?= $user_data['notify'] ? 'checked' : '' ?>
				       onclick="$('#mails').show()"/>
				<label for="notify_1"><?= t('Да') ?></label>

				<input type="radio" name="notify" value="0" <?= !$user_data['notify'] ? 'checked' : '' ?>
				       onclick="$('#mails').hide()"/>
				<label for="notify_0"><?= t('Нет') ?></label>

				<div class="quiet fs11 mt5 mb10">
					<?= t('Уведомления помогут Вам быть в курсе событий на сайте.') ?>
					<?= t('Вы будете получать уведомление на почту, когда кто-либо пишет Вам сообщение, комментирует Ваш блог, отвечает на Ваш опрос и т. д.') ?>
				</div>
			</td>
		</tr>

		<tr>
			<td width="30%" class="aright"></td>
			<td>
				<table width="100%" class="fs12 <?= (!$user_data['notify']) ? 'hide' : '' ?>" id="mails">
					<tr>
						<td>
							<input type="checkbox" name="messages_compose"
							       value="1" <?= ($user_mail_access['messages_compose'] ? 'checked="checked"' : '') ?>/>
						</td>
						<td><?= t('Новое сообщение') ?></td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" name="blogs_comment"
							       value="1" <?= ($user_mail_access['blogs_comment'] ? 'checked="checked"' : '') ?>/>
						</td>
						<td><?= t('Новый комментарий к Вашей мысли') ?></td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" name="polls_comment"
							       value="1" <?= ($user_mail_access['polls_comment'] ? 'checked="checked"' : '') ?>/>
						</td>
						<td><?= t('Новый комментарий к Вашему опросу') ?></td>

					</tr>
					<tr>
						<td>
							<input type="checkbox" name="comment_comment"
							       value="1" <?= ($user_mail_access['comment_comment'] ? 'checked="checked"' : '') ?>/>
						</td>
						<td><?= t('Ответ на Ваш комментарий') ?></td>

					</tr>
					<tr>
						<td>
							<input type="checkbox" name="messages_wall"
							       value="1" <?= ($user_mail_access['messages_wall'] ? 'checked="checked"' : '') ?>/>
						</td>
						<td><?= t('Новая запись на Вашей стене') ?></td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" name="invites_add_group"
							       value="1" <?= ($user_mail_access['invites_add_group'] ? 'checked="checked"' : '') ?>/>
						</td>
						<td><?= t('Новое приглашение в сообщество') ?></td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" name="invites_add_event"
							       value="1" <?= ($user_mail_access['invites_add_event'] ? 'checked="checked"' : '') ?>/>
						</td>
						<td><?= t('Новое приглашение на событие') ?></td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" name="friends_make"
							       value="1" <?= ($user_mail_access['friends_make'] ? 'checked="checked"' : '') ?>/>
						</td>
						<td><?= t('Новое приглашение в друзья') ?></td>
					</tr>
					<? if (in_array($user_data['user_id'], user_auth_peer::get_admins())) { ?>
						<tr>
							<td>
								<input type="checkbox" name="admin_feed"
								       value="1" <?= ($user_mail_access['admin_feed'] ? 'checked="checked"' : '') ?>/>
							</td>
							<td><?= t('Действия модераторов') ?></td>
						</tr>
						<tr>
							<td>
								<input type="checkbox" name="messages_spam"
								       value="1" <?= ($user_mail_access['messages_spam'] ? 'checked="checked"' : '') ?>/>
							</td>
							<td><?= t('Жалоба на спам') ?></td>
						</tr>
						<tr>
							<td>
								<input type="checkbox" name="profile_delete_process"
								       value="1" <?= ($user_mail_access['profile_delete_process'] ? 'checked="checked"' : '') ?>/>
							</td>
							<td><?= t('Самоудаление участника') ?></td>
						</tr>
						<tr>
							<td>
								<input type="checkbox" name="groups_join"
								       value="1" <?= ($user_mail_access['groups_join'] ? 'checked="checked"' : '') ?>/>
							</td>
							<td><?= t('Новая заявка на вступление в закрытое сообщество') ?></td>
						</tr>
						<tr>
							<td>
								<input type="checkbox" name="profile_edit"
								       value="1" <?= ($user_mail_access['profile_edit'] ? 'checked="checked"' : '') ?>/>
							</td>
							<td><?= t('Изменение участником персональных данных') ?></td>
						</tr>
						<tr>
							<td>
								<input type="checkbox" name="profile_invite"
								       value="1" <?= ($user_mail_access['profile_invite'] ? 'checked="checked"' : '') ?>/>
							</td>
							<td><?= t('Рекомендация участника') ?></td>
						</tr>
						<tr>
							<td>
								<input type="checkbox" name="groups_create"
								       value="1" <?= ($user_mail_access['groups_create'] ? 'checked="checked"' : '') ?>/>
							</td>
							<td><?= t('Предложение нового сообщества') ?></td>
						</tr>
						<tr>
							<td>
								<input type="checkbox" name="eventreport_send"
								       value="1" <?= ($user_mail_access['eventreport_send'] ? 'checked="checked"' : '') ?>/>
							</td>
							<td><?= t('Новый агитационный отчет на проверку') ?></td>
						</tr>
						<tr>
							<td>
								<input type="checkbox" name="event_agitation"
								       value="1" <?= ($user_mail_access['event_agitation'] ? 'checked="checked"' : '') ?>/>
							</td>
							<td><?= t('Новое агитационное событие') ?></td>
						</tr>
						<tr>
							<td>
								<input type="checkbox" name="admin_profile_delete"
								       value="1" <?= ($user_mail_access['admin_profile_delete'] ? 'checked="checked"' : '') ?>/>
							</td>
							<td><?= t('Видалення учaсника адміном') ?></td>
						</tr>
					<? } ?>
				</table>
			</td>
		</tr>
		<tr>
			<td class="aright"><?= t('Показывать данные профиля') ?></td>
			<td>
				<?= tag_helper::select('contact_access', user_helper::get_profile_access(), array('use_values' => false, 'value' => $user_data['contact_access'])); ?>
			</td>
		</tr>
		<? if (session::has_credential('admin')) { ?>
			<tr>
				<td class="aright">* <?= t('Скрыть профиль') ?></td>
				<td><input <?= $user['suslik'] == 1 ? 'checked' : '' ?> type="checkbox" id="suslik" name="suslik"
				                                                        value="1"/></td>
			</tr>
		<? } ?>
		<?
		if (session::has_credential('admin')) {
			$share_users = explode(',', str_replace(array('{', '}'), array('', ''), $user_data['share_users']));
			?>
			<tr id="shareuserstr">
				<td class="aright">* <?= t('Показывать некоторым') ?></td>
				<td><a href="javascript:;" class="shareprofile"><?= t('Выбрать') ?></a>
					<table class="m0">
						<tr id="uchasniki"></tr>
						<? if (!empty($share_users[0])) foreach ($share_users as $su) {
							?>
							<tr id="member<?= $su ?>">
								<td>
									<input type="hidden" name="members[]" value="<?= $su ?>"/>
									<?= user_helper::full_name($su, true, array(), false) ?> -
									<a href="javascript:;" rel="<?= $su ?>" class="one_delete_function">Видалити</a>
								</td>
							</tr>
						<? } ?>
					</table>
				</td>
			</tr>
		<? } ?>
		<? if (!$user['del']) { ?>
			<tr>
				<td class="aright">Email</td>
				<td>
					<input name="aemail" rel="<?= t('Вы ввели неправильный email') ?>" class="text" type="text"
					       value="<?= $user['email'] ?>"/>

				</td>
			</tr>
			<tr>
				<td class="aright"><?= t('Новый пароль') ?></td>
				<td>
					<input name="new_password" id="new_password" rel="<?= t('Введенные пароли не совпадают') ?>"
					       class="text" type="password" value=""/>
					<input name="new_password_confirm" id="new_password_confirm" class="text" type="password" value=""/>

					<div class="quiet fs11 mt5 mb10">
						<?= t('Если Вы хотите изменить пароль, введите новый в обоих полях.') ?>
					</div>
				</td>
			</tr>
		<? } ?>

		<? if (session::has_credential('admin') && $user['offline'] && !$user['del']) { ?>
			<tr>
				<td class="aright">*<?= t('Оффлайн') ?></td>
				<td>
					<input type="checkbox" name="doonline" value="1"/>
					<?= t('Сделать онлайн?') ?>
					<div class="quiet fs11 mt5 mb10">
						<?= t('Это оффлайн профиль, участник с таким профилем не может входить в систему. Сделать этот профиль онлайновым?') ?>
					</div>
				</td>
			</tr>
		<? } ?>

		<? if ((!$user['offline'] && !$user['del']) || session::has_credential('admin')) { ?>
			<tr>
				<td class="aright"><?= t('Удаление профиля') ?></td>
				<td>
					<? if (!$user['offline'] && !$user['del']) { ?>
						<a href="javascript:void(0);" style="color: maroon;"
						   onclick="profileController.deleteProfile(<?= $user_data['user_id'] ?>,'0');"><?= t('Удалить профиль') ?></a>
					<? } ?>
					<? if (session::has_credential('admin')) { ?>
						&nbsp;&nbsp;*<a href="javascript:;" style="color: maroon;"
						                onclick="profileController.deleteProfile(<?= $user_data['user_id'] ?>,'1');"><?= t('Удалить окончательно') ?></a>
					<? } ?>

					<div class="quiet fs11 mt5 mb10">
						<b><?= t('Внимание!') ?></b>
						<?= t('Если Вы удалите свой аккаунт, Вы <b>не сможете восстановить</b> его при повторной регистрации') ?>
						!
					</div>
				</td>
			</tr>
		<? } ?>
		<tr>
			<td></td>
			<td>
				<div class="mt10"></div>
				<input type="submit" name="submit" class="button" value=" <?= t('Сохранить') ?> ">
				<input onclick="history.go(-1);" type="button" name="cancel" class="button_gray"
				       value=" <?= t('Отмена') ?> ">
				<?= tag_helper::wait_panel('settings_wait') ?>
				<div class="success hidden mr10 mt10"><?= t('Изменения сохранены') ?></div>
			</td>
		</tr>

	</table>
</form>
<script>
	jQuery(".shareprofile").click(function () {
		Application.ShareProfile();
		return false;
	});
	jQuery(".one_delete_function").click(function () {
		$("#member" + $(this).attr('rel')).remove();
		return false;
	});
	jQuery('input.#suslik').click(function () {
		if ($(this).is(':checked')) {
			$('.shareprofile').show();
			$('#shareuserstr').show();
		}
		else {
			$('.shareprofile').hide();
			$('#shareuserstr').hide();
		}
	});
</script>