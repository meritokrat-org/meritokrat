<?

load::app('modules/admin/controller');
class admin_help_action extends admin_controller
{
	public function execute()
	{
            load::model('help/help');
            $this->list = help_peer::instance()->get_list(array(),array(),array('alias ASC'));
        }
}
