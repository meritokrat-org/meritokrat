<?

load::app('modules/polls/controller');
class polls_create_action extends polls_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->user_data = user_data_peer::instance()->get_item( session::get_user_id() );
		$this->allow_create =  user_auth_peer::get_rights(session::get_user_id(),20);
		//$this->allow_create = true;
                
		if ( request::get('submit') )
		{
			if ( $question = trim(request::get_string('question')) )
			{
				if ( request::get('answer') )
				{
					foreach ( request::get('answer') as $answer )
					{
						if ( $answer = trim($answer) )
						{
							$answers[] = $answer;
						}
					}

					if ( $answers )
					{
						$data = array(
							'user_id' => session::get_user_id(),
							'created_ts' => time(),
							'question' => $question,
							'is_multi' => request::get_bool('is_multi'),
							'is_custom' => request::get_bool('is_custom'),
                                                        'hidden' => request::get_int('hidden'),
                                                        'nocomments' => request::get_int('nocomments')
						);

						$poll_id = polls_peer::instance()->insert($data);

						load::model('feed/feed');
						load::view_helper('tag', true);

						ob_start();
						include dirname(__FILE__) . '/../../feed/views/partials/items/poll.php';
						$feed_html = ob_get_clean();

						$readers = friends_peer::instance()->get_by_user(session::get_user_id());
						feed_peer::instance()->add(session::get_user_id(), $readers, array(
							'actor' => session::get_user_id(),
							'text' => $feed_html,
							'action' => feed_peer::ACTION_POLL,
							'section' => feed_peer::SECTION_PERSONAL,
						));

						$answers = array_reverse($answers);
						foreach ( $answers as $answer )
						{
							$data = array(
								'poll_id' => $poll_id,
								'answer' => $answer
							);

							polls_answers_peer::instance()->insert($data);
						}
					}
				}
			}
                        $this->rkey="public_polls_view_".$poll_id;
                        if(request::get_int('public')==1)
                            db_key::i()->set($this->rkey, 1);
                        else db_key::i()->delete ($this->rkey);
			$this->set_renderer('ajax');
			$this->json = array();
		}
	}
}