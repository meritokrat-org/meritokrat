<div class="left mt10" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10 mt10" style="width: 62%;">
	<h1 class="column_head"><?=t('Новый пользователь')?></h1>

	<div class="box_content acenter p10 fs12">
		<form method="post">
			<table>
				<tr>
					<td class="aright">Имя</td>
					<td class="aleft"><input type="text" class="text" name="first_name"></td>
				</tr>
				<tr>
					<td class="aright">Фамилия</td>
					<td class="aleft"><input type="text" class="text" name="last_name"></td>
				</tr>
				<tr>
					<td class="aright">Пол</td>
					<td class="aleft">
						<input type="radio" checked name="gender" value="m"> М
						<input type="radio" name="gender" value="f"> Ж
					</td>
				</tr>
				<tr>
					<td class="aright">Email (не обязательно)</td>
					<td class="aleft"><input type="email" class="text" name="email"></td>
				</tr>
				<tr>
					<td class="aright">Пароль (не обязательно)</td>
					<td class="aleft"><input type="text" class="text" name="password" value=""></td>
				</tr>
				<tr>
					<td class="aright">Статус</td>
					<td class="aleft"><?=tag_helper::select('type', user_auth_peer::get_types(), array('value' => $user['type']))?></td>
				</tr>
                                <tr>
					<td class="aright"><?=t('Cкрытый профиль')?></td>
					<td class="aleft">
					<input type="checkbox" name="suslik" value="1"/> Так
					</td>
				</tr>
			</table>

			<input type="submit" name="submit" class="button" value="<?=t('Создать')?>" /> &nbsp;
			<a href="/admin/users"><?=t('Отмена')?></a>
		</form>
	</div>
</div>
