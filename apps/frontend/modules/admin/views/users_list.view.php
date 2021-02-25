<script src="/static/javascript/modules/profile.js"></script>

<div class="d-flex flex-row">
<div style="width: 230px;"><?php include 'partials/left.php' ?></div>

<div class="flex-grow-1 ml-2">
	<h1 class="column_head"><?= t('Список пользователей') ?></h1>

	<!--div class="fs10 acenter quiet"><?= t('Последние 500 зарегистрированых пользователей') ?></div-->

	<div class="box_content p5 fs12 mb10">

		<div class="acenter m10">
			<form action="/admin/users_list">
				<label class="fs10 quiet"><?= t('Поиск по IP'); ?></label>
				<input type="text" class="text" name="ip" value="<?= request::get('ip') ?>"/>
				<input type="submit" class="button" value="<?= t('Искать') ?>"/>
                <?php if (request::get('ip')) { ?><a class="fs10" href="/admin/users_list"><?= t('Сбросить') ?></a><?php } ?>
			</form>
		</div>

		<table>
			<tr>
				<th colspan="5" style="color:black;"><a
						href="/admin/users_list">Всі</a> <?= db::get_scalar('SELECT count(id) FROM user_auth', array()) ?>
					| <a
						href="<?= $_SERVER['REQUEST_URI'] ?>&active=1">Активні</a> <?= db::get_scalar('SELECT count(id) FROM user_auth WHERE active=:active', array('active' => 1)) ?>
					| <a
						href="<?= $_SERVER['REQUEST_URI'] ?>&active=false&sh=0">Неактивні</a> <?= db::get_scalar('SELECT count(id) FROM user_auth WHERE active!=:active AND shevchenko=:sh', array('active' => 1, 'sh' => 0)) ?>
					| <a href="<?= $_SERVER['REQUEST_URI'] ?>&active=false&sh=1">Неактивні
						Ш</a> <?= db::get_scalar('SELECT count(id) FROM user_auth WHERE active!=:active AND shevchenko=:sh', array('active' => 1, 'sh' => 1)) ?>
				</th>
			</tr>
			<tr>
				<th colspan="5" style="color:black;">Сортувати за:
					<a href="<?= $_SERVER['REQUEST_URI'] ?>&active=1&sort=activated_ts">Датою активації</a>
					| <a href="<?= $_SERVER['REQUEST_URI'] ?>&active=false&sort=last_invite">Датою останнього
						запрошення</a></th>
			</tr>
			<tr>
				<th style="color:black;">ID</th>
				<th style="color:black;">Ім’я Прізвище</th>
				<th style="color:black;">Останнє запрошення Активація</th>
				<th style="color:black;">&nbsp;&nbsp;&nbsp;<?= t('Статус') ?>&nbsp;&nbsp;&nbsp;&nbsp;</th>
				<th style="color:black;">Повторне<br/>запрошення</th>
			</tr>
            <?php foreach ($list as $id) { ?>
                <?php $user = user_auth_peer::instance()->get_item($id); ?>
                <?php $user_data = user_data_peer::instance()->get_item($id); ?>
				<tr>
					<td><a href="/admin/users?key=<?= $id ?>"><?= $id ?></a></td>
					<td>
						<a href="/admin/users?key=<?= $id ?>"><?= $user_data['first_name'] ?> <?= $user_data['last_name'] ?> </a>
					</td>
					<td class="acenter">
                        <?php $inviter = db::get_row("SELECT user_id, first_name, last_name FROM user_data WHERE user_id = ".$user["invited_by"]);?>

                        <?php if($inviter["user_id"] > 0){ ?>
							<b><?=$inviter["first_name"]?> <?=$inviter["last_name"]?></b></br>
                        <?php }else{ ?>
							<b>Сам</b></br>
                        <?php } ?>

						<?= $user['active'] ? date("d.m.Y", $user['activated_ts']) : date("d.m.Y", $user['last_invite']) ?>
					</td>
					<td><?= $user['active'] ? '<b class="green fs10">активен</b>' : '<em class="maroon fs10">не активен</em>' ?></td>
					<td>
						<a id="resend<?= $id ?>" href="javascript:;" onclick="adminController.reSend(<?= $id ?>)">Відправити</a>

						<div id="status<?= $id ?>" class="left"></div>
						<br/>
						<a href="javascript:;" onclick="profileController.deleteProfile(<?= $id ?>,'1');">Видалити</a>

					</td>
				</tr>
            <?php } ?>
		</table>
	</div>

	<div class="bottom_line_d mb10"></div>
	<div class="right pager"><?= pager_helper::get_full($pager) ?></div>
</div>
</div>