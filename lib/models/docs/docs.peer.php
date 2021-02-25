<?
class docs_peer extends db_peer_postgre
{
	protected $table_name = 'docs';

	/**
	 * @return docs_peer
	 */
	public static function instance()
	{
		return parent::instance( 'docs_peer' );
	}

        public function get_document( $alias )
        {
                return db::get_row("SELECT * FROM ".$this->table_name." WHERE alias = '".$alias."'");
        }

        public function check_alias( $alias )
        {
                return db::get_scalar("SELECT COUNT(*) FROM ".$this->table_name." WHERE alias = '".$alias."'");
        }

}