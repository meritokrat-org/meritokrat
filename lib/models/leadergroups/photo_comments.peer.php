<?

class leadergroups_photo_comments_peer extends db_peer_postgre
{
	protected $table_name = 'leadergroups_photo_comments';

	/**
	 * @return leadergroups_photo_comments_peer
	 */
	public static function instance()
	{
		return parent::instance( 'leadergroups_photo_comments_peer' );
	}

	public function insert($data)
	{
		$id = parent::insert($data);

		$c = 'leadergroup_photo_comments_' . $data['photo_id'];
		mem_cache::i()->delete($c);

		return $id;
	}

	public function get_by_photo( $id )
	{
		$c = 'leadergroup_photo_comments_' . $id;

		if ( !mem_cache::i()->exists($c) )
		{
			$data = $this->get_list(array('photo_id' => $id, 'parent_id' => 0), array(), array('ID ASC'));
			mem_cache::i()->set($c, $data);

		}
		else
		{
			$data = mem_cache::i()->get($c);
		}

		return $data;
	}

	public function has_rated( $comment_id, $user_id )
	{
		return db_key::i()->exists('leadergroups_photo_comment_rate:' . $comment_id . ':' . $user_id);
	}

	public function rate( $comment_id, $user_id )
	{
		db_key::i()->set('leadergroups_photo_comment_rate:' . $comment_id . ':' . $user_id, true);
	}

	public function delete_item($id)
	{
		$data = $this->get_item($id);
		parent::delete_item($id);
		mem_cache::i()->delete('leadergroup_photo_comments_' . $data['photo_id']);
		parent::reset_item($id);
	}
}