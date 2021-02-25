<?
class user_status_log_peer extends db_peer_postgre
{
	protected $table_name = 'user_status_log';
	protected $primary_key = 'id';

	/**
	 * @return user_desktop_peer
	 */
	public static function instance()
	{	
            return parent::instance( 'user_status_log_peer' );
	}

        public function add($array)
        {
            $query = $this->get_last($array['user_id']);
            if(intval($query['status'])!=$array['status'])
            {
                $this->insert($array);
            }
        }

        public function get_last($user_id,$status=false)
        {
            if($status)
                $sqladd = ' AND status = '.intval($status);
            return db::get_row('SELECT * FROM '.$this->table_name.' WHERE user_id = '.$user_id.$sqladd.' ORDER BY date DESC LIMIT 1');
        }
}