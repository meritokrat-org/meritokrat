<div class="left mt10" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10 mt10" style="width: 62%;">
	<h1 class="column_head">Курс валют</h1>

	<div class="box_content p10">
		<form action="">
			Долар:<br>
			<input type="text" class="text" style="width:50px;" name="dollar" value="<?=db_key::i()->get('currency_dollar')?>" /><br>
			Рубль:<br>
			<input type="text" class="text" style="width:50px;" name="ruble" value="<?=db_key::i()->get('currency_ruble')?>" /><br>
			Евро:<br>
			<input type="text" class="text" style="width:50px;" name="euro" value="<?=db_key::i()->get('currency_euro')?>" /><br>
			<br>
			<input type="submit" name="submit" class="button" value="<?=t('Зберегти')?>" />
		</form>
	</div>
</div>
