<?

class user_questions_peer extends db_peer_postgre
{
	protected $table_name = 'user_questions';

	/**
	 * @return user_questions_peer
	 */
	public static function instance()
	{
		return parent::instance( 'user_questions_peer' );
	}

	public function has_rated( $id, $user_id )
	{
		return db_key::i()->exists('profile_question_rate:' . $id . ':' . $user_id);
	}

	public function rate( $id, $user_id )
	{
		db_key::i()->set('profile_question_rate:' . $id . ':' . $user_id, true);
	}

	public function get_by_profile( $id, $where = array(), $sort = 'id DESC' )
	{
		$where['profile_id'] = $id;
		return $this->get_list($where, array(), array($sort));
	}
}