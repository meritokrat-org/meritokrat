<?php

load::app('modules/admin/controller');

class admin_users_action extends admin_controller
{

    public function execute()
    {
        if ($this->user_key = request::get('key')) {
            load::model('candidates/candidates');
            load::model('user/dictionary');
            load::model('ban/ban');
            load::model('messages/messages');

            if (is_numeric($this->user_key)) {
                $this->user = user_auth_peer::i()->get_item($this->user_key);
            } else {
                $this->user = user_auth_peer::i()->get_by_email($this->user_key);
            }

            if ($this->user) {
                $this->user_data        = user_data_peer::instance()->get_item($this->user['id']);
                $this->dictionary_names = user_dictionary_peer::instance()->get_item($this->user['id']);
                $this->ban_days         = ban_peer::instance()->get_ban_days($this->user['id']);
            }
        }

        if ($this->user && request::get('submit')) {
            $array = [
                'id'                 => $this->user['id'],
                'active'             => request::get_bool('active'),
                'hidden_type'        => request::get_int('hidden_type'),
                'famous'             => request::get_int('famous'),
                'identification'     => request::get_int('identification'),
                'expert'             => serialize(request::get('expert')),
                'inviter'            => request::get_int('inviter'),
                'shevchenko'         => request::get_int('shevchenko'),
                'suslik'             => request::get_int('suslik'),
                'desktop'            => request::get_int('desktop'),
                'status'             => request::get_int('user_status'),
                'status_functionary' => request::get_int('user_status_functionary'),
                'status_politician'  => request::get_int('user_status_politician'),
                'function'           => request::get_int('user_function'),
                'position'           => request::get_int('user_position'),
                'functions'          => (array) request::get('user_functions', []),
            ];

            user_auth_peer::i()->update($array);

            $user_data = $this->user_data;

            $banned_id = ban_peer::instance()->is_banned($this->user['id']);

            if (request::get_int('ban') !== $this->ban_days) {
                if (request::get_int('ban') > 0) {
                    ban_peer::instance()->insert(
                        [
                            'user_id'    => $this->user['id'],
                            'admin_id'   => session::get_user_id(),
                            'start_time' => time(),
                            'days'       => request::get_int('ban'),
                        ]
                    );
                    user_auth_peer::instance()->update(
                        ['id' => $this->user['id'], 'status' => 1, 'ban' => $this->user['status']]
                    );
                    $ban_arr = ban_peer::get_types();
                    messages_peer::instance()->add(
                        [
                            'sender_id'   => 10599,
                            'receiver_id' => $this->user['id'],
                            'body'        => "У зв'язку з порушенням <a href='/help/index?rulezz'>правил спілкування у мережі Мерітократ.орг</a> Ваші права у мережі обмежені ".$ban_arr[request::get_int(
                                    'ban'
                                )].' до '.date('d.m.Y', time() + (request::get_int('ban') * 60 * 60 * 24)),
                        ],
                        false,
                        1,
                        true
                    );
                } else {
//                    user_auth_peer::i()->update(
//                        ["id" => $this->user['id'], "status" => $this->user['ban'], "ban" => 0]
//                    );
                }

                if ($banned_id) {
                    ban_peer::instance()->update(
                        [
                            'id'     => $banned_id,
                            'active' => 0,
                        ]
                    );
                }

                $this->ban_days = request::get_int('ban');
            }

//            if (request::get_int('ustatus')) {
//                load::action_helper('membership', false);
//                membership_helper::change_status(
//                    $this->user['id'],
//                    request::get_int('ustatus')
//                );
//            }


            /* user_data_peer::instance()->update( array(
              'user_id' => $this->user['id'],
              'rate' => request::get('rate')
              )); */

            if (request::get('enable_synonyms')) {
                $names = trim(request::get('synonyms'));
                if (!$names) {
                    $names = $this->user_data['first_name'].' '.$this->user_data['last_name'].'; ';
                }
                user_dictionary_peer::instance()->set_names($this->user['id'], $names);
            } else {
                user_dictionary_peer::instance()->delete_item($this->user['id']);
            }

            $this->dictionary_names = user_dictionary_peer::instance()->get_item($this->user['id']);

            if (candidates_peer::instance()->is_candidate($this->user['id']) && !request::get('candidate')) {
                candidates_peer::instance()->delete_item($this->user['id']);
            } else {
                if (!candidates_peer::instance()->is_candidate($this->user['id']) && request::get('candidate')) {
                    candidates_peer::instance()->insert(
                        [
                            'user_id' => $this->user['id'],
                        ]
                    );
                }
            }

            if (candidates_peer::instance()->is_candidate($this->user['id'])
                && ($votes = (int) request::get(
                    'candidate_votes'
                ))) {
                load::model('candidates/votes');

                $min_user = db::get_scalar(
                    'SELECT MIN(user_id) FROM '.candidates_votes_peer::instance()->get_table_name()
                );
                if ($min_user >= 0) {
                    $min_user = -1;
                }

                for ($i = 0; $i < $votes; $i++) {
                    candidates_votes_peer::instance()->insert(
                        [
                            'user_id'      => --$min_user,
                            'candidate_id' => $this->user['id'],
                            'ip'           => '0.0.0.0',
                            'ts'           => time(),
                        ]
                    );
                }

                mem_cache::i()->delete('candidates_rating');
            }

            if (!$this->user['active'] && request::get_bool('active')) {
                /* load::system('email/email');

                  $email = new email();
                  $email->setReceiver($this->user['email']);

                  $body =
                  $this->user_data['first_name'] . ', ' . t('Ваш аккаунт был активирован') . "\n" .
                  "\n" .
                  t('Для входа на сайт зайдите по ссылке: ') . "\n" .
                  'http://' . context::get('host') . '/' . "\n" .
                  "\n" .
                  'meritokrat.org';

                  $email->setBody($body);
                  $email->setSubject( t('Активация аккаунта на') . ' meritokrat.org');

                  $email->send();
                 */
                load::action_helper('user_email', false);
                $options = [
                    '%name%' => $this->user_data['first_name'],
                    '%link%' => 'http://'.context::get('host').'/',
                ];
                user_email_helper::send_sys('admin_users', $this->user['id'], null, $options);
            }

            $this->user  = user_auth_peer::i()->get_item($this->user['id']);
            $this->saved = true;
        }
    }

}
