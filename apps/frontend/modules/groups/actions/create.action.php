<?

load::app('modules/groups/controller');

class groups_create_action extends groups_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->user_data = user_data_peer::instance()->get_item(session::get_user_id());
		$this->user_auth = user_auth_peer::instance()->get_item(session::get_user_id());
		//if (session::has_credential('admin') or $this->user_auth['type']==1 or $this->user_auth['type']==4) $this->allow_create_group = true;
		$this->allow_create_group = user_auth_peer::instance()->get_rights(session::get_user_id(), 10);

		if (request::get('submit') && $this->allow_create_group) {
			if (request::get_int('category') == 2) {
				$title = user_helper::get_program_types(request::get_int('type', 1));
			} else {
				$title = trim(strip_tags(request::get('title')));
			}
			if ($title) {
				request::get_int('category') == 4 ? $hidden = 1 : $hidden = 0;
				session::has_credential('admin') ? $active = 1 : $active = 0;
				(session::has_credential('admin') && request::get_int('private')) ? $private = true : $private = false;

				$id = groups_peer::instance()->insert(array(
					'title' => $title,
					'created_ts' => time(),
					'creator_id' => session::get_user_id(),
					'user_id' => request::get_int("glavaid"),
					'aims' => htmlspecialchars(request::get_string('aims')),
					'description' => htmlspecialchars(request::get_string('description')),
					'category' => request::get_int('category'),
					'teritory' => 1,
					'active' => $active,
					'hidden' => request::get_int('hidden', $hidden),
					'privacy' => request::get_int('privacy', 1),
					'private' => $private,
					'level' => 0,
					'type' => 0
				));
				if (request::get_int('category') == 2) {
					groups_peer::instance()->update(array(
						'id' => $id,
						'type' => request::get_int('type', 1)
					));
				} elseif (request::get_int('category') == 3) {
					groups_peer::instance()->update(array(
						'id' => $id,
						'level' => request::get_int('level', 0)
					));
				}
				groups_members_peer::instance()->add($id, session::get_user_id());

				if (request::get_bool("create_project"))
					$this->create_project($title, request::get_string('aims'), $id);

				if (session::has_credential('admin')) {
//					groups_peer::instance()->add_moderator($id, session::get_user_id());
					$this->redirect('/group' . $id);
				} else {
					load::action_helper('user_email', false);
					//$receivers=array(2,5,4,7,9,29);
					//$receivers=array(29);
					foreach (user_auth_peer::get_admins() as $receiver) {
						/*user_email_helper::send($receiver,
								session::get_user_id(),
								array(
										'subject' => '%sender%'.t(' предлагает новое сообщество'),
										'body' => 'Надійшла пропозиція щодо створення нової спільноти ' .
												' "' . $title . '"' . "\n" .
											'Щоб переглянути, перейдіть за посиланням:' . ": " .
												'http://' . context::get('host') . '/group' . $id
								)
						);*/
						$options = array(
							'%title%' => $title,
							'%link%' => 'http://' . context::get('host') . '/group' . $id,
							'%settings%' => 'http://' . context::get('host') . '/profile/edit?id=' . $receiver . '&tab=settings'
						);
						user_email_helper::send_sys('groups_create', $receiver, session::get_user_id(), $options);
					}

					$this->succes = 1;
				}
			} else $this->redirect('/groups/create');
		}
	}

	private function create_project($title, $descr, $groupId)
	{
		load::model('reform/reform');
		load::model('reform/members');
		load::model('reform/members_history');

		if ($title) {
			$number = 1;

			$scount = db::get_scalar("SELECT MAX(svidnum) FROM reform WHERE active = 1");

			$snumber = $scount + 1;
			session::has_credential('admin') ? $snumber = $snumber : $snumber = 0;

			$id = reform_peer::instance()->insert(array(
				'title' => $title,
				'description' => $descr,
				'number' => $number,
				'created_ts' => time(),
				'creator_id' => session::get_user_id(),
				'category' => 3,
				'active' => 1,
				'type' => 0,
				'adres' => htmlspecialchars(request::get_string('adres')),
				'glava_id' => session::get_user_id(),
				'dzbori' => strtotime(str_replace('/', '-', request::get('dzbori'))),
				'region_id' => 0,
				'city_id' => 0,
				'svidnum' => $snumber
			));

			if (!$id)
				$id = db::exec("SELECT id FROM reform WHERE title=:title and number=:number", array('title' => $title, 'number' => $number,));

			db::exec("INSERT INTO reform_groups_dependence (group_id, project_id) VALUES (:group_id, :project_id)", array("group_id" => $groupId, "project_id" => $id));

			reform_members_peer::instance()->add($id, session::get_user_id());
			reform_members_peer::instance()->set_function($id, session::get_user_id(), 1);
			reform_members_history_peer::instance()->set_function($id, session::get_user_id(), 1);
		}
	}
}
