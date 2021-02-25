<?

class leadergroups_topics_messages_peer extends db_peer_postgre
{
	protected $table_name = 'leadergroups_topics_messages';

	/**
	 * @return leadergroups_topics_messages_peer
	 */
	public static function instance()
	{
		return parent::instance( 'leadergroups_topics_messages_peer' );
	}

	public function get_by_topic( $id )
	{
		return $this->get_list(array('topic_id' => $id), array(), array('id ASC'));
	}

	public function has_rated( $comment_id, $user_id )
	{
		return db_key::i()->exists('leadergroup_message_rate:' . $comment_id . ':' . $user_id);
	}

	public function rate( $comment_id, $user_id )
	{
		db_key::i()->set('leadergroup_message_rate:' . $comment_id . ':' . $user_id, true);
	}

}