<?
class user_payments_log_peer extends db_peer_postgre
{
	protected $table_name = 'user_payments_log';

	/**
	 * @return user_payments_log_peer
	 */
	public static function instance()
	{
            return parent::instance( 'user_payments_log_peer' );
	}

        public function get_payment( $payment_id=0,$type=false )
	{
            if($type)
                $sqladd = ' AND type = '.$type;
            return db::get_cols('SELECT id FROM '.$this->table_name.' WHERE payment_id = '.$payment_id.$sqladd.' ORDER BY date DESC');
	}

}