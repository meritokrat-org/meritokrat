<?

class debates_arguments_peer extends db_peer_postgre
{
	protected $table_name = 'debates_arguments';

	/**
	 * @return debates_arguments_peer
	 */
	public static function instance()
	{
		return parent::instance( 'debates_arguments_peer' );
	}

	public function get_by_debate( $id )
	{
		$where = array('debate_id' => $id, 'parent_id' => 0);
		return $this->get_list($where, array(), array('rate DESC, id DESC'));
	}

	public function get_newest( $limit = 5 )
	{
		return $this->get_list(array(), array(), array('id DESC'), $limit);
	}

	public function has_rated( $id, $user_id )
	{
		return db_key::i()->exists('debate_argument_vote:' . $id . ':' . $user_id);
	}

	public function rate( $id, $user_id )
	{
		db_key::i()->set('debate_argument_vote:' . $id . ':' . $user_id, true);
	}
}