<?
class vnesky_ik_process_action extends basic_controller
{
	protected $authorized_access = false;
        protected $secret_key="iVts9K3uexRN78DP";
        public function execute()
        {
                $status_data=$_REQUEST;
                
                load::model('acquiring_payments');
                
		if ($status_data['ik_payment_state']!='success') header("HTTP/1.1 403 Forbidden");
		$sing_hash_str = $status_data['ik_shop_id'].':'.
			$status_data['ik_payment_amount'].':'.
			$status_data['ik_payment_id'].':'.
			$status_data['ik_paysystem_alias'].':'.
			$status_data['ik_baggage_fields'].':'.
			$status_data['ik_payment_state'].':'.
			$status_data['ik_trans_id'].':'.
			$status_data['ik_currency_exch'].':'.
			$status_data['ik_fees_payer'].':'.
			$this->secret_key;
		$sign_hash = strtoupper(md5($sing_hash_str));
		
                //mail('andimov@gmail.com', 'meritokrat ik test', $sing_hash_str.$sign_hash.$status_data['ik_payment_id'].$status_data['ik_sign_hash']);
                
		if($status_data['ik_sign_hash'] != $sign_hash) 
		{
			header("HTTP/1.1 403 Forbidden");
			die('error');
		}
		

		if ($payment_data=acquiring_payments_peer::instance()->get_item($status_data['ik_payment_id']))
		{
			$payment_data['total_amount']!=$status_data['ik_payment_amount'];

		}
		else
		{
			header("HTTP/1.1 403 Forbidden");
			die('error');
		}
	
		acquiring_payments_peer::instance()->update(array(
			'id' => $status_data['ik_payment_id'],
			'pay_way' => $status_data['ik_paysystem_alias'],
			'ik_id' => $status_data['ik_trans_id'],
                        'confirm_date'=>date('d-m-Y H:i:s',$_SERVER['REQUEST_TIME']),
			'status' => 'success'));
                
                acquiring_payments_peer::instance()->payment_proccess($status_data['ik_payment_id']);
                

                die('ok');
	}
}