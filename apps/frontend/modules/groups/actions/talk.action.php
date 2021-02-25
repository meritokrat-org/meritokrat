<?

load::app('modules/groups/controller');
class groups_talk_action extends groups_controller
{
	public function execute()
	{
		$this->group = groups_peer::instance()->get_item(request::get_int('id'));

		if ( ( $this->group['privacy'] == groups_peer::PRIVACY_PRIVATE ) && !groups_members_peer::instance()->is_member($this->group['id'], session::get_user_id()) &&  !groups_peer::instance()->is_moderator($this->group['id'], session::get_user_id()))
		{
			$this->redirect('/group' . $this->group['id']);
		}
                
		$this->filter = request::get('filter');
		$sort = array('id DESC');
		if ( $this->filter == 'hot' )
		{
			$sort = array('messages_count DESC');
		}

		$this->list = groups_topics_peer::instance()->get_by_group($this->group['id'], $sort);

		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 10);
		$this->list = $this->pager->get_list();

		client_helper::set_title( t('Обсуждения') . ' | ' . $this->group['title'] );
	}
}