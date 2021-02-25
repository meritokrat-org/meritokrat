<?

class friends_index_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
        if(!$this->user_id = request::get_int('user'))
        {
            $this->user_id = session::get_user_id();
        }
        
        if(($this->user_id != session::get_user_id())&&(!friends_peer::instance()->is_friend(session::get_user_id(),$this->user_id))&&(!session::has_credential('admin')))
        {
            throw new public_exception( t('У вас недостаточно прав') );
        }
        
        request::get_int('online') ? $this->friends=user_sessions_peer::instance()->friends_online($this->user_id) : $this->friends = friends_peer::instance()->get_by_user($this->user_id);
        
        load::action_helper('pager');
        $this->pager = pager_helper::get_pager($this->friends, request::get_int('page'), 33);
        $this->friends = $this->pager->get_list();
        
		client_helper::register_variable('l_are_you_sure', t('Вы уверены?') );
	}
}