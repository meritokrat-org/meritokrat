<div class="left mt10" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10 mt10" style="width: 62%;">
	<h1 class="column_head"><?=t('Перевод')?></h1>

	<form method="post" class="fs11">
		<? foreach ( $data as $ru => $ua ) { ?>
			<?=htmlspecialchars($ru)?><br />
			<input type="hidden" name="source[]" value="<?=htmlspecialchars($ru)?>">
			<textarea name="translation[]" style="width: 375px; height: 50px;"><?=htmlspecialchars($ua)?></textarea>
			<br /><br />
		<? } ?>

		<input type="submit" name="submit" class="button" value="<?=t('Сохранить')?>">

	</form>
</div>