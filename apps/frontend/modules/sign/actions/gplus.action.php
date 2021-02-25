<?

load::model('user/user_visits_log');

class sign_gplus_action extends frontend_controller
{

	public function execute()
	{
		$this->set_renderer('ajax');

		if([request::get_all()][0]["user"])
			session::set("user", [request::get_all()][0]["user"]);

		$user = session::get("user");
		$src = substr($user["image"]["url"], 0, strlen($user["image"]["url"]) - 2)."800";
		$user["image"] = $src;

		$this->json["user"] = $user;
		$email = $user["emails"][0]["value"];

		if(request::get_bool("connect")){
			$contacts = request::get("contacts") ? request::get("contacts") : array();
			$contacts[11] = "https://plus.google.com/".$user["id"];
			$contacts = serialize($contacts);
			$user_id = request::get("user_id");

			$__udata = [
				"user_id" => $user_id,
				"contacts" => $contacts
			];
			$__uauth = [
				"id" => $user_id,
				"gplus" => true
			];

			user_data_peer::instance()->update($__udata);
			user_auth_peer::instance()->update($__uauth);

			if(request::get_bool("upphoto"))
				$this->saveImg($user_id, $src);

			$this->in($email);

			$this->json["status"] = "connected";
		}
		elseif(
			($id = db::get_rows("select id from user_auth where email like '".$email."'"))
			&& (count($id) > 0)
		)
		{
			$exist_user_auth = user_auth_peer::instance()->get_item($id[0]["id"]);
			$exist_user_data = user_data_peer::instance()->get_item($id[0]["id"]);
			if($exist_user_auth["gplus"])
			{
				$this->in($email);
				$this->json["status"] = "authorized";
			}
			else
			{
				$this->json["status"] = "connect";
				$this->json["user_auth"] = $exist_user_auth;
				$this->json["user_exist"] = $exist_user_data;
				$this->json["user_contacts"] = unserialize($exist_user_data["contacts"]);
			}
		}
		else
		{
			$this->json["status"] = "registration";

			$this->registration($user_id, $user);
			$this->saveImg($user_id, $src);

			$this->in($email);
		}
	}

	private function registration(&$user_id, $user, $country_id = ["id" => 1])
	{
		load::action_helper('user_email', false);

		$first_name = trim($user["name"]['givenName']);
		$last_name = trim($user["name"]['familyName']);
		$email = strtolower(trim($user["emails"][0]["value"]));
		$phone = "";
		$password = substr(md5(microtime(true)), 0, 8);

		$this->set_renderer('ajax');

		if ($first_name && $last_name && $email) {
			$status = request::get('eco100') ? 6 : 1;
			load::model('user/user_data');
			$user_id = user_auth_peer::instance()->insert(
				$email,
				$password,
				$status,
				false,
				0,
				0,
				0,
				0,
				0,
				0,
				0,
				(strtotime(date("Y-m-d"))-4*24*60*60),
				true
			);

			$insdata = array(
				'first_name' => $first_name,
				'last_name' => $last_name
			);

			$age = $user["ageRange"]["min"] > 0 ? $user["ageRange"]["min"] : 0;

			user_data_peer::instance()->insert(array_merge($insdata, array(
				'user_id' => $user_id,
				'country_id' => $country_id["id"],
				'gender' => substr($user["gender"], 0, 1),
				'age' => $age
			)));

			if (request::get('language') == 'en')
				user_auth_peer::instance()->update(array('id' => $user_id, "en" => true));
			else
				user_auth_peer::instance()->update(array('id' => $user_id, "ru" => true));

			load::model('user/user_bio');

			user_bio_peer::instance()->insert(array(
				'user_id' => $user_id
			));
			load::model('user/user_work');
			user_work_peer::instance()->insert(array(
				'user_id' => $user_id
			));
			load::model('user/user_education');
			user_education_peer::instance()->insert(array(
				'user_id' => $user_id
			));
			load::model('user/user_desktop');
			user_desktop_peer::instance()->insert(array(
				'user_id' => $user_id
			));

			$contacted = 0;

			load::model('user/user_novasys_data');
			user_novasys_data_peer::instance()->insert(array(
				'user_id' => $user_id,
				'phone' => $phone,
				'email0' => $email,
				'email1' => $email,
				'contacted' => $contacted
			));

			load::model('user/user_shevchenko_data');
			user_shevchenko_data_peer::instance()->insert(array(
				'user_id' => $user_id,
				'shevchenko_id' => 0,
				'fname' => $first_name,
				'sname' => $last_name,
				'country' => $country_id["id"]
			));

//			load::model('mailing');
//
//			mailing_peer::instance()->add_maillists_user($email, $first_name, $last_name, array(116, 101, 102));

//			load::action_helper('user_email', false);
//			$options = array(
//				'%fullname%' => $first_name . " " . $last_name,
//				'%email%' => $email,
//				'%password%' => $password
//			);
//			$options['%inviter%'] = user_helper::full_name($from_user_id, false);
//			user_email_helper::send_sys('registration_list', $id, false, $options);
		}
	}

	private function saveImg($id, $src)
	{
		load::system('storage/storage_simple');
		$storage = new storage_simple();

		$salt = user_data_peer::instance()->regenerate_photo_salt( $id );
		$key = 'profile/' . $id . $salt . '.jpg';

		$storage->save_from_path($key, $src);

		$size = getimagesize($storage->get_path($key));

		$W = $size[0];
		$H = $size[1];

		$width = min($W,$H)*0.75;
		$height = min($W,$H);

		$x = ($W-$width)/2;
		$y = ($H-$height)/2;

		if(db_key::i()->exists('crop_coord_user_'.$id))
			db_key::i()->delete('crop_coord_user_'.$id);

		$crop_key = 'user/' . $id . $salt . '.jpg';

		$storage->prepare_path($storage->get_path($crop_key));

		exec("convert {$storage->get_path($key)} -gravity Center -crop {$width}x{$height}+{$x}+{$y} {$storage->get_path($crop_key)}");

		return $salt;
	}

	private function in($email)
	{
		if ( session::is_authenticated() )
			return false;

		$user = user_auth_peer::instance()->get_by_email($email);

		if ( ! $user['active'] ) {
			user_auth_peer::instance()->activate( $user['id'] );

			$inviter = ($user['recomended_by']) ? $user['recomended_by'] : (($user['invited_by']) ? $user['invited_by'] : 0);

			if($inviter)
				rating_helper::updateRating($inviter, 'status');
		}

		session::set_user_id( $user['id'], explode(',', $user['credentials']) );
		cookie::set('auth', "{$user['email']}|{$user['password']}", time() + 60*60*24*31, '/', '.' . context::get('server'));

		$user_data = user_data_peer::instance()->get_item( $user['id'] );
		session::set( 'language', $user_data['language'] ? $user_data['language'] : 'ua' );

		error_log('SIGNIN: ' . $user['id'] . ' from ip ' . $_SERVER['REMOTE_ADDR']);

		user_visits_log_peer::instance()->insert(array(
			"user_id" => session::get_user_id(),
			"time_out" => date("Y-m-d H:i:s")
		));

		if ( ! $user['ip'] )
			user_auth_peer::instance()->update(array('ip' => $_SERVER['REMOTE_ADDR'], 'id' => $user['id']));
	}
}
