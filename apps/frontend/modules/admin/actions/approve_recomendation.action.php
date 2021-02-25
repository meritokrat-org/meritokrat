<?
class admin_approve_recomendation_action extends frontend_controller
{

        protected $authorized_access = true;
	public function execute()
	{
                load::model('user/user_auth');
		if ( $recomendation_id=request::get_int('id') and session::has_credential('admin') )
		{
                       load::action_helper('user_email', false);

                       
                       load::model('user/user_recomendations');
                       $this->recomended_user=user_recomendations_peer::instance()->get_item($recomendation_id);
                       if ($this->recomended_user['accepted_user_id']>0)  throw  new public_exception ('Рекомендация вже схвалена. '.user_helper::full_name($this->recomended_user['accepted_user_id']));
                       if ($exists_user=user_auth_peer::instance()->get_by_email(strtolower($this->recomended_user['email'])))  throw new public_exception('Помилка, користувач з таким email вже існуе '.user_helper::full_name($exists_user['id']));
                           $options = array(
                                   '%first_name%' =>  $this->recomended_user['name'],
                                   '%last_name%' =>  $this->recomended_user['last_name'],
                                   '%from%' => strip_tags(user_helper::full_name($this->recomended_user['user_id'],false))
                               );
                        user_email_helper::send_sys('users_create_recommend',$this->recomended_user['user_id'],false,$options);

                        //db::exec("UPDATE user_desktop SET people_attracted=people_attracted+1 WHERE user_id=:user_id",array('user_id'=> $this->recomended_user['user_id']));

                        $password=substr(md5(microtime(true)), 0, 8);

                        load::model('user/user_data');
                        $id = user_auth_peer::instance()->insert(
                                $this->recomended_user['email'],
                                $password,
                                1,
                                false,
                                0,
                                0,
                                0,
                                $this->recomended_user['user_id']
                        );
                        $this->recomended_user['gender']=='f' ? $gender='f' : $gender='m';
                        $user = user_auth_peer::instance()->get_item($id);
                        user_data_peer::instance()->insert(array(
                                'user_id' => $user['id'],
                                'first_name' => $this->recomended_user['name'],
                                'last_name' => $this->recomended_user['last_name'],
                                'country_id' => $this->recomended_user['country_id'],
                                'region_id' => $this->recomended_user['region_id'],
                                'city_id' => $this->recomended_user['city_id'],
                                'gender' => $gender,
                                'language' => $this->recomended_user['language']
                            ));


                        load::model('user/user_bio');
                        user_bio_peer::instance()->insert(array(
                                'user_id' => $user['id']
                        ));
                        load::model('user/user_work');
                        user_work_peer::instance()->insert(array(
                                'user_id' => $user['id']
                        ));
                        load::model('user/user_education');
                        user_education_peer::instance()->insert(array(
                                'user_id' => $user['id']
                        ));
                        load::model('user/user_desktop');
                        user_desktop_peer::instance()->insert(array(
                                'user_id' => $user['id']
                        ));
                        load::model('user/user_novasys_data');
                        user_novasys_data_peer::instance()->insert(array(
                                'user_id' => $user['id'],
                                'email0' => $user['email']
                        ));
                                
                        load::model('user/user_shevchenko_data');
                        user_shevchenko_data_peer::instance()->insert(array(
                                'user_id' => $user['id'],
                                'shevchenko_id' => 0,
                                'fname' => $this->recomended_user['name'],
                                'sname' => $this->recomended_user['last_name']
                        ));
                        
                        load::model('mailing');
                        mailing_peer::instance()->add_maillists_user($this->recomended_user['email'],$this->recomended_user['name'],$this->recomended_user['last_name'],array(116,101,102)); 
                        
                        $options = array(
                               '%fullname%' => $this->recomended_user['name']." ".$this->recomended_user['last_name'],
                               '%inviter%' => user_helper::full_name($this->recomended_user['user_id'], false),
                               '%email%' => $this->recomended_user['email'],
                               '%password%' => $password
                            );
                        
                        user_email_helper::send_sys('users_create_invite',$user['id'],false,$options);
                        
                        user_recomendations_peer::instance()->update(array(
                            'id' => $recomendation_id,
                            'accepted_user_id' => session::get_user_id(),
                            'accepted_ts' => time(),
                            'created_user_id' => $id
                        )
                        );
                        
                        $this->redirect('/profile-' . $user['id']);
                 }
	}
}
