<?
load::app('modules/admin/controller');

class admin_delete_email_sys_action extends admin_controller
{
	public function execute()
	{
		load::model('email/email');
		if ($id = request::get_int('id')) email_peer::instance()->delete_item($id);
		$this->redirect('/admin/mails');
	}
}