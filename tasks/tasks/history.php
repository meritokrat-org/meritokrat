<?

class history_task extends shell_task
{
	public function execute()
	{
		$this->process_users();
		$this->process_parties();
	}

	public function process_users()
	{
		load::model('user/user_auth');
		load::model('user/user_data');
		load::model('user/trust');

		$list = user_auth_peer::instance()->get_by_type( user_auth_peer::TYPE_POLITIC );
		foreach ( $list as $id )
		{
			$user = user_data_peer::instance()->get_item($id);
			$this->out($user['first_name'] . ' ' . $user['last_name']);
			user_trust_peer::instance()->insert(array(
				'user_id' => $id,
				'trust' => $user['trust'],
				'not_trust' => $user['not_trust'],
				'created_ts' => time(),
			));
		}
	}

	public function process_parties()
	{
		load::model('parties/parties');
		load::model('parties/trust');

		$parties = parties_peer::instance()->get_list();
		foreach ( $parties as $id )
		{
			$party = parties_peer::instance()->get_item($id);
			$this->out($party['title']);
			parties_trust_peer::instance()->insert(array(
				'party_id' => $id,
				'trust' => $party['trust'],
				'not_trust' => $party['not_trust'],
				'created_ts' => time(),
			));
		}
	}
}