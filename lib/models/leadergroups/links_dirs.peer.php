<?

class leadergroups_links_dirs_peer extends db_peer_postgre
{
	protected $table_name = 'leadergroups_links_dirs';

	/**
	 * @return leadergroups_photos_albums_peer
	 */
	public static function instance()
	{
		return parent::instance( 'leadergroups_links_dirs_peer' );
	}

	public function get_by_leadergroup( $id )
	{
		return $this->get_list( array('leadergroup_id' => $id) );
	}

	public function get_album_screen_photo( $id, $leadergroup_id = null )
	{
		$sql = 'SELECT id FROM ' . leadergroups_links_peer::instance()->get_table_name() .
				' WHERE dir_id = :dir_id ' .
				 ( $leadergroup_id ? ' AND leadergroup_id = :leadergroup_id ' : '' ) .
				' LIMIT 1';

		return (int)db::get_scalar($sql, array('dir_id' => $id, 'leadergroup_id' => $leadergroup_id));
	}
}