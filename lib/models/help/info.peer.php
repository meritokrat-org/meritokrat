<?

class help_info_peer extends db_peer_postgre
{
	protected $table_name = 'help_info';

	/**
	 * @return help_info_peer
	 */
	public static function instance()
	{
		return parent::instance( 'help_info_peer' );
	}

        public function key_exists( $alias )
        {
                return db::get_scalar("SELECT COUNT(*) FROM ".$this->table_name." WHERE alias = '".$alias."'");
        }

        public function get_info( $alias )
        {
                return db::get_row("SELECT * FROM ".$this->table_name." WHERE alias = '".$alias."'");
        }

}