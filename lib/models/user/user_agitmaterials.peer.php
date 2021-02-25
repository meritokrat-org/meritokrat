<?
class user_agitmaterials_peer extends db_peer_postgre
{
	protected $table_name = 'user_agitmaterials';

	/**
	 * @return user_agitmaterials_peer
	 */
	public static function instance()
	{
            return parent::instance( 'user_agitmaterials_peer' );
	}

	public function get_user($id,$type=1)
	{
            if(!$id)$id=0;
            return db::get_row( 'SELECT * FROM ' . $this->table_name . ' WHERE type = ' . $type . ' AND user_id = ' . $id );
	}

        public function get_user_by_type($id)
	{
            if(!$id)$id=0;
            return db::get_rows( 'SELECT * FROM ' . $this->table_name . ' 
                WHERE user_id = ' . $id . ' AND (receive != 0 OR given != 0 OR presented != 0)
                ORDER BY type' );
	}

        public function get_user_stat($id)
	{
            return db::get_row( 'SELECT SUM(receive) AS receive, SUM(given) AS given, SUM(presented) AS presented
                FROM ' . $this->table_name . ' WHERE user_id = ' . $id );
	}

        public function get_total()
        {
            return db::get_row('SELECT SUM(receive) AS receive, SUM(given) AS given, SUM(presented) AS presented FROM '.$this->table_name);
        }

        public function get_by_type($type=1)
        {
            return db::get_row('SELECT SUM(receive) AS receive, SUM(given) AS given, SUM(presented) AS presented FROM '.$this->table_name.' WHERE type = '.$type);
        }

        public function delete($id=0)
	{
            db::exec( 'DELETE FROM ' . $this->table_name . ' WHERE user_id = ' . $id );
	}
}