<?

class eventreport_log_peer extends db_peer_postgre
{

	protected $table_name = 'eventreport_log';

	/**
	 * @return events_peer
	 */
	public static function instance()
	{ 
		return parent::instance( 'eventreport_log_peer' );
	}
        
}
