<?

load::app('modules/ideas/controller');
class ideas_comment_action extends ideas_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->disable_layout();

		if ( $text = trim(request::get('text')) )
		{
			load::action_helper('text', true);
			$text = text_helper::smart_trim($text, 4048);

			if ( !$idea = ideas_peer::instance()->get_item(request::get_int('idea_id')) )
			{
				return;
			}

                        if ( $upd_id = request::get_int('upd_id') )
                        {
                            $comment = ideas_comments_peer::instance()->get_item($upd_id);
                            $data = array(
                                    'id' => $comment['id'],
                                    'idea_id' => $comment['idea_id'],
                                    'text' => $text
                            );
                            if($comment['user_id']!=session::get_user_id())
                            {
                                $data['edit'] = session::get_user_id();
                                $data['edit_ts'] = time();
                                /*load::action_helper('user_email', false);
                                user_email_helper::admin_edit(array(
                                    'id' => $comment['id'],
                                    'content_id' => $idea['id'],
                                    'user_id' => $comment['user_id'],
                                    'title'=> $idea['title'],
                                    'type' => 6
                                ));*/
                                load::model('admin_feed');
				admin_feed_peer::instance()->add(session::get_user_id(), admin_feed_peer::TYPE_IDEA_COMMENT, $text, $comment, request::get_string('why'), 1);
                            }
                            ideas_comments_peer::instance()->update($data);
                            die(nl2br(stripslashes($text)));
                        }
                        else
                        {
                            $data = array(
                                    'user_id' => session::get_user_id(),
                                    'text' => $text,
                                    'created_ts' => time(),
                                    'idea_id' => request::get_int('idea_id'),
                                    'parent_id' => request::get_int('parent_id')
                            );

                            $idea_data = ideas_peer::instance()->get_item(request::get_int('idea_id'));
                            user_data_peer::instance()->update_rate($idea_data['user_id'], 0.1);

                            $this->id = ideas_comments_peer::instance()->insert($data);
                            ideas_comments_peer::instance()->rate($this->id, session::get_user_id());

                            if ( $parent_id = request::get_int('parent_id') )
                            {
                                    $this->child_id = $this->id;

                                    $comment = ideas_comments_peer::instance()->get_item($parent_id);
                                    $comment['childs'] .= $this->id . ',';
                                    ideas_comments_peer::instance()->update(array(
                                            'id' => $parent_id,
                                            'childs' => $comment['childs']
                                    ));
                            }

                            if ( $idea['user_id'] != session::get_user_id() )
                            {
                                    load::action_helper('user_email', false);
                                    /*user_email_helper::send(
                                            $idea['user_id'],
                                            session::get_user_id(),
                                            array(
                                                    'subject' => t('Новый комментарий к Вашей идее'),
                                                    'body' => '%receiver%, ' . t('к Вашей идее добавили комментарий') . ':' . "\n\n" .
                                                                      '%sender% ' . t('пишет') . ':' . "\n" . $text . "\n\n" .
                                                                      t('Что-бы ответить, перейдите по ссылке:') . "\n" .
                                                                      'http://' . context::get('host') . '/idea' . $data['idea_id'] . '#comment' . $this->id
                                            )
                                    );*/
                                    $options = array(
                                            '%text%' => $text,
                                            '%link%' =>  'http://' . context::get('host') . '/idea' . $data['idea_id'] . '#comment' . $this->id
                                        );
                                    user_email_helper::send_sys('ideas_comment',$idea['user_id'],session::get_user_id(),$options);
                            }
                        }
		}

		load::model('user/user_data');
		load::view_helper('user');
	}
}