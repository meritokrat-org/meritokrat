<?php
/**
 * Description of zayava_termination
 *
 * @author Morozov Artem
 */
class user_zayava_termination_peer extends db_peer_postgre {
	
	protected $table_name = "user_zayava_termination";

	public static function instance()
	{
		return parent::instance("user_zayava_termination_peer");
	}
	
	public function get_statement($statement_id, $options = array())
	{	
		if(isset($options["visible"]))
			$visible = " AND visible = ".(string) $options["visible"];
		
		$sql = "SELECT * FROM ".$this->table_name." WHERE id = ".(int) $statement_id.$visible." ORDER BY id DESC LIMIT 1;";
		$statement_data = db::get_row($sql);
		
		if( ! $statement_data)
			return false;
		
		return $statement_data;
	}
	
	public function get_statements($options = array())
	{
		$sql = "SELECT id FROM ".$this->table_name." WHERE visible = TRUE ORDER BY id DESC";
		$statements_ids = db::get_cols($sql);
		
		$statements_data = array();
		foreach($statements_ids as $statement_id){
			$statement_data = $this->get_statement($statement_id, $options);
			if(!$statement_data)
				continue;
			$statements_data[] = $statement_data;
		}
		
		return $statements_data;
	}
	
	public function add_statement($statement_data)
	{
		$statement_data = array(
				"user_id" => (int) $statement_data["user_id"],
				"date_created" => date("Y-m-d H:i:s")
		);
		
		$statement_id = parent::insert($statement_data);
		
		return $statement_id;
	}
	
	public function modify_statement($statement_data)
	{
		
	}
	
	public function remove_statement($statement_id)
	{
		parent::delete_item($statement_id);
	}
	
	public function confirm_statement($statement_id, $time = 0)
	{
		$date = date("Y-m-d H:i:s");
		if($time != 0)
			$date = date("Y-m-d H:i:s", $time);
			
		$statement_data = array(
				"id" => $statement_id,
				"confirmation" => true,
				"date_confirmation" => $date,
				"visible" => false
		);
		
		parent::update($statement_data);
	}
	
	public function get_statement_by_user_id($user_id, $options = array())
	{
		if(isset($options["visible"]))
			$visible = " AND visible = ".(string) $options["visible"];
		
		$sql = "SELECT id FROM ".$this->table_name." WHERE user_id = ".(int) $user_id.$visible." ORDER BY id DESC LIMIT 1";
		$statement_id = db::get_scalar($sql);
		$statement_data = $this->get_statement($statement_id, $options);
		return $statement_data;
	}
	
}

?>
