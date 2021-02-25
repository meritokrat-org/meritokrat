<?
load::app('modules/admin/controller');

class admin_user_fill_profile_action extends admin_controller
{

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		load::model('user/zayava');
		load::model('user/user_auth');
		load::model('user/user_data');

		$zayava = user_zayava_peer::instance()->get_item(request::get_int('zayava_id'));
		if (!$zayava['user_id']) {
			$this->json = array('error' => 'Заява не знайдена');
			return;
		}

		$user = user_auth_peer::instance()->get_item($zayava['user_id']);
		if ($user['id']) {
			$array = array(
				'birthday' => $zayava['birthday'],
				'first_name' => $zayava['firstname'],
				'last_name' => $zayava['lastname'],
				'father_name' => $zayava['fathername'],
				'city_id' => $zayava['city'],
				'region_id' => $zayava['region'],
				'country_id' => $zayava['country'],
				'citizenship' => $zayava['citizenship'],
				'postindex' => $zayava['postindex'],
				'street' => $zayava['street'],
				'house' => $zayava['building'],
				'flat' => $zayava['flat']
			);
			foreach ($array as $k => $v) {
				if (!$v) unset($array[$k]);
			}
			$array['user_id'] = $user['id'];

			user_data_peer::instance()->update($array);
		} else {
			$this->json = array('error' => 'Користувач не знайдений');
		}

	}
}
