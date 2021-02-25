<?

load::app('modules/messages/controller');
class messages_bulk_delete_action extends messages_controller
{
	public function execute()
	{
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

				messages_peer::instance()->delete_by_thread($thread['id'], session::get_user_id());
			}
		}

		$this->set_renderer('ajax');
		$this->json = array();
	}
}