<?

class leadergroups_applicants_peer extends db_peer_postgre
{
	protected $table_name = 'leadergroups_applicants';
	protected $primary_key = null;

	/**
	 * @return leadergroups_applicants_peer
	 */
	public static function instance()
	{
		return parent::instance( 'leadergroups_applicants_peer' );
	}

	public function add( $leadergroup_id, $user_id )
	{
		$this->insert(array('leadergroup_id' => $leadergroup_id, 'user_id' => $user_id));
	}

	public function remove( $leadergroup_id, $user_id )
	{
		$sql = 'DELETE FROM ' . $this->table_name . ' WHERE leadergroup_id = :leadergroup_id AND user_id = :user_id';
		return db::exec($sql, array('leadergroup_id' => $leadergroup_id, 'user_id' => $user_id), $this->connection_name);
	}

	public function get_by_leadergroup( $leadergroup_id )
	{
		$sql = 'SELECT user_id FROM ' . $this->table_name . ' WHERE leadergroup_id = :leadergroup_id';
		return db::get_cols($sql, array('leadergroup_id' => $leadergroup_id), $this->connection_name);
	}

	public function is_applicant( $leadergroup_id, $user_id )
	{
		return in_array($user_id, $this->get_by_leadergroup($leadergroup_id));
	}
}