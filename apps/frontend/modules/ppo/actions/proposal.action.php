<?

load::app('modules/ppo/controller');
class ppo_proposal_action extends ppo_controller
{
	public function execute()
	{
		$this->group = ppo_peer::instance()->get_item(request::get_int('id'));

		if ( ( $this->group['privacy'] == ppo_peer::PRIVACY_PRIVATE ) && !ppo_members_peer::instance()->is_member($this->group['id'], session::get_user_id()) )
		{
			$this->redirect('/group' . $this->group['id']);
		}

		$this->filter = request::get('filter');
		$sort = array('id DESC');
		if ( $this->filter == 'hot' )
		{
			$sort = array('messages_count DESC');
		}

		$this->list = ppo_proposal_peer::instance()->get_by_group($this->group['id'], $sort);

		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 10);
		$this->list = $this->pager->get_list();

		client_helper::set_title( t('Предложения') . ' | ' . $this->group['title'] );
	}
}