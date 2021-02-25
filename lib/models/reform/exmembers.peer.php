<?

class reform_exmembers_peer extends db_peer_postgre
{
	protected $table_name = 'reform_exmembers';

	/**
	 * @return reform_exmembers_peer
	 */
	public static function instance()
	{
		return parent::instance('reform_exmembers_peer');
	}
}