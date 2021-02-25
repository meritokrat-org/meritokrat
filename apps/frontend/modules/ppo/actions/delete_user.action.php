<?
load::app('modules/ppo/controller');
class ppo_delete_user_action extends ppo_controller
{
	protected $authorized_access = true;
	public function execute()
	{
                $this->group_id=request::get_int('group_id');
                if ( !ppo_peer::instance()->is_moderator($this->group_id, session::get_user_id()) )
                    {
                            $this->redirect('/');
                    }
		ppo_members_peer::instance()->remove($this->group_id, request::get_int('id'));
                ppo_members_history_peer::instance()->set_member_history($this->group_id,request::get_int('id'),false,time());
                $this->redirect('/ppo/members?id='.request::get_int('group_id'));
	}
}