<?

class mailing_peer extends db_peer_postgre
{
	protected $table_name = 'mailing';
	protected $limit = 75;

	/**
	 * @return mailing_peer
	 */
	public static function instance()
	{
		return parent::instance('mailing_peer');
	}

	public function add_mailing($filter, $data = array(), $subject_tpl, $body_tpl, $sender_email, $sender_name, $send_all = false, $is_maillists = false, $lists = array(), $is_druft = false)
	{
		if ($is_druft == false) $start = time();
		$data = array(
			"filter" => $filter,
			"data" => serialize($data),
			"subject" => $subject_tpl,
			"body" => addslashes($body_tpl),
			"sender_email" => $sender_email,
			"sender_name" => $sender_name,
			"send_all" => $send_all,
			"is_maillists" => $is_maillists,
			"is_druft" => $is_druft,
			"start" => $start
		);
		if (count($lists)) $data['lists'] = implode(',', $lists);
		$id = parent::insert($data);
		return $id;
	}

	public function get_mailing()
	{
		$sql = 'SELECT * FROM ' . $this->table_name . ' WHERE is_complite = false AND is_maillists=false
                AND is_druft=false LIMIT 1';
		$data['mailing'] = db::get_row($sql);
		$data['mailing']['body'] = stripcslashes($data['mailing']['body']);

		if ($data['mailing']['id'] == 0) return false;
		$limit = $this->limit;
		$sqlnot_in = '(SELECT user_id FROM mailing_send_mails WHERE mailing_id=' . $data['mailing']['id'] . ') ';
		$request = unserialize($data['mailing']['data']);
		switch ($data['mailing']['filter']) {
			case 'common':
//				if ($data['mailing']['send_all'] == 0)
//					$sqladd = "AND active = true";
				$sql = 'SELECT id FROM ' . user_auth_peer::instance()->get_table_name().' ORDER BY id';
				break;
			case 'group':
				load::model('groups/members');
				$sql = 'SELECT user_id FROM ' . groups_members_peer::instance()->get_table_name() . '
                    WHERE group_id IN (' . implode(',', $request['groups']) . ')
                        AND user_id NOT IN ' . $sqlnot_in;
				break;
			case 'status':
				$sql = 'SELECT id as user_id FROM ' . user_auth_peer::instance()->get_table_name() . '
                    WHERE status IN (' . implode(',', $request['status']) . ')
                        AND id NOT IN ' . $sqlnot_in;
				break;
			case 'func':
				load::model('user/user_desktop');
				foreach ($request['func'] as $a) {
					$where[] = "functions && '{" . $a . "}'";
				}
				$sql = 'SELECT user_id FROM ' . user_desktop_peer::instance()->get_table_name() . '
                    WHERE ' . implode(' OR ', $where) . ' AND user_id NOT IN ' . $sqlnot_in;
				break;
			case 'region':
				$sql = 'SELECT user_id FROM ' . user_data_peer::instance()->get_table_name() . '
                    WHERE region_id IN (' . implode(',', $request['region']) . ')
                        AND user_id NOT IN ' . $sqlnot_in;
				break;
			case 'lists':
				load::model('lists/lists_users');
				$sql = 'SELECT user_id FROM ' . lists_users_peer::instance()->get_table_name() . '
                    WHERE list_id IN (' . implode(',', $request['lists']) . ')
                        AND type = 0
                        AND user_id NOT IN ' . $sqlnot_in;
				break;
			case 'district':
				$sql = 'SELECT user_id FROM ' . user_data_peer::instance()->get_table_name() . '
                    WHERE city_id IN (' . implode(',', $request['city']) . ')
                        AND user_id NOT IN ' . $sqlnot_in;
				break;

			case 'age':
				load::model('political_views');
				$now = date('Y', time());
				$from = $now - $request['age_from'];
				$to = $now - $request['age_to'];
				$sql = "SELECT user_id, age(birthday) as age FROM " . user_data_peer::instance()->get_table_name() . "
                    WHERE date_part('year',birthday) >= " . $to . "
                        AND date_part('year',birthday) <= " . $from . "
                    AND user_id NOT IN " . $sqlnot_in;
				break;
		}
		$list = db::get_cols($sql);
		$count = count(db::get_cols($sql));

		$data['list'] = $list;
		if ($count == 0)
			db::exec("UPDATE mailing SET is_complite=true,\"end\"=" . time() . " WHERE id=:mailing_id",
				array('mailing_id' => $data['mailing']['id']));
		elseif ($data['mailing']['count'] == 0)
			db::exec("UPDATE mailing SET count=" . $count . " WHERE id=:mailing_id",
				array('mailing_id' => $data['mailing']['id']));
		return $data;
	}

