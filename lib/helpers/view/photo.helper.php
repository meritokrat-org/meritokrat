<?

class photo_helper
{
	public static function photo_path( $id, $size = 'p' )
	{
		$data = photo_peer::instance()->get_item($id);
                if($data['salt'])
                    return "{$size}/photoalbum/{$id}-{$data['salt']}.jpg";
                else
                    return "{$size}/group_photo/0.jpg";
	}

	public static function photo( $id, $size = 'p', $options = array() )
	{
		$data = photo_peer::instance()->get_item($id);
		$options['title'] = htmlspecialchars($data['title']);

		return tag_helper::image(self::photo_path($id, $size), $options, context::get('image_server'));
	}

}
