<?php

load::action_helper('user_email', false);
load::model('user/user_data');
load::model('ppo/members');
load::model('user/user_bio');
load::model('user/user_work');
load::model('user/user_education');
load::model('user/user_desktop');
load::model('user/user_novasys_data');
load::model('user/user_shevchenko_data');
load::model('mailing');

class admin_users_create_action extends frontend_controller
{
    protected $authorized_access = true;

    public function execute()
    {
        load::model('user/user_auth');

        if (
            request::get('submit') &&
            (session::has_credential('admin') || user_auth_peer::instance()->is_inviter(session::get_user_id()))
        ) {
            $email      = strtolower(trim(request::get_string('email')));
            $first_name = trim(request::get('first_name'));
            $last_name  = trim(request::get('last_name'));
            $password   = trim(request::get_string('password', substr(md5(microtime(true)), 0, 8)));
            $invitedBy  = session::get_user_id();
            $status     = request::get_int('status') > 1 ? request::get_int('status') : 1;
            $ppo        = request::get('ppo', []);
            $segment    = request::get_int('segment', null);
            $suslik     = request::get_int('suslik');
            $sendEmail  = request::get_bool('send_email', false);

            if (!empty($email)) {
                $user = user_auth_peer::instance()->get_by_email($email);

                if ($user) {
                    $this->redirect('/profile-'.$user['id']);
                }
            }

            if ('' === $password) {
                $password = substr(md5(microtime(true)), 0, 8);
            }

            if ($segment < 1 or $segment > 38) {
                $segment = null;
            }

            if ($first_name && $last_name) {
                if (18181 === $invitedBy) {
                    $invitedBy = 5;
                }

                $id = user_auth_peer::instance()->create(
                    [
                        'email'          => $email,
                        'password'       => $password,
                        'status'         => $status,
                        'active'         => false,
                        'shevchenko'     => 5 === $invitedBy ? 1 : 0,
                        'identification' => 0,
                        'from'           => 0,
                        'recomended_by'  => 0,
                        'invited_by'     => $invitedBy,
                        'offline'        => 0,
                        'suslik'         => $suslik,
                        'ppo'            => json_encode($ppo),
                    ]
                );

                $user = user_auth_peer::instance()->get_item($id);

                $keys = ['private', 'city', 'region'];
                foreach ($keys as $key) {
                    $ppoId = array_key_exists($key, $ppo) ? (int)$ppo[$key] : 0;

                    if ($ppoId > 0) {
                        ppo_members_peer::instance()->add($ppoId, $id);
                        break;
                    }
                }

                //$birthday = '' !== $_REQUEST['birthday'] ? request::get_string('birthday', "''") : null;
                user_data_peer::instance()->insert(
                    [
                        'user_id'            => $user['id'],
                        'first_name'         => $first_name,
                        'last_name'          => $last_name,
                        'father_name'        => request::get_string('father_name'),
                        'gender'             => request::get('gender', 'm'),
                        'additional_segment' => request::get_int('additional_segment', null),
                        'segment'            => $segment,
                        //'birthday'           => $birthday,
                        'location'           => request::get_string('location', null),
                        'city_id'            => request::get_int('city', 0),
                        'region_id'          => request::get_int('region', 0),
                        'party_city_id'      => request::get_int('city_id', 0),
                        'party_region_id'    => request::get_int('region_id', 0),
                        'phone'              => request::get_string('phone'),
                        'home_phone'         => request::get_string('home_phone'),
                        'work_phone'         => request::get_string('work_phone'),
                        'mobile'             => request::get_string('mobile'),
                        'country_id'         => request::get_int('country', '1'),
                        'language'           => request::get('language', 'ua'),
                        'creator'            => session::get_user_id(),
                    ]
                );

                user_bio_peer::instance()->insert(
                    [
                        'user_id' => $user['id'],
                    ]
                );

                user_work_peer::instance()->insert(
                    [
                        'user_id' => $user['id'],
                    ]
                );

                user_education_peer::instance()->insert(
                    [
                        'user_id' => $user['id'],
                    ]
                );

                user_desktop_peer::instance()->insert(
                    [
                        'user_id' => $user['id'],
                    ]
                );


                user_novasys_data_peer::instance()->insert(
                    [
                        'user_id'   => $user['id'],
                        'email0'    => $user['email'],
                        'contacted' => 5 === $invitedBy ? 1 : 0,
                    ]
                );

                user_shevchenko_data_peer::instance()->insert(
                    [
                        'user_id'       => $user['id'],
                        'shevchenko_id' => 0,
                        'fname'         => $first_name,
                        'sname'         => $last_name,
                    ]
                );

                mailing_peer::instance()->add_maillists_user($email, $first_name, $last_name, [116, 101, 102]);

                /* if (request::get_string('from_shevchenko')==1)
                {

                        $path = conf::get('project_root') . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'mail_tpls' . DIRECTORY_SEPARATOR . 'export_registration' . '.tpl';
                        $spath = conf::get('project_root') . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'mail_tpls' . DIRECTORY_SEPARATOR . 'subject.export_registration' . '.tpl';
                        $mail=file_get_contents($path);
                        $subject=file_get_contents($spath);
                        $body = str_replace(array("%fullname%","%email%","%password%"), array($first_name." ".$last_name, $email, $password), $mail);
                        user_email_helper::send(
                                $user['id'],
                                null,
                                array(
                                        'subject' => $subject,
                                        'body' => $body
                                ),
                                true,
                                'i.shevchenko@meritokrat.org',
                                'Ігор Шевченко'
                        );

                       $options = array(
                           '%fullname%' => $first_name." ".$last_name,
                           '%email%' => $email,
                           '%password%' => $password
                       );
                       user_email_helper::send_sys('users_create_shevchenko',$user['id'],false,$options);

                }
                else
                    {
                 */
                /*$path = conf::get('project_root') . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'mail_tpls' . DIRECTORY_SEPARATOR . 'registration' . '.tpl';
                    $spath = conf::get('project_root') . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'mail_tpls' . DIRECTORY_SEPARATOR . 'subject.registration' . '.tpl';
                    $mail=file_get_contents($path);
                    $subject=file_get_contents($spath);
                    $body = str_replace(array("%fullname%","%email%","%password%"), array($first_name." ".$last_name, $email, $password), $mail);
                    */

                if (true === $sendEmail) {
                    $context = [
                        '%fullname%' => sprintf('%s %s', $first_name, $last_name),
                        '%email%'    => $email,
                        '%password%' => $password,
                        '%inviter%'  => user_helper::full_name($invitedBy, false),
                    ];
                    user_email_helper::send_sys('users_create_inviter', $user['id'], false, $context);
                }

                /*user_email_helper::send(
                        $user['id'],
                        null,
                        array(
                                'subject' => $subject,
                                'body' => $body
                        )
                );*/
                //}
//                if(user_auth_peer::instance()->is_inviter(session::get_user_id()) and ! session::has_credential('admin'))
//	                $this->redirect('/profile/invite?success=1');
//				else
//					$this->redirect('/profile-' . $user['id']);

                if (1 === request::get('is_invite')) {
                    $this->set_template('invited');
                }
            }
        } else {
            if (!session::has_credential('admin')) {
                $this->redirect('/profile/invite');
            }
        }
    }
}
