<?

load::app('modules/admin/controller');
class admin_mails_action extends admin_controller
{
	public function execute()
	{
            load::model('email/email');
            $this->list = email_peer::instance()->get_list();
        }
}
