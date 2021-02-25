<?

class candidates_votes_peer extends db_peer_postgre
{
	protected $table_name = 'candidates_votes';
	protected $primary_key = null;

	/**
	 * @return candidates_votes_peer
	 */
	public static function instance()
	{
		return parent::instance( 'candidates_votes_peer' );
	}

	public function add($user_id, $candidate_id)
	{
		if ( $this->get_vote_by_user($user_id) )
		{
			$sql = 'DELETE FROM ' . $this->get_table_name() . ' WHERE user_id = :user_id';
			$bind = array('user_id' => $user_id);
			db::exec($sql, $bind, $this->connection_name);
		}

		$this->insert(array(
			'user_id' => $user_id,
			'candidate_id' => $candidate_id,
			'ip' => $_SERVER['REMOTE_ADDR'],
			'ts' => time()
		));

		mem_cache::i()->delete('candidate_user_vote' . $user_id);
	}

	public function get_vote_by_user( $user_id )
	{
		$key = 'candidate_user_vote' . $user_id;

		if ( mem_cache::i()->exists($key) )
		{
			return mem_cache::i()->get($key);
		}

		$sql = 'SELECT * FROM ' . $this->get_table_name() . ' WHERE user_id = :user_id LIMIT 1';
		$bind = array('user_id' => $user_id);
		$data = db::get_row($sql, $bind, $this->connection_name);
		mem_cache::i()->set($key, $data);

		return $data;
	}

	public function get_rating()
	{
		$key = 'candidates_rating';

		if ( mem_cache::i()->exists($key) )
		{
			return mem_cache::i()->get($key);
		}

		$sql = 'SELECT c.user_id as id, count(v.user_id) as votes
				FROM ' . candidates_peer::instance()->get_table_name() . ' c
				RIGHT JOIN ' . $this->get_table_name() . ' v ON ( c.user_id = v.candidate_id )
				GROUP BY c.user_id
				ORDER BY votes DESC';
		$data = db::get_rows($sql, array(), $this->connection_name);
		mem_cache::i()->set($key, $data, 60*10);

		return $data;
	}

	public function get_dynamics($candidate_id)
	{
		$key = 'candidates_dynamics' . $candidate_id;

		if ( mem_cache::i()->exists($key) )
		{
			return mem_cache::i()->get($key);
		}

		$sql = 'SELECT count(user_id), MAX(ts) as ts, EXTRACT(DOY FROM TIMESTAMP \'epoch\' + ts * INTERVAL \'1 second\') as doy
				FROM candidates_votes
				WHERE candidate_id = :id
				GROUP BY doy
				ORDER BY ts DESC
				LIMIT 14';

		$data = db::get_rows($sql, array('id' => $candidate_id), $this->connection_name);
		mem_cache::i()->set($key, $data, 60*10);

		return $data;
	}
}