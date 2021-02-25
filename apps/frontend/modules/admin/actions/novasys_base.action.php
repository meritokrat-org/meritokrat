<?

load::app('modules/admin/controller');
class admin_novasys_base_action extends admin_controller
{
	public function execute()
	{
		$where = array();
		if ( request::get('user_id') )
		{
			$where['user_id'] = request::get('user_id');
		}
		if ( request::get('novasys_id') )
		{
			$where['novasys_id'] = request::get('novasys_id');
		}
		if ( request::get('email') )
		{
			$where['email0'] = request::get('email');
		}
		if ( request::get('last_name') )
		{
			$where['lname'] = request::get('last_name');
		}
		if ( request::get('name') )
		{
			$where['name'] = request::get('name');
		}

		if (isset($_GET['sh']))
		{
			$where['shevchenko'] = request::get('sh',0);
		}

		if (isset($_GET['sort']))
		{
			$order =$_GET['sort'].' DESC';
		}
                else $order='user_id ASC';
                
                load::model('user/user_novasys_data');
		
                $this->list = user_novasys_data_peer::instance()->get_list($where, array(), array($order), 2500);
                
		load::action_helper('pager');
		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 100);
		$this->list = $this->pager->get_list();
	}
}
