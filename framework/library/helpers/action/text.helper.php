<?

class text_helper
{
	public static function smart_trim( $text, $size, $delta_max = 16 )
	{
		$char = '';
		$i = 0;

		if ( mb_strlen($text) <= $size)
		{
			return $text;
		}

		while ( ( $i < $delta_max ) && ( $char != ' ' ) )
		{
			$char = mb_substr($text, $size + $i, 1);
			$i++;
		}

		return mb_substr($text, 0, $size + $i) . '...';
	}
}
