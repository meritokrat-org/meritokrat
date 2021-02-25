<?

load::app('modules/admin/controller');
class admin_helpdelete_action extends admin_controller
{
	public function execute()
	{
            $this->set_renderer('ajax');
            $this->json = array();

            db::exec('DELETE FROM help_data WHERE id = '.request::get_int('id'));
        }
}
