<?php
class user_desktop_active_help_peer extends db_peer_postgre
{
	protected $table_name = 'active_help';
	protected $primary_key = 'user_id';

	public static function instance()
	{
		return parent::instance( 'user_desktop_active_help_peer' );
	}
}

?>
