<?

class team_exmembers_peer extends db_peer_postgre
{
	protected $table_name = 'team_exmembers';
	/**
	 * @return team_exmembers_peer
	 */
	public static function instance()
	{
		return parent::instance( 'team_exmembers_peer' );
	}
}