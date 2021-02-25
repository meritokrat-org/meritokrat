<div class="left mt10" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10 mt10" style="width: 62%;">
	<h1 class="column_head"><?=t('Управление товарами')?></h1>
	<a href="/admin/shop_add_items"><?=t("Добавить товар")?></a>

	<table>
		<tr>
			<th style="width: 5%">ID</th>
			<th style="width: 20%">Фото</th>
			<th><?=t("Название")?></th>
			<th style="width: 15%;"><?=t("Действия")?></th>
		</tr>
		<? foreach($list as $item) { ?>
			<? $item = shop_items_peer::instance()->get_item($item); ?>
			<tr>
				<td style="text-align: center;"><?=$item["id"]?></td>
				<td style="text-align: center;"><img src="<?=context::get('image_server')."s/shop/item/".$item["id"].$item["photo"]?>.jpg"></td>
				<td><?=$item["name_".translate::get_lang()]?></td>
				<td>
					<a href="/admin/shop_add_items?edit=true&id=<?=$item["id"]?>"><?=t("Редактировать")?></a><br>
					<a href="/admin/shop_delete_items?id=<?=$item["id"]?>"><?=t("Удалить")?></a>
				</td>
			</tr>
		<? } ?>
	</table>
</div>