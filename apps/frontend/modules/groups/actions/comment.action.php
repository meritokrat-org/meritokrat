<?

class groups_comment_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->disable_layout();

		load::model('blogs/comments');

		if ( $text = trim(request::get('text')) )
		{
			load::action_helper('text', true);
			$text = text_helper::smart_trim($text, 4048);

			load::model('blogs/posts');
			if ( !$post = blogs_posts_peer::instance()->get_item(request::get_int('post_id')) )
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
                            $comment = blogs_comments_peer::instance()->get_item($upd_id);
                            $data = array(
                                    'id' => $comment['id'],
                                    'post_id' => $comment['post_id'],
                                    'text' => $text
                            );
                            if($comment['user_id']!=session::get_user_id())
                            {
                                $data['edit'] = session::get_user_id();
                                $data['edit_ts'] = time();
                                //load::action_helper('user_email', false);
                                /*user_email_helper::admin_edit(array(
                                    'id' => $comment['id'],
                                    'content_id' => $comment['post_id'],
                                    'user_id' => $comment['user_id'],
                                    'title'=> $post['title'],
                                    'type' => 1
                                ));*/
                                load::model('admin_feed');
				admin_feed_peer::instance()->add(session::get_user_id(), admin_feed_peer::TYPE_BLOG_COMMENT, $text, $comment, request::get_string('why'), 1);
                            }
                            blogs_comments_peer::instance()->update($data);
                            die(nl2br(stripslashes($text)));
                        }
                        else
                        {
                            $data = array(
                                    'user_id' => session::get_user_id(),
                                    'text' => $text,
                                    'created_ts' => time(),
                                    'post_id' => request::get_int('post_id'),
                                    'parent_id' => request::get_int('parent_id')
                            );

                            $post_data = blogs_posts_peer::instance()->get_item(request::get_int('post_id'));

                            if ( $post_data['user_id'] != session::get_user_id() )
                            {
                                    user_data_peer::instance()->update_rate($post_data['user_id'], 0.1);
                            }

                            $this->id = blogs_comments_peer::instance()->insert($data);
                            blogs_comments_peer::instance()->rate($this->id, session::get_user_id());

                            if(request::get('neg_msg')==1) {
                                if ( $post_data = blogs_posts_peer::instance()->get_item( request::get_int('post_id') ) )
                                {
                                        if (!blogs_posts_peer::instance()->has_rated($post_data['id'], session::get_user_id()) )
                                        {
                                                blogs_posts_peer::instance()->update( array(
                                                        'id' => $post_data['id'],
                                                        'for' => $post_data['for'],
                                                        'against' => $post_data['against'] + 1
                                                ) );

                                                user_data_peer::instance()->update_rate($post_data['user_id'], -1, session::get_user_id());
                                                blogs_posts_peer::instance()->rate($post_data['id'], session::get_user_id());

                                                load::model('rate_history');
                                                rate_history_peer::instance()->insert(array(
                                                        'type' => rate_history_peer::TYPE_BLOG_POST,
                                                        'object_id' => $post_data['id'],
                                                        'user_id' => session::get_user_id(),
                                                        'rate' => '-1'
                                                ));
                                        }
                                }
                                if(!db_key::i()->exists('negative_group_blog_comment:'.$this->id))
                                    db_key::i()->set('negative_group_blog_comment:'.$this->id, 1);
                            }

                            if ( $parent_id = request::get_int('parent_id') )
                            {
                                    $this->child_id = $this->id;
                                    $this->ajaxaction = 1;

                                    $comment = blogs_comments_peer::instance()->get_item($parent_id);
                                    $comment['childs'] .= $this->id . ',';
                                    blogs_comments_peer::instance()->update(array(
                                            'id' => $parent_id,
                                            'childs' => $comment['childs']
                                    ));
                            }

                            load::action_helper('user_email', false);
                            $post_data=blogs_posts_peer::instance()->get_item($data['post_id']);
                            $options = array(
                                '%text%' => $text,
                                '%postlink%' => '<a href="http://' . context::get('host') . '/blogpost' . $data['post_id'] . '">'.$post_data['title'].'</a>',
                                '%link%' => 'http://' . context::get('host') . '/blogpost' . $data['post_id'] . '#comment' . $this->id,
                                '%settings%'=>'http://'. context::get('host') . '/profile/edit?id='.$post['user_id'].'&tab=settings'
                                
                            );
                            
                            if ( $post['user_id'] != session::get_user_id() )
                            {
                                    
                                    /*user_email_helper::send(
                                            $post['user_id'],
                                            session::get_user_id(),
                                            array(
                                                    'subject' => t('Новый комментарий к записи в блоге'),
                                                    'body' => '%receiver%, ' . t('к Вашей записи в блоге добавили комментарий') . ':' . "\n\n" .
                                                                      '%sender% ' . t('пишет') . ':' . "\n" . $text . "\n\n" .
                                                                      t('Что-бы ответить, перейдите по ссылке:') . "\n" .
                                                                      'http://' . context::get('host') . '/blogpost' . $data['post_id'] . '#comment' . $this->id
                                            )
                                    );*/
                                    
                                    user_email_helper::send_sys('blogs_comment',$post['user_id'],session::get_user_id(),$options);
                            }

                            if( $parent_id && ($comment['user_id'] != session::get_user_id()))
                            {
                                $options['%type%'] = t('мысли');
                                user_email_helper::send_sys('comment_comment',$comment['user_id'],session::get_user_id(),$options);
                            }

                            load::view_helper('tag', true);

                            ob_start();
                            include dirname(__FILE__) . '/../../feed/views/partials/items/blog_post_comment.php';
                            $feed_html = ob_get_clean();

                            $readers = bookmarks_peer::instance()->get_by_object(bookmarks_peer::TYPE_BLOG_POST, $post['id']);
                            $readers[] = $post['user_id'];
                            $readers = array_unique(array_diff($readers, array(session::get_user_id())));
                            feed_peer::instance()->add(session::get_user_id(), $readers, array(
                                    'actor' => session::get_user_id(),
                                    'text' => $feed_html,
                                    'action' => feed_peer::ACTION_BLOG_POST_COMMENT,
                                    'section' => feed_peer::SECTION_DISCUSSIONS,
                            ));
                    }
                }
		load::model('user/user_data');
		load::view_helper('user');
	}
}
