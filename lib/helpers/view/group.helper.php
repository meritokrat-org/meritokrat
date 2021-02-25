<?

class group_helper
{
	public static function photo( $id, $size = 'p', $link = true, $options = array() )
	{
		$html = tag_helper::image(self::photo_path($id, $size), $options, context::get('image_server'));

		if ( $link )
		{
			$group = groups_peer::instance()->get_item( $id );
			$title = htmlspecialchars($group['title']);

			$html = "<a title=\"{$title}\" href=\"/group{$id}\">{$html}</a>";
		}

		return $html;
	}

	public static function photo_path( $id, $size = 'p' )
	{
		$data = groups_peer::instance()->get_item($id);
		return "{$size}/group/{$id}{$data['photo_salt']}.jpg";
	}

	public static function media_photo_path( $id, $size = 'p' )
	{
		$data = groups_photos_peer::instance()->get_item($id);
		return "{$size}/group_photo/{$id}-{$data['salt']}.jpg";
	}

	public static function media_photo( $id, $size = 'p', $options = array() )
	{
		$data = groups_photos_peer::instance()->get_item($id);
		$options['title'] = htmlspecialchars($data['title']);

		return tag_helper::image(self::media_photo_path($id, $size), $options, context::get('image_server'));
	}

	public static function title( $id, $linked = true )
	{
		$data = groups_peer::instance()->get_item($id);
		$html = htmlspecialchars($data['title']);

		if ( $linked )
		{
			$html = "<a href=\"/group{$data['id']}\">{$html}</a>";
		}

		return $html;
	}
}
