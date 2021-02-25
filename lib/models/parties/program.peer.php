<?

class parties_program_peer extends db_peer_postgre
{
	protected $table_name = 'parties_program';

	/**
	 * @return parties_program_peer
	 */
	public static function instance()
	{
		return parent::instance( 'parties_program_peer' );
	}

	public function save( $pary_id, array $program )
	{
		$current = $this->get_by_party($pary_id);
		$current_list = array();
		foreach ( $current as $program_id )
		{
			$program_item = $this->get_item($program_id);
			$current_list[$program_item['segment']] = $program_item;
		}

		foreach ( $program as $segment => $text )
		{
			if ( $current_list[$segment]['id'] )
			{
				$this->update(array(
					'id' => $current_list[$segment]['id'],
					'text' => $text
				));
			}
			else
			{
				$this->insert(array(
					'party_id' => $pary_id,
					'segment' => $segment,
					'text' => $text
				));
			}
		}
	}

	public function get_by_segment( $segment )
	{
		$segment_id = array_keys(ideas_peer::get_segments(), $segment);
		$segment_id = array_shift($segment_id);

		return $this->get_list(array('segment' => $segment_id), array(), array('"for" DESC'), $limit);
	}

	public function get_by_party( $id )
	{
		return $this->get_list(array('party_id' => $id), array(), array('"for" DESC'));
	}

	public function delete_by_party( $id )
	{
		$sql = 'DELETE FROM ' . $this->table_name . ' WHERE party_id = :id';
		db::exec($sql, array('id' => $id), $this->connection_name);
	}

	public function has_rated( $id, $user_id )
	{
		return db_key::i()->exists('party_program_rate:' . $id . ':' . $user_id);
	}

	public function rate( $id, $user_id )
	{
		db_key::i()->set('party_program_rate:' . $id . ':' . $user_id, true);
	}
}