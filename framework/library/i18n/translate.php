<?

class translate
{
	protected static $lang = null;
	protected static $translations = array();

	public static function phrase( $phrase, $vars, $lang )
	{
		if ( !$lang )
		{
			$lang = self::$lang;
		}

		return self::get_translation($phrase, $lang);
	}

	public static function get_translation( $phrase, $lang, $strict = false )
	{
		$i18n_conf = conf::get('i18n');

		if ( $lang == $i18n_conf['default_lang'] )
		{
			return $phrase;
		}

		if ( !self::$translations[$lang] )
		{
			$path = conf::get('project_root') . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'i18n' . DIRECTORY_SEPARATOR . $lang . '.php';
			require $path;
			self::$translations[$lang] = $data;
		}

		if ( $strict )
		{
			return self::$translations[$lang][$phrase];
		}

		return self::$translations[$lang][$phrase] ? self::$translations[$lang][$phrase] : $phrase;
	}

	public static function get_data( $lang )
	{
		$i18n_conf = conf::get('i18n');

		if ( $lang == $i18n_conf['default_lang'] )
		{
			return array();
		}

		if ( !self::$translations[$lang] )
		{
			$path = conf::get('project_root') . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'i18n' . DIRECTORY_SEPARATOR . $lang . '.php';
			require $path;
			self::$translations[$lang] = $data;
		}

		return self::$translations[$lang];
	}

	public static function set_lang( $code )
	{
		return self::$lang = $code;
	}

	public static function get_lang()
	{
		return self::$lang;
	}
}

function t( $phrase, $vars = array(), $lang = null )
{
	return translate::phrase($phrase, $vars, $lang);
}