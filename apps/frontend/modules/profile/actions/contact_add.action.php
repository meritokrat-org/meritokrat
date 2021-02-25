<?

class profile_contact_add_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{

		if ( trim(request::get_string('description')) && request::get_int('user_id')  && request::get_int('who') )
		{
			
                        load::model('user/user_contact');
			if(request::get_int('contact_id')) {
                            user_contact_peer::instance()->update(array(
                                    'id'=>  request::get_int('contact_id'),
                                    'user_id' => request::get_int('user_id'),
                                    'who' => request::get_int('who'),
                                    'type' => request::get_int('types'),
                                    'date' => strtotime(request::get('idate')),
                                    'description' => trim(request::get_string('description'))
                                ));
                        }
                        else {
                            $this->id = user_contact_peer::instance()->insert(array(
                                    'user_id' => request::get_int('user_id'),
                                    'contacter_id' => session::get_user_id(),
                                    'who' => request::get_int('who'),
                                    'type' => request::get_int('types'),
                                    'date' => strtotime(request::get('idate')),
                                    'description' => trim(request::get_string('description'))
                            ));
                        }

                        /* авось погнадобится уведомление
			load::action_helper('user_email', false);
                        $options = array(
                                    '%text%' => trim(request::get_string('text')),
                                    '%link%' =>  'http://' . context::get('host') . '/profile-' . request::get_int('profile_id')
                                    );
                        user_email_helper::send_sys('messages_wall',request::get_int('profile_id'),session::get_user_id(),$options);
                         * 
                         */
		}
                
               $this->redirect('/profile-'.request::get_int('user_id'));
	}
}