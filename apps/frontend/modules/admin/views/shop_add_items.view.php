<div class="left mt10" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10 mt10" style="width: 62%;">
	<h1 class="column_head"><?=$data["id"] > 0 ? t("Редактирование товара") : t("Создание товара") ?></h1>

	<form action="/admin/shop_add_items" method="post" enctype="multipart/form-data">
		<? if ($data["id"] > 0) { ?>
			<input type="hidden" name="id" value="<?= $data["id"] ?>">
		<? } ?>

		<input type="hidden" name="submit" value="true">

		<table>
			<? if ($data["id"] > 0) { ?>
				<tr>
					<td></td>
					<td><img src="<?=$data['photo'] != "" ? context::get('image_server')."m/shop/item/{$data["id"]}{$data['photo']}.jpg" : "/static/images/no_image.jpg"?>" height="126px"></td>
				</tr>
			<? } ?>

			<tr>
				<td><?= t("Название") ?> UA</td>
				<td><input type="text" name="name_ua" value="<?= $data["name_ua"] ?>" style="width: 380px"></td>
			</tr>
			<tr>
				<td style="width: 40%;"><?= t("Название") ?> RU</td>
				<td><input type="text" name="name_ru" value="<?= $data["name_ru"] ?>" style="width: 380px"></td>
			</tr>

			<tr>
				<td><?= t("Приоритет") ?></td>
				<td><input type="number" min="1" name="priority" value="<?= $data["priority"] ?>" style="width: 50px"></td>
			</tr>

			<tr>
				<td><?= t("Категория") ?></td>
				<td>
					<select name="category_id">
						<? foreach ($categories as $category) { ?>
							<option
								value="<?= $category["id"] ?>" <?= $data["category_id"] == $category["id"] ? "selected" : "" ?>><?= $category["title_" . translate::get_lang()] ?></option>
						<? } ?>
					</select>
				</td>
			</tr>

			<tr>
				<td><?= t("Цена") ?></td>
				<td><input type="number" min="1" name="price" value="<?= $data["price"] ?>" style="width: 50px"></td>
			</tr>

			<tr>
				<td><?=t("Размер") ?></td>
				<td>
					<label>
						<input type="checkbox" name="size[]" value="XS" <?=in_array("XS", $data["size"]) ? "checked" : ""?>> - XS
					</label><br>
					<label>
						<input type="checkbox" name="size[]" value="S" <?=in_array("S", $data["size"]) ? "checked" : ""?>> - S
					</label><br>
					<label>
						<input type="checkbox" name="size[]" value="M" <?=in_array("M", $data["size"]) ? "checked" : ""?>> - M
					</label><br>
					<label>
						<input type="checkbox" name="size[]" value="L" <?=in_array("L", $data["size"]) ? "checked" : ""?>> - L
					</label><br>
					<label>
						<input type="checkbox" name="size[]" value="XL" <?=in_array("XL", $data["size"]) ? "checked" : ""?>> - XL
					</label><br>
					<label>
						<input type="checkbox" name="size[]" value="XXL" <?=in_array("XXL", $data["size"]) ? "checked" : ""?>> - XXL
					</label>
				</td>
			</tr>

			<tr>
				<td><?=t("Описание")?> UA</td>
				<td>
					<textarea name="description_ua" style="max-width: 380px"><?= $data["description_ua"] ?></textarea>
				</td>
			</tr>
			<tr>
				<td><?=t("Описание")?> RU</td>
				<td>
					<textarea name="description_ru" style="max-width: 380px"><?= $data["description_ru"] ?></textarea>
				</td>
			</tr>

			<tr>
				<td><?= t("Публична") ?></td>
				<td><input type="checkbox" name="is_public" <?= $data["is_public"] ? "checked" : "" ?>></td>
			</tr>

			<tr>
				<td>Фото</td>
				<td>
					<input type="file" name="photo" />
				</td>
			</tr>

			<tr>
				<td></td>
				<td><input type="submit" value="<?= $data["id"] > 0 ? t("Сохранить") : t("Создать") ?>"></td>
			</tr>
		</table>
	</form>
</div>
<div class="clear"></div>