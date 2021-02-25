<?

class lists_peer extends db_peer_postgre
{
	protected $table_name = 'lists';

	/**
	 * @return lists_peer
	 */
	public static function instance()
	{
		return parent::instance('lists_peer');
	}

	public function own_lists($user_id)
	{
		if (!$user_id) $user_id = 0;
		if (session::has_credential('admin'))
			return db::get_cols('SELECT id FROM ' . $this->table_name);
		else
			return db::get_cols('SELECT id FROM ' . $this->table_name . ' WHERE user_id = ' . $user_id);
	}

	public function get_list_data($user_id)
	{
		if (!$user_id) $user_id = 0;
		$own_lists = $this->own_lists($user_id);
		$edit_lists = db::get_cols('SELECT list_id FROM lists2users WHERE user_id = ' . $user_id . ' AND type = 2');
		$edit_lists = array_diff($edit_lists, $own_lists);
		$list = array_merge($own_lists, $edit_lists);
		if (count($list) > 0) {
			$result = db::get_rows('SELECT id,title FROM lists WHERE id IN (' . implode(',', $list) . ')');
			foreach ($result as $r) {
				$list_data[$r['id']] = $r['title'];
			}
		}
		return (array)$list_data;
	}

}