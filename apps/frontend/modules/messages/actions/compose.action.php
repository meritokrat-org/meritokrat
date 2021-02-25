<?

load::app('modules/messages/controller');
class messages_compose_action extends messages_controller
{
	public function execute()
	{
            
            $count_messages=db::get_scalar('SELECT count(*) FROM messages WHERE created_ts> '.(time()-24*60*60).' and sender_id='.session::get_user_id().' and owner!='.session::get_user_id());
            $uauth=user_auth_peer::instance()->get_item(session::get_user_id());
            //if (session::get_user_id()==996) echo 'SELECT count(*) FROM messages WHERE created_ts> '.(time()-24*60*60).' and sender_id='.session::get_user_id().' and owner!='.session::get_user_id();
						
						$this->uauth = $uauth;
						
            if(request::get_int('group') > 0)
            {
                $group_id = request::get_int('group');
                load::model('groups/groups');
                $group = groups_peer::instance()->get_item($group_id);
                $this->group_title=$group['title'];
            }
            elseif(request::get_int('region') > 0)
            {
                load::model('geo');
                load::model('user/user_desktop');
                $is_regional_coordinator=user_desktop_peer::instance()->is_regional_coordinator(session::get_user_id());
                $this->region_id=request::get_int('region');
                if(!in_array($this->region_id,$is_regional_coordinator) || !session::has_credential('admin'))
                {
                    throw new public_exception(t('У вас недостаточно прав'));
                    return;
                }
                $this->region_name = geo_peer::instance()->get_region($this->region_id);
                $this->set_template('regionalsend');
            }
            elseif(request::get_int('raion') > 0)
            {
                load::model('geo');
                load::model('user/user_desktop');
                $is_raion_coordinator=user_desktop_peer::instance()->is_raion_coordinator(session::get_user_id());
                $this->city_id=request::get_int('raion');
                if(!in_array($this->city_id,$is_raion_coordinator) || !session::has_credential('admin'))
                {
                    throw new public_exception(t('У вас недостаточно прав'));
                    return;
                }
                $this->city_name = geo_peer::instance()->get_city($this->city_id);
                $this->set_template('regionalsend');
            }
            else
            {
                if ((!session::has_credential('admin') && ($uauth['status']==5 && $count_messages>19) || ($uauth['status']==1 && $count_messages>4)) && ($uauth['id']!=31)) {
                        throw new public_exception(t('Превышен лимит на отправление сообщений за сутки.'));
                        return;
                }
								
                if($uauth['ban'] > 0)
                {
                        if(request::get_int("receiver_id") > 0 && request::get_int("receiver_id") != 10599){
                                throw new public_exception(t('Вы можете отправлять сообщения только Администрации Меритократ.орг.'));
                                return;
                        }
                //									if($count_messages >= 5)
                //									{
                //										throw new public_exception(t('Превышен лимит на отправление сообщений за сутки.'));
                //										return;
                //									}
                }

                $friends = friends_peer::instance()->get_by_user( session::get_user_id() );
                foreach ( $friends as $friend_id )
                {
                        $this->friends[$friend_id] = user_helper::full_name($friend_id, false);
                        $this->friends_names[user_helper::full_name($friend_id, false)] = $friend_id;
                }

                if(request::get_int('resend'))
                {
                        $this->message_data = messages_peer::instance()->get_message(request::get_int('resend'));
                }
                
                if(request::get_int('resend_all'))
                {
                        $this->message_data = messages_peer::instance()->get_thread(request::get_int('resend_all'));
                }
                
                if(request::get_int('resend_history'))
                {
                        $this->message_data = messages_peer::instance()->get_sender(request::get_int('resend_history'));
                }

		client_helper::register_variable('friends', $this->friends);
		client_helper::register_variable('friendsNames', $this->friends_names);

		if ( $this->user = user_data_peer::instance()->get_item(request::get_int('user_id')) )
		{
			client_helper::register_variable('receiverName', $this->user['first_name'] . ' ' . $this->user['last_name']);
		}
            }
            if ( request::get('submit') )
            {
                //чистим от мусора
                load::model('blogs/posts');
                $clean_body = blogs_posts_peer::instance()->clean_text(stripslashes(trim(request::get('body'))));
                
                if( request::get_int('group') )
                {
                    if(request::get_int('mailer')) {
                        $this->sendgroup(request::get_int('group'));
                        if(!db_key::i()->exists('group_mailer_id:'.request::get_int('event')))
                            db_key::i()->set ('group_mailer_id:'.request::get_int('event'), 1);

                    }
                    else
                        $this->sendgroup(request::get_int('group'));
                } 
                elseif(request::get_int("ppo"))
                {
                        load::model("user/user_auth");
                        $user_functions = user_auth_peer::get_functions();
                        foreach(array(111, 112, 113, 121, 122, 123) as $key)
                                if(array_key_exists($key, $user_functions)){
                                        $category = ($key - 110) > 3 ? $key - 120 : $key - 110;
                                        break;
                                } else
                                        $key = null;

                        $ppo_data = array();
                        if($key){
                                load::model("ppo/ppo");
                                $ppo_data = ppo_peer::get_user_ppo(session::get_user_id(), $category);
                        }

                        if(request::get_int("selected") == 1){
                                $this->sendregion(request::get_int($ppo_data["region_id"]));
                        } else {
                                load::model("ppo/members");
                                $members = ppo_members_peer::instance()->get_members(request::get_int("ppo"));
                                foreach($members as $member){
                                        $id = messages_peer::instance()->add(array(
                                                'sender_id' => session::get_user_id(),
                                                'receiver_id' => $member,
                                                'body' => $this->replacefriends(trim(request::get('body')))
                                        ), true, 0);

                                }
                        }
                }
                elseif(request::get_int('region_id') && request::get('body'))
                {
                   $this->sendregion(request::get_int('region_id'));
                }
                elseif(request::get_int('city_id') && request::get('body'))
                {
                   $this->sendraion(request::get_int('city_id'));
                }
                else
                {
                    if ( request::get_string('receiver_id') && trim(request::get('body')) )
                    {
                        load::action_helper('user_email', false);
                        (request::get_int('sender_id') and session::has_credential('admin'))  ? $sender_id=request::get_int('sender_id') : $sender_id=session::get_user_id();

                        $receivers = explode(',',request::get_string('receiver_id'));

                        foreach($receivers as $receiver_id)
                        {
                            if(!intval($receiver_id))
                                continue;

                            $id = messages_peer::instance()->add(array(
                                    'sender_id' => $sender_id,
                                    'receiver_id' => $receiver_id,
                                    'body' => $this->replacefriends(trim(request::get('body')))
                            ),true,0);

                            load::action_helper('user_email', false);
                            /*user_email_helper::send(
                                    request::get_int('receiver_id'),
                                    $sender_id,
                                    array(
                                            'subject' => t('Новое сообщение'),
                                            'body' => '%sender% ' . t('пишет') . ':' . "\n\n" . trim(request::get('body')) . "\n\n" .
                                                              t('Что-бы ответить, перейдите по ссылке:') . "\n" .
                                                              'http://' . context::get('host') . '/messages/view?id=' . $id
                                    )
                            );*/
                            $options = array(
                                        '%text%' => tag_helper::get_short(trim(strip_tags(request::get('body'))),120),
                                        '%link%' => 'http://' . context::get('host') . '/messages/view?id=' . $id,
                                        '%settings%'=>'http://'. context::get('host') . '/profile/edit?id='.$receiver_id.'&tab=settings'
                                        );
                            user_email_helper::send_sys('messages_compose',$receiver_id,$sender_id,$options);
                        }
                    }
                }
                $this->set_renderer('ajax');
                $this->json = array();
            }
	}

