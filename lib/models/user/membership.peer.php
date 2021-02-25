<?
class user_membership_peer extends db_peer_postgre
{
	protected $table_name = 'membership';

	/**
	 * @return user_membership_peer
	 */
	public static function instance()
	{
            return parent::instance( 'user_membership_peer' );
	}

        public function get_max_num()
        {
            return db::get_scalar('SELECT MAX(kvnumber) FROM '.$this->table_name);
        }

        public function get_user($user_id)
        {
            return db::get_row('SELECT * FROM '.$this->table_name.' WHERE user_id = '.$user_id);
        }

        public function check_number($user_id,$number)
        {
            return db::get_scalar('SELECT COUNT(*) FROM '.$this->table_name.' WHERE user_id != '.$user_id.' AND kvnumber = '.$number);
        }

        public function get_members()
        {
            return db::get_cols('SELECT id FROM '.$this->table_name.' m,user_auth a WHERE m.user_id = a.id AND a.status = 20');
        }

}