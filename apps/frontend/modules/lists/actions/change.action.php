<?

load::app('modules/lists/controller');

class lists_change_action extends lists_controller
{
	public function execute()
	{
		$id = request::get_int('id');
		$title = htmlspecialchars(trim(request::get_string('title')));
		$in_team = request::get_bool('in_team');

		if ($title == '') {
			die();
		}
		if ($id) {
			lists_peer::instance()->update(array(
				'id' => $id,
				'title' => $title,
				'in_team' => $in_team
			));
			die();
		} else {
			$this->set_layout('');
			$this->set_template('item');
			$this->item['id'] = lists_peer::instance()->insert(array(
				'title' => $title,
				'user_id' => session::get_user_id(),
				'in_team' => $in_team
			));
			$this->item['title'] = $title;
			$this->item['user_id'] = session::get_user_id();
			$this->item['in_team'] = $in_team;
		}
	}
}