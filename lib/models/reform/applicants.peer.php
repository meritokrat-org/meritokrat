<?

class reform_applicants_peer extends db_peer_postgre
{
	protected $table_name = 'reform_applicants';
	protected $primary_key = null;

	/**
	 * @return reform_applicants_peer
	 */
	public static function instance()
	{
		return parent::instance('reform_applicants_peer');
	}

	public function add($group_id, $user_id, $reason)
	{
		$this->insert(array('group_id' => $group_id, 'user_id' => $user_id, 'reason' => $reason));
	}

	public function remove($group_id, $user_id)
	{
		$sql = 'DELETE FROM ' . $this->table_name . ' WHERE group_id = :group_id AND user_id = :user_id';
		return db::exec($sql, array('group_id' => $group_id, 'user_id' => $user_id), $this->connection_name);
	}

	public function get_by_group($group_id)
	{
		$sql = 'SELECT user_id FROM ' . $this->table_name . ' WHERE group_id = :group_id';
		return db::get_cols($sql, array('group_id' => $group_id), $this->connection_name);
	}

	public function is_applicant($group_id, $user_id)
	{
		return in_array($user_id, $this->get_by_group($group_id));
	}
}