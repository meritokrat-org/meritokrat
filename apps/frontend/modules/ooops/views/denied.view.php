<h4 class="mt10 mr10 acenter"><?=t('Войдите на сайт, что-бы продолжить')?></h4>

<div class="mr10 acenter">
	<form id="signin_form" action="/sign/in" method="post">
		Email: <input type="text" title="Email" style="width: 150px;" class="text" name="email" rel="required:<?=t('Введите, пожалуйста')?>, email;email:<?=t('Вы ввели неправильный')?> email;" />
		<?=t('Пароль')?>: <input type="password" title="<?=t('Пароль')?>" style="width:150px;" class="text" name="password" rel="<?=t('Введите пароль')?>" />
		<input type="submit" name="submit" value=" <?=t('Войти')?> ">
		<br />
		<span class="fs11">
			<a href="/sign/recover"><?=t('Забыли пароль')?>?</a>
			<a class="ml10" href="/sign/up"><?=t('Нет аккаунта')?>?</a>
		</span>

		<?=tag_helper::wait_panel() ?>
		<br /><br />
	</form>
</div>

<br /><br />