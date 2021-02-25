<?

load::app('modules/ideas/controller');
class ideas_view_action extends ideas_controller
{
	public function execute()
	{
		$id = request::get_int('id');
		if($id == 38)
		{
			db_key::i()->set('idea.readed.'.session::get_user_id(), 1);
		}
		
		if ( !$this->idea = ideas_peer::instance()->get_item($id) )
		{
			$this->redirect('/ideas');
		}

		if ( !session::get('idea_viewed_' . request::get_int('id')) )
		{
			ideas_peer::instance()->update(array('views' => $this->idea['views'] + 1, 'id' => request::get_int('id')));
			session::set('idea_viewed_' . request::get_int('id'), true);
		}
		$this->comments = ideas_comments_peer::instance()->get_by_idea( $this->idea['id'] );
	}
}