<?
class user_recomendations_peer extends db_peer_postgre
{
	protected $table_name = 'user_recomendations';
	protected $primary_key = 'id';
	/**
	 * @return user_recomendations_peer
	 */
	public static function instance()
	{
		return parent::instance( 'user_recomendations_peer' );
	}


}
