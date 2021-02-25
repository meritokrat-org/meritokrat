<?

load::app('modules/admin/controller');
class admin_info_action extends admin_controller
{
	public function execute()
	{
            load::model('help/info');
            $this->list = help_info_peer::instance()->get_list();
        }
}
