<?

class profile_recommend_user_action extends frontend_controller
{
	public function execute()
	{
                load::model('user/user_recommend');
                load::model('user/user_novasys_data');
                load::model('user/zayava');
                load::action_helper('user_email', false); 
                load::view_helper('status');
                
                $this->user=user_auth_peer::instance()->get_item(request::get('id'));
                $this->user_data=user_data_peer::instance()->get_item(request::get('id'));
                $this->user_novasys_data=user_novasys_data_peer::instance()->get_item(request::get('id'));
                $this->zayava = user_zayava_peer::instance()->check_user(request::get('id'));
                $rId = session::get_user_id();
                $rData = user_data_peer::instance()->get_item(session::get_user_id());
                
                request::get('status') ? $status=request::get('status') : (db_key::i()->exists($this->user['id']."_ask_".status_helper::MPU_MEMBER."_recommendation_".session::get_user_id()) ? $status=20 : (db_key::i()->exists($this->user['id']."_ask_".status_helper::MERITOCRAT."_recommendation_".session::get_user_id()) ? $status=10 : $status=$this->user['status']));
//                print_r($status);
//                exit;

                    if ($status==20 && !$this->zayava) $errors[]=t('отсутствует заявление на вступление в МПУ');
                    if (!$this->user['offline'] && !$this->user_data['photo_salt']) $errors[]=t('не загружена фотография');
                    if (!$this->user_data['birthday']) $errors[]=t('не указан день рождения');
                    if (!$this->user_data['gender']) $errors[]=t('не указан пол');
                    if (!$this->user_data['segment']) $errors[]=t('не указано поле "сфера деятельности"');
                    if (!$this->user_data['location']) $errors[]=t('не указан населенный пункт');
                    if (!$this->user_data['father_name']) $errors[]=t('не указано отчество');
                    if (!$this->user_data['mobile']
                            && !$this->user_data['home_phone']
                            && !$this->user_data['work_phone']
                            && !$this->user_data['phone']
                            && !$this->user_novasys_data['mphone1']
                            && !$this->user_novasys_data['mphone1a']
                            && !$this->user_novasys_data['phone']
                            && !$this->user_novasys_data['phone2']
                            && !$this->user_novasys_data['phone3']
                            && !$this->user_novasys_data['hphone3']) $errors[]=t('не указан телефон');
                    if (db::get_scalar('SELECT count(*) as count FROM user_recommend WHERE recommending_user_id='.session::get_user_id().' AND user_id='.request::get('id').' AND status='.$status)) $errors[]=t('Рекомендация на этого пользователя от Вас уже существует');
                    if ($errors) {
                            //load::model('messages/messages');
                            foreach ($errors as $error) {
                                    if (!strpos($error,'екомендац')) $errors_message[]=t('В Вашей анкете(профиле)').' '.$error;
                            }
                           /* $id = messages_peer::instance()->add(array(
                                            'sender_id' =>  31,
                                            'receiver_id' => request::get('id'),
                                            'body' => t('Уважаемый').' '.user_helper::full_name(request::get('id'),false).
                                            ', '.t('участник').' '.user_helper::full_name(session::get_user_id(),false).' '.
                                            t('не смог дать Вам рекомендацию для').' '.($status==20 ? t('вступления в МПУ') : t('получения статуса').' "'.user_auth_peer::get_status($status).'"').
                                            ' '.t('по следующим причинам:').'<p><br>'.implode("<br>",$errors_message).'</p>'.
                                            t('Когда Вы добавите в свою анкету(профиль) эту информацию можно по рекомендации повторно.')
                                    ),false,0);*/  
                         if ($errors_message) {
                                 $options['%receiver_name%'] = user_helper::full_name(request::get('id'),false);
                                 $options['%sender_name%'] = user_helper::full_name(session::get_user_id(),false);
                                 $options['%status%'] = ($status==20 ? t('вступления в МПУ') : t('получения статуса').' "'.user_auth_peer::get_status($status).'"');
                                 $options['%reasons%'] = implode("<br>",$errors_message);
                                 user_email_helper::send_sys('rec_failed',request::get('id'),31,$options);
                         }
                         
                         print json_encode($errors);
                    }
                    else
                    {
                            if ($status<10) $status=10; //26-е колесо велосипеда
                            user_recommend_peer::instance()->insert(array(
                                    'recommending_user_id'=>session::get_user_id(),
                                    'user_id'=>request::get('id'),
                                    'ts'=>time(),
                                    'accept_ts'=>0,
                                    'accept_user_id'=>0,
                                    'status'=>$status,
                                    'ip'=>$_SERVER['REMOTE_ADDR']
                                    ));
                            
                            if ($status==10) {
                                    user_auth_peer::instance()->update(array(
                                           'id'=>$this->user['id'],
                                           'status'=>10
                                           ));
                                    load::system('storage/storage_simple');
                                    $storage = new storage_simple($this->user['id']); 
                                    $new_salt = user_data_peer::instance()->regenerate_photo_salt($this->user['id']);
                                    user_helper::photo_watermark(
                                            user_helper::change_photo_from_status($storage,$this->user['id'],$this->user_data,$new_salt), 
                                            $status, $this->user['expert']);         
                                    user_data_peer::instance()->update(array(
                                        'user_id' => $this->user['id'], 'photo_salt' => $new_salt));

                                    $options['%receiver%'] = user_helper::full_name(request::get('id'),false);
                                    user_email_helper::send_sys('recomendation_approved',request::get('id'),31,$options);
                                    rating_helper::updateRating($rId, 'status');
                            }
                             
                            
                             
                            print json_encode(array('succes'=>1));
                    }                    
                die();
	}
}
