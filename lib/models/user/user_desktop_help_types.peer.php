<?php
class user_desktop_help_types_peer extends db_peer_postgre
{
	protected $table_name = 'user_desktop_help_types';
	protected $primary_key = 'id';

	/**
	 * @return user_data_peer
	 */
	public static function instance()
	{
		return parent::instance( 'user_desktop_help_types_peer' );
	}
}
?>
