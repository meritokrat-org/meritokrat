<h4 class="mt15 fs28 cbrown acenter" style="text-decoration: none;margin-bottom: 0px;"><?=t('Восстановление пароля')?>:</h4>
<div class="line_light"></div>

<br />

<div class="success hidden"><?=t('Ваш пароль изменен, теперь Вы можете')?> <a href="/"><?=t('войти в систему')?></a></div>

<br />

<div align="center">
	<form id="change_password_form" method="post">
		<input type="hidden" name="c" value="<?=request::get('c')?>" />
                <table>
                    <tr>
                        <td class="aright">
                            <label for="password"><?=t('Введите новый пароль')?></label>
                        </td>
                        <td>
                            <input type="password" style="width: 190px;" class="text" name="password" rel="<?=t('Введите пароль')?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="aright">
                            <label for="password_confirm"><?=t('Повторите пароль')?></label>
                        </td>
                        <td>
                            <input type="password" style="width: 190px;" class="text" name="password_confirm" rel="<?=t('Пароли не совпадают')?>" />
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="submit" name="submit" class="button" value=" <?=t('Изменить пароль')?> " />
                            <input type="button" name="cancel" class="button_gray" onClick="window.location='/'" value=" <?=t('На головну')?> " />
                        </td>
                    </tr>
                </table>
	</form>
</div>
