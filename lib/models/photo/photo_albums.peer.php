<?

class photo_albums_peer extends db_peer_postgre
{
	protected $table_name = 'photo_albums';

	public static function instance()
	{
		return parent::instance( 'photo_albums_peer' );
	}

	public function get_by_obj( $id,$type )
	{
		return $this->get_list( array('obj_id' => $id,'type'=>$type) );
	}

	public function get_album_screen_photo( $id )
	{
		$sql = 'SELECT id FROM ' . photo_peer::instance()->get_table_name() .
				' WHERE album_id = :album_id ' .
                                //' AND cover = 1 '.
				' LIMIT 1';

		return (int)db::get_scalar($sql, array('album_id' => $id));
	}
}