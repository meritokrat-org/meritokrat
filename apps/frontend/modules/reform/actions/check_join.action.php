<?

load::app('modules/ppo/controller');
class ppo_check_join_action extends ppo_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->set_renderer('ajax');
                $count=db::get_scalar('SELECT count(*) 
    FROM ppo_members 
    WHERE user_id = :user_id 
    AND group_id in (SELECT id FROM ppo WHERE active=:active AND category=1)',array('user_id'=>session::get_user_id(),'active'=>1));
                
		$this->json = array('check'=>$count);
        }
}