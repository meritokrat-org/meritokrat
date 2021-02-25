<?
load::app('modules/shop/controller');
load::model('shop/shop_categories');
load::model('shop/shop_items');
load::system("email/email");

class shop_buy_action extends shop_controller
{

	public function execute()
	{

		if(request::get_string("act") == "edit"){
			$this->set_layout(null);
			$this->set_template("edit_cart");

			return;
		}

		$this->cart = json_decode($_COOKIE["cart"]);

		if (request::get("send_email") == 1) {
			$email = new email();

			$email->setSender(request::get_string("email"));
			$email->setSubject("Заказ");
			$email->setReceiver("julie.talant@gmail.com");

			$body =
				"
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

					.column_head { background: url(https://meritokrat.org/static/images/common/box_head.gif) no-repeat -5px 0; height: 27px; -moz-border-radius: 4px; border-radius: 4px; -webkit-border-radius: 4px; margin-bottom: 0px; padding: 4px 10px 0px 15px; color:#ffcc66; font-weight:bold; font-size: 12px;text-transform:UPPERCASE;}
					.column_head a {color: #FFCC66;text-decoration: none}
					.column_head a:hover {text-decoration: underline}
				</style>

				<table style='width: 850px; margin-top: 15px;' cellpadding='0' cellspacing='0' class='cart'>
					<tr class='column_head'>
						<th style='width: 5%'>ID</th>
						<th style='width: 15%'>".t("Фото")."</th>
						<th style='width: 40%'>".t("Название")."</th>
						<th style='width: 7%'>".t("Размер")."</th>
						<th style='width: 7%;'>".t("Цена")."</th>
						<th style='width: 10%;'>".t("Количество")."</th>
						<th>".t("Сумма")."</th>
					</tr>";

			$col = 0;
			$num = 0;

			foreach (json_decode($_COOKIE["cart"]) as $item) {
				$item->item = shop_items_peer::instance()->get_item($item->id);
				$img = $item->item["photo"] != '' ? context::get('image_server').'p/shop/item/'.$item->item["id"].$item->item['photo'].'.jpg' : '/static/images/no_image.jpg';

				$body .= "
					<tr>
						<td style='vertical-align: middle;'>
							".($item->id > 10 ? "0".$item->id : "00".$item->id)."
						</td>
						<td>
							<div style='width: 100%; height: 80px; float: left; background-image: url(".$img."); background-position: center; background-size: contain; background-repeat: no-repeat;'></div>
						</td>
						<td>
							<div style='text-align: left; text-transform: uppercase; font-weight: bolder; min-height: 20px; padding: 5px'>
								".$item->item["name_".translate::get_lang()]."
							</div>
							<div style='text-align: left; color:#888; min-height: 50px; padding: 5px'>
								".$item->item["description_".translate::get_lang()]."
							</div>
						</td>
						<td class='size' style='padding-top: 5px'>
							".$item->size."
						</td>
						<td class='price' style='padding-top: 5px'>
							".$item->price."
						</td>
						<td style='padding-top: 5px'>
							".$item->num."
						</td>
						<td class='sum' style='padding-top: 5px'>
							".$item->price * $item->num."
						</td>
					</tr>";

				$col += $item->num;
				$num += $item->price * $item->num;
			}

			$body .= '
				<tr style="background: #ccc;">
					<td colspan="5" style="text-align: right"><b>'.t("Всего").' :</b></td>
					<td id="sum_col" style="text-align: center" style="padding-top: 5px">'.$col.'</td>
					<td id="sum_price" style="text-align: center" style="padding-top: 5px">'.$num.'</td>
					<td></td>
				</tr>
			</table>
			<table>
				<tr>
					<td style="padding: 5px; text-align: right; font-weight: bold; border-bottom: 1px dotted #888;">
						'.t("Имя").'
					</td>
					<td style="padding: 5px; border-bottom: 1px dotted #888;">
						' . request::get_string("fname").' '.request::get_string("lname") . '
					</td>
				</tr>
				<tr>
					<td style="padding: 5px; text-align: right; font-weight: bold; border-bottom: 1px dotted #888;">
						'.t("Телефон").'
					</td>
					<td style="padding: 5px; border-bottom: 1px dotted #888;">
						' . request::get_string("tel").'
					</td>
				</tr>
				<tr>
					<td style="padding: 5px; text-align: right; font-weight: bold; border-bottom: 1px dotted #888;">
						Email
					</td>
					<td style="padding: 5px; border-bottom: 1px dotted #888;">
						' . request::get_string("email").'
					</td>
				</tr>
				<tr>
					<td style="padding: 5px; text-align: right; font-weight: bold; border-bottom: 1px dotted #888;">
						'.t("Комментарий").'
					</td>
					<td style="padding: 5px; border-bottom: 1px dotted #888;">
						' . request::get_string("body").'
					</td>
				</tr>';

			$email->isHTML();
			$email->setBody($body);
			$email->send();

			$_COOKIE["cart"] = null;
			$this->ok = true;
		}
	}
}