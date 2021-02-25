<?

class groups_proposal_peer extends db_peer_postgre
{
	protected $table_name = 'groups_proposal';

	/**
	 * @return groups_topics_peer
	 */
	public static function instance()
	{
		return parent::instance( 'groups_proposal_peer' );
	}

	public function get_by_group( $id, $sort = array('id DESC') )
	{
		return $this->get_list(array('group_id' => $id), array(), $sort);
	}

	public function has_rated( $id, $user_id )
	{
		return db_key::i()->exists('group_proposal_rate:' . $id . ':' . $user_id);
	}

	public function rate( $id, $user_id )
	{
		db_key::i()->set('group_proposal_rate:' . $id . ':' . $user_id, true);
	}

	public function delete_item($id)
	{
		db::exec('DELETE FROM ' . groups_proposal_messages_peer::instance()->get_table_name() . ' WHERE topic_id = :id', array('id' => $id));
		parent::delete_item($id);
	}
}