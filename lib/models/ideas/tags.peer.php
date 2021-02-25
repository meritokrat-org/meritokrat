<?

load::model('tags');

class ideas_tags_peer extends tags_peer
{
	protected $table_name = 'ideas_tags';

	/**
	 * @return blogs_tags_peer
	 */
	public static function instance()
	{
		return parent::instance( 'ideas_tags_peer' );
	}


	public function get_by_tag( $tag_id )
	{
		$sql = '
		SELECT p.id
		FROM ideas p
		JOIN ' . ideas_to_tags_peer::instance()->get_table_name() . ' pt ON (pt.idea_id = p.id)
		WHERE pt.tag_id = :tag_id
		GROUP BY p.id
		ORDER BY id DESC
		LIMIT 50';
                return db::get_cols($sql, array('tag_id' => $tag_id));

	}
	public function get_top()
	{
		$sql = '
		SELECT
			pt.tag_id as id, count(pt.post_id) as posts
		FROM
		' . ideas_tags_peer::instance()->get_table_name() . ' p
		JOIN
		' . ideas_to_tags_peer::instance()->get_table_name() . ' pt ON (pt.post_id = p.id)
		WHERE
			p.created_ts > :offset
		GROUP BY
			pt.tag_id
		LIMIT 15
		';

		$list = db::get_rows($sql, array('offset' => time() - 60*60*24*14), $this->connection_name, array('top_tags', 60*60*2));

		return tags_helper::normalize($list, 'posts');
	}
}