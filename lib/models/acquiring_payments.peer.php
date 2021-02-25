<?

class acquiring_payments_peer extends db_peer_postgre
{
	protected $table_name = 'acquiring_payments';
        
	public static function instance()
	{
		return parent::instance( 'acquiring_payments_peer' );
	}
        
        public function payment_proccess($payment_id=false) {
            if (!$payment_id) return false;
            
            
            load::model('user/user_auth');
            load::view_helper('user');
            load::model('user/user_payments');
            load::model('user/user_payments_log');
            $approve=0;
            
            $payment_data=self::instance()->get_item($payment_id);
            $payment_data['way']=='interkassa' ? $transaction_id=$payment_data['ik_id'] : $transaction_id=$payment_data['transaction_id'];
            //var_dump($payment_data);
            if ($payment_data['months'] && $payment_data['monthly'])
            {
                
                $last_period=db::get_scalar("SELECT MAX(period) FROM user_payments WHERE type=2 and user_id=".$payment_data['user_id']);
                //$sum=round($payment_data['monthly']/$payment_data['months']);
                $sum=$payment_data['monthly'];
                
                $i=0;
                while ($i++<$payment_data['months'])
                {
                    $last_period>0 ? $period=mktime(0, 0, 0, date('m', $last_period)+$i, 1, date('Y', $last_period)) : $period=mktime(0, 0, 0, date('m')+$i, 1, date('Y'));
                    $pid = user_payments_peer::instance()->insert(array(
                        'user_id' => $payment_data['user_id'],
                        'type' => 2,
                        'summ' => (int)$sum,
                        'method' => 2,
                        'way' => 2,
                    	'approve' => $approve,
                        'date' => time(),
                        'period' => $period,
                        'ps' => $payment_data['way'],
                        'transaction_id' => $transaction_id
                    ));
                    
                    $payment = user_payments_peer::instance()->get_item($pid);
                    
                    //автоапрув закоменчен
                    /*user_payments_log_peer::instance()->insert(array(
                        'payment_id' => $pid,
                        'type' => 2,
                        'who' => 10599,
                        'date' => time(),
                        'payment' => serialize($payment)
                    ));*/
                    
                    $pay_month=$pay_month+1;
                }
            }
            if ($payment_data['opening'])
            {
                $pid = user_payments_peer::instance()->insert(array(
                        'user_id' => $payment_data['user_id'],
                        'type' => 1,
                        'summ' => (int)$payment_data['opening'],
                        'method' => 2,
                        'way' => 2,
                        'date' => time(),
                    	'approve' => $approve,
                        'ps' => $payment_data['way'],
                        'transaction_id' => $transaction_id
                    ));
                    
                    $payment = user_payments_peer::instance()->get_item($pid);
                    //автоапрув закоменчен
                    /*
                    user_payments_log_peer::instance()->insert(array(
                        'payment_id' => $pid,
                        'type' => 2,
                        'who' => 10599,
                        'date' => time(),
                        'payment' => serialize($payment)
                    ));*/
            }
            if ($payment_data['donate'])
            {

            $user_info=user_auth_peer::instance()->get_item($payment_data['user_id']);
            //var_dump($user_info);
                    
                if ($user_info)
                    {
                           $unixtime=time();
                           
                           $pid = user_payments_peer::instance()->insert(array(
                                'user_id' => $payment_data['user_id'],
                                'type' => 3,
                                'summ' => (int)$payment_data['donate'],
                                'method' => 2,
                                'way' => 2,
                                'date' => $unixtime,
                                'period' => $unixtime,
                                'approve' => $approve,
                                'ps' => $payment_data['way'],
                                'transaction_id' => $transaction_id,
                            ));

                            $payment = user_payments_peer::instance()->get_item($pid);

                            //автоапрув закоменчен
                            /*user_payments_log_peer::instance()->insert(array(
                                'payment_id' => $pid,
                                'type' => 2,
                                'who' => 10599,
                                'date' => time(),
                                //'payment' => serialize($payment)
                            ));*/
                    
                            load::model('mpu_payments');
                            
                            mpu_payments_peer::instance()->insert(array(
                                    'date' => $payment_data['date'],
                                    'confirm_date' => $payment_data['confirm_date'],
                                    'amount' => $payment_data['donate'],
                                    'currency' => 1,
                                    'total_amount' => $payment_data['donate'],
                                    'way' => $payment_data['way'],
                                    'fio' => user_helper::full_name($payment_data['user_id'],false),
                                    'email' => $user_info['email'],
                                    'site' => 'meritokrat',
                                    'payment_ts' => $payment_data['payment_ts'],
                                    'status' => 'success',
                                    'user_ip' => $payment_data['user_ip'],
                                    'signature' => $payment_data['signature'],
                                    ));
                            
                            //проверка, постоянный ли плательщик
                            $email_id=db::get_scalar("SELECT id FROM email_users WHERE email='".$user_info['email']."'");
                            if (!db::get_scalar("SELECT user_id FROM email_lists_users WHERE user_id=:user_id AND list_id=:list_id",
                                    array('user_id'=>$email_id,'list_id'=>308)))
                            {
                                    //если впревые платит - шлем писмо-попрошайку
                                    load::action_helper('user_email', false);
                                    $options = array('%email%' => $user_info['email']);
                                    user_email_helper::send_sys('after_payment',$payment_data['user_id'],null,$options);
                            }
                    }
            }
            
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
                return db::get_cols('SELECT id FROM acquiring_payments ' . $where . ' order by '.$order,$bind);
                
        }        
}
?>
