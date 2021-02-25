<?

class debates_peer extends db_peer_postgre
{
	protected $table_name = 'debates';

	/**
	 * @return debates_peer
	 */
	public static function instance()
	{
		return parent::instance( 'debates_peer' );
	}

	public function get_by_user( $user_id )
	{
		return $this->get_list(array('user_id' => $user_id));
	}

	public function get_hot( $limit = 50 )
	{
		$sql = '
		SELECT id
		FROM ' . $this->table_name . '
		WHERE visible = true AND created_ts > :ts
		ORDER BY ("for" + "against") DESC
		LIMIT ' . $limit;

		return db::get_cols($sql, array('ts' => time()-60*60*24*21), $this->connection_name);
	}

	public function get_by_tag( $tag_id )
	{
		$sql = '
		SELECT d.id
		FROM ' . $this->table_name . ' d
		JOIN ' . debates_debates_tags_peer::instance()->get_table_name() . ' dt ON (dt.debate_id = d.id)
		WHERE dt.tag_id = :tag_id AND d.visible = true
		GROUP BY d.id
		ORDER BY id DESC
		LIMIT 50';

		return db::get_cols($sql, array('tag_id' => $tag_id), $this->connection_name);
	}

	public function get_newest($limit = 50 )
	{
		return $this->get_list(array('visible' => true), array(), array('id DESC'), $limit, array('debates_newest' . $limit, 60*10));
	}

	public function has_voted( $id, $user_id )
	{
		return db_key::i()->exists('debate_vote:' . $id . ':' . $user_id);
	}

	public function vote( $id, $user_id )
	{
		db_key::i()->set('debate_vote:' . $id . ':' . $user_id, true);
	}

	public function search( $keyword, $limit = 5 )
	{
		$keyword = str_replace(' ', ' | ', $keyword);
		$sql = 'SELECT id FROM ' . $this->table_name . ' WHERE fti @@ to_tsquery(\'russian\', :keyword) LIMIT :limit;';
		return db::get_cols($sql, array('keyword' => $keyword, 'limit' => $limit), $this->connection_name);
	}

	public function reindex( $id )
	{
		$index_columns = array('text', 'tags_text');
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
}