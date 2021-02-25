<?

class admin_cron_remember_action extends basic_controller
{
	public function execute()
	{
        load::model('mailing');
        load::action_helper('user_email', false);
        load::view_helper('user', false);       
        /*$data = db::get_cols("SELECT ud.user_id FROM user_data ud 
            JOIN user_shevchenko_data us ON us.user_id=ud.user_id
            JOIN user_novasys_data un ON un.user_id=ud.user_id
            WHERE ud.mobile='' AND ud.phone='' AND ud.work_phone='' AND ud.home_phone=''
            AND us.phone='' 
            AND un.phone=''  AND un.mphone1='' AND un.mphone1a='' AND un.phone2=''
            AND un.phone3='' AND un.hphone3='' AND un.mphone3=''
            GROUP BY ud.user_id");*/
        
        $u_data = db::get_cols("SELECT user_id FROM user_data
            WHERE ((mobile='' AND phone='' AND work_phone='' AND home_phone='') 
            or (mobile is NULL AND phone is NULL AND work_phone is NULL AND home_phone is NULL)) 
            AND
            user_id IN (SELECT id FROM user_auth WHERE active=true)");

        $u_s_data = db::get_cols("SELECT user_id FROM user_shevchenko_data
            WHERE phone!=''");
        
        $u_n_data = db::get_cols("SELECT user_id FROM user_novasys_data un
            WHERE un.phone!='' OR un.mphone1!='' OR un.mphone1a!='' OR un.phone2!=''
            OR un.phone3!='' OR un.hphone3!='' OR un.mphone3!=''");
        #print_r($u_n_data);die();
        #echo count($u_data);die();
        $data=array_diff($u_data,$u_s_data,$u_n_data);
        if(!is_array($data))die();
        foreach($data as $f){ #echo user_helper::full_name($f).'<br/>';
           if($f==1337){
                        $user = user_auth_peer::instance()->get_item($f);

			$email = new email();
			$email->setSender("g.stryzhko@meritokratia.info", "Галина Стрижко");
			$email->setReceiver($user['email']);
			$email->setSubject("Запрос контактного номеру телефону");
			$email->setBody("Доброго дня!\nЗаповнюючи свiй профiль в мережi \"МЕРIТОКРАТ\" (meritokrat.org) Ви забули повiдомити контактний номер телефону. Просумо Вас для бiльш зручного спiлкування залишити свiй контактний номер.");
                        $email->send();
			$sent++; 
                        db::exec("INSERT INTO cron_remember(user_id,num) VALUES ($f,(select MAX(num) FROM cron_remember)+1)");
           }  
        }
        echo count($data); die();       
        }
}
