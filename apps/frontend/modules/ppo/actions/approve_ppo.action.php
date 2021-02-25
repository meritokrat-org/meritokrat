<?
load::app('modules/ppo/controller');
class ppo_approve_ppo_action extends ppo_controller
{
	protected $authorized_access = true;
	protected $credentials = array('admin');

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();
                $uparray=array('active'=>1);
                $this->group = ppo_peer::instance()->get_item(request::get_int('ppo_id'));
                if((int)$this->group['svidnum']==0){
                     $scount=db::get_scalar("SELECT MAX(svidnum) 
                                    FROM ppo 
                                    WHERE active=1"); 
                     $uparray['svidnum']=$scount+1;  
                }
		$this->group = ppo_peer::instance()->update($uparray,array('id'=>request::get_int('ppo_id')));

		$this->group['creator_id'];
                #ppo_peer::instance()->add_moderator($this->group['id'], $this->group['creator_id']);
		load::action_helper('user_email', false);
                $options = array(
                        '%title%' => $this->group['title'],
                        '%link%' => 'http://' . context::get('host') . '/ppo'.$this->group['id'].'/'.$this->group['number'].'/'
                    );
                user_email_helper::send_sys('ppo_approve',$this->group['creator_id'],null,$options);
                
                $this->redirect('/ppo'.$this->group['id'].'/'.$this->group['number'].'/');
        }
}