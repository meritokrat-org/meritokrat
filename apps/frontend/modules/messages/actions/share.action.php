<?

load::app('modules/messages/controller');
class messages_share_action extends messages_controller
{
	public function execute()
	{
            load::view_helper('tag', true);
                $this->friends = friends_peer::instance()->get_by_user(session::get_user_id());
                if ($group_id=str_replace(array("https://meritokrat.org/group"),array(""),$_SERVER['HTTP_REFERER']))
                        {
                            load::model('groups/members');
                            $this->groupmembers=groups_members_peer::instance()->get_members(intval($group_id));
                            $this->friends=array_diff( $this->friends, $this->groupmembers);
                        }
		$this->disable_layout();

		/*$this->icons = array(
			'blog_post' => 'blogs',
			'debate' => 'debates',
			'poll' => 'polls',
			'idea' => 'ideas',
			'group' => 'groups',
			'party' => 'parties',
		);*/

		$this->types = array(
			'blog_post' => t('Запись в блоге'),
			'debate' => t('Дебаты'),
			'poll' => t('Опрос'),
			'idea' => t('Идея'),
			'group' => t('Группа'),
			'party' => t('Партия'),
                        'event' => t('Событие'),
		);

		$this->type = request::get('type');

		if ( !$this->types[$this->type] )
		{
			return;
		}

		switch ( $this->type )
		{
			case 'blog_post':
				load::model('blogs/posts');
				$this->data = blogs_posts_peer::instance()->get_item(request::get_int('id'));
				break;

			case 'debate':
				load::model('debates/debates');
				$this->data = debates_peer::instance()->get_item(request::get_int('id'));
				break;

			case 'poll':
				load::model('polls/polls');
				$this->data = polls_peer::instance()->get_item(request::get_int('id'));
				break;

			case 'idea':
				load::model('ideas/ideas');
				$this->data = ideas_peer::instance()->get_item(request::get_int('id'));
				break;

			case 'party':
				load::view_helper('party');
				load::model('parties/parties');
				$this->data = parties_peer::instance()->get_item(request::get_int('id'));
				break;

			case 'group':
				load::view_helper('group');
				load::model('groups/groups');
				$this->data = groups_peer::instance()->get_item(request::get_int('id'));

                                $this->users = db::get_cols("SELECT id FROM user_auth WHERE active=:active AND id NOT IN (SELECT user_id FROM groups_members WHERE group_id=:group_id)",array('active'=>1,'group_id'=>request::get_int('id')));
                                
                                load::action_helper('pager', true);
                                $this->pager = pager_helper::get_pager($this->friends, request::get_int('page'), 12);
                                $this->userpager = pager_helper::get_pager($this->users, request::get_int('page'), 12);
                                $this->users = $this->userpager->get_list();

				break;

                         case 'event':
				load::model('events/events');
                                $this->data = events_peer::instance()->get_item(request::get_int('id'));
                                $this->users = db::get_cols("SELECT id FROM user_auth WHERE active=:active AND id NOT IN (SELECT user_id FROM events2users WHERE event_id=:obj_id)",array('active'=>1,'obj_id'=>request::get_int('id')));

                                load::action_helper('pager', true);
                                $this->pager = pager_helper::get_pager($this->friends, request::get_int('page'), 12);
                                $this->userpager = pager_helper::get_pager($this->users, request::get_int('page'), 12);
                                $this->users = $this->userpager->get_list();

				break;
		}

                if(request::get_int('typ'))
                {
                    $this->headers = array(
			'group' => t('Пригласить в сообщество'),
			'event' => t('Пригласить на событие'),
                    );
                    $this->header = $this->headers[$this->type];
                    $this->messages = array(
			'group' => 'Доброго дня, запрошую Вас приєднатися до спільноти.',
			'event' => 'Доброго дня, запрошую Вас вiдвiдати подiю.',
                    );
                    $this->message = $this->messages[$this->type];

                    if($this->type == 'event' && session::has_credential('admin'))
                    {
                        load::model('geo');
                        $this->regions = geo_peer::instance()->get_regions(1);

                        load::view_helper('group');
                        load::model('groups/groups');
                        $this->groups = groups_peer::instance()->get_list();

                        load::model('user/user_auth');
                        $this->statuses = user_auth_peer::get_types();
                    }

                    load::view_helper('image');
                    $this->item_id = request::get_int('id');
                    $this->item_type = request::get_int('typ');
                    $this->set_template('invite');
                }
                else
                {
//                    if ( $this->data['user_id'] == session::get_user_id() and $this->type!='group' )
//                    {
//                            $this->error = t('Свой контент рекомендовать нельзя');
//                            return;
//                    }

                    if ( mem_cache::i()->get('share_user=' . session::get_user_id()) and $this->type!='group' and !session::has_credential('admin'))
                    {
                            $this->error = t('Рекомендовать контент можно не чаще, чем раз в час');
                            return;
                    }

                   /* if (!user_auth_peer::get_rights(session::get_user_id(),10))*/ mem_cache::i()->set('share_user=' . session::get_user_id(), true, 60*60);
                }
                ob_start();
                include dirname(__FILE__) . '/../views/partials/share/' . $this->type . '.php';
                $this->html = ob_get_clean();
                if ( $selected_friends = request::get('friends'))
                {
                        if ( $selected_friends = array_intersect($selected_friends, $this->friends)  or $this->type=='group' )
                        {

                                load::model('messages/messages');
                                load::action_helper('user_email', false);

                                if ( !$body = trim(request::get('message')) )
                                {
                                        $this->type=='group' ? $body = t('Здравствуйте, приглашаю вас присоединиться к сообществу') : $body = t('Привет, хочу поделиться с тобой полезной информацией') . ':';
                                }
                                if ($this->type=='group')
                                {
                                    $body.= ': '.stripslashes($this->data['title']) . "\n\n" . t('Чтобы принять приглашение, перейдите по ссылке') . ':';
                                    $selected_friends = request::get('friends');
                                }

                                foreach ( $selected_friends as $friend_id )
                                {
                                        $id = messages_peer::instance()->add(array(
                                                'sender_id' => session::get_user_id(),
                                                'receiver_id' => $friend_id,
                                                'body' => $body,
                                                'attached' => $this->html
                                        ), false);

                                        /*user_email_helper::send(
                                                $friend_id,
                                                session::get_user_id(),
                                                array(
                                                        'subject' => '%sender%:' . t('Новое сообщение'),
                                                        'body' => '%sender% ' . t('пишет') . ':' . "\n\n" . $body . "\n\n" ./* $this->html . "\n\n" .*/
                                                                          /*t('Что-бы ответить, перейдите по ссылке:') . "\n" .
                                                                          'http://' . context::get('host') . '/messages/view?id=' . $id
                                                )
                                        );*/
                                        $options = array(
                                                    '%text%' => $body,
                                                    '%link%' =>  'http://' . context::get('host') . '/messages/view?id=' . $id
                                                    );
                                        user_email_helper::send_sys('messages_share',$friend_id,session::get_user_id(),$options);
                                }
                        }

                        $this->set_renderer('ajax');
                        $this->json = $selected_friends;
                    }
	}
}
