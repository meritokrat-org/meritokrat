<?

class party_helper
{
	public static function photo( $id, $size = 'p', $link = true, $options = array() )
	{
		$html = tag_helper::image(self::photo_path($id, $size), $options, context::get('image_server'));

		if ( $link )
		{
			$party = parties_peer::instance()->get_item( $id );
			$title = htmlspecialchars($party['title']);

			$html = "<a title=\"{$title}\" href=\"/party{$id}\">{$html}</a>";
		}

		return $html;
	}

	public static function photo_path( $id, $size = 'p' )
	{
		$data = parties_peer::instance()->get_item($id);
		return "{$size}/party/{$id}{$data['photo_salt']}.jpg";
	}

	public static function title( $id, $linked = true )
	{
		$data = parties_peer::instance()->get_item($id);
		$html = htmlspecialchars($data['title']);

		if ( $linked )
		{
			$html = "<a href=\"/party{$data['id']}\">{$html}</a>";
		}

		return $html;
	}
}