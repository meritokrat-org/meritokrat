<?

load::app('modules/groups/controller');
class groups_members_action extends groups_controller
{
	public function execute()
	{
		if ( $this->group = groups_peer::instance()->get_item( request::get_int('id') ) )
		{
			if ( ( $this->group['privacy'] == groups_peer::PRIVACY_PRIVATE ) && !groups_members_peer::instance()->is_member($this->group['id'], session::get_user_id()) && !session::has_credential('admin') )
			{
				$this->redirect('/group' . $this->group['id']);
			}

			$this->list = groups_members_peer::instance()->get_members( $this->group['id'] );
			$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 16);
			$this->list = $this->pager->get_list();
		}
	}
}