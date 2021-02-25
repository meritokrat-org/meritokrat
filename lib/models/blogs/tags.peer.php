<?

load::model('tags');

class blogs_tags_peer extends tags_peer
{
	protected $table_name = 'blogs_tags';

	/**
	 * @return blogs_tags_peer
	 */
	public static function instance()
	{
		return parent::instance( 'blogs_tags_peer' );
	}

	public function get_top()
	{
		$sql = '
                SELECT bpt.tag_id as id, count(bpt.post_id) as posts FROM blogs_posts bp
                JOIN blogs_posts_tags bpt ON (bpt.post_id = bp.id)
                GROUP BY bpt.tag_id
                LIMIT 15';

                

		$list = db::get_rows($sql, array(), $this->connection_name, array('top_tags', 60*60*2));

		return tags_helper::normalize($list,'posts');
	}
}