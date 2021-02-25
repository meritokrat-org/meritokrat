<?

class mpu_payments_peer extends db_peer_postgre
{
	protected $table_name = 'payment';
	protected $connection_name = 'mpu';
        
	public static function instance()
	{
		return parent::instance( 'mpu_payments_peer' );
	}
        
        public function search($filters = array(),$order='id DESC',$status='success')
        {
                $bind=array();
                
		if ( $filters ) foreach ( $filters as $name => $value )
		{
                        if ( is_array($value) )
			{
                                $where[] = " {$name} in ('".  implode("','", $value)."')";
			}
                        elseif($name=='period_begin')
                        {
                                $where[] = "date>:begin AND date<:end";
                                $bind['begin'] = $value;
                                $filters['period_end'] ? $bind['end'] = $filters['period_end'] : $bind['end'] = time();
                        }
                        elseif($name=='amount_start')
                        {
                                $where[] = "amount>:amount_start AND amount<:amount_end";
                                $bind['amount_start'] = $value;
                                $filters['amount_end'] ? $bind['amount_end'] = $filters['amount_end'] : $bind['amount_end'] = 10000000000;
                        }
                        
                }
                
		if ( $status && $status!='null') 
		{
                        $where[] = "status=:success";
                        $bind['success']=$status;
                }
                else 
                {
                        $where[] = 'status is NULL';
                }
                
                if ($where) $where = 'WHERE '.implode(' AND ', $where);
               // die('SELECT id FROM payment ' . $where . ' order by '.$order);
                return db::get_cols('SELECT id FROM payment ' . $where . ' order by '.$order,$bind,'mpu');
                
        }
        
        public static function getcurrency($name) {
            $currency = array(
                            '',
                            'UAH',
                            'USD',
                            'RUR',
                            'EUR'
                        );
            return $currency[$name];
        }
}
?>
