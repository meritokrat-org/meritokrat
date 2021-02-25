<?

load::app('modules/lists/controller');
class lists_show_users_action extends lists_controller
{
        public function execute()
	{
                $this->disable_layout();
                $this->id = request::get_int('id');
                session::set('list_id',$this->id);

                $this->data = lists_peer::instance()->get_item($this->id);

                $this->in_list = lists_users_peer::instance()->get_users_by_list($this->id);
                $this->viewers = lists_users_peer::instance()->get_users_by_list($this->id,1);
                $this->editors = lists_users_peer::instance()->get_users_by_list($this->id,2);

                $this->users = user_auth_peer::instance()->get_list(array('active' => 1),array(),array('id ASC'));
                $this->users = array_diff($this->users,$this->in_list,$this->viewers,$this->editors);

                load::action_helper('pager', true);
                $this->userpager = pager_helper::get_pager($this->users, request::get_int('page'), 12);
                $this->users = $this->userpager->get_list();
	}
}
