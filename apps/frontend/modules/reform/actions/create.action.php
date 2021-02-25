<?

load::app('modules/reform/controller');

class reform_create_action extends reform_controller
{
	protected $authorized_access = true;

	public function execute()
	{

		$this->user_data = user_data_peer::instance()->get_item(session::get_user_id());
		$this->user_auth = user_auth_peer::instance()->get_item(session::get_user_id());
		if (request::get('submit') && $this->allow_create) {
			$this->set_renderer('ajax');
			$this->disable_layout();
			$title = trim(strip_tags(request::get('title')));
			if ($title) {
				session::has_credential('admin') ? $active = 1 : $active = 0;
				if (request::get_int('category') == 1) {
					$rcount = db::get_scalar("SELECT count(*)
                                    FROM reform
                                    WHERE category=1 AND region_id=:region_id AND active=1",
						array("region_id" => (int)request::get_int('region_id')));
					$number = $rcount + 1;
				} else
					$number = 1;

				$scount = db::get_scalar("SELECT MAX(svidnum)
                                    FROM reform
                                    WHERE active=1");
				$snumber = $scount + 1;
				session::has_credential('admin') ? $snumber = $snumber : $snumber = 0;
				$id = reform_peer::instance()->insert(array(
					'title' => $title,
					'number' => $number,
					'created_ts' => time(),
					'creator_id' => session::get_user_id(),
					'aims' => htmlspecialchars(request::get_string('aims')),
					'description' => htmlspecialchars(request::get_string('description')),
					'category' => request::get_int('category', 1),
					'teritory' => request::get('teritory'),
					'active' => (int)$active,
					'privacy' => request::get_int('privacy', 1),
					'type' => 0,
					'ptype' => request::get_int('ptype'),
					'location' => htmlspecialchars(request::get_string('location')),
					'adres' => htmlspecialchars(request::get_string('adres')),
					'glava_id' => request::get_int('glavaid'),
					'secretar_id' => request::get_int('secretarid'),
					'dzbori' => strtotime(str_replace('/', '-', request::get('dzbori'))),
					'region_id' => (int)request::get_int('region_id', 0),
					'city_id' => (int)request::get_int('city_id', 0),
					'map_lat' => request::get('map_lat'),
					'map_lon' => request::get('map_lon'),
					'map_zoom' => (int)request::get_int('map_zoom'),
					'svidnum' => $snumber,
					'coords' => request::get('coords')
				));
				parent::update_geo($id);
				if (!$id) $id = db::exec("SELECT id FROM reform WHERE title=:title and number=:number", array('title' => $title, 'number' => $number,));

				if (request::get_int('glavaid') > 0) {
					reform_members_peer::instance()->add($id, request::get_int('glavaid'));
					reform_members_peer::instance()->set_function($id, request::get_int('glavaid'), 1);
					reform_members_history_peer::instance()->set_function($id, request::get_int('glavaid'), 1);
				}

				if (request::get_int('secretarid') > 0) {
					reform_members_peer::instance()->add($id, request::get_int('secretarid'));
					reform_members_peer::instance()->set_function($id, request::get_int('secretarid'), 2);
					reform_members_history_peer::instance()->set_function($id, request::get_int('secretarid'), 2);
				}

				load::action_helper('user_email', false);
				$this->group = reform_peer::instance()->get_item($id);
				$options = array(
					'%title%' => $title,
					'%link%' => 'http://' . context::get('host') . '/project' . $this->group['id'] . '/' . $this->group['number'] . '/'
				);

				/*user_email_helper::send_sys('ppo_leadership', request::get_int('glavaid'), 31,
					array_merge($options, array("%posada%" => t('Главы'),
						"%member_name%" => strip_tags(user_helper::full_name(request::get_int('glavaid'))))));

				user_email_helper::send_sys('ppo_leadership', request::get_int('secretarid'), 31,
					array_merge($options, array("%posada%" => t('Ответственного секретаря'),
						"%member_name%" => strip_tags(user_helper::full_name(request::get_int('secretarid'))))));*/

				if (session::has_credential('admin')) {
					$this->json = array('id' => $id, 'success' => 0);
				} else {
					/*foreach (user_auth_peer::get_admins() as $receiver)
						user_email_helper::send_sys('reform_create', $receiver, session::get_user_id(), $options);*/
					$this->json = array('id' => $id, 'success' => 1);
				}
			}
		}
	}
}
