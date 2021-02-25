<?

class parties_members_peer extends db_peer_postgre
{
	protected $table_name = 'parties_members';
	protected $primary_key = null;

	/**
	 * @return parties_members_peer
	 */
	public static function instance()
	{
		return parent::instance( 'parties_members_peer' );
	}

	public function get( $user_id )
	{
		return db::get_scalar(
			'SELECT party_id FROM ' . $this->table_name . ' WHERE user_id = :user_id LIMIT 1',
			array('user_id' => $user_id),
			$this->connection_name
		);
	}

	public function get_by_party( $id )
	{
		return db::get_cols(
			'SELECT user_id FROM ' . $this->table_name . ' WHERE party_id = :party_id',
			array('party_id' => $id),
			$this->connection_name
		);
	}

	public function is_member( $user_id, $party_id )
	{
		return $party_id == $this->get($user_id);
	}

	public function remove( $user_id )
	{
		db::exec(
			'DELETE FROM ' . $this->table_name . ' WHERE user_id = :user_id',
			array('user_id' => $user_id),
			$this->connection_name
		);
	}

	public function add( $user_id, $party_id )
	{
		$this->remove($user_id);

		$this->insert(array(
			'user_id' => $user_id,
			'party_id' => $party_id
		));
	}
}