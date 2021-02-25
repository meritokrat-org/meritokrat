<?

class comments_peer extends db_peer_postgre
{
	protected $table_name = 'comments';

	/**
	 * @return comments_peer
	 */
	public static function instance()
	{
		return parent::instance( 'comments_peer' );
	}

	public function insert($data)
	{
		$id = parent::insert($data);

		$c = 'object_comments_' . $data['otype'] . '-' . $data['oid'];
		mem_cache::i()->delete($c);

		# Todo: increment
		$c = 'object_comments_count_' . $data['otype'] . '-' . $data['oid'];
		mem_cache::i()->delete($c);

		return $id;
	}

	public function get_count_by_post( $otype, $oid )
	{
			$c = 'object_comments_count_' . $otype . '-' . $oid;

			if ( !mem_cache::i()->exists($c) )
			{
				$data = db::get_scalar('SELECT count(id) FROM ' . $this->table_name . ' WHERE otype = :type AND oid = :id', array('id' => $oid, 'type' => $otype));
				mem_cache::i()->set($c, $data);

			}
			else
			{
				$data = mem_cache::i()->get($c);
			}

			return $data;
	}


	public function get_comments( $otype, $oid )
	{
		$c = 'object_comments_' . $otype . '-' . $oid;

		if ( !mem_cache::i()->exists($c) )
		{
			$data = $this->get_list(array('oid' => $oid, 'otype' => $otype, 'parent_id' => 0), array(), array('ID ASC'));
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
		return db_key::i()->exists('comment_rate:' . $comment_id . ':' . $user_id);
	}

	public function rate( $comment_id, $user_id )
	{
		db_key::i()->set('comment_rate:' . $comment_id . ':' . $user_id, true);
	}

	public function delete_item($id)
	{
		$data = $this->get_item($id);
		parent::delete_item($id);
		mem_cache::i()->delete('object_comments_' . $data['otype'] . '-' . $data['oid']);
		parent::reset_item($id);
	}
}
