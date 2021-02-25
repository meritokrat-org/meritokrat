<?

class parties_news_peer extends db_peer_postgre
{
	protected $table_name = 'parties_news';

	/**
	 * @return parties_news_peer
	 */
	public static function instance()
	{
		return parent::instance( 'parties_news_peer' );
	}

	public function get_by_party( $party_id )
	{
		return $this->get_list(array('party_id' => $party_id));
	}

	public function get_new()
	{
		return $this->get_list(array(), array(), array('id DESC'), 100, array('parties_news_new', 60*60));
	}
}