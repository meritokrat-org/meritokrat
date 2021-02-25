<?

load::app('modules/ideas/controller');
class ideas_delete_action extends ideas_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		if ( request::get_int('id') )
		{
			$this->idea_data = ideas_peer::instance()->get_item( request::get_int('id') );
			if ( ( $this->idea_data['user_id'] != session::get_user_id() ) && !session::has_credential('moderator') )
			{
			$this->redirect('/ideas/mine');
			}

			ideas_peer::instance()->delete_item($this->idea_data['id']);

			if ( session::has_credential('moderator') )
			{
				load::model('admin_feed');
				$text = $this->idea_data['text'] . '<br /><br /> Автор: ' .
						user_helper::full_name($this->idea_data['user_id']);

				//admin_feed_peer::instance()->add(session::get_user_id(), admin_feed_peer::TYPE_IDEA, $text);
			}

			$this->redirect('/ideas/mine');
		}
	}
}