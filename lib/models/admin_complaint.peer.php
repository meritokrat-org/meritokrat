<?

class admin_complaint_peer extends db_peer_postgre
{
	protected $table_name = 'complaints';

	/**
	 * @return admin_complaint_peer
	 */
	public static function instance()
	{
		return parent::instance( 'admin_complaint_peer' );
	}

        public function get_row($array)
        {
            if(count($array)==0)
                return false;
            foreach($array as $k => $v)
            {
                $where[] = $k.' = '.$v;
            }
            return db::get_row('SELECT * FROM ' . $this->get_table_name() . ' WHERE '.implode(' AND ',$where).' LIMIT 1');
        }

	public function get_by_moderator($moderator_id)
	{
		return $this->get_list(array('moderator_id'=>$moderator_id));
                //return db::get_cols('SELECT * FROM ' . $this->get_table_name() . ' WHERE moderator_id = '.$user_id.' ORDER BY id DESC');
	}

        public function get_by_user($user_id)
	{
		return $this->get_list(array('user_id'=>$user_id));
                //return db::get_cols('SELECT * FROM ' . $this->get_table_name() . ' WHERE user_id = '.$user_id.' ORDER BY id DESC');
	}
}