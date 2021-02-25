<?

class ppo_photo_comments_peer extends db_peer_postgre
{
	protected $table_name = 'ppo_photo_comments';

	/**
	 * @return ppo_photo_comments_peer
	 */
	public static function instance()
	{
		return parent::instance( 'ppo_photo_comments_peer' );
	}

	public function insert($data)
	{
		$id = parent::insert($data);

		$c = 'group_photo_comments_' . $data['photo_id'];
		mem_cache::i()->delete($c);

		return $id;
	}

	public function get_by_photo( $id )
	{
		$c = 'group_photo_comments_' . $id;

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
		return db_key::i()->exists('ppo_photo_comment_rate:' . $comment_id . ':' . $user_id);
	}

	public function rate( $comment_id, $user_id )
	{
		db_key::i()->set('ppo_photo_comment_rate:' . $comment_id . ':' . $user_id, true);
	}

	public function delete_item($id)
	{
		$data = $this->get_item($id);
		parent::delete_item($id);
		mem_cache::i()->delete('group_photo_comments_' . $data['photo_id']);
		parent::reset_item($id);
	}
}