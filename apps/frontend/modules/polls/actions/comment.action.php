<?

load::app('modules/polls/controller');
class polls_comment_action extends polls_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->disable_layout();

		load::model('polls/comments');

		if ( $text = trim(request::get('text')) )
		{
			load::action_helper('text', true);
			$text = text_helper::smart_trim($text, 4048);

			load::model('blogs/posts');
			if ( !$poll_data = polls_peer::instance()->get_item(request::get_int('poll_id')) )
			{
				return;
			}

			if ( $upd_id = request::get_int('upd_id') )
                        {
                            $comment = polls_comments_peer::instance()->get_item($upd_id);
                            $data = array(
                                    'id' => $comment['id'],
                                    'poll_id' => $comment['poll_id'],
                                    'text' => $text
                            );
                            if($comment['user_id']!=session::get_user_id())
                            {
                                $data['edit'] = session::get_user_id();
                                $data['edit_ts'] = time();
                                /*load::action_helper('user_email', false);
                                user_email_helper::admin_edit(array(
                                    'id' => $comment['id'],
                                    'content_id' => $poll_data['id'],
                                    'user_id' => $comment['user_id'],
                                    'title'=> $poll_data['question'],
                                    'type' => 5
                                ));*/
                                load::model('admin_feed');
				admin_feed_peer::instance()->add(session::get_user_id(), admin_feed_peer::TYPE_POLL_COMMENT, $text, $comment, request::get_string('why'), 1);
                            }
                            polls_comments_peer::instance()->update($data);
                            die(nl2br(stripslashes($text)));
                        }
                        else
                        {
                            $data = array(
                                    'user_id' => session::get_user_id(),
                                    'text' => $text,
                                    'created_ts' => time(),
                                    'poll_id' => request::get_int('poll_id'),
                                    'parent_id' => request::get_int('parent_id')
                            );

                            if ( $poll_data['user_id'] != session::get_user_id() )
                            {
                                    user_data_peer::instance()->update_rate($poll_data['user_id'], 0.1);
                            }

                            $this->id = polls_comments_peer::instance()->insert($data);
                            polls_comments_peer::instance()->rate($this->id, session::get_user_id());

                            if ( $parent_id = request::get_int('parent_id') )
                            {
                                    $this->child_id = $this->id;

                                    $comment = polls_comments_peer::instance()->get_item($parent_id);
                                    $comment['childs'] .= $this->id . ',';
                                    polls_comments_peer::instance()->update(array(
                                            'id' => $parent_id,
                                            'childs' => $comment['childs']
                                    ));
                            }

                            if ( $poll_data['user_id'] != session::get_user_id() )
                            {
                                    load::action_helper('user_email', false);
                                    /*user_email_helper::send(
                                            $poll['user_id'],
                                            session::get_user_id(),
                                            array(
                                                    'subject' => t('Новый комментарий к опросу'),
                                                    'body' => '%receiver%, ' . t('к Вашему опросу добавили комментарий') . ':' . "\n\n" .
                                                                      '%sender% ' . t('пишет') . ':' . "\n" . $text . "\n\n" .
                                                                      t('Что-бы ответить, перейдите по ссылке:') . "\n" .
                                                                      'http://' . context::get('host') . '/poll' . $data['poll_id'] . '#comment' . $this->id
                                            )
                                    );*/
                                    $options = array(
                                                '%text%' => $text,
                                                '%link%' => 'http://' . context::get('host') . '/poll' . $data['poll_id'] . '#comment' . $this->id,
                                                '%settings%'=>'http://'. context::get('host') . '/profile/edit?id='.$poll_data['user_id'].'&tab=settings'
                                                );
                                    user_email_helper::send_sys('polls_comment',$poll_data['user_id'],session::get_user_id(),$options);
                            }

                            if( $parent_id && ($comment['user_id'] != session::get_user_id()))
                            {
                                $options['%type%'] = t('мысли');
                                user_email_helper::send_sys('comment_comment',$comment['user_id'],session::get_user_id(),$options);
                            }
                        }
		}

		load::model('user/user_data');
		load::view_helper('user');
	}
}