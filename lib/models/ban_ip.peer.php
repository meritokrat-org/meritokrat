<?php
class ban_ip_peer extends db_peer_postgre
{
	protected $table_name = 'ban_ip';

	/**
	 * @return admin_complaint_peer
	 */
	public static function instance()
	{
		return parent::instance( 'ban_ip_peer' );
	}
        public function check_ip($ip) {
            return in_array($ip, db::get_cols("SELECT ip FROM ".$this->table_name));
        }
}

?>
