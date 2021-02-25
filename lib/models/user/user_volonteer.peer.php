<?
class user_volonteer_peer extends db_peer_postgre
{
	protected $table_name = 'user_volonteer';
	protected $primary_key = 'user_id';
        protected $lang_cols=array("expert","specialty");
	/**
	 * @return user_data_peer
	 */
	public static function instance()
	{
                load::action_helper('lang',false);
		return parent::instance( 'user_volonteer_peer' );
	}

	public function update( $data, $keys = null )
	{
		parent::update(lang_helper::set_lang_cols($this->lang_cols,$data), $keys);
	}

	public function insert($data, $ignore_duplicate = false)
	{
		$id = parent::insert($data, $ignore_duplicate);
		return $id;
	}
        
        public function get_item($id,$session='language'){ 
            $data=parent::get_item($id); 
            return lang_helper::get_lang_cols($this->lang_cols,$data,$session);
        }
}
