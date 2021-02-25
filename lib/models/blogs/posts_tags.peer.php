<?

class blogs_posts_tags_peer extends db_peer_postgre
{
	protected $table_name = 'blogs_posts_tags';
	protected $primary_key = null;

	/**
	 * @return blogs_posts_tags_peer
	 */
	public static function instance()
	{
		return parent::instance( 'blogs_posts_tags_peer' );
	}

	public function delete_by_post( $id )
	{
		$sql = 'DELETE FROM ' . $this->table_name . ' WHERE post_id = :post_id';
		db::exec($sql, array('post_id' => $id), $this->connection_name);
	}
}