	public function get_mailing_lists()
	{
		$sql = 'SELECT * FROM ' . $this->table_name . '
                WHERE is_complite = false AND is_maillists=true LIMIT 1';
		$data['mailing'] = db::get_row($sql);
		$data['mailing']['body'] = stripcslashes($data['mailing']['body']);
		if ($data['mailing']['id'] == 0) return false;
		$sql = 'SELECT * FROM email_users
                WHERE blacklisted=0
                AND id IN (SELECT user_id FROM email_lists_users WHERE list_id IN(' . $data['mailing']['lists'] . '))
                AND id NOT IN
                (SELECT mailing_user_id FROM mailing_send_mails WHERE mailing_id=' . $data['mailing']['id'] . ')
                ORDER BY id';
		$data['list'] = db::get_rows($sql . ' LIMIT ' . $this->limit);
		$count = count(db::get_cols($sql));
		if ($count == 0)
			db::exec("UPDATE mailing SET is_complite=true,\"end\"=" . time() . " WHERE id=:mailing_id",
				array('mailing_id' => $data['mailing']['id']));
		elseif ($data['mailing']['count'] == 0)
			db::exec("UPDATE mailing SET count=" . $count . " WHERE id=:mailing_id",
				array('mailing_id' => $data['mailing']['id']));
		return $data;
	}

	public function set_sendmail($mailing_id, $user_id, $is_lists = false)
	{
		if ($is_lists == true) {
			$data = array('mailing_id' => $mailing_id, 'user_id' => 0, 'mailing_user_id' => $user_id);
			db::exec("INSERT INTO mailing_send_mails(mailing_id,user_id,mailing_user_id) VALUES(:mailing_id,:user_id,:mailing_user_id)", $data);
		} else {
			$data = array('mailing_id' => $mailing_id, 'user_id' => $user_id);
			db::exec("INSERT INTO mailing_send_mails(mailing_id,user_id) VALUES(:mailing_id,:user_id)", $data);
		}
		db::exec("UPDATE mailing SET count_send=(SELECT count(*) FROM mailing_send_mails WHERE mailing_id=:mailing_id) WHERE id=:mailing_id",
			array('mailing_id' => $mailing_id));
	}

	public function  get_maillists()
	{
		$data = db::get_rows('SELECT id,name FROM email_lists');
		foreach ($data as $v) $mlist[$v['id']] = $v['name'];
		return $mlist;
	}

