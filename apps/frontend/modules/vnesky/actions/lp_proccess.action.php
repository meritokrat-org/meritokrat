<?php
class vnesky_lp_proccess_action extends basic_controller
{
	protected $authorized_access = false;
        protected $signature="7o8uJHdTtGOZPvnfjf2XU9sJ1wgc7aAO9m3sq";
        protected $rec_signature = 0;


        public function execute()
        {
            $this->disable_layout();
            $this->set_renderer(false);
            
            load::model('acquiring_payments');
            load::view_helper('simple_html_dom');
            load::view_helper('rssnews');
            
            /*if(!request::get('order'))
                throw new public_exception('order='.request::get('order'));*/
                
            $request_xml = request::get('operation_xml');
            $this->rec_signature = request::get('signature',0);
            

            if($request_xml) {
               if($this->rec_signature) {
                    $xml = base64_decode($request_xml);
                    $xmlObj    = new XMLParser();
                    $xmlObj->xml = $xml;
                    $request = $xmlObj->createArray();
                    
                    $request_data = $request['response'];
                    $order = $request_data['order_id'];
                    
                    $recId = acquiring_payments_peer::instance()->get_list(array('order_id'=>$order));

                    if(!empty($recId)) {
                        if($this->check($xml)) {
                            $update_data = array(
                                            'id'=>$recId[0],
                                            'transaction_id'=>$request_data['transaction_id'],
                                            'pay_way'=>  'card',
                                            'status'=>$request_data['status'],
                                            'confirm_date'=>date('d-m-Y H:i:s',$_SERVER['REQUEST_TIME']),
                                            'signature'=>$this->rec_signature
                                );
                            mail('andimov@gmail.com', 'meritokrat lp update', serialize($update_data));
                            
                            acquiring_payments_peer::instance()->update($update_data);
                
                            if ($request_data['status']=='success') acquiring_payments_peer::instance()->payment_proccess($recId[0]);
                        }
                        else {
                            $update_data = array(
                                            'id'=>$recId[0],
                                            'transaction_id'=>$request_data['transaction_id'],
                                            'pay_way'=>  payment_peer::getpayway($request_data['pay_way']),
                                            'status'=>$request_data['status'],
                                            'confirm_date'=>date('d-m-Y H:i:s',$_SERVER['REQUEST_TIME']),
                                            'signature'=>$this->rec_signature
                                        );
                            acquiring_payments_peer::instance()->update($update_data);
                         }
                    }                
                    die('ok');
                 }
            }
            elseif(request::get_all())
                acquiring_payments_peer::instance()->insert(array('ik_id'=>  implode('!---!',request::get_all())));

        }
        private function check($xml) {
            $new_sign = base64_encode(sha1($this->signature.$xml.$this->signature,1));
            
            //mail('andimov@gmail.com', 'meritokrat lp checkxml', serialize($xml).'\n'.$this->rec_signature.'\n'.$new_sign);
            
            if($new_sign==$this->rec_signature)
                return true;
            else
                return false;
        }
       
}

?>