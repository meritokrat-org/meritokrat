<? if ($ok) { ?>
	<div style="width: 98%; margin-top: 15px;" class="success">
		<?= t('Ваш заказ принят ! Ожидайте, наш менеджер свяжеться с Вами !') ?>
	</div>
	<script>
		$.cookie("cart", null, { path: '/'});
	</script>
<? } else { ?>
	<? if(count(json_decode($_COOKIE["cart"])) > 0){ ?>
		<div class="order">
			<style>
				.cart td {
					text-align: center;
					border-bottom: 2px dotted #f0f0f0;
					vertical-align: top;
					padding: 5px;
				}

				input[type=text] {
					width: 190px;
				}

			</style>
			<table style="width: 100%; margin-top: 15px;" cellpadding="0" cellspacing="0" class="cart">
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

				<? foreach (json_decode($_COOKIE["cart"]) as $cart) {?>
					<? $cart->item = shop_items_peer::instance()->get_item($cart->id) ?>

					<tr class="item" id="<?=$cart->id?>">
						<td style="vertical-align: middle;">
							<?=$cart->id > 10 ? "0".$cart->id : "00".$cart->id?>
						</td>
						<td>
							<div style='width: 100%; height: 80px; float: left; background-image: url("<?=$cart->item["photo"] != "" ? context::get('image_server')."m/shop/item/{$cart->item["id"]}{$cart->item['photo']}.jpg" : "/static/images/no_image.jpg"?>"); background-position: center; background-size: contain; background-repeat: no-repeat;'></div>
						</td>
						<td>
							<div style="text-align: left; text-transform: uppercase; font-weight: bolder; min-height: 20px; padding: 5px">
								<?=$cart->item["name_".translate::get_lang()]?>
							</div>
							<div style="text-align: left; color:#888; min-height: 50px; padding: 5px">
								<?=$cart->item["description_".translate::get_lang()]?>
							</div>
						</td>
						<td class="size">
							<?=$cart->size?>
						</td>
						<td class="price"><?=$cart->price?></td>
						<td>
							<input type="number" min="1" style="width: 40px" value="<?=$cart->num?>">
						</td>
						<td class="sum">
							<?=$cart->price * $cart->num?>
						</td>
						<td>
							<a class="del" style="cursor: pointer" data-id="<?=$cart->id?>_<?=$cart->size?>">X</a>
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

			<div class="notice">* <?=t("Тільки самовивіз (місто Кіїв, вул.Константинівська, 5 поверх, офіс Ігоря Шевченка)")?></div>

			<form action="/shop/buy" method="post" id="cart_info">
				<input type="hidden" name="send_email" value="1">
				<table>
					<tr>
						<td style="width: 25%; text-align: right">Email<span style="color: red;">*</span></td>
						<td><input type="email" name="email" id="cart_email"></td>
					</tr>
					<tr>
						<td style="text-align: right">Iм’я<span style="color: red;">*</span></td>
						<td><input type="text" name="fname" id="cart_fname"></td>
					</tr>
					<tr>
						<td style="text-align: right">Прізвище<span style="color: red;">*</span></td>
						<td><input type="text" name="lname" id="cart_lname"></td>
					</tr>
					<tr>
						<td style="text-align: right">Телефон<span style="color: red;">*</span></td>
						<td><input type="text" name="tel" id="cart_tel"></td>
					</tr>
					<tr>
						<td style="text-align: right">Повідомлення</td>
						<td><textarea id="body" style="max-width: 410px; width: 410px" name="body"></textarea></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<input id="submit_cart" style="background: none repeat scroll 0% 0% #800000; color: #ffffff; font-weight: bold; margin: 0 auto 10px auto;" type="button" value="<?=t("Оформить заказ")?>">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<div class="error hide" id="err_email">
								<?=t("Email введен не корректно")?>
							</div>
							<div class="error hide" id="err_fname">
								<?=t("Введите имя")?>
							</div>
							<div class="error hide" id="err_lname">
								<?=t("Введите фамилию")?>
							</div>
							<div class="error hide" id="err_tel">
								<?=t("Телефон введен не корректно")?>
							</div>
						</td>
					</tr>
				</table>
			</form>

			<script>
				$(document).ready(function(){
					save();

					$("input[type=number]").change(function(){
						var item = $(this).parent().parent();
						var price = parseInt($(item.find(".price")).html());
						var sum = item.find(".sum");

						if(parseInt($(this).val()) > 0)
							$(sum).html(price * parseInt($(this).val()));
						else
							$(sum).html("0");

						save();
					});

					$("a.del").click(function(){
						$(this).parent().parent().remove();
						save();
					});

					$("#submit_cart").click(function(){
						var email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
						var phone = /^\d{10}$/;
						var tel = $("#cart_tel").val();

						if( ! email.test($("#cart_email").val()))
							$("#err_email").show();
						else
							$("#err_email").hide();

						tel = tel.replace(/\s+/g, '');

						if( ! phone.test( tel ))
							$("#err_tel").show();
						else
							$("#err_tel").hide();

						if($("#cart_fname").val() == "")
							$("#err_fname").show();
						else
							$("#err_fname").hide();

						if($("#cart_lname").val() == "")
							$("#err_lname").show();
						else
							$("#err_lname").hide();

						$(".error").each(function(n, e){
							if($(e).css("display") == "block")
								return false;
						});

						$("#cart_info").submit();
					});

					function save(){
						var cart = new Array;

						$("tr.item").each(function(n, e){
							var item = {};

							if(parseInt($($(e).find("[type=number]")).val()) > 0) {
								item.id = $(this).attr("id");
								item.size = $($(e).find(".size")).html();
								item.price = parseInt($($(e).find(".price")).html());
								item.num = parseInt($($(e).find("[type=number]")).val());

								cart.push(item);
							}
						});

						if(cart.length == 0) {
							$(".order").html("<div class='notice' style='margin-top: 15px;'><?=t("Вы ничего не заказали")?> </div>");
						}

						$.cookie("cart", JSON.stringify(cart), { path: '/'});

						setCart();
					}
				});
			</script>
		</div>
	<? }else{ ?>
		<div class="notice" style="margin-top: 15px;">
			<?=t("Вы ничего не заказали")?>
		</div>
	<? } ?>
<? } ?>