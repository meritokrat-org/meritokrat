<?

load::app('modules/ppo/controller');
class ppo_proposal_topic_action extends ppo_controller
{
	public function execute()
	{
		if ( !$this->topic = ppo_proposal_peer::instance()->get_item(request::get_int('id')) )
		{
			$this->redirect('/ppo');
		}

		if ( !session::get('proposal_viewed_' . $this->topic['id']) )
		{
			ppo_proposal_peer::instance()->update(array('views' => $this->topic['views'] + 1, 'id' => $this->topic['id']));
			session::set('proposal_viewed_' . $this->topic['id'], true);
		}

		$this->group = ppo_peer::instance()->get_item($this->topic['group_id']);
		if ( ( $this->group['privacy'] == ppo_peer::PRIVACY_PRIVATE ) && !ppo_members_peer::instance()->is_member($this->group['id'], session::get_user_id()) )
		{
			$this->redirect('/group' . $this->group['id']);
		}

		$this->list = ppo_proposal_messages_peer::instance()->get_by_topic($this->topic['id']);

		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 10);
		if ( request::get('last') && ( $this->pager->get_page() < $this->pager->get_pages() ) )
		{
			$this->redirect('proposal_topic?id=' . $this->topic['id'] . '&page=' . $this->pager->get_pages());
		}

		$this->list = $this->pager->get_list();

		client_helper::register_variable('l_confirm', t('Вы уверены?'));

		client_helper::set_title( $this->topic['topic'] . ' | ' . $this->group['title'] );
	}
}