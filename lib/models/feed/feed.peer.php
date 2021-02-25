<?

class feed_peer extends db_peer_postgre
{
	protected $table_name = 'feed';

	const ACTION_BLOG_POST = 1;
	const ACTION_POLL = 3;
	const ACTION_IDEA = 4;
	const ACTION_GROUP_FORUM_POST = 6;
	const ACTION_BLOG_POST_COMMENT = 7;
	const ACTION_GROUP_PHOTO_ADD = 8;

	const SECTION_PERSONAL = 1;
	const SECTION_DISCUSSIONS = 2;

	public static function get_action( $id )
	{
		$actions = array(
			self::ACTION_BLOG_POST => t('Новая запись'),
			self::ACTION_POLL => t('Новый опрос'),
			self::ACTION_IDEA => t('Новая идея'),
			self::ACTION_GROUP_FORUM_POST => t('Новая тема в группе'),
			self::ACTION_BLOG_POST_COMMENT => t('оставил комментарий к посту'),
			self::ACTION_GROUP_PHOTO_ADD => t('Новые фотографии в группе'),
		);

		return $actions[$id];
	}

	public static function set_user_flag( $user_id )
	{
		db_key::i()->set('feed:user' . $user_id, true);
	}

	public static function reset_user_flag( $user_id )
	{
		db_key::i()->delete('feed:user' . $user_id);
	}

	public static function has_updates( $user_id )
	{
		return db_key::i()->exists('feed:user' . $user_id);
	}

	/**
	 * @return feed_peer
	 */
	public static function instance()
	{
		return parent::instance( 'feed_peer' );
	}

	public function get_by_user( $user_id )
	{
		return $this->get_list(array('user_id' => $user_id));
	}

	public function clear_by_user( $user_id )
	{
		$sql = 'DELETE FROM ' . $this->table_name . ' WHERE user_id = :user_id';
		return db::exec($sql, array('user_id' => $user_id), $this->connection_name);
	}


	public function clear_by_content($oid=0, $action=1, $actor_id=false )
	{
                if ($oid)
                {
                    if ($actor_id) $where=' AND actor='.$actor_id;
                    $sql = 'DELETE FROM ' . $this->table_name . ' WHERE oid=:oid AND action = :action'.$where;
                    return db::exec($sql, array('oid' => $oid,'action' => $action), $this->connection_name);
                }
	}

	public function add( $initiator, $readers, $data )
	{
		if ( !$readers )
		{
			return;
		}

		$data['created_ts'] = time();
                if (in_array($data['action'],array(1,7))){

                    foreach ( $readers as $user_id )
                    {
                            $data['user_id'] = $user_id;
                            $this->insert($data);

                            self::set_user_flag($user_id);
                    }
                }
	}
}
