<?

class team_news_peer extends db_peer_postgre
{
	protected $table_name = 'team_news';

	/**
	 * @return team_news_peer
	 */
	public static function instance()
	{
		return parent::instance( 'team_news_peer' );
	}

	public function get_by_group( $id )
	{
		return $this->get_list(array('group_id' => $id));
	}

	public function get_new($team=false)
	{       
                if ($team and count($team)>1) $where='WHERE group_id in ('.explode(',',$team).')';
		return db::get_cols('SELECT ' . $this->primary_key . ' FROM ' . $this->table_name .' '.$where.' ORDER by id DESC LIMIT 10', array(), null, array('team_news_new_'.session::get_user_id(), 60*60));
	}
}