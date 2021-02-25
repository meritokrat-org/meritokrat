<div class="left mt10" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10 mt10" style="width: 62%;">
	<h1 class="column_head"><?=$data["id"] > 0 ? t("Редактирование категории") : t("Создание категории") ?></h1>

	<form action="/admin/shop_add_categories" method="post" enctype="multipart/form-data">
		<? if ($data["id"] > 0) { ?>
			<input type="hidden" name="id" value="<?= $data["id"] ?>">
		<? } ?>

		<input type="hidden" name="submit" value="true">

		<table>
			<? if ($data["id"] > 0) { ?>
				<tr>
					<td></td>
					<td><img src="<?=$data['photo'] != "" ? context::get('image_server')."m/shop/category/{$data["id"]}{$data['photo']}.jpg" : "/static/images/no_image.jpg"?>" height="126px"></td>
				</tr>
			<? } ?>

			<tr>
				<td style="width: 40%;"><?=t("Название на русском")?></td>
				<td><input type="text" name="title_ru" value="<?=$data["title_ru"]?>" style="width: 100%"></td>
			</tr>
			<tr>
				<td><?=t("Название на украинском")?></td>
				<td><input type="text" name="title_ua" value="<?=$data["title_ua"]?>" style="width: 100%"></td>
			</tr>
			<tr>
				<td><?=t("Публична")?></td>
				<td><input type="checkbox" name="is_public" <?=$data["is_public"] ? "checked" : ""?>></td>
			</tr>

			<tr>
				<td>Фото</td>
				<td>
					<input type="file" name="photo" />
				</td>
			</tr>

			<tr>
				<td></td>
				<td><input type="submit" value="<?=$data["id"] > 0 ? t("Сохранить") : t("Создать")?>"></td>
			</tr>
		</table>
	</form>
</div>
<div class="clear"></div>