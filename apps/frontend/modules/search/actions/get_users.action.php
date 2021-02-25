<?
//unused method
class search_get_users_action extends frontend_controller
{
	protected $authorized_access = true;
	public function execute()
	{
		load::model('political_views');

		$list = array();
		if ( $users = user_data_peer::instance()->get_by_name(request::get_string('key')) )
		{
			foreach ( $users as $id )
			{
				$user_data = user_data_peer::instance()->get_item($id);
				$user = user_auth_peer::instance()->get_item($id);
				
				$user_data['details'] =
					user_auth_peer::get_type($user['type']) . ', ' .
					political_views_peer::get_name($user_data['political_views']);

				$list[] = $user_data;
			}
		}

		$this->set_renderer('ajax');
		$this->json = $list;
	}
}
