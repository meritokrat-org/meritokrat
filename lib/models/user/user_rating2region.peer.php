<?php
class user_rating2region_peer extends db_peer_postgre {
        
        protected $table_name = 'user_rating2region_ratio';

	/**
	 * @return user_payments_log_peer
	 */
	public static function instance()
	{
            return parent::instance( 'user_rating2region_peer' );
	}

}
?>