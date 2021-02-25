<?

class photo_competition_peer extends db_peer_postgre
{
	protected $table_name = 'photo_competition';

	public static function instance()
	{
		return parent::instance( 'photo_competition_peer' );
	}
        
	public function has_voted( $photo_id, $user_id )
	{
		return db::get_scalar("SELECT user_id FROM photo_competition WHERE  id =".$photo_id." AND  voters && '{".$user_id."}'");
	}	
        
        public static function photo( $id, $size = 'p', $options = array(), $linked = true )
	{
            return tag_helper::image(self::photo_path($id, $size), $options, context::get('image_server'));
        }

	public static function photo_path( $id, $size = 'p' )
	{
            return "{$size}/photocompetition/".substr(md5($id),0,8).".jpg";
	}
}