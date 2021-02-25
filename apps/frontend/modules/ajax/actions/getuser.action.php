<?php
class ajax_getuser_action extends frontend_controller
{
        public function execute()
        {
                load::model('geo');

                $this->disable_layout();

                if($user['user_id'] = request::get_int('id'))
                {
                    $this->user = $user;
                    $this->user_list_data=user_data_peer::instance()->get_item($user['user_id']);
                    $this->user_list_auth=user_auth_peer::instance()->get_item($user['user_id']);
                }
        }
}