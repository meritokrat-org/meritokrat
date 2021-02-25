<? if(count($list) > 0){ ?>
	<? foreach ($list as $item) { ?>

		<div style="height: 200px; margin-top: 10px">
			<div class="thumb" data-photo="<?=$item["id"].$item['photo']?>" style="width: 250px; height: 100%; margin-right: 10px; float: left; cursor: pointer; background-image: url('<?=$item['photo'] != "" ? context::get('image_server')."f/shop/item/{$item["id"]}{$item['photo']}.jpg" : "/static/images/no_image.jpg"?>'); background-position: top; background-size: contain; background-repeat: no-repeat;"></div>
			<div style="float: left;" data-id="<?=$item["id"]?>">
				<div>
					<b><span style="color: #000; text-transform: uppercase;"><?=$item["name_".translate::get_lang()]?></span></b>
				</div>
				<div>
					<span style="font-size: 16px; color: #aaaaaa;">Код товару: <strong style="color: #565656;"><?=$item["id"] > 10 ? "0".$item["id"] : "00".$item["id"]?></strong></span>
				</div>

				<? if(count($item["size"]) > 0){?>
					<div>
						<table>
							<tr>
								<td style="margin: 0; padding-left: 0;">
									<span style="font-size: 16px; color: #aaaaaa;">Розмiр :</span>
								</td>
								<td>
									<? foreach ($item["size"] as $size) { ?>
										<label>
											<input type="radio" name="size_<?=$item["id"]?>" value="<?=$size?>"> - <?=$size?>
										</label>
									<? } ?>
								</td>
							</tr>
						</table>
					</div>
				<?}?>

				<div style="max-width: 500px; margin-top: 10px">
					<p>
						<?=$item["description_".translate::get_lang()]?>
					</p>
				</div>
				<div>
					<span style="font-size: 20px; font-weight: bold; color: #800000">&nbsp;<span style="font-size: 24px;" class="price"><?=$item["price"]?></span></span> <span style="font-size: 12px;">грн </span><span style="color: #ff0000;"><strong>*</strong></span>
				</div>
				<p style="text-align: left;">
					<input type="number" min="1" max="100" value="1">
					<input data-id="<?=$item["id"]?>" class="pay_submit" style="background: none repeat scroll 0% 0% #800000; color: #ffffff; font-weight: bold;" type="button" value="<?=t("Купить")?>">
				</p>
				<div class="error hide">
					Выберите размер
				</div>
				<div class="success hide">
					Товар добавлен в корзину
				</div>
			</div>
		</div>
		<hr>

	<? } ?>
<? }else {?>
	<p style="text-align: center"><?=t("Здесь пока нет товаров")?></p>
<? } ?>

<script>
	$(document).ready(function(){
		$(".thumb").click(function(){
			Popup.show();
			Popup.setHtml("<a style='cursor:pointer' class='closeThumb'><img src='/static/images/close.png' style='position: absolute; top: -11px; right: -11px;'></a><img src='<?=context::get('image_server')."o/shop/item/"?>"+$(this).attr("data-photo")+".jpg' style='height: 650px;'>");
			Popup.position();
		});

		$(".closeThumb").live("click", function(){
			Popup.close();
		});
	});
</script>
