<div class="left mt10" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10 mt10" style="width: 62%;">
	<h1 class="column_head"><?=t('Шаблоны писем')?></h1>
        <br/>&nbsp;&nbsp;<b><?=t('Регистрация')?></b><br/>
	<form method="post" class="fs11">
            Тема:<br/>
            <input type="text" name="subject" value="<?=$subject?>" style="width: 375px;"><br/>

           Текст: <br/>
			<textarea name="tpl" style="width: 375px; height: 350px;"><?=$tpl?></textarea>
			<br /><br />

		<input type="submit" name="submit" class="button" value="<?=t('Сохранить')?>">

	</form>
</div>