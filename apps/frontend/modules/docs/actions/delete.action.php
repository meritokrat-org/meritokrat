<?
load::app('modules/docs/controller');
class docs_delete_action extends docs_controller
{
	protected $authorized_access = true;
	protected $credentials = array('admin');

        public function execute()
	{
            $this->set_renderer('ajax');
            $this->json = array();

            if(!session::has_credential('admin'))
            {
                return false;
            }

            if(!$id = request::get_int('id'))
            {
                return false;
            }
            else
            {
                docs_peer::instance()->delete_item($id);
            }
	}
}