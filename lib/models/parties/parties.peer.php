<?

class parties_peer extends db_peer_postgre
{
	protected $table_name = 'parties';

	/**
	 * @return parties_peer
	 */
	public static function instance()
	{
		return parent::instance( 'parties_peer' );
	}

	public function regenerate_photo_salt( $id )
	{
		$salt = substr(md5(microtime(true)), 0, 8);

		$this->update(array('photo_salt' => $salt, 'id' => $id));
		return $salt;
	}

	public static function get_contact_types()
	{
		return array(
			11 => t('Карта на Mapia.ua'),
		);
	}

	public static function get_contact_type( $id )
	{
		$list = self::get_contact_types();
		return $list[$id];
	}

	public function get_sorted_list( $sort, $limit = 100 )
	{
		$where = array();
		switch ( $sort )
		{
			case 'popularity':
				$order = array('trust DESC');
				$where['state'] = 'old';
				break;

			case 'rating':
				$order = array('rate DESC');
				$where['state'] = 'old';
				break;

			case 'date':
				$order = array('id DESC');
				break;
		}

		return $this->get_list($where, array(), $order, $limit);
	}
	
	public function get_not_approved( $limit = 250 )
	{
		$where = array(
			'state' => 'new'
		);
		$order = array('id DESC');
		return $this->get_list($where, array(), $order, $limit);
	}

	public function get_new( $limit = 250 )
	{
		$sql = "SELECT id FROM " . $this->table_name . " WHERE state <> 'bad' ORDER BY id DESC LIMIT " . $limit;
		return db::get_cols($sql, array(), $this->connection_name);
	}

	public function get_by_direction( $direction, $sort = 'popularity', $limit = 150 )
	{
		$where = array();
		switch ( $sort )
		{
			case 'popularity':
				$order = array('trust DESC');
				$where['state'] = 'old'; 
				break;

			case 'rating':
				$order = array('rate DESC');
				$where['state'] = 'old';
				break;

			case 'date':
				$order = array('id DESC');
				break;
		}

		$direction_id = array_keys(political_views_peer::get_list(), $direction);
		$direction_id = array_shift($direction_id);

		$where['direction'] = $direction_id;
		return $this->get_list($where, array(), $order, $limit);
	}

	public function get_directions_cloud()
	{
		$sql = 'SELECT direction, count(id) as total FROM ' . $this->table_name . ' GROUP BY direction';
		$list = db::get_rows($sql, array(), $this->connection_name);
		return tags_helper::normalize($list, 'total');
	}

	public function update_rate( $id, $value, $user_id = null )
	{
		if ( !$data = $this->get_item($id) )
		{
			return;
		}

		if ( $user_id )
		{
			$value = $value * user_data_peer::instance()->get_rate_multiplier($user_id);
		}

		$this->update(array(
			'id' => $id,
			'rate' => $data['rate'] + $value
		));
	}

	public function get_moderators( $id )
	{
		$data = db_key::i()->exists('parties_moderators:' . $id) ? unserialize(db_key::i()->get('parties_moderators:' . $id)) : array();

		return $data;
	}

	public function is_moderator( $id, $user_id )
	{
		if ( session::has_credential('admin') )
		{
			return true;
		}

		$data = $this->get_item($id);
		if ( $data['user_id'] == $user_id )
		{
			return true;
		}

		return in_array($user_id, $this->get_moderators($id));
	}
	
	public function add_moderator( $id, $user_id )
	{
		$moderators = $this->get_moderators($id);
		$moderators[] = $user_id;
		$moderators = array_unique($moderators);
		
		db_key::i()->set('parties_moderators:' . $id, serialize($moderators));
	}

	public function delete_moderator( $id, $user_id )
	{
		$moderators = $this->get_moderators($id);
		$moderators = array_diff($moderators, array($user_id));

		db_key::i()->set('parties_moderators:' . $id, serialize($moderators));
	}

	public function get_leaders( $id )
	{
		$data = db_key::i()->exists('parties_leaders:' . $id) ? unserialize(db_key::i()->get('parties_leaders:' . $id)) : array();

		return $data;
	}

	public function add_leader( $id, $user_id )
	{
		$list = $this->get_leaders($id);
		$list[] = $user_id;
		$list = array_unique($list);

		db_key::i()->set('parties_leaders:' . $id, serialize($list));
	}

	public function delete_leader( $id, $user_id )
	{
		$list = $this->get_leaders($id);
		$list = array_diff($list, array($user_id));

		db_key::i()->set('parties_leaders:' . $id, serialize($list));
	}

	public function search( $keyword, $limit = 5 )
	{
		$keyword = str_replace(' ', ' | ', $keyword);
		$sql = 'SELECT id FROM ' . $this->table_name . ' WHERE fti @@ to_tsquery(\'russian\', :keyword) LIMIT :limit;';
		return db::get_cols($sql, array('keyword' => $keyword, 'limit' => $limit), $this->connection_name);
	}

	public function reindex( $id )
	{
		$index_columns = array('title');
		$index_expr = 'coalesce(' . implode(',\'\') ||\' \'|| coalesce(', $index_columns) . ',\'\')';

		db::exec(
			'UPDATE ' . $this->table_name . ' SET fti = to_tsvector(\'russian\', ' . $index_expr . ') WHERE id = :id',
			array('id' => $id)
		);
	}

	public function update( $data, $keys = null )
	{
		parent::update($data, $keys);
		$this->reindex($data[$this->primary_key]);
	}

	public function insert($data, $ignore_duplicate = false)
	{
		$id = parent::insert($data, $ignore_duplicate);
		$this->reindex($id);

		return $id;
	}

	public function get_select_list()
	{
		$list = $this->get_list();
		$select = array();
		foreach ( $list as $id )
		{
			$data = $this->get_item($id);
			$select[$id] = $data['title'];
		}
		return $select;
	}
}