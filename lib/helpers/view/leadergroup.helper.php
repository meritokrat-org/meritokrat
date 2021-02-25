<?

class leadergroup_helper
{
	public static function photo( $id, $size = 'p', $link = true, $options = array() )
	{
		$html = tag_helper::image(self::photo_path($id, $size), $options, context::get('image_server'));

		if ( $link )
		{
			$leadergroup = leadergroups_peer::instance()->get_item( $id );
			$title = htmlspecialchars($leadergroup['title']);

			$html = "<a title=\"{$title}\" href=\"/leadergroup{$id}\">{$html}</a>";
		}

		return $html;
	}

	public static function photo_path( $id, $size = 'p' )
	{
		$data = leadergroups_peer::instance()->get_item($id);
		return "{$size}/leadergroup/{$id}{$data['photo_salt']}.jpg";
	}

	public static function media_photo_path( $id, $size = 'p' )
	{
		$data = leadergroups_photos_peer::instance()->get_item($id);
		return "{$size}/leadergroup_photo/{$id}-{$data['salt']}.jpg";
	}

	public static function media_photo( $id, $size = 'p', $options = array() )
	{
		$data = leadergroups_photos_peer::instance()->get_item($id);
		$options['title'] = htmlspecialchars($data['title']);

		return tag_helper::image(self::media_photo_path($id, $size), $options, context::get('image_server'));
	}

	public static function title( $id, $linked = true )
	{
		$data = leadergroups_peer::instance()->get_item($id);
		$html = htmlspecialchars($data['title']);

		if ( $linked )
		{
			$html = "<a href=\"/leadergroup{$data['id']}\">{$html}</a>";
		}

		return $html;
	}
}
