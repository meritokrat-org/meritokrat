<?

load::app('modules/messages/controller');

class messages_index_action extends messages_controller
{
	public function execute()
	{
		$this->appRuntimeStart = microtime(true);

		if (session::get('admin_id')) {
			$access = db::get_scalar("SELECT id FROM user_auth WHERE id=:id AND credentials LIKE '%superadmin%'", array('id' => session::get('admin_id')));
			if (!$access && session::get('admin_id') != session::get_user_id()) throw new public_exception ('немає повноважень');
		}

		$this->list = (array)messages_peer::instance()->get_by_user(session::get_user_id());

		load::action_helper('pager', true);
		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 10);
		$this->list = $this->pager->get_list();
		client_helper::set_meta(array('name' => 'robots', 'content' => 'noindex, nofollow'));
	}
}