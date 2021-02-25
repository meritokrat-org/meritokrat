<?
load::app('modules/reestr/controller');
class reestr_addmembers_action extends reestr_controller
{
	public function execute()
	{
                $this->set_renderer('ajax');
                $users = request::get('fr');
                                
                if(trim(request::get_string('invnumber')))
                {
                    $array['invnumber'] = trim(request::get_string('invnumber'));
                    $array['invdate'] = $this->get_timestamp();
                    $array['date'] = time();
                }
                if(request::get_int('kvmake'))$array['kvmake'] = 1;
                if(request::get_int('kvgive'))$array['kvgive'] = 1;

                if(count($users)>0)
                {
                    ini_set('memory_limit','1024M');
                    ini_set('max_execution_time', 600);
                    load::action_helper('user_email',false);
                    load::action_helper('membership', false);

                    foreach ( $users as $user_id )
                    {
                        if($array['invnumber'] || $array['kvmake'] || $array['kvgive'])
                        {
                            $item = user_membership_peer::instance()->get_user($user_id);
                            $array['user_id'] = $user_id;
                            if($item['id'])
                            {
                                $array['id'] = $item['id'];
                                user_membership_peer::instance()->update($array);
                                unset($array['id']);
                            }
                            else
                            {
                                user_membership_peer::instance()->insert($array);
                            }

                            if($array['invnumber'])
                            {
                                if(!intval(db_key::i()->get('schanger'.session::get_user_id())) && !in_array(session::get_user_id(),array(2,5,29,1360,3949)))
                                die('error');
                                membership_helper::change_status($user_id, 20);
                            }
                        }

                        $options = array(
                            "%fullname%" => user_helper::full_name($user_id,false,array(),false)
                        );
                        if(request::get_int('msg_photo'))
                        {
                            user_email_helper::send_sys('partbilet_need_photo',$user_id,null,$options);
                        }
                        if(request::get_int('msg_ukr'))
                        {
                            user_email_helper::send_sys('partbilet_need_lang',$user_id,null,$options);
                        }
                    }
                }
                die();
	}

        private function get_timestamp()
        {
            if(!request::get_int('day') || !request::get_int('month') || !request::get_int('year'))
                return 0;
            else {
                return mktime(0, 0, 0, request::get_int('month'), request::get_int('day'), request::get_int('year'));
            }
        }
}