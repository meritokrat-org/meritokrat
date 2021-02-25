<?

class profile_share_profile_action extends frontend_controller
{
        protected $authorized_access = true;

        public function execute()
	{
                $this->disable_layout();

                load::model('invites/invites');
                load::model('friends/friends');
                $this->friends = friends_peer::instance()->get_by_user(session::get_user_id());
                $this->users = db::get_cols("SELECT id FROM user_auth WHERE active = TRUE");

               
                load::action_helper('page',false);
                $this->pager = pager_helper::get_pager($this->friends, request::get_int('page'), 12);
                $this->userpager = pager_helper::get_pager($this->users, request::get_int('page'), 12);
                $this->users = $this->userpager->get_list();

                load::view_helper('image');
                $this->item_id = request::get_int('id');
                $this->item_type = request::get_int('typ');
	}
}
