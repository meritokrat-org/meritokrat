<?

load::app('modules/polls/controller');
class polls_user_action extends polls_controller
{
	protected $authorized_access = false;

	public function execute()
	{
		if ( !request::get_int('user_id') )
		{
			$this->redirect('/polls');
		}

		if ( request::get_int('user_id') == session::get_user_id() )
		{
			$this->redirect('/polls/mine');
		}

		$this->user_id = request::get_int('user_id');
		$list = polls_peer::instance()->get_by_user( $this->user_id );

		$this->pager = pager_helper::get_pager($list, request::get_int('page'), 10);
		$this->list = $this->pager->get_list();
	}
}