<?
load::app('modules/invites/controller');

class invites_edit_action extends invites_controller
{
	public function execute()
	{
		$this->set_renderer('ajax');
		$this->arr = array(
			'',
			'event',
			'group',
			'ppo',
			'projects',
            'team'
		);

		//status: 1-agree, 2-disagree, 3-not sure

		$this->status = request::get_int('status');
		if (session::get_user_id()) $this->to_id = session::get_user_id();
		elseif (request::get_int('user')) $this->to_id = request::get_int('user');
		$this->id = request::get_int('id');

		if (!$this->status || !$this->id || !$this->to_id) {
			return;
		}

		$item = invites_peer::instance()->get_item($this->id);
		if (!$item['to_id'] || !$item['obj_id'] || !$item['type'])
			$this->redirect('/events');

		db::exec('UPDATE invites SET status = ' . $this->status . ' WHERE
                    to_id = ' . $item['to_id'] . ' AND obj_id = ' . $item['obj_id'] . ' AND type = ' . $item['type']);


		if ($this->status == 2) {
			return;
		}

		if (!$item['id']) {
			return;
		}

		switch ($item['type']) {
			case '1':
				load::model('events/members');
				if (!events_members_peer::instance()->is_member($item['obj_id'], $this->to_id))
					events_members_peer::instance()->insert(array(
						'event_id' => $item['obj_id'],
						'user_id' => $this->to_id,
						'status' => $this->status,
						'leads' => request::get_int('leads')
					));

				break;

			case '2':
				load::model('groups/members');
				if (!groups_members_peer::instance()->is_member($item['obj_id'], $this->to_id))
					groups_members_peer::instance()->add($item['obj_id'], $this->to_id);

				break;

			case '4':
				load::model('ppo/members');
				if (!ppo_members_peer::instance()->is_member($item['obj_id'], $this->to_id))
					ppo_members_peer::instance()->add($item['obj_id'], $this->to_id);

				break;

			case '5':
				load::model('team/members');
				if (!team_members_peer::instance()->is_member($item['obj_id'], $this->to_id))
					team_members_peer::instance()->add($item['obj_id'], $this->to_id);

                break;
                
            case '6':
				load::model('reform/members');
				if ( ! reform_members_peer::instance()->is_member($item['obj_id'], $this->to_id))
                    reform_members_peer::instance()->add($item['obj_id'], $this->to_id);

				break;

            case '3':
                //load::model('polls/polls');
                //$this->data = polls_peer::instance()->get_item($this->id);

                break;
        }

        if (request::get('commit')) {
            $this->redirect('/' . $this->arr[$item['type']] . $item['obj_id']);
        }

    }
}