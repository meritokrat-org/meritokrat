<?

load::app('modules/polls/controller');
class polls_vote_action extends polls_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		if ( $this->id = request::get_int('poll_id') )
		{
			if ( !polls_votes_peer::instance()->has_voted(request::get_int('poll_id'), session::get_user_id()) )
			{
				$poll = polls_peer::instance()->get_item($this->id);
				if ( $poll['is_multi'] )
				{
					foreach ( (array)request::get('answer') as $answer_id => $flag )
					{
						polls_votes_peer::instance()->vote($answer_id, session::get_user_id());

						$answer = polls_answers_peer::instance()->get_item($answer_id);
						polls_answers_peer::instance()->update(array(
							'id' => $answer['id'],
							'count' => $answer['count'] + 1
						));
					}
				}
				else
				{
					if ( request::get_int('answer') )
					{
						polls_votes_peer::instance()->vote(request::get_int('answer'), session::get_user_id());

                                                db::exec("UPDATE invites SET status=:status
                                                    WHERE obj_id=:poll_id AND to_id=:user_id AND type=:type",
                                                        array(
                                                        'poll_id' => $this->id,
                                                        'status'=> 1,
                                                        'type' => 3,
                                                        'user_id' => session::get_user_id()
                                                ));

						$answer = polls_answers_peer::instance()->get_item(request::get_int('answer'));
						polls_answers_peer::instance()->update(array(
							'id' => $answer['id'],
							'count' => $answer['count'] + 1
						));
					}
				}

				$is_custom = $poll['is_multi'] ? (bool)request::get('answer_custom') : request::get('answer') == 'custom';

				if ( $poll['is_custom'] && $is_custom )
				{
					if ( $text = trim(request::get('custom')) )
					{
				 		polls_votes_peer::instance()->vote_custom($poll['id'], session::get_user_id());

						polls_custom_peer::instance()->insert(array(
							'poll_id' => $poll['id'],
							'user_id' => session::get_user_id(),
							'text' => $text
						));
					}
				}

				polls_peer::instance()->update(array(
					'id' => $poll['id'],
					'count' => $poll['count'] + 1
				));
			}
		}
	}
}