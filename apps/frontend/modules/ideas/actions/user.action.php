<?

load::app('modules/ideas/controller');
class ideas_user_action extends ideas_controller
{
	#protected $authorized_access = true;

	public function execute()
	{
		if ( !$this->user_id = request::get_int('id') )
		{
			$this->redirect('/ideas');
		}

		if ( $this->user_id == session::get_user_id() )
		{
			$this->redirect('/ideas/mine');
		}

		$this->list = ideas_peer::instance()->get_by_user( $this->user_id );

		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 10);
		$this->list = $this->pager->get_list();
	}
}