<div class="left mt10" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10 mt10" style="width: 62%;">
	<h1 class="column_head">Підписи</h1>

	<div class="box_content p10">
		<form action="">
			Зібрано підписів:<br>
			<input type="text" class="text" style="width:100px;" name="signatures" value="<?=db_key::i()->get('signatures')?>" /><br>
			Покрито районів:<br>
			<input type="text" class="text" style="width:50px;" name="districts" value="<?=db_key::i()->get('districts')?>" /><br>
			Область лідер:<br>
                        <? load::model('geo');
                        $regions = geo_peer::instance()->get_regions(1); ?>
                        <?=tag_helper::select('signatures_region_leader',$regions , array('use_values' => false, 'value'=>db_key::i()->get('signatures_region_leader') )); ?>
                        <br>
			<input type="submit" name="submit" class="button" value="<?=t('Зберегти')?>" />
		</form>
	</div>
</div>
