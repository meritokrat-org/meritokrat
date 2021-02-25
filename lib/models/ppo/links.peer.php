<?

class ppo_links_peer extends db_peer_postgre
{
	protected $table_name = 'ppo_links';

	/**
	 * @return ppo_photos_peer
	 */
	public static function instance()
	{
		return parent::instance( 'ppo_links_peer' );
	}

	public function get_by_group( $id, $dir_id = null )
	{
		$where = array('group_id' => $id);

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