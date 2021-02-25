<?

class leadergroups_files_dirs_peer extends db_peer_postgre
{
	protected $table_name = 'leadergroups_files_dirs';

	/**
	 * @return leadergroups_files_dirs_peer
	 */
	public static function instance()
	{
		return parent::instance( 'leadergroups_files_dirs_peer' );
	}

	public function get_by_leadergroup( $id )
	{
		return $this->get_list( array('leadergroup_id' => $id) );
	}

}