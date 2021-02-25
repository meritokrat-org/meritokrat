<?

class photo_competition_comments_peer extends db_peer_postgre
{
	protected $table_name = 'photo_competition_comments';

	public static function instance()
	{
		return parent::instance( 'photo_competition_comments_peer' );
	}
}