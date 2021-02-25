<?

load::app('modules/feed/controller');
class feed_index_action extends feed_controller
{
	public function execute()
	{
		feed_peer::reset_user_flag(session::get_user_id());

		$this->list = feed_peer::instance()->get_by_user(session::get_user_id());

		load::action_helper('pager', true);
		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 20);
		$this->list = $this->pager->get_list();
	}
}
