<?

class admin_cron_action extends basic_controller
{
	public function execute()
	{
        load::model('mailing');
        load::action_helper('user_email', false);
        $mailists = request::get_int('mailists');
            if($mailists==1)
            {

        $data = mailing_peer::instance()->get_mailing_lists();
        var_dump($data);
        if(!is_array($data) || !is_array($data['list']))die();
        $sonrmail=0;

  		foreach ( $data['list'] as $user_id )
		{

			$name =  $user_id['first_name'];

			$subject = str_replace('NAME', $name, $subject_tpl);
			$body = str_replace('NAME', $name, $body_tpl);

			$email = new email();
			$email->setSender($data['mailing']['sender_email'], $data['mailing']['sender_name']);
			$email->setReceiver( $user_id['email'] );
			$email->setSubject( $data['mailing']['subject'] );
			$email->setBody( $data['mailing']['body'] );
                        $email->isHTML();
                        $email->send();
                        mailing_peer::instance()->set_sendmail($data['mailing']['id'],$user_id['id'],true);
			$sent++;
		}
        die();
            }
            elseif(request::get_int('activation')==1)
            {

                load::model('user/user_auth');
                load::model('user/user_data');
                load::action_helper('user_email',false);
                $today_start=strtotime(date("Y-m-d"));
                $first_date_start=$today_start-5*24*60*60;
                $first_date_end=$first_date_start+24*60*60;
                $data = db::get_cols("SELECT id FROM user_auth WHERE (created_ts BETWEEN ".($today_start-5*24*60*60)." AND ".($today_start-4*24*60*60)." or  created_ts BETWEEN ".($today_start-10*24*60*60)." AND ".($today_start-9*24*60*60).") AND active is FALSE AND del=0");
                if(!is_array($data))die();
                foreach ( $data as $id )
                        {
                                $password = substr(md5(microtime(true)), 0, 8);
                                $user = user_auth_peer::instance()->get_item($id);
                                user_auth_peer::instance()->update(array(
                                                'id' => $id,
                                                'password' => md5($password),
                                                'last_invite' => time()
                                        ));

                                $user_data = user_data_peer::instance()->get_item($id);

                                $options = array(
                                    "%fullname%" => $user_data['first_name']." ".$user_data['last_name'],
                                    "%email%" => $user['email'],
                                    "%password%" => $password
                                    );
                                user_email_helper::send_sys('user_resend',$user['id'],null,$options);

                        }
                        var_dump($data);
        die();
            }else{
        $data = mailing_peer::instance()->get_mailing();
        if(!is_array($data) || !is_array($data['list']) || !count($data['list']))die();
        $sonrmail=0;
		foreach ( $data['list'] as $user_id )
		{
			$user = user_auth_peer::instance()->get_item($user_id);

			$user_data = user_data_peer::instance()->get_item($user_id);

			$name = $user_data['first_name'];

			$subject = str_replace('NAME', $name, $subject_tpl);
			$body = str_replace('NAME', $name, $body_tpl);

			$email = new email();
			$email->setSender($data['mailing']['sender_email'], $data['mailing']['sender_name']);
			$email->setReceiver( $user['email'] );
			$email->setSubject( $data['mailing']['subject'] );
			$email->setBody( $data['mailing']['body'] );
                        $email->isHTML();
                       /* if($user_id==1337 && $sonrmail==0)
                            {
                            $email->send();
                            $sonrmail=1;
                            }
			$email->send(true);*/
                        $email->send();
                        mailing_peer::instance()->set_sendmail($data['mailing']['id'],$user_id);
			$sent++;
		}
        die();
	}}
}
