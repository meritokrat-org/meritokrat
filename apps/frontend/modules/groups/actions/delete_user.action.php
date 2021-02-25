<?
load::app('modules/groups/controller');
class groups_delete_user_action extends groups_controller
{
	protected $authorized_access = true;
	public function execute()
	{
                $this->group_id=request::get_int('group_id');
                if ( !groups_peer::instance()->is_moderator($this->group_id, session::get_user_id()) )
                    {
                            $this->redirect('/');
                    }
		groups_members_peer::instance()->remove($this->group_id, request::get_int('id'));
                $this->redirect('/groups/members?id='.request::get_int('group_id'));
	}
}