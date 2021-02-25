<?

class profile_questions_action extends frontend_controller
{
	protected $authorized_access = true;
	public function execute()
	{
		$this->user = user_auth_peer::instance()->get_item( request::get_int('id') ? request::get_int('id') : session::get_user_id() );

		load::model('user/user_data');
		$this->user_data = user_data_peer::instance()->get_item( $this->user['id'] );

		$sort = 'rate DESC';
		
		$this->filter = request::get('filter');
		if ( $this->filter == 'new' )
		{
			$sort = 'id DESC';
		}
		else if ( $this->filter == 'no_reply' )
		{
			$where = array('reply' => '');
			$sort = 'id DESC';
		}

		load::model('user/questions');
		$this->questions = user_questions_peer::instance()->get_by_profile($this->user['id'], $where, $sort);

		load::action_helper('pager', true);
		$this->pager = pager_helper::get_pager($this->questions, request::get_int('page'), 10);
		$this->questions = $this->pager->get_list();
	}
}