<?

class leadergroups_photos_albums_peer extends db_peer_postgre
{
	protected $table_name = 'leadergroups_photos_albums';

	/**
	 * @return leadergroups_photos_albums_peer
	 */
	public static function instance()
	{
		return parent::instance( 'leadergroups_photos_albums_peer' );
	}

	public function get_by_leadergroup( $id )
	{
		return $this->get_list( array('leadergroup_id' => $id) );
	}

	public function get_album_screen_photo( $id, $leadergroup_id = null )
	{
		$sql = 'SELECT id FROM ' . leadergroups_photos_peer::instance()->get_table_name() .
				' WHERE album_id = :album_id ' .
				 ( $leadergroup_id ? ' AND leadergroup_id = :leadergroup_id ' : '' ) .
				' LIMIT 1';

		return (int)db::get_scalar($sql, array('album_id' => $id, 'leadergroup_id' => $leadergroup_id));
	}
}