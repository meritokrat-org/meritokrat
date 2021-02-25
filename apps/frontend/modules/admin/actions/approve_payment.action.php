<?
load::app('modules/admin/controller');
class admin_approve_payment_action extends admin_controller
{

	public function execute()
	{
        
                load::model('mpu_payments');
               
                if ($id=request::get('approve'))
                {
                    mpu_payments_peer::instance()->update(array(
                        'id' => $id,
                        'status' => 'success'
                        ));
                    echo 1;
                }
                die();
                
        }
}
?>
