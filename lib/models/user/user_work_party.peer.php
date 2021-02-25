<?
class user_work_party_peer extends db_peer_postgre
{
	protected $table_name = 'user_work_party';
        protected $primary_key = 'user_id';

	public static function instance()
	{
		return parent::instance( 'user_work_party_peer' );
	}

        public function get_user($user_id)
	{
		return db::get_rows('SELECT * FROM '.$this->table_name.' WHERE user_id = '.$user_id);
	}

        public function del_user($user_id)
	{
		return db::exec('DELETE FROM '.$this->table_name.' WHERE user_id = '.$user_id);
	}

}