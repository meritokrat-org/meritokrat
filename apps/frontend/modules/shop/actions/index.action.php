<?
load::app('modules/shop/controller');

class shop_index_action extends shop_controller
{

	public function execute()
	{
		switch (request::get_string("view")) {
			case "categories":
				foreach (shop_categories_peer::instance()->get_list(["is_public" => true]) as $item)
					$this->list[] = shop_categories_peer::instance()->get_item($item);

				break;

			case "items":
				foreach (shop_items_peer::instance()->get_list(["is_public" => true, "category_id" => request::get_int("category")], [], ["priority ASC"]) as $item) {
					$item = shop_items_peer::instance()->get_item($item);
					$item["size"] = strlen($item["size"]) > 0 ? unserialize($item["size"]) : array();

					$this->list[] = $item;
				}
				break;
		}
	}
}