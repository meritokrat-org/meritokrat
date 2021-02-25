<?

class bookmarks_peer extends db_peer_postgre
{
	protected $table_name = 'bookmarks';

	const TYPE_BLOG_POST = 1;

	/**
	 * @return bookmarks_peer
	 */
	public static function instance()
	{
            return parent::instance( 'bookmarks_peer' );
	}

	public function add( $user_id, $type, $oid )
	{
            $this->insert(array('user_id' => $user_id,'type' => $type,'oid' => $oid));
            mem_cache::i()->delete('user_bookmarks_' .$type. ':' . $user_id);
            mem_cache::i()->delete('user_bookmarks:' . $user_id);
	}

	public function get_by_user( $user_id, $type = 0 )
	{
            $cache_key = 'user_bookmarks:'. $user_id;
            $bind['user_id'] = $user_id;
            if($type)
            {
                $where = "AND type = :type";
                $bind['type'] = $type;
                $cache_key = 'user_bookmarks_' . $type . ':' . $user_id;
            }
            $sql = 'SELECT type, oid, id FROM ' . $this->table_name . ' WHERE user_id = :user_id '.$where.' ORDER BY id DESC';
            return db::get_rows($sql, $bind, $this->connection_name, $cache_key);
	}

	public function get_users_who_like( $user_id, $type = 6 ) //все пользователи у кого товарищь в любимых авторах(блог в закладках и тп)
	{
            $cache_key = 'user_who_like:' . $user_id;
            if (!$user_id) $user_id=session::get_user_id();
            $bind['user_id'] = $user_id;
            if($type)
            {
                $where = "AND type = :type";
                $bind['type'] = $type;
                $cache_key = 'user_who_like:' . $type . ':' . $user_id;
            }
            $sql = 'SELECT user_id FROM ' . $this->table_name . ' WHERE oid=:user_id '.$where.' ORDER BY id DESC';
            return db::get_cols($sql, $bind, $this->connection_name, $cache_key);
	}

	public function is_bookmarked( $user_id, $type, $oid )
	{
            $sql = 'SELECT id FROM ' . $this->table_name . ' WHERE user_id = :user_id AND type = :type AND oid = :oid LIMIT 1';
            $query = db::get_row($sql, array('user_id'=>$user_id,'type'=>$type,'oid'=>$oid), $this->connection_name, $cache_key);
            return $query['id'];
	}

	public function get_by_object( $type, $id )
	{
            $sql = 'SELECT user_id FROM ' . $this->table_name . ' WHERE type = :type AND oid = :oid';
            return db::get_cols($sql, array('type' => $type, 'oid' => $id), $this->connection_name);
	}

	public function delete_item( $id )
	{
            $data = $this->get_item($id);
            mem_cache::i()->delete('user_bookmarks:' . $data['user_id']);
            mem_cache::i()->delete('user_bookmarks_'.$data['type'].':' . $data['user_id']);
            parent::delete_item($id);
	}

        public function get_by_usr( $user_id, $type  )
	{
            return db::get_cols('SELECT oid FROM ' . $this->table_name . ' WHERE user_id = '.$user_id.' AND type = '.$type);
	}
}