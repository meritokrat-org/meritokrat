<?

abstract class tags_peer extends db_peer_postgre
{
	public function string_to_array( $string )
	{
		$list = explode(',', $string);
		$tags = array();
		foreach ( $list as $name )
		{
			if ( $name = trim(strip_tags($name)) )
			{
				$tags[] = $name;
			}
		}

		return array_unique($tags);
	}

	public function obtain_id( $name )
	{
		if ( !$id = $this->get_by_name($name) )
		{
			$id = $this->insert(array(
				'name' => $name
			));
		}

		return $id;
	}

	public function get_by_name( $name )
	{
		$sql = 'SELECT id FROM ' . $this->table_name . ' WHERE name = :name LIMIT 1';
		return db::get_scalar($sql, array('name' => $name), $this->connection_name);
	}

	public function get_name( $id )
	{
		$data = $this->get_item($id);
		return $data['name'];
	}
}