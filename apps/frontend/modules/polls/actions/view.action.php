<?

load::app('modules/polls/controller');
class polls_view_action extends polls_controller
{
	#protected $authorized_access = true;

	public function execute()
	{
		$this->poll_id = request::get_int('id');
		if ( !$this->poll = polls_peer::instance()->get_item(request::get_int('id')) )
		{
			$this->redirect('/polls');
		}

                load::model('invites/invites');
                if(session::get_user_id())$invites = invites_peer::instance()->get_by_id(session::get_user_id(), $this->poll_id, 3);
                if( $this->poll['hidden'] && !$invites && !session::has_credential('admin') )
                {
                    throw new public_exception( t('У вас недостаточно прав') );
                }

		if ( $this->poll['is_custom'] )
		{
			$this->custom_list = polls_custom_peer::instance()->get_by_poll( $this->poll_id );
		}

		load::model('polls/comments');
		$this->comments = polls_comments_peer::instance()->get_by_poll( $this->poll_id );

		client_helper::set_title($this->poll['question']);
	}
}