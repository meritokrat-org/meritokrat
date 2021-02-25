<?

class leadergroups_news_peer extends db_peer_postgre
{
	protected $table_name = 'leadergroups_news';

	/**
	 * @return leadergroups_news_peer
	 */
	public static function instance()
	{
		return parent::instance( 'leadergroups_news_peer' );
	}

	public function get_by_leadergroup( $id )
	{
		return $this->get_list(array('leadergroup_id' => $id));
	}

	public function get_new()
	{
		return $this->get_list(array(), array(), array('id DESC'), 100, array('leadergroups_news_new', 60*60));
	}
}