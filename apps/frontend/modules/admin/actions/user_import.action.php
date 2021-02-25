<?

class admin_user_import_action extends basic_controller
{
	public function execute()
	{

		load::model('user/user_auth');
		if (request::get('submit')) {
			load::action_helper('user_email', false);
			$first_name = trim(request::get('first_name'));
			$last_name = trim(request::get('last_name'));
			$email = strtolower(trim(request::get('email')));
			request::get_int('type') > 1 ? $type = request::get_int('type') : $type = 1;
			$password = trim(request::get_string('password', substr(md5(microtime(true)), 0, 8)));

			$segment = request::get_int('segment', null);

			if ($segment < 1 or $segment > 28) $segment = null;

			if (user_auth_peer::instance()->get_by_email($email)) die('користувач з таким email вже існує-errors_here-');//throw new public_exception('Помилка, користувач з таким email вже існуе <a href="'.$_SERVER['HTTP_REFERER'].'">назад</a>');
			elseif ($first_name && $last_name && $email) {

				load::model('user/user_data');
				$id = user_auth_peer::instance()->insert(
					$email,
					$password,
					$type,
					false,
					$shevchenko,
					0,
					request::get_int('from', 1)
				);

				$user = user_auth_peer::instance()->get_item($id);
				$region_id = $_GET['region_id'];
				if ($region_id > 0) $region_id = $region_id + 1;

				$_REQUEST['birthday'] == '' ? $birthday = NULL : $birthday = request::get_string('birthday', "''");
				user_data_peer::instance()->insert(array(
					'user_id' => $user['id'],
					'first_name' => $first_name,
					'last_name' => $last_name,
					'father_name' => request::get_string('father_name'),
					'gender' => request::get('gender', 'm'),
					'additional_segment' => request::get_int('additional_segment', null),
					'segment' => $segment,
					'birthday' => $birthday,
					'location' => request::get_string('location', null),
					'city_id' => request::get_int('city_id', 0),
					'region_id' => request::get_int('region_id', 0),
					'party_city_id' => request::get_int('city_id', 0),
					'party_region_id' => request::get_int('region_id', 0),
					'phone' => request::get_string('phone'),
					'home_phone' => request::get_string('home_phone'),
					'work_phone' => request::get_string('work_phone'),
					'language' => request::get_string('lang'),
					'mobile' => request::get_string('mobile'),
					'country_id' => request::get_int('country_id', '1')
				));

				// Тут нужно отправлять письмо-приглашение на меритократ от Игоря Шевченко
				$options = array(
					'%fullname%' => $first_name . " " . $last_name,
					'%email%' => $email,
					'%password%' => $password);
				user_email_helper::send_sys('users_create_shevchenko', $user['id'], false, $options);

				load::model('user/user_bio');
				user_bio_peer::instance()->insert(array(
					'user_id' => $user['id']
				));
				load::model('user/user_work');
				user_work_peer::instance()->insert(array(
					'user_id' => $user['id']
				));
				load::model('user/user_education');
				user_education_peer::instance()->insert(array(
					'user_id' => $user['id']
				));
				load::model('user/user_desktop');
				user_desktop_peer::instance()->insert(array(
					'user_id' => $user['id']
				));
				load::model('mailing');
				mailing_peer::instance()->add_maillists_user($email, $first_name, $last_name, array(116, 101, 102));


				load::model('user/user_novasys_data');
				$ns_user = user_novasys_data_peer::instance()->get_item($user_id);
				if ($ns_user['user_id']) {
					user_novasys_data_peer::instance()->insert(array(
						'user_id' => $user['id'],
						'email0' => $email
					));
				}


				load::model('user/user_shevchenko_data');
				$shev_user = user_novasys_data_peer::instance()->get_item($user_id);
				if ($shev_user['user_id']) {
					user_shevchenko_data_peer::instance()->insert(array(
						'user_id' => $user['id'],
						'shevchenko_id' => 0,
						'fname' => $first_name,
						'sname' => $last_name,
						'about' => request::get_string('about')
					));
				}


				//$sql_q="UPDATE user_shevchenko_data SET user_id=:user_id WHERE email=:email";
				//db::exec($sql_q, array('user_id'=>$user['id'],'email'=>$email)) or mail('andimov@gmail.com', 'import_action string 90', $sql_q);

				//if ( request::get('photo')!='')  db::exec("INSERT INTO user_temp_photos VALUES ('".$user['id']."', '".request::get('photo')."')");
				if (request::get_int('shev_id')) $this->redirect('http://shevchenko.ua/csv/update_import_id.php?shev_id=' . request::get_int('shev_id') . '&id=' . $user['id']);
				elseif (request::get_int('from') == 3) $this->redirect('/profile-' . $id);
				die(/*conf::get('server').*/
					'/sign/autologin?email=' . $email . '&password=' . $password . '-created_id-' . $user['id']);
			}
			die('не вистачає данних-errors_here-');
		}
	}
}
