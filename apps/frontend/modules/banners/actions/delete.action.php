<?
load::app('modules/banners/controller');
class banners_delete_action extends banners_controller
{
    protected $credentials = array('admin');

    public function execute()
	{
		load::model('banners/items');
		if ( !$this->banner = banners_items_peer::instance()->get_item( request::get_int('id') ) )
		{
			throw new public_exception('Баннер не найден');
		}

		if ( session::has_credential('admin') )
		{
			banners_items_peer::instance()->delete_item($this->banner['id']);
		}

		$this->redirect('/banners');
	}
}
