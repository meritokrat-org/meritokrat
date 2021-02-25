<?php
class profile_additional_info_action extends frontend_controller
{
	protected $authorized_access = true;
	public function execute()
	{	
            
                load::model('user/user_data');
                load::model('user/user_desktop');
                load::model('user/user_view');
                load::model('user/user_novasys_data');
                load::model('user/user_shevchenko_data');
                load::model('geo');
                
                $data = user_data_peer::instance()->get_item(session::get_user_id());
                $user = user_auth_peer::instance()->get_item(session::get_user_id());
                
                
                if (request::get_int('year') && request::get_int('month') && request::get_int('day'))
                {
                        $data['birthday']=request::get_int('year').'-'.request::get_int('month').'-'.request::get_int('day');
                }
                
                if (trim(request::get('phone'))) $data['phone']=trim(request::get('phone'));
                if (trim(request::get('about'))) $data['about']=trim(request::get('about'));
                if (trim(request::get('why_join'))) $data['why_join']=trim(request::get('why_join'));
                
                if (request::get('can_do')) $data['can_do']=serialize(request::get('can_do'));
                
                user_data_peer::instance()->update($data);
								
                $user_novasys = array(
                        "user_id" => $data['user_id'],
                        "phone" => $data['phone'],
                );
                user_novasys_data_peer::instance()->update($user_novasys);

                $user_shevchenko = array(
                        "user_id" => $data['user_id'],
                        "about" => $data['about'],
                );
                user_shevchenko_data_peer::instance()->update($user_shevchenko);
                
                //Если заполнена дата и телефон отправляем письма
                if ($data['birthday'] && $data['phone'])
                {
                    //массив получателей
                    $for_secretariat=array(
                        2,//Света
                        4,//Худолей
                        5,//Шеф
                        2641,//Чаплыга
                        3949//Стрижко
                        );
                    
                    $regional_coordinators = user_desktop_peer::instance()->get_regional_coordinators($data['region_id']);
                    $raion_coordinators = user_desktop_peer::instance()->get_raion_coordinators($data['city_id']);
                    $ppo_leaders=db::get_cols("SELECT user_id FROM ppo_members
                                    WHERE function in (1,2)
                                    AND group_id in 
                                    (SELECT id FROM ppo
                                    WHERE (city_id=".$data['city_id']." and category in (1,2))
                                    OR (region_id=".$data['region_id']." and category=3))");
                    
                    $email_receivers=array_unique(
                        array_merge(
                            $for_secretariat,
                            $regional_coordinators,
                            $raion_coordinators,
                            $ppo_leaders
                        )
                    );
                    
                    
                    //$email_receivers[]=29;
                    //$email_receivers=array(29);
                    
                    //отправляем письма
                    foreach($email_receivers as $receiver_id)
                    {
                            load::action_helper('user_email', false);
/*
                            ob_start();
                            var_dump($data);
                            $debug_data = ob_get_clean();
 */                           
                            $options = array(
                                '%name%' => $data['first_name'],
                                '%father_name%' => $data['father_name'],
                                '%last_name%' => $data['last_name'],
                                '%country%' => geo_peer::instance()->get_country_name(($data['country_id'] ? $data['country_id'] : 1 )),
                                '%region%' => geo_peer::instance()->get_region_name($data['region_id']),
                                '%city%' => geo_peer::instance()->get_city_name($data['city_id']),
                                '%location%' => $data['location'],
                                '%birthday%' => $data['birthday'],
                                '%email%' => $user['email'],
                                '%phone%' => $data['phone'],
                                '%sfera%' => user_auth_peer::get_segment($data['segment']),
                                '%additional_sfera%' => user_auth_peer::get_segment($data['additional_segment']),
                                '%about%' =>  $data['about'],
                                '%why_join%' =>  $data['why_join'],
                                '%can_do%' => user_data_peer::get_can_do($data['can_do']),
                                '%lang%' => session::get('language'),
                                '%link%' => 'http://' . context::get('host') . '/profile-' . session::get_user_id()
                                        );
                                
                                
                            user_email_helper::send_sys('additional_info',$receiver_id,$sender_id,$options);
                    }
                    
                    //формируем инфу про непросмотренные профили для кабинетов
                    
                    $for_cabinet=array_diff($email_receivers,array(2,5,2641)); //Свете, Шефу и Мише это не нужно
                    
                    if (!$view_id=db::get_scalar("SELECT id FROM user_view WHERE user_id=".session::get_user_id()))
                    {
                            user_view_peer::instance()->insert(array(
                                            'user_id' => session::get_user_id(),
                                            'not_viewed' => '{'.  implode(',', $for_cabinet).'}'
                                    ));
                    }
                    else 
                    {
                            user_view_peer::instance()->update(array(
                                            'id' => $view_id,
                                            'user_id' => session::get_user_id(),
                                            'not_viewed' => '{'.  implode(',', $for_cabinet).'}'
                                    ));
                    }   
                    
                }
                $this->redirect($_SERVER['HTTP_REFERER']);
        }
}
?>
