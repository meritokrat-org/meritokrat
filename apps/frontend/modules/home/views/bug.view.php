<div class="form_bg mt10 mr10">
	<h1 class="column_head"><?=t('Нашли ошибку или есть предложения?')?></h1>
	<div class="m10 success hidden"><?=t('Спасибо! Мы в ближайшее время обработаем Ваш запрос')?></div>
	<form id="bug_form" class="fs12 ml10 mr10">
		<div class="mb10"><?=t('Опишите суть ошибки/проблемы/предложения')?></div>
		<textarea rel="<?=t('Введите текст')?>" name="text" style="width: 98%;"></textarea><br /><br />
		<input type="submit" class="button" name="submit" value="<?=t('Отправить')?>" />
		<?=tag_helper::wait_panel()?>
	</form>
	<br />
</div>