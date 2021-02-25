<?

class leadergroups_members_peer extends db_peer_postgre
{
	protected $table_name = 'leadergroups_members';
	protected $primary_key = null;

	/**
	 * @return leadergroups_members_peer
	 */
	public static function instance()
	{
		return parent::instance( 'leadergroups_members_peer' );
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

	public function get_members( $leadergroup_id )
	{
		$sql = 'SELECT user_id FROM ' . $this->table_name . ' WHERE leadergroup_id = :leadergroup_id';
		return db::get_cols($sql, array('leadergroup_id' => $leadergroup_id), $this->connection_name);
	}

	public function is_member( $leadergroup_id, $user_id )
	{
		return in_array($user_id, $this->get_members($leadergroup_id));
	}

	public function get_leadergroups( $user_id )
	{
                $bind=array('user_id' => $user_id);
                $where=" AND leadergroup_id in (SELECT id FROM leadergroups WHERE active=:active";
                $bind['active']=1;
                if (!session::has_credential('admin'))
                    {
                        $where.=" AND hidden=:hidden";
                        $bind['hidden']=0;
                    }
                $sql = 'SELECT leadergroup_id FROM ' . $this->table_name . ' WHERE user_id = :user_id'.$where.')';
		return db::get_cols($sql, $bind, $this->connection_name);
	}
}