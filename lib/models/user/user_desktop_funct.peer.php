<?
class user_desktop_funct_peer extends db_peer_postgre
{
	protected $table_name = 'user_desktop_funct';
        protected $primary_key = 'user_id';

	public static function instance()
	{
		return parent::instance( 'user_desktop_funct_peer' );
	}

        public function get_user($user_id,$functions)
	{
                foreach ($functions as $function_id=>$function_title){
		$arr[$function_id] = db::get_rows('SELECT * FROM '.$this->table_name.' 
                    WHERE user_id = '.$user_id.' AND function_id='.$function_id);
                } 
                return $arr;
	}

            public function del_user($user_id)
	{
		return db::exec('DELETE FROM '.$this->table_name.' WHERE user_id = '.$user_id);
	}
       
}