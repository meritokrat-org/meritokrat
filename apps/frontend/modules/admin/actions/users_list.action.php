<?

load::app('modules/admin/controller');
class admin_users_list_action extends admin_controller
{
	public function execute()
	{
		$where = array();
		if ( request::get('ip') )
		{
			$where['ip'] = request::get('ip');
		}
		if ( request::get('active') )
		{
			$where['active'] = request::get('active');
		}

		if (isset($_GET['sh']))
		{
			$where['shevchenko'] = request::get('sh',0);
		}

		if (isset($_GET['sort']))
		{
			$order =$_GET['sort'].' DESC';
		}
                else $order='id DESC';

		$this->list = user_auth_peer::instance()->get_list($where, array(), array($order), 5000);
                load::model('user/user_data');
		load::action_helper('pager');
		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 40);
		$this->list = $this->pager->get_list();
	}
}
