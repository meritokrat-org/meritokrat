<?

load::app('modules/invites/controller');

class invites_add_action extends invites_controller
{
	public function execute()
	{
		$this->set_renderer('ajax');
		$this->friends = request::get('fr');
		$this->type = request::get_int('item_type');
		$this->from = session::get_user_id();
		$this->id = request::get_int('item_id');
		$this->message = request::get_string('message');
		if (count($this->friends) > 0 || request::get_int('all')) {
			db_key::i()->set('invite_' . $this->type . '_' . $this->id, $this->message);

			load::view_helper('image');
			switch ($this->type) {
				case '1':
					load::model('events/events');
					$this->data = events_peer::instance()->get_item($this->id);
					$this->friends = invites_peer::instance()->get_users(request::get('stype'), $this->friends);
					if (request::get_int('all')) {
						load::model('groups/members');
						$this->friends = groups_members_peer::instance()->get_members(request::get_int('all'));
					}

					$this->name = strip_tags(user_helper::full_name(session::get_user_id()), "<a>");
					$this->title = $this->data['name'] . ' ( Дата: ' . date("d.m.Y H:i", $this->data['start']) . ' ' . t('Адрес') . ': ' . $this->data['adress'] . ')';
					$this->profile = 'http://' . conf::get('server') . '/event' . $this->id;
					$this->image = image_helper::photo($this->data['photo'], 's', 'events', array('align' => 'left', 'hspace' => '10', 'vspace' => '5'));

					break;

				case '2':
					load::model('groups/groups');
					load::view_helper('group');
					$this->data = groups_peer::instance()->get_item($this->id);
					$this->friends = invites_peer::instance()->get_users(request::get('stype'), $this->friends);
					$this->name = strip_tags(user_helper::full_name(session::get_user_id()), "<a>");
					$this->title = $this->data['title'];
					$this->profile = 'http://' . conf::get('server') . '/group' . $this->id;
					$this->image = group_helper::photo($this->id, 's', false, array('align' => 'left', 'hspace' => '10', 'vspace' => '5'));

					break;

				case '3':
					load::model('polls/polls');
					$this->data = polls_peer::instance()->get_item($this->id);
					$this->friends = invites_peer::instance()->get_users(request::get('stype'), $this->friends);
					$this->name = strip_tags(user_helper::full_name(session::get_user_id()), "<a>");
					$this->title = $this->data['question'];
					$this->profile = 'http://' . conf::get('server') . '/poll' . $this->id;
					$this->image = '';
                    
					break;

				case '4':
					load::model('ppo/ppo');
					$this->data = ppo_peer::instance()->get_item($this->id);
					$this->friends = invites_peer::instance()->get_users(request::get('stype'), $this->friends);
					$this->name = strip_tags(user_helper::full_name(session::get_user_id()), "<a>");
					$this->title = $this->data['title'];
					$this->profile = 'http://' . conf::get('server') . '/ppo' . $this->id . '/' . $this->number;
					$this->image = user_helper::ppo_photo(user_helper::ppo_photo_path($this->id, 'p', $this->data['photo_salt']));
					break;

                case '5':
                    load::model('team/team');
                    $this->data = team_peer::instance()->get_item($this->id);
                    $this->friends = invites_peer::instance()->get_users(request::get('stype'), $this->friends);
                    $this->name = strip_tags(user_helper::full_name(session::get_user_id()), "<a>");
                    $this->title = $this->data['title'];
                    $this->profile = 'http://' . conf::get('server') . '/team' . $this->id . '/' . $this->number;
                    $this->image = user_helper::team_photo(user_helper::team_photo_path($this->id, 'p', $this->data['photo_salt']));
                    break;
                
				case '6':
					load::model('reform/reform');
					$this->data = reform_peer::instance()->get_item($this->id);
					$this->friends = invites_peer::instance()->get_users(request::get('stype'), $this->friends);
					$this->name = strip_tags(user_helper::full_name(session::get_user_id()), "<a>");
					$this->title = $this->data['title'];
					$this->profile = 'http://' . conf::get('server') . '/projects' . $this->id . '/' . $this->number;
					$this->image = user_helper::reform_photo(user_helper::reform_photo_path($this->id, 'p', $this->data['photo_salt']));
					break;
			}

            /*ob_start();
			include dirname(__FILE__) . '/../views/partials/item_' . $this->type . '.php';
			$this->html = ob_get_clean();*/

			load::action_helper('user_email', false);
			$options = array(
				'%name%' => $this->name,
				'%title%' => $this->title,
				'%image%' => $this->image,
				'%profile%' => $this->profile,
				'%message%' => $this->message
			);

			$templates = array(
                1 => 'invites_add_event',
                2 => 'invites_add_group',
                3 => 'invites_add_poll',
                4 => 'invites_add_ppo',
                5 => 'invites_add_team',
                6 => 'invites_add_projects'
			);

			foreach ($this->friends as $friend_id) {
				$id = invites_peer::instance()->add($friend_id, array(
					'from_id' => $this->from,
					'obj_id' => $this->id,
					'type' => $this->type
				));

				/*$this->link = 'http://'.conf::get('server').'/invites/edit?commit=1&user='.$friend_id.'&id='.$id.'&status=1';
				$this->html = str_replace(
						array('%link%','%message%'),
						array($this->link,$this->message),
						$this->html);
				user_email_helper::send(
						$friend_id,
						session::get_user_id(),
						array(
							'subject' => '%sender%:' . t('Приглашение'),
							'body' => $this->html
						),
						true
				);*/

				$options['%link%'] = 'http://' . conf::get('server') . '/invites/edit?commit=1&user=' . $friend_id . '&id=' . $id . '&status=1';
				//user_email_helper::send_sys($templates[$this->type],$friend_id,session::get_user_id(),$options);
				if ($this->type == 4) {
					$usr = user_auth_peer::instance()->get_item($friend_id);
					$options = array('%off_name%' => user_helper::full_name($friend_id, false, array(), false),
						'%profile_id%' => $friend_id);
					if ($usr['offline'] > 0) user_email_helper::send_sys('invite_you_offline', $usr['offline'], session::get_user_id(), $options);
				}
			}
		}
	}
}