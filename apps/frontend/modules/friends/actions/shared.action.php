<?

class friends_shared_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
        $this->user_id = request::get_int('user');
        
        if($this->user_id)
        {
//            if(($this->user_id != session::get_user_id())&&(!friends_peer::instance()->is_friend(session::get_user_id(),$this->user_id)))
//            {
//                throw new public_exception( t('У вас недостаточно прав') );
//            }
            if(request::get_int('online'))
            {
                $list1 = friends_peer::instance()->shared_friends(session::get_user_id(),$this->user_id);
                $list2 = user_sessions_peer::instance()->friends_online($this->user_id);
                $this->friends = array_intersect($list1,$list2);
                $this->total = count($list1);
            }
            else
            {
                $this->friends = friends_peer::instance()->shared_friends(session::get_user_id(),$this->user_id);
                $this->total = count($this->friends);
            }
            if(count($this->friends)>0)
            {
                $this->online = db::get_scalar('SELECT count(user_id) FROM user_sessions WHERE visit_ts>=:visit_ts and user_id in ('.implode(',',$this->friends).')',array('visit_ts'=>time()-600));
            }
            else
            {
                $this->online = 0;
            }
            load::action_helper('pager');
            $this->pager = pager_helper::get_pager($this->friends, request::get_int('page'), 21);
            $this->friends = $this->pager->get_list();
        }
        else
        {
            $this->redirect('/friends/');
        }
	}
}