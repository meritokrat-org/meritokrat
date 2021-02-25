<div class="sub_menu mt10 mb5" style="width:1000px">
	<b class="fs12">Заяви</b>
	<a class="ml5" href="/reestr">Члени МПУ</a>
	<a class="ml5" href="/reestr/exmembers"> Колишні члени МПУ </a>
</div>
<div class="sub_menu mt5 mb10">
	<a href="/zayava/list?status=1" <?=(request::get_int('status') == 1) ? 'class="bold"' : '' ?>>
		<?=t('Заявления на вступление')?>
	</a>
	
	<? if(session::has_credential("admin")){ ?>
		<a href="/zayava/list?filter=deleted" <?=(request::get('filter')=="deleted")?'class="bold"':''?>>
			<?=t('Удаленные')?>
		</a>
	<? } ?>
	
	<a
		href="/zayava/list?filter=termination"
		<? if(request::get('filter') == "termination"){ ?>
			class="bold"
		<? } ?>
		>
		<?=t('Заявления на выход')?>
	</a>
	
	<div class="right">
		<form method="GET" action="/zayava/list">
			<input type="hidden" name="status" value="<?=request::get_int('status',1)?>" />
			<input type="text" value="<?=request::get_string('req')?>" class="text" name="req" />
			<input type="submit" value="Пошук" class="button" name="submit" />
		</form>
	</div>
	
</div>