<?

class polls_peer extends db_peer_postgre
{
	protected $table_name = 'polls';

	/**
	 * @return polls_peer
	 */
	public static function instance()
	{
		return parent::instance( 'polls_peer' );
	}

	public function get_by_user( $user_id )
	{
		return $this->get_list( array('user_id' => $user_id) );
	}

	public function get_newest()
	{
		$result = $this->get_list( array('visible' => true), array(), array('id DESC'), 100, array('polls_newest', 60*10) );
                if (session::is_authenticated()) $invites = db::get_cols("SELECT obj_id FROM invites WHERE type=3 AND to_id=".session::get_user_id());
                if(!is_array($invites))$invites=array();
                foreach($result as $k => $v)
                {
                    $item = db::get_row('SELECT id,user_id,hidden FROM polls WHERE id = '.$v);
                    if( $item['hidden'] && !in_array($item['id'], $invites) &&  !session::has_credential('admin') )
                    {
                         unset($result[$k]);
                    }
                }
                return $result;
	}

	public function get_promoted()
	{
		return $this->get_list( array('promoted' => true), array(), array('id DESC'), 5 );
	}

	public function get_hot()
	{
		$sql = 'SELECT id FROM ' . $this->table_name . ' WHERE visible = true AND created_ts > :offset ORDER BY "count" DESC, id DESC LIMIT 100';
		return db::get_cols($sql, array('offset' => time() - 60*60*24*21), $this->connection_name);
	}

	public function search( $keyword, $limit = 5 )
	{
		$keyword = str_replace(' ', ' | ', $keyword);
		$sql = 'SELECT id FROM ' . $this->table_name . ' WHERE fti @@ to_tsquery(\'russian\', :keyword) LIMIT :limit;';
		return db::get_cols($sql, array('keyword' => $keyword, 'limit' => $limit), $this->connection_name);
	}

	public function reindex( $id )
	{
		$index_columns = array('question');
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