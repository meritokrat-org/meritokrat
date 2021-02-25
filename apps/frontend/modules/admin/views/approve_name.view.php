<h1 class="mt10 mr10 column_head">
	<?=t('Премодерация имен')?>
</h1>
<? foreach ( $list as $item ) {?>
	<div id="name_<?= $item["user_id"] ?>" class="box_content border1 mr10 p10 mb10">
		<?=$item["first_name"]?> <?=$item["last_name"]?> <span class="fs16">→</span> <?=$item["new_fname"] ? $item["new_fname"] : $item["first_name"]?> <?=$item["new_lname"] ? $item["new_lname"] : $item["last_name"]?>
		<div style="height: 40px; width: 100px; float: right;">
			<a rel="<?= $item["user_id"] ?>" class="apr" href="javascript:;"><?= t('Утвердить') ?></a><br/>
			<a rel="<?= $item["user_id"] ?>" class="del" href="javascript:;"><?= t('Удалить') ?></a>
		</div>
		<div class="clear"></div>
	</div>
<? } ?>

<? if(count($list) == 0){ ?>
	<div id="name_<?= $item["user_id"] ?>" class="box_content border1 mr10 p10 mb10" style="text-align: center">
		<?=t("Тут еще ничего нет")?>
	</div>
<? } ?>

<script type="text/javascript">
	$(document).ready(function($){
		$('.apr').click(function(){
			$.post('/admin/approve_name',{
				id : $(this).attr('rel'),
				approve : 1
			});
			$('#name_'+$(this).attr('rel')).hide(500);
		});

		$('.del').click(function(){
			$.post('/admin/approve_name',{
				id : $(this).attr('rel'),
				approve : 0
			});
			$('#name_'+$(this).attr('rel')).hide(500);
		});
	});
</script>