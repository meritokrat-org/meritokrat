<?

load::app('modules/lists/controller');

class lists_delete_action extends lists_controller
{
	public function execute()
	{
		lists_peer::instance()->delete_item(request::get_int('id'));
		$this->redirect('/lists');
	}
}