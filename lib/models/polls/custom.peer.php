<?

class polls_custom_peer extends db_peer_postgre
{
	protected $table_name = 'polls_custom';
	protected $primary_key = null;

	/**
	 * @return polls_custom_peer
	 */
	public static function instance()
	{
		return parent::instance( 'polls_custom_peer' );
	}

	public function get_by_poll( $id )
	{
		$sql = 'SELECT text, count(user_id) as total FROM ' . $this->table_name . ' WHERE poll_id = :poll_id GROUP BY text ORDER BY total';
		return db::get_rows($sql, array('poll_id' => $id), $this->connection_name);
	}

	public function get_voters( $poll_id )
	{
		$sql = 'SELECT user_id, text FROM ' . $this->table_name . ' WHERE poll_id = :poll_id ORDER BY text';
		$list = db::get_rows($sql, array('poll_id' => $poll_id), $this->connection_name);

		return $list;
	}

	public function delete( $poll_id, $answer )
	{
		$sql = 'DELETE FROM ' . $this->table_name . ' WHERE poll_id = :poll_id AND text = :answer';
		db::exec($sql, array('poll_id' => $poll_id, 'answer' => $answer), $this->connection_name);
	}
}