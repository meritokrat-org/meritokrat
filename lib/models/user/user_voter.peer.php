<?php

class user_voter_peer extends db_peer_postgre
{
	protected $table_name = "user_voter";
	
	public static function instance()
	{
		return parent::instance("user_voter_peer");
	}
	
	public function get_item($pkey)
	{
		if( ! $item = parent::get_item($pkey))
			return false;
		
		$fields = array('user_data', 'admin_data', 'informator', 'tasks_data',
			'money_collection', 'opinion_leaders', 'agents_influence', 'command_member_tasks');
		foreach($fields as $field)
		{
			$item[$field] = unserialize($item[$field]);
		}
		
		return $item;
	}
	
	public function insert($data, $ignore_duplicate = false)
	{
		$fields = array('user_data', 'admin_data', 'informator', 'tasks_data',
			'money_collection', 'opinion_leaders', 'agents_influence', 'command_member_tasks');
		foreach($fields as $field)
		{
			if(isset($data[$field]) && is_array($data[$field]))
				$data[$field] = serialize($data[$field]);
		}
		
		return parent::insert($data, $ignore_duplicate);
	}
	
	public function update($data, $keys = null)
	{
		$fields = array('user_data', 'admin_data', 'informator', 'tasks_data',
			'money_collection', 'opinion_leaders', 'agents_influence', 'command_member_tasks');
		foreach($fields as $field)
		{
			if(isset($data[$field]) && is_array($data[$field]))
				$data[$field] = serialize($data[$field]);
		}
		
		return parent::update($data, $keys);
	}
}

?>