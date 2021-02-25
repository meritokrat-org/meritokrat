<?

class leadergroups_links_peer extends db_peer_postgre
{
	protected $table_name = 'leadergroups_links';

	/**
	 * @return leadergroups_photos_peer
	 */
	public static function instance()
	{
		return parent::instance( 'leadergroups_links_peer' );
	}

	public function get_by_leadergroup( $id, $dir_id = null )
	{
		$where = array('leadergroup_id' => $id);

		if( !is_null($dir_id) )
		{
			$where['dir_id'] = $dir_id;
		}

		return $this->get_list( $where );
	}

	public static function generate_photo_salt()
	{
		return rand(1000000, 9999999);;
	}
}