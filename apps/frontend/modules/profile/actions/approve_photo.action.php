<?php
class profile_approve_photo_action  extends frontend_controller
{
	protected $authorized_access = true;
	public function execute()
	{
           $this->disable_layout();
           $this->set_renderer(null);
           load::model('user/user_data');
           
           if(!session::has_credentials(array('admin','moderator'))){
			die();
		}
                
            $user_id=request::get_int('id');
            if (db_key::i()->exists('new_crop_coord_user_' . $user_id)){
                    $redis_data = db_key::i()->get('new_crop_coord_user_' . $user_id);
                   # print_r($redis_data);
                    db_key::i()->set('crop_coord_user_' . $user_id, $redis_data);
            }     
            
                $user_data=user_data_peer::instance()->get_item($user_id);
                user_data_peer::instance()->update(array("user_id"=>$user_id,
                    "photo_salt"=>$user_data['new_photo_salt'],"new_photo_salt"=>""));
                
                if(request::get_int('attention')==1){
                load::action_helper('user_email', false);    
                $options = array(
                        "%fullname%" => user_helper::full_name($user_id,false,array(),false)
                        );
                user_email_helper::send_sys('photo_appr_attention',$user_id,31,$options);
                }
        }
}

