<?

class events_comments_peer extends db_peer_postgre
{
	protected $table_name = 'events_comments';

	/**
	 * @return events_comments_peer
	 */
	public static function instance()
	{
		return parent::instance( 'events_comments_peer' );
	}

	public function insert($data)
	{
		$id = parent::insert($data);

		$c = 'events_comments_' . $data['event_id'];
		mem_cache::i()->delete($c);

		# Todo: increment
		$c = 'events_comments_count_' . $data['event_id'];
		mem_cache::i()->delete($c);

		$this->count_comment( $data['event_id'], $data['user_id'] );

		return $id;
	}

	public function is_allowed( $event_id, $user_id )
	{
		$c = 'events_comments_user_limit_' . $event_id . $user_id;
		return mem_cache::i()->get($c) < 3;
	}

	public function count_comment( $event_id, $user_id )
	{
		$c = 'events_comments_user_limit_' . $event_id . $user_id;
		mem_cache::i()->set($c, mem_cache::i()->get($c) + 1, 60*60);
	}

	public function get_count_by_event( $event_id )
	{
			$c = 'events_comments_count_' . $event_id;

			if ( !mem_cache::i()->exists($c) )
			{
				$data = db::get_scalar('SELECT count(id) FROM ' . $this->table_name . ' WHERE event_id = :id', array('id' => $event_id));
				mem_cache::i()->set($c, $data);

			}
			else
			{
				$data = mem_cache::i()->get($c);
			}

			return $data;
	}


	public function get_by_event( $event_id )
	{ 
		$c = 'events_comments_' . $event_id;

		if ( !mem_cache::i()->exists($c) )
		{
			$data = $this->get_list(array('event_id' => $event_id, 'parent_id' => 0), array(), array('ID ASC'));
			mem_cache::i()->set($c, $data);

		}
		else
		{
			$data = mem_cache::i()->get($c);
		}

		return $data;
	}

	public function delete_item($id)
	{
		$data = $this->get_item($id);
		parent::delete_item($id);
		mem_cache::i()->delete('events_comments_' . $data['event_id']);
		parent::reset_item($id);
	}

        public function delete_event($event_id)
        {
                $data = db::get_cols("SELECT id FROM events_comments WHERE event_id=:event_id", array('event_id' => $event_id));
                foreach($data as $id)$this->delete_item($id);
        }

        public function update($data)
	{
		$id = parent::update($data);

		$c = 'events_comments_' . $data['post_id'];
		mem_cache::i()->delete($c);
	}

        public function get_item($id)
	{
		return db::get_row('SELECT * FROM ' . $this->table_name . ' WHERE id = :id', array('id' => $id));
	}
}
