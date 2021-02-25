<?

class messages_threads_peer extends db_peer_postgre
{
	protected $table_name = 'messages_threads';

	/**
	 * @return messages_threads_peer
	 */
	public static function instance()
	{
		return parent::instance( 'messages_threads_peer' );
	}
}