<?

class candidates_peer extends db_peer_postgre
{
	protected $table_name = 'candidates';
	protected $primary_key = 'user_id';

	/**
	 * @return candidates_peer
	 */
	public static function instance()
	{
		return parent::instance( 'candidates_peer' );
	}

	public function is_candidate( $user_id )
	{
		return (bool)$this->get_item($user_id);
	}
}