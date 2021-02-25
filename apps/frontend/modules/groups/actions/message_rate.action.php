<?
class groups_message_rate_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		load::model('groups/topics_messages');
		if ( $message = groups_topics_messages_peer::instance()->get_item( request::get_int('id') ) )
		{
			if ( !groups_topics_messages_peer::instance()->has_rated($message['id'], session::get_user_id()) )
			{
				groups_topics_messages_peer::instance()->update( array(
					'id' => $message['id'],
					'rate' => $message['rate'] + ( request::get_int('positive') ? 1 : -1 )
				) );

				user_data_peer::instance()->update_rate($message['user_id'], request::get_int('positive') ? 0.1 : -0.1, session::get_user_id());

				groups_topics_messages_peer::instance()->rate($message['id'], session::get_user_id());
			}
		}

		$this->set_renderer('ajax');
		$this->json = array();
	}
}