<?

class parties_trust_peer extends db_peer_postgre
{
	protected $table_name = 'parties_trust';
	protected $primary_key = null;

	/**
	 * @return parties_trust_peer
	 */
	public static function instance()
	{
		return parent::instance( 'parties_trust_peer' );
	}

	public function get_by_party( $id )
	{
		if ( !$time_offset )
		{
			$time_offset = 60*60*24*7*5;
		}

		$sql = 'SELECT trust, not_trust, created_ts FROM ' . $this->table_name . '
				WHERE party_id = :id
				ORDER BY created_ts DESC
				LIMIT 30';

		return db::get_rows($sql, array('id' => $id), $this->connection_name);
	}
}