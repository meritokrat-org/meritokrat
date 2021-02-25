<?
load::app('modules/admin/controller');
class admin_users_change_status_action extends admin_controller
{

	public function execute()
	{
                $this->set_renderer('ajax');
                $this->json = array();

                $uid = request::get_int('id');
                if(!$uid)
                    return;

                load::action_helper('membership', false);
                membership_helper::change_status($uid, 20);
	}
}