	public function  get_maillists_users_count($id)
	{
		return db::get_scalar("SELECT count(*) FROM email_users WHERE blacklisted=0
                     AND id IN(SELECT user_id FROM email_lists_users WHERE list_id=$id)");
	}

	public function  get_maillists_user($email)
	{
		return db::get_row("SELECT * FROM email_users WHERE email='$email'");
	}

	public function get_maillists_user_lists($id)
	{
		$data = db::get_rows('SELECT list_id FROM email_lists_users WHERE user_id=' . $id);
		foreach ($data as $v) $mlist[] = $v['list_id'];
		return $mlist;
	}

	public function save_maillists_user($id, $first_name, $last_name, $blacklisted)
	{
		db::exec("UPDATE email_users
         SET first_name=:first_name,last_name=:last_name,blacklisted=:blacklisted
                WHERE id=:id",
			array("first_name" => $first_name,
				"last_name" => $last_name,
				"blacklisted" => $blacklisted,
				"id" => $id));
	}

	public function del_maillists_user($id)
	{
		db::exec("DELETE FROM email_lists_users WHERE user_id=$id");
		db::exec("DELETE FROM email_users WHERE id=$id");
	}

	public function save_maillists_user_lists($user_id, $lists = array())
	{
		db::exec("DELETE FROM email_lists_users WHERE user_id=$user_id");
		if (count($lists)) {
			foreach ($lists as $v)
				db::exec("INSERT INTO email_lists_users(user_id,list_id) VALUES(:user_id,:list_id)",
					array('user_id' => $user_id, 'list_id' => $v));
			return true;
		}
	}

	public function get_maillists_list_users($list_id, $page = 0, $limit = 50)
	{
		$sql = 'SELECT * FROM email_users WHERE id
            IN(SELECT user_id FROM email_lists_users WHERE list_id=' . $list_id . ') ';
		$data['list'] = db::get_rows($sql . ' LIMIT ' . $limit . ' OFFSET ' . $page);
		$data['count'] = db::get_cols($sql);
		return $data;
	}

	public function add_maillists_user($email, $first_name, $last_name, $lists = array())
	{
		$data = array("email" => $email,
			"first_name" => $first_name,
			"last_name" => $last_name,
			"blacklisted" => 0);
		if (!db::get_scalar("SELECT id FROM email_users WHERE email='$email'")) {
			db::exec("INSERT INTO email_users(email,first_name,last_name,blacklisted)
                VALUES(:email,:first_name,:last_name,:blacklisted)", $data);

			$id = db::get_scalar("SELECT id FROM email_users WHERE email='$email'");
			if (count($lists)) {
				foreach ($lists as $v)
					db::exec("INSERT INTO email_lists_users(user_id,list_id) VALUES(:user_id,:list_id)",
						array('user_id' => $id, 'list_id' => $v));
				return true;
			}
		} else return $email;
	}

	function del_maillists($id)
	{
		db::exec("DELETE FROM email_lists_users WHERE list_id=$id");
		db::exec("DELETE FROM email_lists WHERE id=$id");
	}

	function add_maillists($name)
	{
		db::exec("INSERT INTO email_lists(name) VALUES(:name)",
			array('name' => $name));
	}

	function get_mailings_send()
	{
		return db::get_rows('SELECT * FROM ' . $this->table_name . " WHERE is_complite = true AND filter!='func' AND filter!='region' ORDER BY id DESC");
	}

	function get_mailings_users_send()
	{
		return db::get_rows('SELECT * FROM ' . $this->table_name . " WHERE is_complite = true AND (filter='func' or filter='region') ORDER BY id DESC");
	}

	function get_mailings_druft()
	{
		return db::get_rows('SELECT * FROM ' . $this->table_name . ' WHERE is_druft=true ORDER BY id DESC');
	}

	function get_mailings_act()
	{
		return db::get_rows('SELECT * FROM ' . $this->table_name . ' WHERE is_druft=false AND is_complite = false ORDER BY id DESC');
	}

	function get_list_info($id)
	{
		$sql = 'SELECT * FROM ' . $this->table_name . ' WHERE id=' . $id;
		$data = db::get_row($sql);
		$data['body'] = stripcslashes($data['body']);
		return $data;
	}

	function del_mailing($id)
	{
		db::exec('DELETE FROM ' . $this->table_name . ' WHERE id=' . $id);
	}

	function save_mailing($id, $subject_tpl, $body_tpl, $sender_email, $sender_name, $is_druft = true)
	{
		$sql = 'SELECT * FROM ' . $this->table_name . ' WHERE id=' . $id;
		$data = db::get_row($sql);
		if ($data['is_druft'] == false) $is_druft = false;
		db::exec("UPDATE $this->table_name SET subject=:subject,body=:body,sender_email=:sender_email,sender_name=:sender_name,is_druft=:is_druft
                WHERE id=:mailing_id",
			array("subject" => $subject_tpl,
				"body" => addslashes($body_tpl),
				"sender_email" => $sender_email,
				"sender_name" => $sender_name,
				"mailing_id" => $id,
				"is_druft" => $is_druft));
		if ($is_druft == false) {
			if ($data['start'] == 0)
				db::exec("UPDATE $this->table_name
                     SET start=" . time() . "
                     WHERE id=:mailing_id",
					array('mailing_id' => $id));
		}
	}
}