<?
class user_contact_peer extends db_peer_postgre
{
	protected $table_name = 'user_contact';
        protected $primary_key = 'id';
	/**
	 * @return user_shevchenko_data
	 */
	public static function instance()
	{
		return parent::instance( 'user_contact_peer' );
	}

        public function get_user($user_id,$start=0,$end=0)
	{
		$where = '';
                if($start)$where .= ' AND date >= '.$start.' ';
                if($end)$where .= ' AND date <= '.$end.' ';
                if(!$user_id) $user_id=0;
                return db::get_cols('SELECT '.$this->primary_key.'
                    FROM '.$this->table_name.'
                    WHERE user_id = '.$user_id.' '.$where.'
                    ORDER BY "date" ASC');
	}

        public function get_user_by_contacter($user_id=0,$contacter=0,$who=0)
	{
                if($contacter)$sqladd .= ' AND contacter_id = '.$contacter.' ';
                if($who)$sqladd .= ' AND who != '.$who.' ';
                return db::get_cols('SELECT '.$this->primary_key.'
                    FROM '.$this->table_name.'
                    WHERE user_id = '.$user_id.$sqladd.' 
                    ORDER by date ASC');
	}
}
