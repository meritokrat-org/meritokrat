<?

class rate_history_peer extends db_peer_postgre
{
	protected $table_name = 'rate_history';

	const TYPE_BLOG_POST = 1;

	/**
	 * @return rate_history_peer
	 */
	public static function instance()
	{
		return parent::instance( 'rate_history_peer' );
	}

	public function get_by_object( $type, $object_id )
	{
		/*return db::get_rows(
			'SELECT user_id, rate FROM ' . $this->table_name . ' WHERE type = :type AND object_id = :object_id',
			array('type' => $type, 'object_id' => $object_id),
			$this->connection_name
		);*/
		return db::get_rows(
			'SELECT user_id, rate FROM ' . $this->table_name . ' WHERE object_id = :object_id',
			array('object_id' => $object_id),
			$this->connection_name
		);
	}
}