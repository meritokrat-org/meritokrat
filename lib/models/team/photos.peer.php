<?

class team_photos_peer extends db_peer_postgre
{
	protected $table_name = 'team_photos';

	/**
	 * @return team_photos_peer
	 */
	public static function instance()
	{
		return parent::instance( 'team_photos_peer' );
	}

	public function get_by_group( $id, $album_id = null )
	{
		$where = array('group_id' => $id);

		if( !is_null($album_id) )
		{
			$where['album_id'] = $album_id;
		}

		return $this->get_list( $where );
	}

	public static function generate_photo_salt()
	{
		return rand(1000000, 9999999);;
	}
}