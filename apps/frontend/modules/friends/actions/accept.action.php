<?

class friends_accept_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
        $this->set_renderer('ajax');
		$this->json = array();

		load::model('friends/pending');
		if ( friends_pending_peer::instance()->is_pending(session::get_user_id(), request::get_int('user_id')) )
		{
			friends_pending_peer::instance()->delete(session::get_user_id(), request::get_int('user_id'));
                        if (!db::get_cols("SELECT id  FROM friends WHERE user_id=:user_id AND friend_id=:friend_id", array('friend_id'=>request::get_int('user_id'), 'user_id'=>session::get_user_id())) ) {
                            load::model('friends/friends');
                                    friends_peer::instance()->insert(array(
                                    'user_id' => session::get_user_id(),
                                    'friend_id' => request::get_int('user_id')
                            ));
                        }
                        if (!db::get_cols("SELECT id  FROM friends WHERE user_id=:user_id AND friend_id=:friend_id", array('user_id'=>request::get_int('user_id'), 'friend_id'=>session::get_user_id())) ) {

                            friends_peer::instance()->insert(array(
				'friend_id' => session::get_user_id(),
				'user_id' => request::get_int('user_id')
                            ));
                        }
                        
                        load::model('friends/news');
                        $time=time();
                        friends_news_peer::instance()->insert(array(
                                    'user_id' => session::get_user_id(),
                                    'sent_by' => request::get_int('user_id'),
                                    'created_ts'=>$time
                            ));
                        
		}
        $total = friends_pending_peer::instance()->get_by_user(session::get_user_id());
        if(count($total)==0)
        {
            die('1');
        }
	}
}