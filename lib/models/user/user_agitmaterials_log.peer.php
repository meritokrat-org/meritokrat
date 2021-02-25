<?
class user_agitmaterials_log_peer extends db_peer_postgre
{
	protected $table_name = 'user_agitmaterials_log';

	/**
	 * @return user_agitmaterials_log_peer
	 */
	public static function instance()
	{
            return parent::instance( 'user_agitmaterials_log_peer' );
	}

        public function get_total()
        {
            return db::get_row('SELECT SUM(receive) AS receive, SUM(given) AS given, SUM(presented) AS presented FROM '.$this->table_name);
        }

        public function get_user($id,$type=1,$sql='')
	{
            if(!$id)$id=0;
            return db::get_row( 'SELECT SUM(receive) AS receive, SUM(given) AS given, SUM(presented) AS presented
                FROM ' . $this->table_name . ' WHERE type = ' . $type . ' AND user_id = ' . $id . '
                ' . $sql . '
                GROUP BY user_id');
	}

        public function get_user_by_type($id,$sql='')
	{
            if(!$id)$id=0;
            return db::get_rows( 'SELECT type, SUM(receive) AS receive, SUM(given) AS given, SUM(presented) AS presented
                FROM ' . $this->table_name . '
                WHERE user_id = ' . $id . ' AND (receive != 0 OR given != 0 OR presented != 0) 
                ' . $sql . '
                GROUP BY type ORDER BY type' );
	}

}