
<div class="mb15" style="float:center;">
<h2 style="color:white;text-align: center;"><?=t('Восстановление пароля')?></h2>
<div style="color:white; text-align: center;">
	<form id="recover_form" method="post">
		<label for="email"><?=t('Введите')?> Email</label><br />
		<input type="text" class="text" name="email" rel="required:<?=t('Введите, пожалуйста')?>, email;email:<?=t('Вы ввели неправильный')?> email;" />
		<br/>
                <input type="submit" name="submit" value=" <?=t('Восстановить пароль')?> " />
	</form>
</div>
<div class="line_light"></div>
<div class="success hidden"><?=t('Ссылка отправлена на почту')?></div>
</div>
