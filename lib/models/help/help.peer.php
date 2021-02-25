<?

class help_peer extends db_peer_postgre
{
	protected $table_name = 'help_data';

	/**
	 * @return help_peer
	 */
	public static function instance()
	{
		return parent::instance( 'help_peer' );
	}

        public function key_exists( $alias,$id )
        {
                if($id)
                    return db::get_scalar("SELECT COUNT(*) FROM ".$this->table_name." WHERE alias = '".$alias."' AND id != ".$id);
                else
                    return db::get_scalar("SELECT COUNT(*) FROM ".$this->table_name." WHERE alias = '".$alias."'");
        }

        public function get_data( $alias )
        {
                return db::get_row("SELECT * FROM ".$this->table_name." WHERE alias = '".$alias."'");
        }

}