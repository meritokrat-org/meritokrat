<?

load::app('modules/debates/controller');
class debates_user_action extends debates_controller
{
	protected $authorized_access = false;

	public function execute()
	{
		if ( !request::get_int('user_id') )
		{
			$this->redirect('/debates');
		}

		if ( request::get_int('user_id') == session::get_user_id() )
		{
			$this->redirect('/debates/mine');
		}
		
		$this->user_id = request::get_int('user_id');
		$list = debates_peer::instance()->get_by_user( $this->user_id );

		$this->pager = pager_helper::get_pager($list, request::get_int('page'), 10);
		$this->list = $this->pager->get_list();
	}
}