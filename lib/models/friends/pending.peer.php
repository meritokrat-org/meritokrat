<?

class friends_pending_peer extends db_peer_postgre
{
	protected $table_name = 'friends_pending';

	/**
	 * @return friends_pending_peer
	 */
	public static function instance()
	{
		return parent::instance( 'friends_pending_peer' );
	}

	public function add( $user_id, $sent_by )
	{
		$this->insert(array('user_id' => $user_id, 'sent_by' => $sent_by, 'date' => mktime(0, 0, 0, date('n'), date('j'), date('Y'))));
		mem_cache::i()->delete('friends_pending:' . $user_id);
	}

	public function delete( $user_id, $sent_by )
	{
		$sql = 'DELETE FROM ' . $this->table_name . ' WHERE user_id = :user_id AND sent_by = :sent_by';
		db::exec($sql, array('user_id' => $user_id, 'sent_by' => $sent_by), $this->connection_name);
		mem_cache::i()->delete('friends_pending:' . $user_id);
	}

	public function get_by_user( $user_id )
	{
		$sql = 'SELECT sent_by FROM ' . $this->table_name . ' WHERE user_id = :user_id';
		return db::get_cols($sql, array('user_id' => $user_id), $this->connection_name, 'friends_pending:' . $user_id);
	}

	public function is_pending( $user_id, $friend_id )
	{
		$list = $this->get_by_user($user_id);
		return in_array($friend_id, $list);
	}

        public function check_limit( $user_id )
        {
                $num = db::get_scalar('SELECT COUNT(*) FROM ' . $this->table_name . ' WHERE sent_by = '.$user_id.' AND date = '.mktime(0, 0, 0, date('n'), date('j'), date('Y')));
                if($num>=10)
                    return false;
                else
                    return true;
        }
}