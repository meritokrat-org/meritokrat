<?

load::model('tags');

class debates_tags_peer extends tags_peer
{
	protected $table_name = 'debates_tags';

	/**
	 * @return debates_tags_peer
	 */
	public static function instance()
	{
		return parent::instance( 'debates_tags_peer' );
	}

	public function get_top()
	{
		$sql = '
		SELECT
			dt.tag_id as id, count(dt.debate_id) as debates
		FROM
		' . debates_peer::instance()->get_table_name() . ' d
		JOIN
		' . debates_debates_tags_peer::instance()->get_table_name() . ' dt ON (dt.debate_id = d.id)
		WHERE
			d.created_ts > :offset
		GROUP BY
			dt.tag_id
		LIMIT 15
		';

		$list = db::get_rows($sql, array('offset' => time() - 60*60*24*7));

		return tags_helper::normalize($list, 'debates');
	}
}