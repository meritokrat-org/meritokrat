<?

load::app('modules/messages/controller');
class messages_delete_action extends messages_controller
{
	public function execute()
	{
		$this->thread = messages_threads_peer::instance()->get_item(request::get_int('id'));
		if ( ( $this->thread['sender_id'] != session::get_user_id() ) && ( $this->thread['receiver_id'] != session::get_user_id() ) )
		{
			$this->redirect('/messages');
		}

		messages_peer::instance()->delete_by_thread($this->thread['id'], session::get_user_id());

		if ( request::get('spam') )
		{
                    load::action_helper('user_email', false);

                    $spammer = $this->thread['sender_id'] == session::get_user_id() ? $this->thread['receiver_id'] : $this->thread['sender_id'];
                    $last_message=db::get_row('SELECT body FROM  messages WHERE id=(SELECT MAX(id) as id FROM  messages WHERE thread_id='.$this->thread['id'].' AND receiver_id = '.session::get_user_id().'  LIMIT 1)');
                    foreach ( user_auth_peer::get_admins() as $user_id )
                    {
                        $options = array(
                                '%date%' => date('d/m/Y H:i'),
                                '%fullname%' => strip_tags(user_helper::full_name(session::get_user_id()),'<a>'),
                                '%name%' => strip_tags(user_helper::full_name($spammer),'<a>'),
                                '%text%' => strip_tags(html_entity_decode($last_message['body'])),
                                '%settings%'=>'http://'. context::get('host') . '/profile/edit?id='.$user_id.'&tab=settings'
                        );
                        user_email_helper::send_sys('messages_spam',$user_id,$sender_id,$options);
                    }

		}

		$this->redirect('/messages');
	}
}