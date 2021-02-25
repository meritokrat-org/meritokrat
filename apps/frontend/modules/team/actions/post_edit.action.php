<?

load::app('modules/team/controller');

class team_post_edit_action extends team_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		client_helper::set_title(t('Мысли') . ' ' . t('Команды') . ' | ' . conf::get('project_name'));

		load::model('team/team');
		load::model('team/members');
		load::model('blogs/posts');

		if (request::get_int('id')) {
			$this->post_data = blogs_posts_peer::instance()->get_item(request::get_int('id'));
			$this->why = request::get_string('why');


			/*
                        //@todo: доработать права
                        if ( ( $this->post_data['user_id'] != session::get_user_id() ) && !session::has_credential('moderator') && !ppo_peer::instance()->is_moderator($this->post_data['group_id'], session::get_user_id()))
			{
                            throw new public_exception(t('У вас недостаточно прав'));
                        }
                         * */
		}

		request::get_int('team_id') ? $team_id = request::get_int('team_id') : $team_id = $this->post_data['team_id'];

		//if (session::get_user_id()==996) var_dump(team_members_peer::instance()->is_member(request::get_int('group_id'), session::get_user_id() )).var_dump(team_peer::instance()->is_moderator(request::get_int('group_id'), session::get_user_id()));
		if (team_peer::instance()->is_moderator($team_id, session::get_user_id()) || (user_auth_peer::instance()->get_rights(session::get_user_id(), 10) && team_members_peer::instance()->is_member($team_id, session::get_user_id()))) {

			if (in_array($team_id, array(199, 200, 201, 202, 203)) && !user_auth_peer::instance()->get_rights(session::get_user_id(), 15)) throw new public_exception(t('У вас недостаточно прав'));

			$this->group = team_peer::instance()->get_item(($team_id));
			if (($this->group['privacy'] == team_peer::PRIVACY_PRIVATE) && !team_members_peer::instance()->is_member($this->group['id'], session::get_user_id()) && !session::has_credential('admin')) {
				$this->redirect('/group' . $this->group['id']);
			}

			if (request::get('submit')) {
				if (trim(request::get('body')) && trim(request::get('title')) && trim(request::get('team_id'))) {

//					$tags = blogs_tags_peer::instance()->string_to_array(mb_strtolower(request::get('tags')));

					$clean_text = blogs_posts_peer::instance()->clean_text(stripslashes(request::get('body')));
					$render_text = blogs_posts_peer::instance()->namize_text($clean_text, $named_users);

					load::action_helper('text', true);
					$data = array(
						'title' => mb_substr(trim(request::get('title')), 0, 256),
						'body' => $clean_text,
						'text_rendered' => $render_text,
						'preview' => nl2br(text_helper::smart_trim(strip_tags($clean_text), 256)),
//						'tags_text' => implode(', ', $tags),
						'public' => session::has_credential('editor'),
						//'type' => request::get_int('type'),
						'team_id' => request::get_int('team_id')
					);

					if (session::has_credential('admin') && request::get_int('views') > 1) {
						$data['views'] = request::get_int('views');
					}

					if (!$this->post_data) {
						$data['created_ts'] = time();
						$data['sort_ts'] = time();
						$data['user_id'] = session::get_user_id();

						$post_id = blogs_posts_peer::instance()->insert($data, false, blogs_posts_peer::TYPE_GROUP_POST);
						blogs_posts_peer::instance()->rate($post_id, session::get_user_id());

						load::model('feed/feed');
						load::view_helper('tag', true);

						ob_start();
						include dirname(__FILE__) . '/../../feed/views/partials/items/blog_post.php';
						$feed_html = ob_get_clean();

						/*
						load::model('bookmarks/bookmarks');

						$readers = array_merge(
								friends_peer::instance()->get_by_user(session::get_user_id()),
								bookmarks_peer::instance()->get_users_who_like(session::get_user_id(),6));

						feed_peer::instance()->add(session::get_user_id(), $readers, array(
								'actor' => session::get_user_id(),
								'text' => $feed_html,
								'action' => feed_peer::ACTION_BLOG_POST,
								'section' => feed_peer::SECTION_PERSONAL,
						));*/
					} else {
						$post_id = $data['id'] = $this->post_data['id'];

						if ($this->post_data['user_id'] != session::get_user_id()) {
							$data['edit'] = session::get_user_id();
							/*load::action_helper('user_email', false);
							user_email_helper::admin_edit(array(
								'user_id' => $this->post_data['user_id'],
								'moderator' => session::get_user_id(),
								'link' => 'http://'.conf::get('server').'/blogpost'.$this->post_data['id'],
								'title'=> $this->post_data['title'],
								'type' => t('пост')
							));*/
							load::model('admin_feed');
							admin_feed_peer::instance()->add(session::get_user_id(), admin_feed_peer::TYPE_BLOG_POST, $text, $this->post_data, request::get_string('why'), 1);
						}

						if (session::has_credential('admin')) $data['edit'] = $this->post_data['user_id'];
						blogs_posts_peer::instance()->update($data);
						blogs_posts_tags_peer::instance()->delete_by_post($post_id);
					}

					$this->rkey = "public_team_post_" . $post_id;
					if (request::get_int('public') == 1)
						db_key::i()->set($this->rkey, 1);
					else db_key::i()->delete($this->rkey);

					//$mentions = (array)request::get('mentioned');
					//$mentions = array_unique(array_merge($mentions, $named_users));
					//blogs_mentions_peer::instance()->save_mentions($post_id, $mentions);

//					foreach ($tags as $tag) {
//						$tag = mb_substr($tag, 0, 48);
//
//						$tag_id = blogs_tags_peer::instance()->obtain_id($tag);
//						blogs_posts_tags_peer::instance()->insert(array(
//							'post_id' => $post_id,
//							'tag_id' => $tag_id
//						));
//					}

					$this->redirect('/team/post?team_id=' . $this->group['id'] . '&id=' . $post_id);
				} else $this->warning = 1;
			} else $this->rkey = "public_team_post_" . request::get_int('id');

		} else throw new public_exception(t('У вас недостаточно прав'));
	}
}
