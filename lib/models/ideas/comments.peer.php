<?

class ideas_comments_peer extends db_peer_postgre
{
	protected $table_name = 'ideas_comments';

	/**
	 * @return ideas_comments_peer
	 */
	public static function instance()
	{
		return parent::instance( 'ideas_comments_peer' );
	}

        public function get_item($id)
	{
		return db::get_row('SELECT * FROM ' . $this->table_name . ' WHERE id = :id', array('id' => $id));
	}

	public function insert($data)
	{
		$id = parent::insert($data);

		$c = 'ideas_comments_' . $data['idea_id'];
		mem_cache::i()->delete($c);

		return $id;
	}

        public function update($data)
	{
		$id = parent::update($data);

		$c = 'ideas_comments_' . $data['idea_id'];
		mem_cache::i()->delete($c);
	}

	public function get_count_by_idea( $idea_id )
	{
			$c = 'idea_comments_count_' . $idea_id;

			/*if ( !mem_cache::i()->exists($c) )
			{ */
				$data = db::get_scalar('SELECT count(id) FROM ' . $this->table_name . ' WHERE idea_id = :id', array('id' => $idea_id));
				mem_cache::i()->set($c, $data);

		/*	}
			else
			{
				$data = mem_cache::i()->get($c);
			}
*/
			return $data;
	}
	public function get_by_idea( $idea_id )
	{
		$c = 'ideas_comments_' . $idea_id;

		if ( !mem_cache::i()->exists($c) )
		{
			$data = $this->get_list(array('idea_id' => $idea_id, 'parent_id' => 0), array(), array('ID ASC'));
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
		return db_key::i()->exists('ideas_comments_rate:' . $comment_id . ':' . $user_id);
	}

	public function rate( $comment_id, $user_id )
	{
		db_key::i()->set('ideas_comments_rate:' . $comment_id . ':' . $user_id, true);
	}

	public function delete_item($id)
	{
		$data = $this->get_item($id);
		parent::delete_item($id);
		mem_cache::i()->delete('ideas_comments_' . $data['idea_id']);
		parent::reset_item($id);
	}
}