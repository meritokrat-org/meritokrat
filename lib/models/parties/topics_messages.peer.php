<?

class parties_topics_messages_peer extends db_peer_postgre
{
	protected $table_name = 'parties_topics_messages';

	/**
	 * @return parties_topics_messages_peer
	 */
	public static function instance()
	{
		return parent::instance( 'parties_topics_messages_peer' );
	}

	public function get_by_topic( $id )
	{
		return $this->get_list(array('topic_id' => $id), array(), array('id ASC'));
	}
}