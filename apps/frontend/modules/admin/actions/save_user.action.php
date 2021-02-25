<?

load::app('modules/admin/controller');

class admin_save_user_action extends admin_controller
{
	public function execute()
	{

		load::model('user/user_novasys_data');

		if ( ! request::get_int('ajx')) {
			user_auth_peer::instance()->update(array(
				'id' => request::get_int('user_id'),
				'credentials' => implode(',', (array)request::get('credentials')),
				'interesting' => request::get_bool('interesting', false)
			));
		}
		$user_id = request::get_int('user_id');
		$contacted = request::get_int('contacted');
		$user = user_novasys_data_peer::instance()->get_item($user_id);

		if ( ! $user['user_id']) {
			user_novasys_data_peer::instance()->insert(array(
				'user_id' => $user_id,
				'contacted' => $contacted
			));
		} else {
			user_novasys_data_peer::instance()->update(array(
				'user_id' => $user_id,
				'contacted' => $contacted
			));
		}

		if (request::get_int('schanger')) {
			db_key::i()->set('schanger' . $user_id, request::get_int('schanger'));
		} else {
			db_key::i()->delete('schanger' . $user_id);
		}

		if (request::get_int('sendcontact') && in_array($contacted, array(1, 2, 3, 4, 5, 6, 7, 8, 9, 11, 12, 13, 14, 15))) {
			load::model('user/user_data');
			load::model('user/user_desktop');
			load::model('ppo/ppo');
			load::model('ppo/members');
			$user_data = user_data_peer::instance()->get_item($user_id);

			switch ($contacted) {
				//@todo айдишники заменить на статусы
				case 1:
					$coordinators = array(5); // IAS
					break;

				case 2:
					$coordinators = array(4); // Худолій
					break;

				case 3:
					$coordinators = array(3949); // Стрижко
					break;

				case 4:
					$coordinators = user_desktop_peer::instance()->get_regional_coordinators($user_data['region_id']);
					break;

				case 5:
					$coordinators = user_desktop_peer::instance()->get_raion_coordinators($user_data['city_id']);
					break;

				case 6:
					$coordinators = array(2641); //Чаплига
					break;

				case 7:
					$coordinators = array(1299); //Шульцева
					break;

				case 8:
					$coordinators = array(2); //Коломіець
					break;

				case 11:
					$coordinators = array(2464); //Predstavitel` VNZ
					break;

				case 12:
					$ppo = ppo_peer::instance()->get_by_user_data($user_data, 1);
					if ($ppo) {
						$cid = ppo_members_peer::instance()->get_user_by_function(1, $ppo);
						if ($cid) $coordinators = array($cid); //PPO
					}
					break;
				case 13:
					$ppo = ppo_peer::instance()->get_by_user_data($user_data, 2);
					if ($ppo) {
						$cid = ppo_members_peer::instance()->get_user_by_function(1, $ppo);
						if ($cid) $coordinators = array($cid); //MPO
					}
					break;
				case 14:
					$ppo = ppo_peer::instance()->get_by_user_data($user_data, 3);
					if ($ppo) {
						$cid = ppo_members_peer::instance()->get_user_by_function(1, $ppo);
						if ($cid) $coordinators = array($cid); //RPO
					}
					break;

				case 15:
					$coordinators = array(3949);
					break;

			}
			//die(print_r($coordinators));
			if (count($coordinators) > 0) {
				load::action_helper('user_email', false);
				$options = array(
					'%message%' => '',
					'%name%' => strip_tags(user_helper::full_name($user_id)),
					'%link%' => '<a href="http://' . conf::get('server') . '/profile-' . $user_id . '">http://' . conf::get('server') . '/profile-' . $user_id . '</a>'
				);
				$txt = trim(strip_tags(request::get('contactedtext')));
				if ($txt != '')
					$options['%message%'] = $txt;
				foreach ($coordinators as $coordinator) {
					$options['%coordinator%'] = strip_tags(user_helper::full_name($coordinator));
					/*print_r('sending email....');
					echo "<pre>";
					print_r($options);
												echo "</pre>";*/
					user_email_helper::send_sys('contacts_binding', $coordinator, false, $options);
				}
			}
		}

		if (!request::get_int('ajx')) $this->redirect($_SERVER['HTTP_REFERER']);
		else die('1');
	}
}