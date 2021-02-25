<?

load::app('modules/groups/controller');
class groups_talk_topic_action extends groups_controller
{
	public function execute()
	{
		if ( !$this->topic = groups_topics_peer::instance()->get_item(request::get_int('id')) )
		{
			$this->redirect('/groups');
		}

		if ( !session::get('topic_viewed_' . $this->topic['id']) )
		{
			groups_topics_peer::instance()->update(array('views' => $this->topic['views'] + 1, 'id' => $this->topic['id']));
			session::set('topic_viewed_' . $this->topic['id'], true);
		}

		$this->group = groups_peer::instance()->get_item($this->topic['group_id']);
		if ( ( $this->group['privacy'] == groups_peer::PRIVACY_PRIVATE ) && !groups_members_peer::instance()->is_member($this->group['id'], session::get_user_id()) && !session::has_credential('admin') )
		{
			$this->redirect('/group' . $this->group['id']);
		}

		$this->list = groups_topics_messages_peer::instance()->get_by_topic($this->topic['id']);

		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 10);
		if ( request::get('last') && ( $this->pager->get_page() < $this->pager->get_pages() ) )
		{
			$this->redirect('talk_topic?id=' . $this->topic['id'] . '&page=' . $this->pager->get_pages());
		}

		$this->list = $this->pager->get_list();

		client_helper::register_variable('l_confirm', t('Вы уверены?'));

		client_helper::set_title( htmlspecialchars(stripslashes($this->topic['topic'] . ' | ' . $this->group['title'])));
	}
}