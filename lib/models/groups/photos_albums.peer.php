<?

class groups_photos_albums_peer extends db_peer_postgre
{
	protected $table_name = 'groups_photos_albums';

	/**
	 * @return groups_photos_albums_peer
	 */
	public static function instance()
	{
		return parent::instance( 'groups_photos_albums_peer' );
	}

	public function get_by_group( $id )
	{
		return $this->get_list( array('group_id' => $id) );
	}

	public function get_album_screen_photo( $id, $group_id = null )
	{
		$sql = 'SELECT id FROM ' . groups_photos_peer::instance()->get_table_name() .
				' WHERE album_id = :album_id ' .
				 ( $group_id ? ' AND group_id = :group_id ' : '' ) .
				' LIMIT 1';

		return (int)db::get_scalar($sql, array('album_id' => $id, 'group_id' => $group_id));
	}
}