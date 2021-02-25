<?

class friends_news_peer extends db_peer_postgre
{
	protected $table_name = 'friends_news';

	/**
	 * @return friends_news_peer
	 */
	public static function instance()
	{
		return parent::instance( 'friends_news_peer' );
	}

	public function add( $user_id, $sent_by )
	{
		$this->insert(array('user_id' => $user_id, 'sent_by' => $sent_by));
		mem_cache::i()->delete('friends_news:' . $user_id);
	}

	public function delete( $user_id, $sent_by )
	{
		$sql = 'DELETE FROM ' . $this->table_name . ' WHERE user_id = :user_id AND sent_by = :sent_by';
		db::exec($sql, array('user_id' => $user_id, 'sent_by' => $sent_by), $this->connection_name);
		mem_cache::i()->delete('friends_news:' . $user_id);
	}

	public function get_by_user( $user_id )
	{
        $arr=array();    
	$friends = db::get_cols('SELECT friend_id FROM friends WHERE user_id = ' . $user_id);	
        foreach($friends as $fid){
            $sql = 'SELECT id FROM ' . $this->table_name . ' WHERE sent_by = :sent_by AND user_id!=:user_id';
		$arr = array_merge($arr,db::get_cols($sql, array('sent_by' => $fid,'user_id'=>$user_id), $this->connection_name));
        } 
        return $arr;
        }
}