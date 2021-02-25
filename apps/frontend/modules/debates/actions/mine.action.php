<?

load::app('modules/debates/controller');
class debates_mine_action extends debates_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->list = debates_peer::instance()->get_by_user( session::get_user_id() );

		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 10);
		$this->list = $this->pager->get_list();
	}
}