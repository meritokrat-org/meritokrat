<?

load::app('modules/messages/controller');
class messages_mark_read_action extends messages_controller
{
	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		$messages = request::get('messages');

		if ( is_array($messages) && $messages )
		{
			foreach ( $messages as $id )
			{
				$thread = messages_threads_peer::instance()->get_item((int)$id);
				if ( ( $thread['sender_id'] != session::get_user_id() ) && ( $thread['receiver_id'] != session::get_user_id() ) )
				{
					continue;
				}

				$list = messages_peer::instance()->get_by_thread($thread['id'], session::get_user_id());
				foreach ( $list as $m_id )
				{
					$message = messages_peer::instance()->get_item($m_id);

					if ( !$message['is_read'] )
					{
						messages_peer::instance()->update(array(
							'id' => $m_id,
							'is_read' => true
						));
					}
				}
			}
			
			messages_peer::instance()->reset_new_messages(session::get_user_id());
                        mem_cache::i()->delete('user_messages:' . session::get_user_id());
		}
	}
}