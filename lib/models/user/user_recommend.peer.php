<?

class user_recommend_peer extends db_peer_postgre
{
	protected $table_name = 'user_recommend';
	protected $primary_key = 'id';

	/**
	 * @return user_recomendations_peer
	 */
	public static function instance()
	{
		return parent::instance('user_recommend_peer');
	}

	public function check_recommend($user_id)
	{
		return db::get_scalar('SELECT ' . $this->primary_key . ' FROM ' . $this->table_name . ' WHERE user_id = ' . $user_id);
	}

	public function get_recommenders($user_id, $status = false)
	{
		$sql = 'SELECT recommending_user_id FROM ' . $this->table_name . ' WHERE user_id = ' . $user_id . ' ' . ($status >= 10 ? 'and status=' . intval($status) : '');
		return db::get_cols($sql);
	}
}
