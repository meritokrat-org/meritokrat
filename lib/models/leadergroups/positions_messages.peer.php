<?

class groups_positions_messages_peer extends db_peer_postgre
{
	protected $table_name = 'groups_position_messages';

	/**
	 * @return groups_topics_messages_peer
	 */
	public static function instance()
	{
		return parent::instance( 'groups_positions_messages_peer' );
	}

	public function get_by_topic( $id )
	{
		return $this->get_list(array('topic_id' => $id), array(), array('id ASC'));
	}

	public function has_rated( $comment_id, $user_id )
	{
		return db_key::i()->exists('group_position_message_rate:' . $comment_id . ':' . $user_id);
	}

	public function rate( $comment_id, $user_id )
	{
		db_key::i()->set('group_position_message_rate:' . $comment_id . ':' . $user_id, true);
	}

}