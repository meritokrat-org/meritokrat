<?

class tags_helper
{
	public static function normalize( $list, $weight_key = 'weight' )
	{
		$max = 0;
		foreach ( $list as $data )
		{
			if ( $max < $data[$weight_key] ) $max = $data[$weight_key];
		}

		$tags = array();

		foreach ( $list as $data )
		{
			$tag_data = $data;
			$tag_data['weight'] = intval($data[$weight_key]*10/$max);
			$tags[] = $tag_data;
		}

		return $tags;
	}
}