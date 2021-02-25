<?

class team_links_dirs_peer extends db_peer_postgre
{
	protected $table_name = 'team_links_dirs';

	/**
	 * @return team_photos_albums_peer
	 */
	public static function instance()
	{
		return parent::instance( 'team_links_dirs_peer' );
	}

	public function get_by_group( $id )
	{
		return $this->get_list( array('group_id' => $id) );
	}

	public function get_album_screen_photo( $id, $group_id = null )
	{
		$sql = 'SELECT id FROM ' . team_links_peer::instance()->get_table_name() .
				' WHERE dir_id = :dir_id ' .
				 ( $group_id ? ' AND group_id = :group_id ' : '' ) .
				' LIMIT 1';

		return (int)db::get_scalar($sql, array('dir_id' => $id, 'group_id' => $group_id));
	}
}