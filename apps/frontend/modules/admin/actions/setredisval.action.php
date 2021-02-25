<?

load::app('modules/admin/controller');
class admin_setredisval_action extends admin_controller
{
	public function execute()
	{
            $this->set_renderer('ajax');
            $this->json = array();

            $key = request::get('key');
            $value = request::get('val');

            if(!$key)return;

            if($value)
                db_key::i()->set($key, $value);
            else
                db_key::i()->delete($key);
	}
}
