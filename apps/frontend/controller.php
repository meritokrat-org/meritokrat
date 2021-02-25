<?php

load::model('user/user_auth');
load::model('user/user_data');
load::model('program_quotes');
load::model('user/user_voter');
load::model('user/user_visits_log');

abstract class frontend_controller extends basic_controller
{
    public function pre_init()
    {
        if (!session::is_authenticated()) {
            if ($auth = cookie::get('auth')) {
                $auth_data = explode('|', $auth);
                if ($auth_data[0] && $auth_data[1] && ($user = user_auth_peer::instance()->get_by_email(
                        $auth_data[0]
                    )) && ($user['active'] || $user['offline'] > 0) && ($user['password'] == $auth_data[1])) {
                    session::set_user_id($user['id'], explode(',', $user['credentials']));
                    $user_data = user_data_peer::instance()->get_item($user['id']);
                    session::set('language', $user_data['language'] ? $user_data['language'] : 'ua');
                    error_log('SIGNIN: '.$user['id'].' from ip '.$_SERVER['REMOTE_ADDR']);

                    user_visits_log_peer::instance()->insert(
                        array(
                            "user_id" => session::get_user_id(),
                            "time_out" => date("Y-m-d H:i:s"),
                        )
                    );

                    if (!$user['ip']) {
                        user_auth_peer::i()->update(array('ip' => $_SERVER['REMOTE_ADDR'], 'id' => $user['id']));
                    }
                } else {
                    cookie::set('auth', null, null, '/', '.'.context::get('host'));
                }
            }
        } else {
            $user = user_auth_peer::i()->get_item(session::get_user_id());
            if (!$user['active'] && !$user['offline']) {
                cookie::set('auth', null, null, '/', '.'.context::get('host'));
                session::unset_user();
            }
            if ($user['del'] > 0 && $user['active'] == 'false') {
                cookie::set('auth', null, null, '/', '.'.context::get('host'));
                session::unset_user();
                die(
                '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//RU" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                                <html xmlns="http://www.w3.org/1999/xhtml">
                                <head>
                                <title>Ваш профіль у мережі "Мерітократ" був видалений адміністраторами.</title>
                                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                                </head>
                                <body>
                                Ваш профіль у мережі "Мерітократ" був видалений адміністраторами через порушення вами Правил роботи у цій мережі.
                                </body>
                                </html>'
                );
            }
        }
//                var_dump(getenv('LANGUAGE'));

//                if(session::has_credential('admin'))  {
        session::set('language', getenv('LANGUAGE'));
        translate::set_lang(session::get('language', 'ua'));
//                } else {
//                    session::set('language','ua'); 
//                    translate::set_lang( session::get('language', 'ua') );
//                }


        parent::pre_init();

        client_helper::set_meta(
            array(
                'name' => 'keywords',
                'content' => t('test'),
            )
        );
        client_helper::set_meta(
            array(
                'name' => 'description',
                'content' => t('Общественно-политическая социальная сеть для активных граждан'),
            )
        );
    }

