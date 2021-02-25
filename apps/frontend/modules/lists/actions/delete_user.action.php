<?

load::app('modules/lists/controller');
class lists_delete_user_action extends lists_controller
{
        public function execute()
	{
                $this->set_renderer('ajax');
                lists_users_peer::instance()->delete_user(request::get_int('uid'),request::get_int('lid'));
	}
}
