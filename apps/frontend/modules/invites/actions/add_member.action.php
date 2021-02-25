<?
load::app('modules/invites/controller');

class invites_add_member_action extends invites_controller
{
	public function execute()
	{
		$this->set_renderer('ajax');
		$this->friends = request::get('fr');
		$this->from = session::get_user_id();
		$this->id = request::get_int('user_id');
		$this->message = request::get_string('message');

		if (count($this->friends) > 0) {
			load::model('groups/groups');
			load::model('groups/members');

			foreach ($this->friends as $friend_id) {
				if (!groups_members_peer::instance()->is_member($friend_id, $this->id)) {
					groups_members_peer::instance()->add($friend_id, $this->id, 2);
					groups_peer::instance()->update_rate($friend_id, 1, $this->id);
				}

			}
		}
		die();
	}
}