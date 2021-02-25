<?
load::app('modules/admin/controller');

class admin_delete_payment_action extends admin_controller
{

	public function execute()
	{
		load::model('mpu_payments');

		if (request::get_int('del')) {
			mpu_payments_peer::instance()->delete_item(request::get_int('del'));
			echo 1;
		}
		die();
	}
}

?>
