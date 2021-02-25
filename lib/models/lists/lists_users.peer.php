<?

class lists_users_peer extends db_peer_postgre
{
	protected $table_name = 'lists2users';
	protected $primary_key = 'list_id';

	/**
	 * @return lists_users_peer
	 */
	public static function instance()
	{
		return parent::instance('lists_users_peer');
	}

	public function check_in_list($list_id, $user_id, $type = 0)
	{
		return db::get_scalar('SELECT COUNT(*) FROM ' . $this->table_name . ' WHERE list_id = ' . $list_id . ' AND user_id = ' . $user_id . ' AND type = ' . $type);
	}

	public function get_lists_by_user($user_id, $type = 0)
	{
		if (!$user_id) $user_id = 0;
		return db::get_cols('SELECT list_id FROM ' . $this->table_name . ' WHERE user_id = ' . $user_id . ' AND type = ' . $type);
	}

	public function get_users_by_list($list_id, $type = 0)
	{
		return db::get_cols('SELECT user_id FROM ' . $this->table_name . ' WHERE list_id = ' . $list_id . ' AND type = ' . $type . ' ORDER BY user_id ASC');
	}

	public function delete_user($user_id, $list_id)
	{
		db::exec('DELETE FROM ' . $this->table_name . ' WHERE user_id = ' . $user_id . ' AND list_id = ' . $list_id);
	}
}