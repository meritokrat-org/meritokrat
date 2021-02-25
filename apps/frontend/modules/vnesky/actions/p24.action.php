<?php
class vnesky_p24_action extends basic_controller
{
	protected $authorized_access = false;
        protected $privat_password='vQ06OjDK7Uh4wC5g51j5ZgJD9xxBP371';//BhJJFTqdr2zgME2Ew313K7zNr44ZEqPF';
        
        public function execute()
        {
                $this->disable_layout();
                load::model('acquiring_payments');
                $payment_array=explode('&',$_REQUEST['payment']);
                foreach($payment_array as $payment_desc){
                        $payment_tmp=explode('=',$payment_desc);
                        $payment_info[$payment_tmp[0]]=$payment_tmp[1];
                }
                
                //mail('andimov@gmail.com', "p24 test", 'key: '.sha1(md5($_REQUEST['payment'].$this->privat_password)).'; signature:'.$_REQUEST['signature'].'; payment:'.$_REQUEST['payment'].'; payment_id:'.$payment_info['order']);
		
                if ($_REQUEST['signature']!=sha1(md5($_REQUEST['payment'].$this->privat_password)))
                {
                    header("HTTP/1.1 403 Forbidden");
                    die('error');
                }
                
                
		acquiring_payments_peer::instance()->update(array(
			'id' => $payment_info['order'],
                        'confirm_date'=>date('d-m-Y H:i:s',$_SERVER['REQUEST_TIME']),
                        'signature' => $_REQUEST['signature'],
                        'phone' => $payment_info['sender_phone'],
			'transaction_id' => $payment_info['ref'],
			'status' => 'success'));
                
                
		acquiring_payments_peer::instance()->payment_proccess($payment_info['order']);
                
                
                die('ok');
        }
}
?>