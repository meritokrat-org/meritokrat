<?

load::app('modules/invites/controller');

class invites_index_action extends invites_controller
{
//	public $types = array(1, 2, 3, 4, 5, 6);
	public $types = array(1, 2, 3, 5, 6);

	public function execute()
	{
		load::model('invites/invites');
		load::model('events/events');
		load::model('groups/groups');
		load::model('ppo/ppo');
		load::model('ppo/members');
		load::model('team/team');
		load::model('team/members');
		load::model('polls/polls');
		load::model('polls/votes');
		load::model('reform/reform');
		load::model('reform/members');

		$type = request::get_int('type');
		if (!$type) {
			$this->type = 0;
			$this->set_template('all');
			$this->list_0 = invites_peer::instance()->get_by_user(session::get_user_id());
		} else {
			$this->type = $type;
		}

		$main = 'list_' . $this->type;

		foreach ($this->types as $t) {
			$name = 'list_' . $t;
			$this->$name = invites_peer::instance()->get_by_user(session::get_user_id(), $t);
			$this->items[$t] = $name;
		}

		load::action_helper('pager');
		$this->pager = pager_helper::get_pager($this->$main, request::get_int('page'), 10);
		$this->$main = $this->pager->get_list();

		$this->clean_old();
	}

	public function clean_old()
	{
		db::exec('DELETE FROM invites WHERE created_ts < ' . (time() - 864000));
	}
}