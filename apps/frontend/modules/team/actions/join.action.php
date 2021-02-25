<?

load::app('modules/ppo/controller');
class ppo_join_action extends ppo_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		if ( !$this->group = ppo_peer::instance()->get_item(request::get_int('id')) )
		{
			return;
		}

                if (request::get('invite')==1)
                {
                    if (!ppo_members_peer::instance()->is_member(request::get_int('id'), session::get_user_id() )) {
                	ppo_members_peer::instance()->add( request::get_int('id'), session::get_user_id(), 1 );
			ppo_peer::instance()->update_rate( request::get_int('id'), 1, session::get_user_id() );
                        ppo_members_history_peer::instance()->set_member_history(request::get_int('id'),session::get_user_id(),time());
                    }
                        $this->redirect('/ppo'.$this->group['id'].'/'.$this->group['number'].'/');
                }
			load::model('ppo/applicants');
			ppo_applicants_peer::instance()->add( request::get_int('id'), session::get_user_id(),request::get('text') );
                        load::system('email/email');
                        
                        if ($moders=ppo_peer::get_moderators($this->group['id']))
                        {
                                foreach($moders as $id){ 
                                $user = user_auth_peer::instance()->get_item($id);
                                load::action_helper('user_email',false);
                                $options = array(
                                    '%title%' => $this->group['title'],
                                    '%name%' => strip_tags(user_helper::full_name(session::get_user_id(),false),'<a>'),
                                    '%link%' =>  'http://' . conf::get('server').'/ppo/edit?id='.$this->group['id']
                                    );
                                user_email_helper::send_sys('ppo_join',$user['id'],$sender_id,$options);
                                }
                        }
                db::exec("UPDATE invites SET status=:status WHERE obj_id=:event_id AND to_id=:user_id AND type=:type",
                                array(
                                    'event_id' => request::get_int('id'),
                                    'status'=> 1,
                                    'type' => 2,
                                    'user_id' => session::get_user_id()
                                    ));
	}
}