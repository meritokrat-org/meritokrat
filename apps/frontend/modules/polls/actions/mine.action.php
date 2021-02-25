<?

load::app('modules/polls/controller');
class polls_mine_action extends polls_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->list = polls_peer::instance()->get_by_user( session::get_user_id() );

		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 10);
		$this->list = $this->pager->get_list();
	}
}