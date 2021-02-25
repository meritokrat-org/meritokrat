<?

class admin_cron_coordinator_info_action extends basic_controller
{

        public function execute()
        {
            $this->disable_layout();
            $this->set_renderer(false);

            load::model('user/user_auth');
            load::model('user/user_data');
            load::model('user/user_desktop');
            load::model('user/zayava');
            load::model('user/user_recommend');
            load::view_helper('user');
            load::action_helper('user_email',false);

            foreach(user_zayava_peer::instance()->get_by_status() as $zayava_id)
            {
                $zayava = user_zayava_peer::instance()->get_item($zayava_id);
                $recommend = user_recommend_peer::instance()->get_recommenders($zayava['user_id'],20);

                if(count($recommend)==0 && (intval($zayava['date'])+(86400*3))<time())
                {   echo "<b>".$zayava['user_id']."</b> (";
                    $regcoordinators = $raicoordinators = array();
                    $data = user_data_peer::instance()->get_item($zayava['user_id']);
                    if($data['region_id'])
                        $regcoordinators = user_desktop_peer::instance()->get_regional_coordinators($data['region_id']);
                    if($data['city_id'])
                        $raicoordinators = user_desktop_peer::instance()->get_raion_coordinators($data['city_id']);
                    $coordinators = array_unique($regcoordinators+$raicoordinators);
                    if(count($coordinators)==0)
                    {
                        $coordinators = array('3949');
                    }

                    foreach($coordinators as $coordinator)
                    {
                        $auth = user_auth_peer::instance()->get_item($coordinator);
                        if($auth['status']==20)
                        {
                            $options = array(
                                "%coordinator%" => user_helper::full_name($coordinator,false,array(),false),
                                "%fullname%" => '<a href="http://'.context::get('server').'/profile-'.$zayava['user_id'].'">id'.$zayava['user_id'].'</a>'
                                );
                            user_email_helper::send_sys('coordinator_inform',$coordinator,31,$options);
                            echo $coordinator.',';
                        }
                    }
                    echo ")<br/>";
                    //break;

                }

            }

        }

}
