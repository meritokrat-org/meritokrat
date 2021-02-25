<?

class polls_votes_peer extends db_peer_postgre
{
	protected $table_name = 'polls_votes';

	/**
	 * @return polls_votes_peer
	 */
	public static function instance()
	{
		return parent::instance( 'polls_votes_peer' );
	}

	public function vote( $answer_id, $user_id )
	{
		$answer = polls_answers_peer::instance()->get_item($answer_id);

		$this->instance()->insert(array(
			'user_id' => $user_id,
			'answer_id' => $answer_id,
			'poll_id' => $answer['poll_id']
		));

		mem_cache::i()->delete('poll_votes:' . $answer['poll_id']);
	}

	public function vote_custom( $poll_id, $user_id )
	{
		$this->instance()->insert(array(
			'user_id' => $user_id,
			'answer_id' => 0,
			'poll_id' => $poll_id
		));

		mem_cache::i()->delete('poll_votes:' . $poll_id);
	}

	public function has_voted( $poll_id, $user_id )
	{
		$sql = 'SELECT user_id FROM ' . $this->table_name . ' WHERE poll_id = :poll_id';
		$voted = db::get_cols($sql, array('poll_id' => $poll_id), $this->connection_name, 'poll_votes:' . $poll_id);
		
		return in_array($user_id, $voted);
	}

	public function get_voters( $poll_id, $answer_id )
	{
		$sql = 'SELECT user_id FROM ' . $this->table_name . ' WHERE poll_id = :poll_id AND answer_id = :answer_id';
		$list = db::get_cols($sql, array('poll_id' => $poll_id, 'answer_id' => $answer_id), $this->connection_name);

		return $list;
	}
}