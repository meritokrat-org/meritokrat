<?

class polls_comments_peer extends db_peer_postgre
{
	protected $table_name = 'polls_comments';

	/**
	 * @return polls_comments_peer
	 */
	public static function instance()
	{
		return parent::instance( 'polls_comments_peer' );
	}

        public function get_item($id)
	{
		return db::get_row('SELECT * FROM ' . $this->table_name . ' WHERE id = :id', array('id' => $id));
	}

	public function insert($data)
	{
		$id = parent::insert($data);

		$c = 'poll_comments_' . $data['poll_id'];
		mem_cache::i()->delete($c);

		# Todo: increment
		$c = 'poll_comments_count_' . $data['poll_id'];
		mem_cache::i()->delete($c);

		return $id;
	}

        public function update($data)
	{
		$id = parent::update($data);

		$c = 'poll_comments_' . $data['poll_id'];
		mem_cache::i()->delete($c);
	}

	public function get_count_by_poll( $poll_id )
	{
		$c = 'poll_comments_count_' . $poll_id;

		if ( !mem_cache::i()->exists($c) )
		{
			$data = db::get_scalar('SELECT count(id) FROM ' . $this->table_name . ' WHERE poll_id = :id', array('id' => $poll_id));
			mem_cache::i()->set($c, $data);

		}
		else
		{
			$data = mem_cache::i()->get($c);
		}

		return $data;
	}


	public function get_by_poll( $poll_id )
	{
		$c = 'poll_comments_' . $poll_id;

		if ( !mem_cache::i()->exists($c) )
		{
			$data = $this->get_list(array('poll_id' => $poll_id, 'parent_id' => 0), array(), array('ID ASC'));
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
		return db_key::i()->exists('poll_comment_rate:' . $comment_id . ':' . $user_id);
	}

	public function rate( $comment_id, $user_id )
	{
		db_key::i()->set('poll_comment_rate:' . $comment_id . ':' . $user_id, true);
	}

	public function delete_item($id)
	{
		$data = $this->get_item($id);
		parent::delete_item($id);
		
		mem_cache::i()->delete('poll_comments_' . $data['poll_id']);
		parent::reset_item($id);
	}
}
