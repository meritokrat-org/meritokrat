<?

class profile_addagitation_action extends frontend_controller
{
	protected $authorized_access = true;
	public function execute()
	{
		$this->set_renderer('ajax');
                $this->json = array();

                load::model('user/user_agitmaterials');
                load::model('user/user_agitmaterials_log');

                if(request::get_int('change') && session::has_credential('admin'))
                {#отключаем все делаем через лог
                    /*$user_id = request::get_int('user');
                    $item = user_agitmaterials_peer::instance()->get_user($user_id,request::get_int('agitation'));
                    if(!$item['id'])
                    {
                        user_agitmaterials_peer::instance()->insert(array(
                            'user_id' => $user_id,
                            'type' => request::get_int('agitation')
                        ));
                    }

                    db::exec('UPDATE user_agitmaterials
                        SET '.request::get('type').' = '.request::get_int('changeval').'
                        WHERE type = '.request::get_int('agitation').' AND user_id = '.$user_id);

                    $item = user_agitmaterials_peer::instance()->get_user($user_id,request::get_int('agitation'));
                    $sum = $item['receive'] - $item['given'] - $item['presented'];
                    if($sum <= 0)$sum = '0';

                    $this->json = array('result'=>1,'sum'=>$sum);*/
                }
                elseif(request::get_int('plus') && request::get_int('user'))
                {
                    $this->dotransaction();

                    if(session::has_credential('admin') && request::get('type')=='given' && request::get_int('profile'))
                    {
                        $this->dotransaction(request::get_int('profile'), 'receive');
                    }

                    $this->json = array('result'=>'1');
                }

	}

        private function dotransaction($user_id=0,$type=0)
        {
                if(!$user_id)$user_id = request::get_int('user');
                if(!$type)$type = request::get('type');
                if($type=='given' || ($type=='presented' && request::get_int('profile')==9999999))$profile = request::get_int('profile');
                else $profile = 0;

                $item = user_agitmaterials_peer::instance()->get_user($user_id,request::get_int('agitation'));
                if(!$item['id'])
                {
                    $item['id'] = user_agitmaterials_peer::instance()->insert(array(
                        'user_id' => $user_id,
                        'type' => request::get_int('agitation')
                    ));
                }

                user_agitmaterials_log_peer::instance()->insert(array(
                    'user_id' => $user_id,
                    'type' => request::get_int('agitation'),
                    $type => request::get_int('plus'),
                    'date' => $this->getdate(request::get('data')),
                    'profile' => $profile,
                    'who' => session::get_user_id()
                ));

                $value = user_agitmaterials_log_peer::instance()->get_user($user_id,request::get_int('agitation'));

                user_agitmaterials_peer::instance()->update(array(
                    'id' => $item['id'],
                    'receive' => $value['receive'],
                    'given' => $value['given'],
                    'presented' => $value['presented']
                ));

                if($type!='receive')
                {
                    $item = user_agitmaterials_peer::instance()->get_user($user_id,request::get_int('agitation'));
                    if(!$item['id'])
                    {
                        $ostatok = intval($item['receive']) - intval($item['given']) - intval($item['presented']);
                    }

                    if($ostatok <= 30)
                    {
                        $this->send_low_message(request::get_int('user'),request::get_int('agitation'));
                    }
                }
        }

        private function send_low_message($id,$type)
        {
            load::action_helper('user_email', false);
            $options = array(
                        '%username%' => '<a href="http://'.context::get('host').'/profile/desktop_edit?id='.$id.'&tab=information">'.user_helper::full_name($id, false).'</a>',
                        '%type%' => user_helper::get_agimaterials($type)
                        );
            user_email_helper::send_sys('agitation_low',2546,31,$options); //2546
        }

        private function getdate($str)
        {
            $segments = explode('-',$str);
            return mktime(0, 0, 0, $segments[1], $segments[0], $segments[2]);
        }
}