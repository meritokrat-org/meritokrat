<?

class photo_peer extends db_peer_postgre
{
	protected $table_name = 'photo';

	public static function instance()
	{
		return parent::instance('photo_peer');
	}

	public function get_album($album_id)
	{
		$where['album_id'] = $album_id;
		return $this->get_list($where);
	}

	public function get_by_obj($id, $type)
	{
		$albums = db::get_cols('SELECT id FROM photo_albums WHERE type = ' . $type . ' AND obj_id = ' . $id);
		if (count($albums))
			return db::get_cols('SELECT id FROM ' . $this->table_name . ' WHERE album_id IN (' . implode(',', $albums) . ')');
		else
			return array();
	}

	public static function generate_photo_salt()
	{
		return rand(1000000, 9999999);;
	}
}