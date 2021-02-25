<?

load::app('modules/groups/controller');
class groups_join_action extends groups_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		if ( !$this->group = groups_peer::instance()->get_item(request::get_int('id')) )
		{
			return;
		}

                if (request::get('invite')==1)
                {
                    if (!groups_members_peer::instance()->is_member(request::get_int('id'), session::get_user_id() )) {
                	groups_members_peer::instance()->add( request::get_int('id'), session::get_user_id(), 1 );
			groups_peer::instance()->update_rate( request::get_int('id'), 1, session::get_user_id() );
                    }
                        $this->redirect('/group'.request::get_int('id'));
                }
		elseif ( $this->group['privacy'] == groups_peer::PRIVACY_PUBLIC)
		{
			groups_members_peer::instance()->add( request::get_int('id'), session::get_user_id() );
			groups_peer::instance()->update_rate( request::get_int('id'), 1, session::get_user_id() );
		}
		else if ( $this->group['privacy'] == groups_peer::PRIVACY_PRIVATE )
		{
			load::model('groups/applicants');
			groups_applicants_peer::instance()->add( request::get_int('id'), session::get_user_id() );
                        load::system('email/email');
                        $moders=groups_peer::get_moderators($this->group['id']);
                        foreach($moders as $id){ 
                        $user = user_auth_peer::instance()->get_item($id);
                 	/*
                        $email = new email();
			$email->setReceiver( $user['email'] );
			$email->setSubject('Нова заявка на вступ до спільноти "'.$this->group['title'].'"');
			$email->setBody( user_helper::full_name(session::get_user_id(),false).' подав заявку на вступ до  спільноти "'.$this->group['title']. "\",\n\n".
                                'щоб переглянути список заявок перейдіть за посиланням '.
                                conf::get('server').'/groups/edit?id='.$this->group['id']);
                        $email->send();
                        */
                        load::action_helper('user_email',false);
                        $options = array(
                            '%title%' => $this->group['title'],
                            '%name%' => strip_tags(user_helper::full_name(session::get_user_id(),false),'<a>'),
                            '%link%' =>  'http://' . conf::get('server').'/groups/edit?id='.$this->group['id'],
                            '%settings%'=>'http://'. context::get('host') . '/profile/edit?id='.$user['id'].'&tab=settings'
                            );
                        user_email_helper::send_sys('groups_join',$user['id'],$sender_id,$options);
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