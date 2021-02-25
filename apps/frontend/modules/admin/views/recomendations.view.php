<div class="left ml10 mt10" style="width: 95%;">
 <?   if ($recomendation) { ?>
	<div style="width: 500px;" class="screen_message">Додано!</div>
<? } ?>
	<h1 class="column_head">Додавання рекомендацій вручну</h1>

	<div class="box_content acenter p10 fs12">

		<form method="post" action="/admin/recomendations">
			<table>
				
				<tr>
                                    <td height="10px"></td>
				</tr>
				<tr>
					<td class="aright">Запросив<br>ID чи "Ім’я Прізвище"</td>
					<td class="aleft"><input type="text" class="text" name="inviter" value="<?=request::get('inviter')?>"></td>
				</tr>
				<tr>
					<td class="aright"><?=t('Имя')?></td>
					<td class="aleft"><input type="text" class="text" name="first_name" value="<?=request::get('first_name')?>"></td>
				</tr>
				<tr>
					<td class="aright"><?=t('Фамилия')?></td>
					<td class="aleft"><input type="text" class="text" name="last_name" value="<?=request::get('last_name')?>"></td>
				</tr>
				<tr>
					<td class="aright">Email</td>
					<td class="aleft"><input type="text" class="text" name="email" value="<?=request::get('email')?>"></td>
				</tr>
                                <? if (!user_auth_peer::instance()->is_inviter(session::get_user_id())) { ?>
				<tr>
                                    <td class="aright"><?=t('Рекомендация')?><br/><span class="quiet fs10">якщо є</span></td>
					<td class="aleft"><textarea cols="5" rows="10" name="recomendation"><?=request::get('recomendation')?></textarea></td>
				</tr>
                                <? } ?>
				<tr>
					<td class="aright bold"><?=t('Внимание')?>!</td>
					<td class="aleft"><?=t('все поля обязательны для заполнения')?></td>
				</tr>
			</table>

			<input type="submit" name="submit" class="button" value="<?=t('Отправить')?>" />
		</form>
	</div>
</div>
