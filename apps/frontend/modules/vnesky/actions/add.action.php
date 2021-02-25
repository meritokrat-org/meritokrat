<?
class vnesky_add_action extends frontend_controller
{
        protected $authorized_access = true;
        public $commissions = array(
            'bank' => 1,
            'liqpay' => 1,
            'privat' => 1,
            'interkassa' => 3
        );
        public $ways = array(
            'liqpay' => 'LiqPay',
            'privat' => 'Privat24',
            'interkassa' => 'Interkassa'
        );
        
        protected $signature='7o8uJHdTtGOZPvnfjf2XU9sJ1wgc7aAO9m3sq';
        protected $merchant_id='i0833927305';// //0429575032
        protected $url="https://www.liqpay.com/?do=clickNbuy";
        protected $method='card';
        protected $privat_password='vQ06OjDK7Uh4wC5g51j5ZgJD9xxBP371';//BhJJFTqdr2zgME2Ew313K7zNr44ZEqPF';

        // your registration data
        protected $mrh_login = "meritokrat";      // your login here
        protected $mrh_pass1 = "bHEN7Ap6Dq";   // merchant pass1 here

        public function execute()
        {         
            
            load::model('acquiring_payments');
            
            request::get_string('way') ? $way=str_replace('_','',request::get_string('way')) : $way='liqpay';
                        
            (request::get('opening') && in_array('opening', $_REQUEST['type'])) ? $opening=request::get('opening') : $opening=0;
            (request::get('monthly') && in_array('monthly', $_REQUEST['type'])) ? $monthly=request::get('monthly') : $monthly=0;
            (request::get('months') && in_array('monthly', $_REQUEST['type'])) ? $months=request::get('months') : $months=0;
            (request::get('donate') && in_array('donate', $_REQUEST['type'])) ? $donate=request::get('donate') :  $donate=0;
                                            
                                            
            $amount = preg_replace('/\,/i', '.', request::get('total'));
            $currency = 'UAH';
            $order = md5($_SERVER['REQUEST_TIME']);

            if(request::get('submit'))
            {
                if(!filter_var($amount, FILTER_VALIDATE_FLOAT)) die(json_encode(array("error"=>"Введіть суму")));
                $insert_data = array(
                                    'user_id'=>session::get_user_id(),
                                    'amount'=>$amount,
                                    'total_amount'=>request::get('total_fees'),
                                    //'currency'=>  $currency,
                                    'order_id'=>$order,
                                    'date'=>date('d-m-Y H:i:s',$_SERVER['REQUEST_TIME']),
                                    'user_ip'=>$this->getip(),
                                    'payment_ts'=>time(),
                                    'way'=>$way,
                                    'opening'=>$opening,
                                    'monthly'=>$monthly,
                                    'months'=>$months,
                                    'donate'=>$donate,
                                 );

                $id=acquiring_payments_peer::instance()->insert($insert_data);
                
                switch ($way)
                {
                    case 'bank':
                    case 'liqpay':
                        $xml="<request>
                                <version>1.2</version>
                                <result_url>https://meritokrat.org/vnesky/thanks?order=".$order."</result_url>
                                <server_url>https://meritokrat.org/vnesky/lp_proccess</server_url>
                                <merchant_id>".$this->merchant_id."</merchant_id>
                                <order_id>".$order."</order_id>
                                <amount>".$amount."</amount>
                                <currency>".$currency."</currency>
                                <description>".$desc."</description>
                                <default_phone>".$tel."</default_phone>
                                <pay_way>".$this->method."</pay_way>
                                </request>
                                ";

                        $xml_encoded = base64_encode($xml);
                        $lqsignature = base64_encode(sha1($this->signature.$xml.$this->signature,1));
                        die(json_encode(array('url'=>$this->url,'encoded_xml'=>$xml_encoded,'signature'=>$lqsignature)));
                    break;
                        /*
                    case 'bank'://ROBOKASSA

                        // build CRC value
                        $crc  = md5("$this->mrh_login:$amount:$id:$this->mrh_pass1");
                        die(json_encode(array('crc'=>$crc,'id'=>$id)));
                        
                    break;
                */
                    default: //webmoney, privat24, interkassa
                        echo $id;
                        die();
                    break;
                }
                        
            }             
        }
        
        private function getip()
        {
             if (!empty($_SERVER['HTTP_CLIENT_IP']))
               $ip=$_SERVER['HTTP_CLIENT_IP'];
             elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
               $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
             else
               $ip=$_SERVER['REMOTE_ADDR'];
         return $ip;
        }
}