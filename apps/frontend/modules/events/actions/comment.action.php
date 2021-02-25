<?

class events_comment_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->disable_layout();

		load::model('events/comments');

		if ( $text = trim(request::get('text')) )
		{
			load::action_helper('text', true);
			$text = text_helper::smart_trim($text, 4048);

			load::model('events/events');
			if ( !$event = events_peer::instance()->get_item(request::get_int('event_id')))
			{
				return;
			}

			load::model('user/blacklist');
			if ( user_blacklist_peer::is_banned( $this->post_data['user_id'], session::get_user_id() ) )
			{
				return;
			}
                        
                        if ( $upd_id = request::get_int('upd_id') )
                        {
                            $comment = events_comments_peer::instance()->get_item($upd_id);
                            $data = array(
                                    'id' => $comment['id'],
                                    'event_id' => $comment['event_id'],
                                    'text' => $text
                            );
                            if($comment['user_id']!=session::get_user_id())
                            {
                                $data['edit'] = session::get_user_id();
                                $data['edit_ts'] = time();
                                /*load::action_helper('user_email', false);
                                user_email_helper::admin_edit(array(
                                    'id' => $comment['id'],
                                    'content_id' => $event['id'],
                                    'user_id' => $comment['user_id'],
                                    'title'=> $event['name'],
                                    'type' => 7
                                ));*/
                                load::model('admin_feed');
				admin_feed_peer::instance()->add(session::get_user_id(), admin_feed_peer::TYPE_EVENT_COMMENT, $text, $comment, request::get_string('why'), 1);
                            }
                            events_comments_peer::instance()->update($data);
                            die(nl2br(stripslashes($text)));
                        }

			$data = array(
				'user_id' => session::get_user_id(),
				'text' => $text,
				'created_ts' => time(),
				'event_id' => request::get_int('event_id'),
				'parent_id' => request::get_int('parent_id')
			);

			$this->id = events_comments_peer::instance()->insert($data);

			if ( $parent_id = request::get_int('parent_id') )
			{
				$this->child_id = $this->id;

				$comment = events_comments_peer::instance()->get_item($parent_id);
				$comment['childs'] .= $this->id . ',';
				events_comments_peer::instance()->update(array(
					'id' => $parent_id,
					'childs' => $comment['childs']
				));
			}

			if ( $post['user_id'] != session::get_user_id() )
			{
				load::action_helper('user_email', false);
				/*user_email_helper::send(
					$post['user_id'],
					session::get_user_id(),
					array(
						'subject' => t('Новый комментарий к записи в блоге'),
						'body' => '%receiver%, ' . t('к Вашей записи в блоге добавили комментарий') . ':' . "\n\n" .
								  '%sender% ' . t('пишет') . ':' . "\n" . $text . "\n\n" .
								  t('Что-бы ответить, перейдите по ссылке:') . "\n" .
								  'http://' . context::get('host') . '/event' . $data['post_id'] . '#comment' . $this->id
					)
				);*/
                                $options = array(
                                        '%text%' => $text,
                                        '%link%' => 'http://' . context::get('host') . '/event' . request::get_int('event_id') . '#comment' . $this->id,
                                        '%settings%'=>'http://'. context::get('host') . '/profile/edit?id='.$comment['user_id'].'&tab=settings'
                                    );
                                user_email_helper::send_sys('events_comment',$event['user_id'],session::get_user_id(),$options);
			}

                        if( $parent_id && ($comment['user_id'] != session::get_user_id()))
                        {
                            $options['%type%'] = t('мысли');
                            user_email_helper::send_sys('comment_comment',$comment['user_id'],session::get_user_id(),$options);
                        }

		}

		load::model('user/user_data');
		load::view_helper('user');
	}
}
