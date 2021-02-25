<?php
class user_rating_admin_points_peer extends db_peer_postgre
{
	protected $table_name = 'user_rating_admin_points';
        
        public static function instance()
	{
                return parent::instance('user_rating_admin_points_peer');
	}
}
?>
