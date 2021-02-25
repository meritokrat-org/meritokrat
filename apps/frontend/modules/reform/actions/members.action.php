<?

load::app('modules/reform/controller');

class reform_members_action extends reform_controller
{
    protected $authorized_access = true;
	public function execute()
	{
		load::model('reform/reform');

		if ( $this->group = reform_peer::instance()->get_item( request::get_int('id') ) )
		{
			if ( ( $this->group['privacy'] == reform_peer::PRIVACY_PRIVATE ) && !reform_members_peer::instance()->is_member($this->group['id'], session::get_user_id()) && !session::has_credential('admin') )
			{
				$this->redirect('/project' . $this->group['id'].'/'. $this->group['number']);
			}

			$this->list = reform_members_peer::instance()->get_members( $this->group['id'],false,$this->group );
			$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 16);
			$this->list = $this->pager->get_list();
		}
	}
}