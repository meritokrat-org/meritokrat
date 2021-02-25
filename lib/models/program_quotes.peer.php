<?php

class program_quotes_peer extends db_peer_postgre
{
	protected $table_name = 'program_quotes';
	
	public static function instance()
	{
		return parent::instance('program_quotes_peer');
	}
}

?>
