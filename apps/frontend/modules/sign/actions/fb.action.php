<?

load::model('user/user_visits_log');

class sign_fb_action extends frontend_controller
{

	public function execute()
	{
		$this->set_renderer('ajax');

		if(request::get("valid"))
		{
			if([request::get_all()][0]["user"])
				session::set("user", [request::get_all()][0]["user"]);

			$user = session::get("user");
			$src = "http://graph.facebook.com/".$user["id"]."/picture?height=1500";
			$user["image"] = $src;

			$this->json["user"] = $user;

			if(request::get_bool("connect")){
				$this->json["status"] = "connected";

				$contacts = unserialize(request::get("contacts", array()));
				$contacts[3] = "http://facebook.com/".$user["id"];
				$contacts = serialize($contacts);
				$user_id = request::get("user_id");

				$__udata = [
					"user_id" => $user_id,
					"contacts" => $contacts
				];
				$__uauth = [
					"id" => $user_id,
					"fb" => true
				];

				user_data_peer::instance()->update($__udata);
				user_auth_peer::instance()->update($__uauth);

				if(request::get_bool("upphoto"))
					$this->saveImg($user_id, $src);

				$this->in($user["email"]);
			}
			elseif(
				($id = db::get_rows("select id from user_auth where email like '".$user["email"]."'"))
				&& (count($id) > 0)
			)
			{
				$exist_user_auth = user_auth_peer::instance()->get_item($id[0]["id"]);
				$exist_user_data = user_data_peer::instance()->get_item($id[0]["id"]);

				if ($exist_user_auth["fb"]) {
					$this->in($user["email"]);
					$this->json["status"] = "authorized";
				} else {
					$this->json["status"] = "connect";
					$this->json["user_exist"] = $exist_user_data;
					$this->json["user_contacts"] = unserialize($exist_user_data["contacts"]);
				}
			}
			else
			{
				$this->json["status"] = "registration";
				$this->json["user"] = session::get("user");

				$location = request::get("location");
				load::model('geo');

				if(request::get("location") == "none")
				{
					$country_id = ["id" => 1];
					$city = ["id" => 0, "region_id" => 0];
				}
				else
				{
					$city = db::get_row("select id, region_id from districts where name_en like '%".$location["city"]."%'");
					$country_id = db::get_row("select id from countries where name_en like '".$location["country"]."'");
				}

				if($country_id["id"] < 1)
					$country_id = ["id" => 1];

				$this->registration($user_id, $user, $country_id, $city);
				$this->saveImg($user_id, $src);

				$this->in($user["email"]);
			}
		}
	}

	private function registration(&$user_id, $user, $country_id = ["id" => 1], $city = ["id" => 0, "region_id" => 0])
	{
		load::action_helper('user_email', false);

		$first_name = trim($user['first_name']);
		$last_name = trim($user['last_name']);
		$email = strtolower(trim($user['email']));
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
				false,
				true
			);

			$insdata = array(
				'first_name' => $user["first_name"],
				'last_name' => $user["last_name"]
			);

			list($month, $day, $year) = explode("/", $user["birthday"]);

			if($day >= 1)
				$insdata["birthday"] = "$year-$month-$day";

			$contacts[3] = "http://facebook.com/".$this->json["user"]["id"];

			if($city["id"] > 0){
				$insdata["country_id"] = $country_id["id"];
				$insdata["region_id"] = $city["region_id"];
				$insdata["city_id"] = $city["id"];
			}

			user_data_peer::instance()->insert(array_merge($insdata, array(
				'user_id' => $user_id,
				'gender' => substr($user["gender"], 0, 1),
				"contacts" => serialize($contacts)
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

			if($city["id"] > 0){
				$shevchenko["country"] = $country_id["id"];
				$shevchenko["region"] = $city["region_id"];
				$shevchenko["district"] = $city["id"];
			}

			load::model('user/user_shevchenko_data');
			user_shevchenko_data_peer::instance()->insert(array_merge($shevchenko, array(
				'user_id' => $user_id,
				'shevchenko_id' => 0,
				'fname' => $first_name,
				'sname' => $last_name
			)));
		}
	}

	private function saveImg($id, $src)
	{
		load::system('storage/storage_simple');
		$storage = new storage_simple();

		$salt = user_data_peer::instance()->regenerate_photo_salt( $id, "new_" );
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
