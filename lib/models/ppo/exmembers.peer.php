<?

class ppo_exmembers_peer extends db_peer_postgre
{
	protected $table_name = 'ppo_exmembers';
	/**
	 * @return ppo_exmembers_peer
	 */
	public static function instance()
	{
		return parent::instance( 'ppo_exmembers_peer' );
	}
}