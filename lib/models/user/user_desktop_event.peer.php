<?
class user_desktop_event_peer extends db_peer_postgre
{
	protected $table_name = 'user_desktop_event';
	protected $primary_key = 'id';

	/**
	 * @return user_desktop_event_peer
	 */
	public static function instance()
	{
		return parent::instance( 'user_desktop_event_peer' );
	}

}