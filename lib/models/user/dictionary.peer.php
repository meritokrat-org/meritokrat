<?

class user_dictionary_peer extends db_peer_postgre
{
	protected $table_name = 'user_dictionary';
	protected $primary_key = 'user_id';

	/**
	 * @return user_dictionary_peer
	 */
	public static function instance()
	{
		return parent::instance( 'user_dictionary_peer' );
	}

	public function set_names( $user_id, $names )
	{
		$this->delete_item($user_id);
		return parent::insert(array('user_id' => $user_id, 'names' => $names));
	}

	public function get_all()
	{
		$list = db::get_rows('SELECT names, user_id FROM ' . $this->get_table_name());
//die('SELECT names, user_id FROM ' . $this->get_table_name());
		if ( $dict = mem_cache::i()->get('named_users_dictionary') ) return $dict;

		foreach ( $list as $data )
		{
			$terms_dirty = explode(';', $data['names']);
			$terms = array();
			foreach ( $terms_dirty as $i => $term )
			{
				if ( !$term = trim($term) ) continue;

				$terms[] = $term;
				$terms = array_merge( $terms, $this->get_rev_terms( $term ) );
			}
			
			$terms = array_unique( $terms );

			foreach ( $terms as $term ) $dict[mb_strtolower($term)] = $data['user_id'];
		}

		mem_cache::i()->set('named_users_dictionary', $dict, 60*60*24);

		return $dict;
	}

	protected function get_rev_terms( $sentence )
	{
		if ( !$words = explode( ' ', $sentence ) ) return array();

		foreach ( $words as &$word )
		{
			$word = trim($word);
		}

		return $this->get_word_forms($words);
	}

	protected function get_word_forms( $words )
	{
		return array(
			$words[0] . ' ' . $words[2],
			$words[0] . ' ' . $words[1],
			mb_substr($words[0], 0, 1) . ' ' . $words[2],
			mb_substr($words[0], 0, 1) . ' ' . mb_substr($words[1], 0, 1) . ' ' . $words[2],
			$words[2]
		);
	}
}
