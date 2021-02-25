<?

load::app('modules/ppo/controller');
class ppo_approve_applicant_action extends ppo_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		$this->group = ppo_peer::instance()->get_item(request::get_int('group_id'));
		if ( $this->group['user_id'] != session::get_user_id() and !ppo_peer::instance()->is_moderator(request::get_int('group_id'), session::get_user_id()))
		{
			exit;
		}
        load::system('email/email');     
        load::action_helper('user_email',false);
        $applicant=db::get_row("SELECT * FROM ppo_applicants WHERE user_id=:user_id AND group_id=:group_id",
        array("user_id"=>request::get_int('id'),"group_id"=>  $this->group['id']));

        if($old_ppo_id=db::get_scalar("SELECT group_id FROM ppo_members WHERE user_id=:user_id",
        array("user_id"=>request::get_int('id')))){
                    $old_ppo = ppo_peer::instance()->get_item($old_ppo_id);
                    $options = array(
                        '%member_name%' => strip_tags(user_helper::full_name(request::get_int('id'))),
                        '%ppo_name%' => $old_ppo['title'],
                        '%reason%' => $applicant['reason']
                    ); 
                    foreach (user_auth_peer::get_admins() as $receiver)
                    {
                      user_email_helper::send_sys('ppo_move_member_admin',$receiver,null,$options);  
                    }
                    $glava=ppo_members_peer::instance()->get_user_by_function(1,$old_ppo_id);
                    if($glava>0)user_email_helper::send_sys('ppo_move_member',$glava,null,$options);
                    $sekretar=ppo_members_peer::instance()->get_user_by_function(2,$old_ppo_id);
                    if($sekretar>0)user_email_helper::send_sys('ppo_move_member',$sekretar,null,$options);
                }
                
                db::exec("DELETE FROM ppo_members WHERE user_id=".request::get_int('id'));
                db::exec("DELETE FROM ppo_applicants WHERE user_id=".request::get_int('id'));
                
		ppo_members_peer::instance()->add( $this->group['id'], request::get_int('id') );
		ppo_peer::instance()->update_rate( $this->group['id'], 1, request::get_int('id') );
                ppo_members_history_peer::instance()->set_member_history($this->group['id'],request::get_int('id'),time(),false,$applicant['reason']);
                rating_helper::update_regional_ratio(request::get_int('id'));
                $memebers_count=db::get_scalar('SELECT count(user_id) 
                    FROM ppo_members 
                    WHERE group_id=:group_id 
                    AND user_id not 
                    IN(SELECT id FROM user_auth WHERE del>0)',array('group_id'=>$old_ppo_id));
                if($memebers_count>=4 && !db_key::i()->exists('ppo_over_for:'.$old_ppo_id))
                                    db_key::i()->set('ppo_over_for:'.$old_ppo_id,1);
                
                if(db_key::i()->exists('ppo_over_for:'.$old_ppo_id) && $memebers_count<4)
                {
                    $options = array(
                        '%ppo_name%' => $old_ppo['title'],
                    ); 
                    user_email_helper::send_sys('ppo_members_alert',ppo_members_peer::instance()->get_user_by_function(1,$old_ppo_id),null,$options);
                    user_email_helper::send_sys('ppo_members_alert',ppo_members_peer::instance()->get_user_by_function(2,$old_ppo_id),null,$options);
                    foreach (user_auth_peer::get_admins() as $receiver)
                    {
                      user_email_helper::send_sys('ppo_members_admin_alert',$receiver,null,$options);  
                    }
                }
                
		load::action_helper('user_email', false);

                $options = array(
                        '%title%' => $this->group['title'],
                        '%link%' => 'http://' . context::get('host') . '/ppo'.$this->group['id'].'/'.$this->group['number'].'/'
                    );
                user_email_helper::send_sys('ppo_approve_applicant',request::get_int('id'),null,$options);
                
	}
}