<? header("Content-Type: text/html; charset=utf-8"); ?>
<html>
<head>
	<script type="text/javascript" src='/static/javascript/jquery/jquery-1.4.2.js'></script>
	<script type="text/javascript" src='/static/javascript/library/plugins/cookie.js'></script>
	<style>
		.column_head { background: url(/static/images/common/box_head.gif) no-repeat -5px 0; height: 27px; -moz-border-radius: 4px; border-radius: 4px; -webkit-border-radius: 4px; margin-bottom: 0px; padding: 4px 10px 0px 15px; color:#ffcc66; font-weight:bold; font-size: 12px;text-transform:UPPERCASE;}
		.column_head a {color: #FFCC66;text-decoration: none}
		.column_head a:hover {text-decoration: underline}
	</style>
</head>
<body>
	<style>
		td {
			text-align: center;
			border-bottom: 2px dotted #f0f0f0;
			vertical-align: top;
			padding: 5px;
		}

	</style>
	<table style="width: 100%;" cellpadding="0" cellspacing="0">
		<tr class="column_head">
			<th style="width: 5%">ID</th>
			<th style="width: 15%"><?=t("Фото")?></th>
			<th style="width: 40%"><?=t("Название")?></th>
			<th style="width: 7%"><?=t("Размер")?></th>
			<th style="width: 7%;"><?=t("Цена")?></th>
			<th style="width: 10%;"><?=t("Количество")?></th>
			<th><?=t("Сумма")?></th>
			<th><?=t("Удалить")?></th>
		</tr>
		<? foreach (json_decode($_COOKIE["cart"]) as $item) {?>
			<? $item->item = shop_items_peer::instance()->get_item($item->id) ?>

			<tr class="item" id="<?=$item->id?>">
				<td style="vertical-align: middle;">
					<?=$item->id > 10 ? "0".$item->id : "00".$item->id?>
				</td>
				<td>
					<div style='width: 100%; height: 80px; float: left; background-image: url("<?=$item->item["photo"] != "" ? context::get('image_server')."p/shop/item/{$item->item["id"]}{$item->item['photo']}.jpg" : "/static/images/no_image.jpg"?>"); background-position: center; background-size: contain; background-repeat: no-repeat;'></div>
				</td>
				<td>
					<div style="text-align: left; text-transform: uppercase; font-weight: bolder; min-height: 20px; padding: 5px">
						<?=$item->item["name_".translate::get_lang()]?>
					</div>
					<div style="text-align: left; color:#888; min-height: 50px; padding: 5px">
						<?=$item->item["description_".translate::get_lang()]?>
					</div>
				</td>
				<td class="size">
					<?=$item->size?>
				</td>
				<td class="price"><?=$item->price?></td>
				<td>
					<input type="number" min="1" style="width: 40px" value="<?=$item->num?>">
				</td>
				<td class="sum">
					<?=$item->price * $item->num?>
				</td>
				<td>
					<a class="del" style="cursor: pointer" data-id="<?=$item->id?>_<?=$item->size?>">X</a>
				</td>
			</tr>
		<? } ?>
		<tr style="background: #ccc;">
			<td colspan="5" style="text-align: right"><b><?= t("Всего") ?> :</b></td>
			<td id="sum_col" style="text-align: center"></td>
			<td id="sum_price" style="text-align: center"></td>
			<td></td>
		</tr>
	</table>
	<div style="width: 100%; text-align: center; padding-top: 10px;">
		<input id="save" style="background: none repeat scroll 0% 0% #800000; color: #ffffff; font-weight: bold; margin: 0 auto 10px auto;" type="button" value="<?=t("Выйти")?>">
	</div>
<script>
	$(document).ready(function(){
		save();

		$("input[type=number]").change(function(){
			var item = $(this).parent().parent();
			var price = parseInt($(item.find(".price")).html());
			var sum = item.find(".sum");

			$(sum).html(price * parseInt($(this).val()));
			save();
		});

		$("a.del").click(function(){
			$(this).parent().parent().remove();
			save();
		});

		$("#save").click(function(){
			save();

			window.opener.setCart();
			window.close();
		});

		function save(){
			var cart = new Array;

			$("tr.item").each(function(n, e){
				var item = {};

				item.id = $(this).attr("id");
				item.size = $($(e).find(".size")).html();
				item.price = parseInt($($(e).find(".price")).html());
				item.num = parseInt($($(e).find("[type=number]")).val());

				cart.push(item);
			});

			$.cookie("cart", JSON.stringify(cart), { path: '/'});

			window.opener.setCart();

			var num = 0;
			var sum = 0;

			if($.cookie("cart"))
				JSON.parse($.cookie("cart")).forEach(function(e) {
					num += e.num;
					sum += e.num * e.price;
				});

			if(num > 0)
				$("#sum_col").html(num);

			if(sum > 0)
				$("#sum_price").html(sum);
		}
	});
</script>
</body>
</html>