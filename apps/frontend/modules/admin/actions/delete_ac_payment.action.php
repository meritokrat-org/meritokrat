<?
load::app('modules/admin/controller');

class admin_delete_ac_payment_action extends admin_controller
{

	public function execute()
	{

		load::model('acquiring_payments');

		if (request::get_int('del')) {
			acquiring_payments_peer::instance()->delete_item(request::get_int('del'));
			echo 1;
		}
		die();
	}
}

?>
