<?php
class profile_target_action extends frontend_controller
{
	protected $authorized_access = true;
	public function execute()
	{	
                if ( request::get_int('id') && session::has_credential('admin') )
                {
                        $user_id = request::get_int('id');
                }
                else 
                        $user_id = session::get_user_id();
                if ($get_targets=request::get('target')){
                db_key::i()->set('have_target_'.session::get_user_id(),1); 
                        load::model('user/user_data');   
                        
                        $target_update='{';
                        $targets=array_keys(user_auth_peer::get_targets());
                        foreach ($get_targets as $get_target_id) if (in_array(intval($get_target_id), $targets)) $target_update.= $get_target_id.',';
                        $target_update.='}';
                        $update_target_data=array(
					'user_id' => $user_id,
                                        'target' => str_replace(',}','}',$target_update));
                        $user_target_data=user_data_peer::instance()->get_item($user_id);
                        if (!$user_target_data['target']) $update_target_data['admin_target'] = str_replace(',}','}',$target_update);
                        user_data_peer::instance()->update($update_target_data);
                }
                elseif ($get_admin_targets=request::get('admin_target')  && session::has_credential('admin')){
                    
                        load::model('user/user_data');  
                        
                        $target_update='{';
                        $targets=array_keys(user_auth_peer::get_targets());
                        foreach ((array)$get_admin_targets as $get_target_id) if (in_array(intval($get_target_id), $targets)) $target_update.= $get_target_id.',';
                        $target_update.='}';
                        user_data_peer::instance()->update(array(
					'user_id' => $user_id,
                                        'admin_target' => str_replace(',}','}',$target_update),
                                ));
                }
                $this->redirect('/profile-'.$user_id);
        }
}
?>
