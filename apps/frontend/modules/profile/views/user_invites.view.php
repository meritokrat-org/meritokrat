<script>
	$(function () {
		$('#left').hide();
		$('#invites_table').parent().removeAttr('style').css('width', '100%');
		$('#main_content').css('min-height', ($(window).height() - 303) + 'px');
	});
</script>

<div class="aleft mt10 mb10">
	<a class="mr15 <? if (request::get('active') == 0) echo ' bold'; ?>"
	   href="/profile/user_invites?active=0"><?= t('Не активированные') ?></a>|
	<a class="mr15 ml15 <? if (request::get('active') == 1) echo ' bold'; ?>"
	   href="/profile/user_invites?active=1"><?= t('Активированные') ?></a>|
	<a class="mr15 ml15 <? if (request::get('active') == 2) echo ' bold'; ?>"
	   href="/profile/user_invites?active=2"><?= t('Все') ?></a>|
	<a class="mr15 ml15" href="/profile/invite"><?= t('Пригласить') ?></a>|
	<a class="mr15 ml15" href="/profile-<?= session::get_user_id() ?>"><?= t('Назад к профилю') ?></a>
</div>

<div class="clear"></div>
<table id="invites_table" style="width: 100%;" class="acenter border fs12">
	<tr>
		<td colspan="10">
			<a href=""></a>
		</td>
	</tr>
	<tr style="background: #cacaca;">
		<th><?= t('Фамилия') ?></th>
		<th><?= t('Имя') ?></th>
		<th>Email</th>
		<th>
			<a href="/profile/user_invites?order=created_ts&direct=<?= (request::get('direct') == 'ASC') ? 'DESC' : 'ASC' ?><?= (intval(request::get_int('active'))) ? '&active=' . request::get_int('active') : '&active=0'; ?>"><?= t('Дата приглашения') ?></a>
		</th>
		<th>
			<a href="/profile/user_invites?order=last_invite&direct=<?= (request::get('direct') == 'ASC') ? 'DESC' : 'ASC' ?><?= (intval(request::get_int('active'))) ? '&active=' . request::get_int('active') : '&active=0'; ?>"><?= t('Последнее приглашение') ?></a>
		</th>
		<th><?= t('Количество приглашений') ?></th>
		<? if (request::get_int('active') != 1) { ?>
			<th><?= t('Действие') ?></th> <? } ?>
	</tr>
	<?
	if ($invites) {
		foreach ($invites as $k => $v) {
			$invAuth = user_auth_peer::instance()->get_item($v['user_id']);
			?>
			<tr>
				<td>
					<? if (session::has_credential('admin')) { ?>
						<a href='/profile-<?= $v['user_id'] ?>'><?= $v['last_name'] ?></a>
					<? } else {
						echo $v['last_name'];
					} ?>
				</td>
				<td>
					<? if (session::has_credential('admin')) { ?>
						<a href='/profile-<?= $v['user_id'] ?>'><?= $v['first_name'] ?></a>
					<? } else {
						echo $v['first_name'];
					} ?>
				</td>
				<td>
					<?
					$userAuth = user_auth_peer::instance()->get_item($v['user_id']);
					echo $userAuth['email'];
					?>
				</td>
				<td>
					<?= ($invAuth['created_ts']) ? date('d.m.Y H:i', $invAuth['created_ts']) : ' - '; ?>
				</td>
				<td id="lastinv<?= $v['user_id'] ?>">
					<?= ($invAuth['last_invite']) ? date('d.m.Y H:i', $invAuth['last_invite']) : ' - ' ?>
				</td>
				<td id="result<?= $v['user_id'] ?>">
					<?= (db_key::i()->exists($v['user_id'] . '_invited_again')) ? db_key::i()->get($v['user_id'] . '_invited_again') : '1'; ?>
				</td>
				<? if (request::get_int('active') != 1) { ?>
					<td>
						<table style="margin: 0px;" class="aleft">
							<tr>
								<? if (!$userAuth['active']) { ?>
									<td style="padding: 0px 5px 0 0 ;">
										<img class="pointer" onClick="reSend('<?= $v['user_id'] ?>');"
										     alt="<?= t('Повторное приглашение') ?>"
										     style="width: 15px; border: 1px solid black; height: 12px;"
										     src="/static/images/icons/4.4.jpg">
									</td>
								<? } ?>
								<? if (session::has_credential('admin')) { ?>
									<td style="padding: 0px 5px 0 0 ;">
										<a href="/profile/edit?id=<?= $v['user_id'] ?>">
											<img class="pointer" alt="<?= t('Редактировать') ?>"
											     src="/static/images/icons/2.2.png">
										</a>
									</td>
								<? } ?>
								<td style="padding: 0px;">
									<img class="pointer"
									     onClick="profileController.deleteProfile(<?= $v['user_id'] ?>,'0');"
									     alt="<?= t('Удалить') ?>" src="/static/images/icons/3.3.png">
								</td>
							</tr>
						</table>
					</td>
				<? } ?>
			</tr>
		<? }
	} else { ?>
		<tr>
			<td colspan="12">
				<div class="screen_message quiet acenter fs12">Тут ще немає записів</div>
			</td>
		</tr>
	<? } ?>
</table>
<? if ($pager) { ?>
	<div class="right pager"><?= pager_helper::get_full($pager) ?></div>
<? } ?>
<script>
	function reSend(id) {
		$.ajax({
			type: 'post',
			url: '/profile/user_inv_ajax',
			data: {
				id: id,
				act: 'resend'
			},
			success: function (resp) {
				data = eval("(" + resp + ")");
				context.obj_id = data.message;
				if (data.success != 'ok') {
					context.obj_id = data.message;
					Popup.show();
					Popup.setHtml(data.html);
					Popup.position();
				} else {
					$('#result' + id).html("<b>" + data.c + "</b>");
					var date = new Date(parseInt(data.last_invite) * 1000);
					$('#lastinv' + id).html('<b>' + addZero(date.getDate()) + '.' + addZero(date.getUTCMonth()) + '.' + date.getFullYear() + ' ' + addZero(date.getHours()) + ':' + addZero(date.getMinutes()) + '</b>');
				}
			}
		});
	}
	function addZero(i) {
		return i < 10 ? '0' + i : '' + i;
	}
</script>