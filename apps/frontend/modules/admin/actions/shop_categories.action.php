<?

load::app('modules/admin/controller');

class admin_shop_categories_action extends admin_controller
{
	public function execute()
	{
		load::model('shop/shop_categories');
		$this->list = shop_categories_peer::instance()->get_list();
	}
}
