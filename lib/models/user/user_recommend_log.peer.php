<?
class user_recommend_log_peer extends db_peer_postgre
{
	protected $table_name = 'user_recommend_log';
	/**
	 * @return user_recommend_log_peer
	 */
	public static function instance()
	{
		return parent::instance( 'user_recommend_log_peer' );
	}

        public function get_by_user($user_id)
        {
                return db::get_cols('SELECT id FROM '.$this->table_name.' WHERE user_id = '.$user_id);
        }
}
