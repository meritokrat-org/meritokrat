<?

class groups_members_peer extends db_peer_postgre
{
	protected $table_name = 'groups_members';
	protected $primary_key = null;

	/**
	 * @return groups_members_peer
	 */
	public static function instance()
	{
		return parent::instance('groups_members_peer');
	}

	public function add($group_id, $user_id, $type = false)
	{
		$insert = array('group_id' => $group_id, 'user_id' => $user_id);
		if (!$type) {
			if (db::get_scalar('SELECT id FROM invites WHERE type = 2 AND obj_id = ' . $group_id . ' AND to_id = ' . $user_id)) {
				$insert['type'] = 1;
			}
		} else {
			$insert['type'] = $type;
		}
		$this->insert($insert);
	}

	public function get_type($group_id, $user_id)
	{
		$index = db::get_scalar('SELECT type FROM ' . $this->table_name . ' WHERE group_id = ' . $group_id . ' AND user_id = ' . $user_id);
		$array = array(
			t('Присоединился'),
			t('Приглашен'),
			t('Присоединен')
		);
		return $array[$index];
	}

	public function remove($group_id, $user_id)
	{
		$sql = 'DELETE FROM ' . $this->table_name . ' WHERE group_id = :group_id AND user_id = :user_id';
		return db::exec($sql, array('group_id' => $group_id, 'user_id' => $user_id), $this->connection_name);
	}

	public function get_members($group_id)
	{
		$sql = 'SELECT user_id FROM ' . $this->table_name . ' WHERE group_id = :group_id  AND user_id not IN(SELECT id FROM user_auth WHERE del>0)';
		return db::get_cols($sql, array('group_id' => $group_id), $this->connection_name);
	}

	public function is_member($group_id, $user_id)
	{
		return in_array($user_id, $this->get_members($group_id));
	}

	public function get_groups($user_id, $order = false)
	{
		$bind = array('user_id' => $user_id);
		$where = " AND group_id in (SELECT id FROM groups WHERE active=:active";
		$bind['active'] = 1;
		if (!session::has_credential('admin') and session::get_user_id() != $user_id) {
			$where .= " AND hidden=:hidden";
			$bind['hidden'] = 0;
		}
		$sql = 'SELECT group_id FROM ' . $this->table_name . ' WHERE user_id = :user_id' . $where . ')';
		$return_data = db::get_cols($sql, $bind, $this->connection_name);
		if ($return_data && $order == 'count_users') {
			$return_data = db::get_cols("SELECT group_id FROM groups_members WHERE group_id in (" . implode(',', $return_data) . ") GROUP BY group_id ORDER BY count(group_id) DESC");
		}
		return $return_data;
	}
}