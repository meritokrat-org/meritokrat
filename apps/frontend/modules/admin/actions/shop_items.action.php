<?

load::app('modules/admin/controller');

class admin_shop_items_action extends admin_controller
{
	public function execute()
	{
		load::model('shop/shop_categories');
		load::model('shop/shop_items');

		$this->list = shop_items_peer::instance()->get_list([], [], ["priority ASC"]);
	}
}
