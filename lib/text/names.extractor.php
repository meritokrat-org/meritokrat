<?

load::model('user/user_data');

class names_extractor
{
	public static function process( $original_text, &$users = null )
	{
		$dictionary = self::get_dictionary();

		$text = mb_strtolower($original_text);
		
		preg_match_all("/\p{L}[\p{L}\p{Mn}\p{Pd}'\x{2019}]*/u", $text, $matches, PREG_OFFSET_CAPTURE);
		
		$words = array();
		foreach ($matches[0] as $match)
		{
			if ( $word = self::stem($match[0]) )
			{
				$words[] = array('word' => $word, 'start' => $match[1], 'end' => $match[1] + strlen($match[0]));
			}
		}
		
		$t = microtime(true);
		foreach ( $words as $i => $word_data )
		{
			$forms = self::get_forms( $i, $words );
			foreach ( $forms as $form )
			{
				if ( $user_id = $dictionary[$form['phrase']] )
				{
					$replacements[] = array('user_id' => $user_id, 'start' => $form['start'], 'end' => $form['end']);
					break;
				}
			}
		}

		$delta = 0;
		load::view_helper('user');
		$users = array();
		if ( $replacements ) foreach ( $replacements as $data )
		{
			$length = $data['end'] - $data['start'];

			$substr = substr($original_text, $delta + $data['start'], $length);

			$user_title = user_helper::full_name($data['user_id'], false);
			$replace = "<a title=\"{$user_title}\" href=\"/profile-{$data['user_id']}\">{$substr}</a>";

			$original_text = substr_replace( $original_text, $replace, $delta + $data['start'], $length );
			$delta += strlen($replace) - $length;

			$users[] = $data['user_id'];
		}

		$users = array_unique($users);

		return $original_text;
	}

	protected static function get_dictionary()
	{
		load::model('user/dictionary');
		$dict = user_dictionary_peer::instance()->get_all();

		foreach ( $dict as $key => $value )
		{
			$keys = explode(' ', $key);
			foreach ( $keys as &$word ) $word = self::stem($word);
			$key = implode( '', $keys );
			$dictionary[$key] = $value;
		}

		return $dictionary;
	}

	protected static function get_forms( $index, &$words, $forms = 3 )
	{
		for ( $f = $forms; $f > 0; $f-- )
		{
			if ( ( $start = $index - $f + 1 ) < 0 ) $start = 0;
			for ( $i = $start; $i <= $index; $i++ )
			{
				$list[$f]['phrase'] .= $words[$i]['word'];
				$list[$f]['start'] = $list[$f]['start'] ? min( $list[$f]['start'], $words[$i]['start'] ) : $words[$i]['start'];
				$list[$f]['end'] = max( $list[$f]['end'], $words[$i]['end'] );
			}
		}

		return $list;
	}
	
	protected static function stem( $word )
	{
		$word = preg_replace('/[уеыаоэяюийіїєё]+$/u', '', $word);
		return preg_replace('/(ем|ом)$/u', '', $word);
	}
}
