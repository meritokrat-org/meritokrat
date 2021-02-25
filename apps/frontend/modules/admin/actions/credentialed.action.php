<?
load::app('modules/admin/controller');
class admin_credentialed_action extends admin_controller
{
	public function execute()
	{
		if ($credential=request::get('credential'))
                {
                       $this->users=db::get_cols("SELECT id FROM user_auth WHERE credentials like '%".$credential."%' ORDER by id ASC");
                }
                else
                {
                       $this->users=db::get_cols("SELECT id FROM user_auth WHERE credentials like '%,%' ORDER by id ASC");
                }
        }
}
