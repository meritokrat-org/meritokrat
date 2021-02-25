<?
class vnesky_thanks_action extends frontend_controller
{
	protected $authorized_access = true;
        public function execute()
        {
                $order_id = request::get('order');
                if(!isset($order_id) || db::get_scalar("SELECT id FROM acquiring_payments WHERE order_id='".$order_id."' and status='success'"))
                    $this->status='success';
        }
}