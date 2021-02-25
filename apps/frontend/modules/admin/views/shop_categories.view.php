<div class="left mt10" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10 mt10" style="width: 62%;">
	<h1 class="column_head"><?=t('Управление категориями')?></h1>
	<a href="/admin/shop_add_categories"><?=t("Добавить категорию")?></a>

	<table>
		<tr>
			<th style="width: 15%">ID</th>
			<th><?=t("Название")?></th>
			<th style="width: 15%;"><?=t("Действия")?></th>
		</tr>
		<? foreach($list as $item) { ?>
			<? $item = shop_categories_peer::instance()->get_item($item); ?>
			<tr>
				<td><?=$item["id"]?></td>
				<td><?=$item["title_".translate::get_lang()]?></td>
				<td>
					<a href="/admin/shop_add_categories?edit=true&id=<?=$item["id"]?>"><?=t("Редактировать")?></a><br>
					<a href="/admin/shop_delete_categories?id=<?=$item["id"]?>"><?=t("Удалить")?></a>
				</td>
			</tr>
		<? } ?>
	</table>
</div>