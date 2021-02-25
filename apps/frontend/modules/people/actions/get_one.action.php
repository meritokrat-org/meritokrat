<?php
class people_get_one_action extends frontend_controller
{
	protected $authorized_access = true;
	public function execute()
	{   
            load::model('user/user_sessions');
            $this->disable_layout();
            if ($id=request::get_int('id')) {
            
                load::model('user/user_auth');
		$this->user_auth = user_auth_peer::instance()->get_item( $id);
                
                load::model('user/user_data');
		$this->user_data = user_data_peer::instance()->get_item( $id);
                
                load::model('user/user_shevchenko_data');
                $this->user_info=user_shevchenko_data_peer::instance()->get_item($id);
                
                load::model('user/user_contact');
                $this->user_contact = user_contact_peer::instance()->get_user($id);

                load::model('user/user_novasys_data');
                $this->user_novasys = user_novasys_data_peer::instance()->get_item($id);
            }
        }
}