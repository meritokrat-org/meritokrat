<?

load::app('modules/ideas/controller');
class ideas_mine_action extends ideas_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->list = ideas_peer::instance()->get_by_user( session::get_user_id() );

		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 10);
		$this->list = $this->pager->get_list();
	}
}