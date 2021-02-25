<?php
class vnesky_robokassa_action extends basic_controller
{
	protected $authorized_access = false;
        protected $mrh_pass2='bHEN7Ap6Dq';
        
        public function execute()
        {
                
                load::model('acquiring_payments');

                // HTTP parameters:
                $out_summ = $_REQUEST["OutSum"];
                $inv_id = $_REQUEST["InvId"];
                $crc = $_REQUEST["SignatureValue"];

                // HTTP parameters: $out_summ, $inv_id, $crc
                $crc = strtoupper($crc);   // force uppercase

                // build own CRC
                $my_crc = strtoupper(md5("$out_summ:$inv_id:$this->mrh_pass2"));

                if (strtoupper($my_crc) != strtoupper($crc))
                {
                        echo "bad sign\n".$my_crc.'\n'.$crc;
                        die();
                }
                else
                {
                        acquiring_payments_peer::instance()->update(array(
                                'id' => $inv_id,
                                'confirm_date'=>date('d-m-Y H:i:s',$_SERVER['REQUEST_TIME']),
                                'signature' => $crc,
                                'status' => 'success'));

                        acquiring_payments_peer::instance()->payment_proccess($inv_id);
                }
                // print OK signature
                echo "OK$inv_id\n";
                die();
        }
}
?>