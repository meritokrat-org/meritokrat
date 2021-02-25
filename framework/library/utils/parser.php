<?

class parser
{
	public static function get_match( $pattern, $string )
	{
		preg_match($pattern, $string, $matches);

		if ( $matches )
		{
			array_shift($matches);
			return $matches;
		}
	}

	public static function get_match_scalar( $pattern, $string )
	{
		$matches = self::get_match( $pattern, $string );

		return $matches[0];
	}

	public static function get_matches( $pattern, $string )
	{
		preg_match_all($pattern, $string, $matches);

		if ( $sub_count = count($matches) )
		{
			if ( $found_count = count($matches[1]) )
			{
				$found = array();

				for ( $i = 0; $i < $found_count; $i ++ )
				{
					for ( $j = 0; $j < $sub_count; $j++ )
					{
						$found[$i][$j] = $matches[$j][$i];
					}
				}

				return $found;
			}
		}

		return null;
	}
}