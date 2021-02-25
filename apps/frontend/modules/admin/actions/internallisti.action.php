<?php

load::app('modules/admin/controller');
load::model('messages/messages');
load::action_helper('user_email', false);
load::action_helper('page', false);

class admin_internallisti_action extends admin_controller
{
    public function execute()
    {
        if (!request::get('message')) {
            $this->disable_layout();
            //$this->users = db::get_cols("SELECT id FROM user_auth WHERE active=true");
            //$this->userpager = pager_helper::get_pager($this->users, request::get_int('page'), 15);
            //$this->users = $this->userpager->get_list();
            $this->users = [];
        } else {
            $this->set_renderer('ajax');
            $this->json = [];

            $this->friends   = request::get('fr');
            $this->from      = session::get_user_id();
            $this->message   = trim(request::get_string('message'));
            $this->receivers = $this->friends;

            if ('' !== $this->message && count($this->receivers) > 0) {
                $sender_id = session::get_user_id();
                foreach ($this->receivers as $receiver_id) {
                    if (!(int)$receiver_id) {
                        continue;
                    }
                    $id = messages_peer::instance()->add(
                        [
                            'sender_id'   => $sender_id,
                            'receiver_id' => $receiver_id,
                            'body'        => trim($this->message),
                        ],
                        true,
                        0
                    );

                    $options = [
                        '%text%'     => tag_helper::get_short(trim(strip_tags($this->message)), 120),
                        '%link%'     => 'https://'.context::get('host').'/messages/view?id='.$id,
                        '%settings%' => 'https://'.context::get(
                                'host'
                            ).'/profile/edit?id='.$receiver_id.'&tab=settings',
                    ];
                    user_email_helper::send_sys('messages_compose', $receiver_id, $sender_id, $options);
                }
            }
        }
    }
}