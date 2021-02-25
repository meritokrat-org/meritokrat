<?

load::app('modules/messages/controller');
class messages_reply_action extends messages_controller
{
	public function execute()
	{
		$this->disable_layout();

                $friends = friends_peer::instance()->get_by_user( session::get_user_id() );
		foreach ( $friends as $friend_id )
		{
			$this->friends[$friend_id] = user_helper::full_name($friend_id, false);
		}

                if (request::get_int('thread_id')) $this->thread = messages_threads_peer::instance()->get_item(request::get_int('thread_id'));
		if ( ( $this->thread['sender_id'] == session::get_user_id() ) || ( $this->thread['receiver_id'] == session::get_user_id() ) )
		{
                        $id=request::get_int('thread_id');
			$this->id = messages_peer::instance()->reply( array(
				'thread_id' => $this->thread['id'],
				'body' => $this->replacefriends(trim(request::get('body'))),
				'sender_id' => session::get_user_id(),
				'receiver_id' => $this->thread['receiver_id'] == session::get_user_id() ? $this->thread['sender_id'] : $this->thread['receiver_id']
			) );
                        $this->thread['receiver_id'] == session::get_user_id() ? $receiver_id=$this->thread['sender_id'] : $receiver_id=$this->thread['receiver_id'];
                }
                elseif(request::get_int('sender_id') && trim(request::get('body')))
		{
                        $receiver_id=request::get_int('sender_id');
                        $this->new_thread_id=$id = messages_peer::instance()->add(array(
					'sender_id' => session::get_user_id(),
					'receiver_id' => $receiver_id,
					'body' => $this->replacefriends(trim(request::get('body')))
				));
                }
			load::action_helper('user_email', false);
			/*user_email_helper::send(
				$receiver_id,
				session::get_user_id(),
				array(
					'subject' => '%sender%: ' . t('Новое сообщение'),
					'body' => '%sender% ' . t('пишет') . ':' . "\n\n" . trim(request::get('body')) . "\n\n" .
							  t('Что-бы ответить, перейдите по ссылке:') . "\n" .
							  'http://' . context::get('host') . '/messages/view?id=' . $id
				)
			);*/
                        $options = array(
                                    '%text%' => tag_helper::get_short(trim(strip_tags(request::get('body'))),50),
                                    '%link%' =>  'http://' . context::get('host') . '/messages/view?id=' . $id
                                    );
                        user_email_helper::send_sys('messages_reply',$receiver_id,session::get_user_id(),$options);
	}

        #the function replacing friends user names with profile links
        private function replacefriends($text)
        {
            if(count($this->friends)>0)
            {
                foreach($this->friends as $k=>$v)
                {
                    $text = str_replace($v, '<a href="http://'.conf::get('server').'/profile-'.$k.'">'.$v.'</a>', $text);
                }
            }
            return $text;
        }
}