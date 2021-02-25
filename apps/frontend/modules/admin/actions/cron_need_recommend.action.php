<?

class admin_cron_need_recommend_action extends basic_controller
{

        public function execute()
        {
            $this->disable_layout();
            $this->set_renderer(false);

            load::model('user/zayava');
            load::model('user/user_recommend');
            load::view_helper('user');
            load::action_helper('user_email',false);

            foreach(user_zayava_peer::instance()->get_by_status() as $zayava_id)
            {
                $zayava = user_zayava_peer::instance()->get_item($zayava_id);
                $recommend = user_recommend_peer::instance()->get_recommenders($zayava['user_id'],20);
                if(count($recommend)==0 && $zayava['notify']==0 && (intval($zayava['date'])+(86400*3))<time())
                {
                    user_zayava_peer::instance()->update(array(
                        'id' => $zayava_id,
                        'notify' => time()
                    ));
                    $options = array(
                        "%fullname%" => user_helper::full_name($zayava['user_id'],false,array(),false)
                        );
                    user_email_helper::send_sys('need_recommend',$zayava['user_id'],null,$options);
                }
            }

        }

}
