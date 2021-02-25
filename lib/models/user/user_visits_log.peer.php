<?php

class user_visits_log_peer extends db_peer_postgre
{
	protected $table_name = "user_visits_log";
	
	public static function instance($peer = "user_visits_log_peer")
	{
		return parent::instance($peer);
	}
}

?>
