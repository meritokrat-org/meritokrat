<?

class events_members_peer extends db_peer_postgre
{
	protected $table_name = 'events2users';
	protected $primary_key = null;

	/**
	 * @return events_members_peer
	 */
	public static function instance()
	{
		return parent::instance( 'events_members_peer' );
	}

	public function add( $event_id, $user_id )
	{
		$this->insert(array('event_id' => $event_id, 'user_id' => $user_id));
	}

        public function get_members( $event_id, $status=0, $confirm=0 )
	{
            if($status>0)$sqladd = " AND status=".$status;
            if($confirm>0)$sqladd = " AND confirm=".$confirm;
            $sql = 'SELECT * FROM ' . $this->table_name . ' WHERE event_id = :event_id'.$sqladd;
		return db::get_rows($sql, array('event_id' => $event_id), $this->connection_name);
	}

        public function is_member( $event_id, $user_id )
	{
                $sql = 'SELECT COUNT(user_id) FROM ' . $this->table_name . ' WHERE user_id = :user_id AND event_id = :event_id';
		return db::get_scalar($sql, array('user_id' => $user_id, 'event_id' => $event_id), $this->connection_name);
	}

        public function get_item($event_id, $user_id )
        {
            return db::get_row("SELECT user_id FROM ". $this->table_name."
                WHERE event_id = :event_id AND user_id=:user_id", array('event_id' => $event_id, 'user_id' => $user_id));
        }

        public function delete_event($event_id)
        {
                db::exec('DELETE FROM ' . $this->table_name . ' WHERE event_id = '.$event_id);
        }

        public function set_status( $event_id, $user_id, $status )
	{
                 db::exec("UPDATE $this->table_name SET status=:status 
                         WHERE event_id=:event_id AND user_id=:user_id",
                     array('event_id' => $event_id, 'user_id' => $user_id, 'status' => $status));
	}
}