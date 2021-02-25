<?

class i18n_translate_task extends shell_task
{
	public function execute()
	{
		load::system('i18n/translate');

		$this->out('Parsing files and gathering language phrases');
		$this->parse_files(conf::get('project_root'), $list);

		$list = array_unique($list);

		$translated = array();

		foreach ( $list as $phrase )
		{
			if ( $translation = translate::get_translation($phrase, 'ua', true) )
			{
				$translated[$phrase] = $translation;
			}
			else
			{
				$untranslated[] = $phrase;
			}
		}

		$untranslated = array_unique($untranslated);

		if ( $untranslated )
		{
			$pos = 0;
			$translated_delta = array();
			$this->out('Translating');
			while ( $sub_list = array_slice($untranslated, $pos, 10) )
			{
				if ( !$translated_sub_list = translateTexts($sub_list, 'ru', 'uk') )
				{
					exit;
				}
				
				foreach ( $translated_sub_list as $phrase )
				{
					$translated_delta[] = $phrase;
				}

				echo '.';
				sleep(1);
				$pos += 2;
			}
			$this->out();

			foreach ( $untranslated as $k => $phrase )
			{
				$translated[$phrase] = $translated_delta[$k];
			}
		}

		$this->save_i18n_data($translated, 'ua');
		$this->out('Processed ' . count($list) . ' phrases');
	}

	public function save_i18n_data($phrases, $code)
	{
		$php = '<? $data = array(';
		foreach ( $phrases as $phrase => $translation )
		{
			$phrase = str_replace("'", '\\\'', $phrase);
			$translation = str_replace("'", '\\\'', $translation);
			$php .= "\n\t'{$phrase}' => '{$translation}',";
		}
		$php .= ');';

		$path = conf::get('project_root') . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'i18n' . DIRECTORY_SEPARATOR . $code . '.php';
		file_put_contents($path, $php);
	}

	public function parse_files( $dir, &$list )
	{
		$d = opendir($dir);
		while ( $f = readdir($d) )
		{
			if ( ( $f != '.' ) && ( $f != '..' ) )
			{
				if ( is_dir($dir . '/' . $f) )
				{
					$this->parse_files($dir . '/' . $f, $list);
				}
				else if ( strpos($f, '.php') )
				{
					$data = file_get_contents($dir . '/' . $f);
					$phrases = array();
					
					preg_match_all('/[= ]t\(\'([^\']+)\'\)/', $data, $phrases);
					if ( $phrases[1] )
					{
						foreach ( $phrases[1] as $phrase )
						{
							$list[] = $phrase;
						}
					}
				}
			}
		}

		if ( $list )
		{
			$list = array_values(array_unique($list));
		}
	}
}

function translateTexts($src_texts = array(), $src_lang, $dest_lang){
  //setting language pair
  $lang_pair = $src_lang.'|'.$dest_lang;

  $src_texts_query = "";
  foreach ($src_texts as $src_text){
    $src_texts_query .= "&q=".urlencode($src_text);
  }

  $url = "http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&langpair=".urlencode($lang_pair);

  // sendRequest
  // note how referer is set manually

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $src_texts_query);
  curl_setopt($ch, CURLOPT_REFERER, "http://www.google.com");
  $body = curl_exec($ch); 
  curl_close($ch);

  // now, process the JSON string
  $json = json_decode($body, true);

  if ($json['responseStatus'] != 200){
    return false;
  }

  $results = $json['responseData'];

  $return_array = array();

  foreach ($results as $result){
	  if ( is_array($result) )
	  {
		$return_array[] = $result['responseData']['translatedText'];
	  }
	  else
	  {
		$return_array[] = $result;
	  }
  }

  //return translated text
  return $return_array;
}