<?php
class profile_unapprove_photo_action  extends frontend_controller
{
	protected $authorized_access = true;
	public function execute()
	{
           $this->disable_layout();
           $this->set_renderer(null);
           load::model('user/user_data');
           
           if(!session::has_credentials(array('admin','moderator')))
			die();
                
            $user_id=request::get_int('id');
            if (db_key::i()->exists('new_crop_coord_user_' . $user_id))
            db_key::i()->delete('new_crop_coord_user_' . $user_id);   
            
            $user_data=user_data_peer::instance()->get_item($user_id);
            user_data_peer::instance()->update(array("user_id"=>$user_id,"new_photo_salt"=>""));
            
        load::model('messages/messages');
        messages_peer::instance()->add(array(
            'sender_id' => 31,
            'receiver_id' => $user_id,
            'body' => t('Ваша фотография не прошла модерацию. Пожалуйста, загрузите фото портретного формата, на котором Ваше лицо будет изображено крупным планом.'))
        );
        }
}

