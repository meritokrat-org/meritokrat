<?

load::app('modules/polls/controller');
class polls_edit_action extends polls_controller
{
	protected $authorized_access = true;
        protected $credentials = array('moderator');

	public function execute()
	{
                $id = request::get_int('id');
                if(!$id)die('error');

                $this->why = request::get_string('why');

                $this->poll = polls_peer::instance()->get_item($id);
		$this->answers = polls_answers_peer::instance()->get_by_poll($id);
                $this->rkey="public_polls_view_".request::get_int('id');

		if ( request::get('submit') )
		{
                        if(request::get_int('public')==1)
                            db_key::i()->set($this->rkey, 1);
                        else db_key::i()->delete ($this->rkey);
			if ( $question = trim(request::get_string('question')) )
			{  
                                        $data = $this->poll;
                                        $data['question'] = $question;
                                        $data['is_multi'] = request::get_bool('is_multi');
                                        $data['is_custom'] = request::get_bool('is_custom');
                                        $data['hidden'] = request::get_int('hidden');
                                        $data['nocomments'] = request::get_int('nocomments');

                                        if($data['user_id']!=session::get_user_id())
                                        {
                                            $data['edit'] = session::get_user_id();
                                            $data['edit_ts'] = time();
                                            load::model('admin_feed');
                                            admin_feed_peer::instance()->add(session::get_user_id(), admin_feed_peer::TYPE_POLL, $text, $this->poll, request::get_string('why'), 1);
                                        }

                                        polls_peer::instance()->update($data);

/*
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
                                                $data = $this->poll;
                                                $data['question'] = $question;
                                                $data['is_multi'] = request::get_bool('is_multi');
                                                $data['is_custom'] = request::get_bool('is_custom');
                                                $data['hidden'] = request::get_int('hidden');

                                                if($data['user_id']!=session::get_user_id())
                                                {
                                                    $data['edit'] = session::get_user_id();
                                                    $data['edit_ts'] = time();
                                                    load::model('admin_feed');
                                                    admin_feed_peer::instance()->add(session::get_user_id(), admin_feed_peer::TYPE_POLL, $text, $this->poll, request::get_string('why'), 1);
                                                }

						polls_peer::instance()->update($data);

						$answers = array_reverse($answers);
                                                $count = array_reverse(request::get('answer_count'));
                                                polls_answers_peer::instance()->delete_by_poll($id);
                                                
						foreach ( $answers as $k => $answer )
						{
                                                        if(!$count[$k])
                                                            $count[$k] = 0;
							$data = array(
								'poll_id' => $id,
								'answer' => $answer,
                                                                'count' => $count[$k]
							);

							polls_answers_peer::instance()->insert($data);
						}
					}
				}
 * 
 */
			}

			$this->set_renderer('ajax');
			$this->json = array();
		}
	}
}