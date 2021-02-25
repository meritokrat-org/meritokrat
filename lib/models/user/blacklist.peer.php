<?

class user_blacklist_peer
{
	public static function insert( $user_id, $pair_id )
	{
		$list = self::get_list( $user_id );
		$list[] = $pair_id;
		$list = array_unique($list);

		db_key::i()->set('user_blacklist' . $user_id, serialize($list));
	}

	public static function delete( $user_id, $pair_id )
	{
		$list = self::get_list( $user_id );
		$list = array_diff($list, array($pair_id));

		db_key::i()->set('user_blacklist' . $user_id, serialize($list));
	}

	public static function is_banned( $user_id, $pair_id )
	{
		return in_array($pair_id, self::get_list($user_id));
	}

	public static function get_list( $user_id )
	{
		if ( !$list = db_key::i()->get('user_blacklist' . $user_id) )
		{
			return array();
		}
		else
		{
			return unserialize($list);
		}
	}
}