<?

class debates_debates_tags_peer extends db_peer_postgre
{
	protected $table_name = 'debates_debates_tags';
	protected $primary_key = null;

	/**
	 * @return debates_debates_tags_peer
	 */
	public static function instance()
	{
		return parent::instance( 'debates_debates_tags_peer' );
	}

	public function delete_by_debate( $id )
	{
		$sql = 'DELETE FROM ' . $this->table_name . ' WHERE debate_id = :debate_id';
		db::exec($sql, array('debate_id' => $id), $this->connection_name);
	}
}