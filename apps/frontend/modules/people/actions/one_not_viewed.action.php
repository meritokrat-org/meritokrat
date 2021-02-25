<?php
class people_one_not_viewed_action extends frontend_controller
{
	protected $authorized_access = true;
	public function execute()
	{   
            load::model('user/user_view');
            
            $this->disable_layout();
            if ($id=request::get_int('id')) {
            
                user_view_peer::instance()->delete_one_additional_info($id);
                
                load::model('user/user_auth');
		$this->user_auth = user_auth_peer::instance()->get_item( $id);
                
                load::model('user/user_data');
		$this->data = user_data_peer::instance()->get_item( $id);
                
            }
        }
}