    public function init()
    {
        parent::init();
        client_helper::set_title(t('Социальная сеть Меритократ.org'));

        load::model('bookmarks/bookmarks');
        load::model('user/user_data');
        load::model('seo_text');
        load::model('seo_tags');

        load::view_helper('context');
        load::view_helper('user');
        load::view_helper('date');
        load::view_helper('seo');

        load::action_helper('text', true);
        load::action_helper('tags', true);
        load::action_helper('rating', false);

        rating_helper::init();

        $key = 'public_'.context::get_controller()->get_module().'_'.$this->get_action().'_'.request::get_int('id');

        if (session::is_authenticated() || db_key::i()->exists($key) || context::get_controller()->get_module(
            ) == 'ideas') {
            if (preg_match("/MSIE\s6\./s", getenv("USER_AGENT")) && $this->get_action() != 'ie') {
                $this->redirect('/ooops/ie');
            }

            load::model('feed/feed');

            load::model('friends/pending');
            load::model('friends/friends');

            $this->pending_friends = friends_pending_peer::instance()->get_by_user(session::get_user_id());
        } elseif (context::get_controller()->get_module() != 'sign') {
            if (preg_match("/MSIE\s6\./s", getenv("USER_AGENT")) && $this->get_action() != 'ie') {
                $this->redirect('/ooops/ie');
            }
//                    else
//                        $this->redirect('/sign');
        }


        $user_id = session::get_user_id();
        if (request::get_int('id')) {
            $user_id = request::get_int('id');
        }

        $this->show_program_user_id = $user_id;

        $this->show_program_block = 0;
        if (db_key::i()->exists('profile.prgram_block.'.$user_id)) {
            $this->show_program_block = (int)db_key::i()->get('profile.prgram_block.'.$user_id);

            $this->show_program_block++;

            if ($this->show_program_block > 2) {
                $this->show_program_block = 0;
            }
        }

        if (time() - db_key::i()->get('profile.prgram_block.'.$user_id.'.timeout') > 5) {
            db_key::i()->set('profile.prgram_block.'.$user_id.'.timeout', time());
            db_key::i()->set('profile.prgram_block.'.$user_id, $this->show_program_block);
        }

        $this->show_program_block = 1;

        $program_quotes_list = program_quotes_peer::instance()->get_list();
        $this->count_program_quote = count($program_quotes_list);
        $this->program_quote = program_quotes_peer::instance()->get_item(
            $program_quotes_list[rand(0, count($program_quotes_list) - 1)]
        );

        $cond = array("user_id" => $user_id);
        $list = user_voter_peer::instance()->get_list($cond);
        $this->count_people_will_vote = 0;
        $this->task_cover_facebook = 0;
        if (count($list) > 0) {
            $user_voter = user_voter_peer::instance()->get_item($list[0]);
            foreach ($user_voter['informator'] as $informator) {
                if ($informator['contacts'][count($informator['contacts']) - 1]['result'] == 1) {
                    $this->count_people_will_vote++;
                }
            }
            if ($user_voter["tasks_data"]["coverInFacebook"] == 1 && $user_voter["tasks_data"]["coverInFacebook"] != "") {
                $this->task_cover_facebook = 1;
            }

            $this->mini_desktop_data = array();

            if ($user_voter["admin_data"]["willVote"] == 1) {
                $this->mini_desktop_data['willVote'] = 1;
            }

            if ($user_voter["admin_data"]["financialSupport"] == 1) {
                $this->mini_desktop_data['financialSupport'] = 1;
            }

            if ($user_voter["admin_data"]["agitator"] == 1) {
                $this->mini_desktop_data['agitator'] = 1;
            }

            if ($user_voter["admin_data"]["wantRun"] == 1) {
                $this->mini_desktop_data['wantRun'] = 1;
            }

            if ($user_voter["admin_data"]["wantToBeObserver"] == 1) {
                $this->mini_desktop_data['wantToBeObserver'] = 1;
            }

            if ($user_voter["admin_data"]["wantToBeCommissioner"] == 1) {
                $this->mini_desktop_data['wantToBeCommissioner'] = 1;
            }

            if ($user_voter["admin_data"]["wantToBeLawyer"] == 1) {
                $this->mini_desktop_data['wantToBeLawyer'] = 1;
            }
        }

        if (session::is_authenticated()) {
            load::model('user/user_payments');
            $payments = user_payments_peer::instance()->get_user($user_id);
            $this->payments_summa = 0;
            foreach ($payments as $p) {
                $pitem = user_payments_peer::instance()->get_item($p);
                if ($pitem['type'] == 5) {
                    $this->payments_summa += $pitem['summ'];
                }
            }

            $this->set_slot('top.context', '/partials/context.auth');
        }
    }

    public function post_action()
    {
        if (session::is_authenticated()) {
            load::model('messages/messages');
            $this->new_messages = messages_peer::instance()->get_new_count_by_user(session::get_user_id());
        } else {
            session::set_user_id(0, array());
            load::model('ban_ip');
            $client_ip = $this->get_user_ip();
            if (ban_ip_peer::instance()->check_ip($client_ip)) {
                throw new public_exception (
                    'Выбачте, Ваша адреса знаходиться у списку недозволенних. Якщо Ви вважаєте що це помилка - зв&apos;яжіться з адміністрацією мережі. <br/> <a href="mailto:secretariat@meritokratia.info">secretariat@meritokratia.info</a>'
                );
            }
        }
    }

    private function get_user_ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}