        private function sendgroup($group_id)
        {
            load::model('internal_mailing');

            (request::get_int('sender_id') and session::has_credential('admin'))  ? $sender_id=request::get_int('sender_id') : $sender_id=session::get_user_id();

            $insert_data = array(
                                    'sender_id'=>$sender_id,
                                    'filters'=>'group:'.$group_id,
                                    'body'=>  $this->replacefriends(trim(request::get('body'))),
                                    'active'=>0,
                                    'count'=>0,
                                    'sended'=>0
                                );
            $id = internal_mailing_peer::instance()->insert($insert_data);
        }

        private function sendregion($region_id)
        {
            load::model('internal_mailing');

            (request::get_int('sender_id') and session::has_credential('admin'))  ? $sender_id=request::get_int('sender_id') : $sender_id=session::get_user_id();

            $insert_data = array(
                                    'sender_id'=>$sender_id,
                                    'filters'=>'region:'.$region_id,
                                    'body'=>  $this->replacefriends(trim(request::get('body'))),
                                    'active'=>0,
                                    'count'=>0,
                                    'sended'=>0
                                );
            $id = internal_mailing_peer::instance()->insert($insert_data);
        }

        private function sendraion($city_id)
        {
            load::model('internal_mailing');

            (request::get_int('sender_id') and session::has_credential('admin'))  ? $sender_id=request::get_int('sender_id') : $sender_id=session::get_user_id();

            $insert_data = array(
                                    'sender_id'=>$sender_id,
                                    'filters'=>'district:'.$city_id,
                                    'body'=>  $this->replacefriends(trim(request::get('body'))),
                                    'active'=>0,
                                    'count'=>0,
                                    'sended'=>0
                                );
            $id = internal_mailing_peer::instance()->insert($insert_data);
        }

        #the function replacing friends user names with profile links
        private function replacefriends($text)
        {
            if(count($this->friends)>0)
            {
                foreach($this->friends as $k=>$v)
                {
                    $text = str_replace($v, '<a href="http://'.conf::get('server').'/profile-'.$k.'">'.$v.'</a>', $text);
                }
            }
            return $text;
        }
}
