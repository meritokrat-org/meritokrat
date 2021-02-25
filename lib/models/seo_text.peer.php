<?

class seo_text_peer extends db_peer_postgre
{
	protected $table_name = 'seo_text';
        
        public static function instance()
	{
		return parent::instance( 'seo_text_peer' );
	}
        public static function get_by_alias($alias, $dyn = false) {
            return db::get_row("SELECT * FROM seo_text WHERE alias=:alias AND dynamic=:dyn",array('alias'=>$alias,'dyn'=>$dyn));
        }
}

