<?php

load::model('ppo/ppo');
load::model('ppo/members');
load::model('user/locality');

class profile_edit_action extends frontend_controller
{

    protected $authorized_access = true;

    public function execute()
    {
        if ( ! $user_id = request::get_int('id')) {
            $user_id = session::get_user_id();
        }

        if ( ! session::get('prof_lang')) {
            session::set('prof_lang', session::get('language', $code));
        }

        load::model('user/user_shevchenko_data');

        $user = user_auth_peer::instance()->get_item($user_id);
        $this->rkey = "public_profile_index_".request::get_int('id');
        $this->uid = $user_id;

        if (request::get_int('id') && request::get_int('id') !== session::get_user_id()) {
            if (session::has_credential('admin')) {
                $this->access = 1;
            } else {
                if ( ! strpos($user['credentials'], 'admin')) {
                    load::model('user/user_desktop');
                    $udata = user_data_peer::instance()->get_item(request::get_int('id'));
                    $adata = user_auth_peer::instance()->get_item(request::get_int('id'));

                    $region_coordinator = user_desktop_peer::instance()->is_regional_coordinator(
                        session::get_user_id(),
                        true
                    );
                    $raion_coordinator = user_desktop_peer::instance()->is_raion_coordinator(
                        session::get_user_id(),
                        true
                    );

                    if ($udata['region_id'] && is_array($region_coordinator) && in_array(
                            $udata['region_id'],
                            $region_coordinator
                        )) {
                        $this->access = 1;
                    } else {
                        if ($udata['city_id'] && is_array($raion_coordinator) && in_array(
                                $udata['city_id'],
                                $raion_coordinator
                            )) {
                            $this->access = 1;
                        } else {
                            if ($adata['offline'] == session::get_user_id()) {
                                $this->access = 1;
                            }
                        }
                    }
                }
            }
        }

        if ($this->access) {
            $user_id = request::get_int('id');
            $this->user = $user;
        } else {
            $user_id = session::get_user_id();
            $this->user = user_auth_peer::instance()->get_item(session::get_user_id());
        }

        $this->user_data = user_data_peer::instance()->get_item($user_id, 'prof_lang');

        load::model('user/blacklist');
        $this->blacklist = user_blacklist_peer::get_list($user_id);

        load::model('user/user_education');
        $this->user_education = user_education_peer::instance()->get_item($this->user_data['user_id'], 'prof_lang');

        load::model('user/user_work');
        $this->user_work = user_work_peer::instance()->get_item($user_id, 'prof_lang');

        load::model('user/user_volonteer');
        $this->user_volonteer = user_volonteer_peer::instance()->get_item($user_id, 'prof_lang');

        load::model('user/user_bio');
        $this->user_bio = user_bio_peer::instance()->get_item($user_id, 'prof_lang');

        load::model('user/user_mail_access');
        $this->user_mail_access = user_mail_access_peer::instance()->get_item($user_id);

        if (session::has_credential('admin')) {
            $this->user_info = user_shevchenko_data_peer::instance()->get_item($this->user_data['user_id']);

            load::model('user/user_contact');
            $this->user_contact = user_contact_peer::instance()->get_user($this->user_data['user_id']);

            load::model('user/user_novasys_data');
            $this->user_novasys = user_novasys_data_peer::instance()->get_item($this->user_data['user_id']);

            load::model('user/user_work_party');
            load::model('user/user_work_election');
            load::model('user/user_work_action');
            $this->work_party = user_work_party_peer::instance()->get_user($user_id);
            $this->work_election = user_work_election_peer::instance()->get_user($user_id);
            $this->work_action = user_work_action_peer::instance()->get_user($user_id);
        }

        load::view_helper('user');

        load::model('political_views');

        client_helper::register_variable('defaultTab', request::get_string('tab', 'political'));
        client_helper::register_variable('politicalViewsSub', political_views_peer::get_sub_list());
        client_helper::register_variable('politicalViewsOther', political_views_peer::get_other_list());
        client_helper::register_variable('userPoliticalViewsSub', $this->user_data['political_views_sub']);

        if (request::get('submit')) {
            $this->set_renderer('ajax');
            $this->json = [];
            $user_data = user_data_peer::instance()->get_item($user_id);
            if (request::get_int('public') === 1) {
                db_key::i()->set($this->rkey, 1);
            } else {
                db_key::i()->delete($this->rkey);
            }

            if (request::get('type') === 'common') {
                user_data_peer::instance()->update([
                    'user_id' => $user_id,
                    'birthday' => date('Y-m-d', user_helper::dateval('birthday')),
                    '_birthday' => json_encode(array_filter([
                        'd' => request::get_int('birthday_day'),
                        'm' => request::get_int('birthday_month'),
                        'y' => request::get_int('birthday_year'),
                    ], static function ($v) {
                        return (int)$v > 0;
                    })),
                ]);

                request::get_int('party_city') ? $party_city = request::get_int('party_city') : $party_city = 0;
                // request::get_int('party_region') ? $party_region=request::get_int('party_region') : $party_region=request::get_int('region');
                $party_region = request::get_int('party_region');

                user_data_peer::instance()->update([
                    'user_id' => $user_id,
                    'first_name' => mb_substr(trim(request::get_string('first_name')), 0, 64),
                    'last_name' => mb_substr(trim(request::get_string('last_name')), 0, 64),
                    // $this->user_data["first_name"] === mb_substr(
                    //     trim(request::get_string('first_name')),
                    //     0,
                    //     64
                    // ) ? "first_name" : 'new_fname' => mb_substr(trim(request::get_string('first_name')), 0, 64),
                    // $this->user_data["last_name"] == mb_substr(
                    //     trim(request::get_string('last_name')),
                    //     0,
                    //     64
                    // ) ? "last_name" : 'new_lname'  =>  mb_substr(trim(request::get_string('last_name')), 0, 64),
                    'father_name' => mb_substr(trim(request::get_string('father_name')), 0, 64),
                    'location' => mb_substr(request::get_string('location'), 0, 128),
                    'last_location' => mb_substr(request::get_string('last_location'), 0, 256),
                    'position' => mb_substr(request::get_string('position'), 0, 128),
//                    'city_id' => request::get_int('city', 0),
//                    'region_id' => request::get_int('region', 0),
//                    'city' => request::get_string('city_txt', ""),
//                    'region' => request::get_string("region_txt", ""),
//                    'country_id' => request::get_int('country'),
                    'party_city_id' => $party_city,
                    'party_region_id' => $party_region,
                    'party_location' => mb_substr(request::get_string('party_location'), 0, 128),
                    'segment' => request::get_int('segment'),
                    'additional_segment' => request::get_int('additional_segment'),
                    //'age' => request::get_int('age'),
                    'gender' => request::get_string('gender'),
                    'email' => request::get_string('email'),
                    'language' => request::get_string('language'),
                    'about' => mb_substr(request::get('about'), 0, 8096),
                    'street' => mb_substr(trim(request::get_string('street')), 0, 128),
                    'house' => mb_substr(trim(request::get_string('house')), 0, 8),
                    'additional_info' => request::get_string('additional_info'),
                ]);

                $locality = request::get('locality');
                user_locality_peer::instance()->synchronize($user_id, $locality['id']);

                var_dump($locality);

                if (session::get('prof_lang') == 'en' && trim(request::get_string('first_name')) != '' && trim(
                        request::get_string('last_name')
                    ) != '' && $this->user['en'] == false) {
                    user_auth_peer::instance()->update(["en" => true]);
                }

                if ((session::get('prof_lang') == 'ua' || session::get('prof_lang') == 'ru') && trim(
                        request::get_string('first_name')
                    ) != '' && trim(
                        request::get_string('last_name')
                    ) != '' && $this->user['ru'] == false) {
                    user_auth_peer::instance()->update(["ru" => true]);
                }


                if (request::get_int('showadrop') == 1) {
                    db_key::i()->set("showadrop_".$user_id, 1);
                } else {
                    db_key::i()->delete("showadrop_".$user_id);
                }
                /* старые статусы
                  $type=request::get_int('utype');
                  if (in_array($this->user['type'],array(4,5,7,0)) && in_array($type,array(4,5,7))) {
                  user_auth_peer::instance()->update(array(
                  'id' => $this->user['id'],
                  'type' => $type
                  ));
                  }
                 */

                if (request::get_string('father_name', false)) {
                    user_shevchenko_data_peer::instance()->update(
                        [
                            'user_id' => $user_id,
                            'fathername' => mb_substr(trim(request::get_string('father_name')), 0, 64),
                        ]
                    );
                }


                $status = request::get_int('ustatus');

                if ( ! session::has_credential('admin') and (mb_substr(
                            trim(request::get_string('first_name')),
                            0,
                            64
                        ) != $this->user_data['first_name'] or mb_substr(
                            trim(request::get_string('last_name')),
                            0,
                            64
                        ) != $this->user_data['last_name'])) {
                    load::action_helper('user_email', false);
                    //$receivers=array(2,5,4,7,9,29);
                    //$receivers=array(29);
                    foreach (user_auth_peer::get_admins() as $receiver) {
                        /* user_email_helper::send($receiver,
                          session::get_user_id(),
                          array(
                          'subject' => 'Учасник '.$this->user_data['last_name'].' '.$this->user_data['first_name'].' змінив персональні дані',
                          'body' => $this->user_data['last_name'].' '.$this->user_data['first_name'].' змінив персональні дані. Тепер його звати: '.mb_substr(trim(request::get_string('last_name')), 0, 64).' '.mb_substr(trim(request::get_string('first_name')), 0, 64)
                          )
                          ); */
                        $options = [
                            '%name%' => user_helper::full_name($user_id, false),
                            '%newname%' => '<a href="http://'.context::get('host').'/profile-'.$user_id.'">'.mb_substr(
                                    trim(request::get_string('last_name')),
                                    0,
                                    64
                                ).' '.mb_substr(trim(request::get_string('first_name')), 0, 64).'</a>',
                            '%settings%' => 'http://'.context::get(
                                    'host'
                                ).'/profile/edit?id='.$receiver.'&tab=settings',
                        ];
                        user_email_helper::send_sys('profile_edit', $receiver, session::get_user_id(), $options);
                    }
                }
            }
            if (request::get('type') === 'work_space') {
                user_data_peer::instance()->update(
                    [
                        'user_id' => $user_id,
                        'koordinator' => request::get_int('koordinator', 0),
                        'brochure_remaining' => request::get_int('brochure_remaining'),
                        'brochure_given' => request::get_int('brochure_given'),
                    ]
                );
            } else {
                if (request::get('type') === 'education') {
                    user_education_peer::instance()->update(
                        [
                            'user_id' => $user_id,
                            'midle_edu_country' => mb_substr(
                                trim(request::get_string('midle_edu_country')),
                                0,
                                50
                            ),
                            'midle_edu_city' => mb_substr(
                                trim(request::get_string('midle_edu_city')),
                                0,
                                80
                            ),
                            'midle_edu_name' => mb_substr(
                                trim(request::get_string('midle_edu_name')),
                                0,
                                50
                            ),
                            'midle_edu_admission' => mb_substr(
                                trim(request::get_string('midle_edu_admission')),
                                0,
                                20
                            ),
                            'midle_edu_graduation' => mb_substr(
                                trim(request::get_string('midle_edu_graduation')),
                                0,
                                20
                            ),
                            'smidle_edu_country' => mb_substr(
                                trim(request::get_string('smidle_edu_country')),
                                0,
                                50
                            ),
                            'smidle_edu_city' => mb_substr(
                                trim(request::get_string('smidle_edu_city')),
                                0,
                                128
                            ),
                            'smidle_edu_name' => mb_substr(
                                trim(request::get_string('smidle_edu_name')),
                                0,
                                128
                            ),
                            'smidle_edu_admission' => mb_substr(
                                trim(request::get_string('smidle_edu_admission')),
                                0,
                                20
                            ),
                            'smidle_edu_graduation' => mb_substr(
                                trim(request::get_string('smidle_edu_graduation')),
                                0,
                                20
                            ),
                            'major_edu_country' => mb_substr(
                                trim(request::get_string('major_edu_country')),
                                0,
                                50
                            ),
                            'major_edu_city' => mb_substr(
                                trim(request::get_string('major_edu_city')),
                                0,
                                80
                            ),
                            'major_edu_name' => mb_substr(
                                trim(request::get_string('major_edu_name')),
                                0,
                                128
                            ),
                            'major_edu_faculty' => mb_substr(
                                trim(request::get_string('major_edu_faculty')),
                                0,
                                128
                            ),
                            'major_edu_department' => mb_substr(
                                trim(request::get_string('major_edu_department')),
                                0,
                                128
                            ),
                            'major_edu_form' => request::get_int('major_edu_form'),
                            'major_edu_status' => request::get_int('major_edu_status'),
                            'major_edu_admission' => mb_substr(
                                trim(request::get_string('major_edu_admission')),
                                0,
                                20
                            ),
                            'major_edu_graduation' => mb_substr(
                                trim(request::get_string('major_edu_graduation')),
                                0,
                                20
                            ),
                            'additional_edu_country' => mb_substr(
                                trim(request::get_string('additional_edu_country')),
                                0,
                                50
                            ),
                            'additional_edu_city' => mb_substr(
                                trim(request::get_string('additional_edu_city')),
                                0,
                                80
                            ),
                            'additional_edu_name' => mb_substr(
                                trim(request::get_string('additional_edu_name')),
                                0,
                                128
                            ),
                            'additional_edu_faculty' => mb_substr(
                                trim(request::get_string('additional_edu_faculty')),
                                0,
                                128
                            ),
                            'additional_edu_department' => mb_substr(
                                trim(request::get_string('additional_edu_department')),
                                0,
                                80
                            ),
                            'additional_edu_form' => request::get_int('additional_edu_form'),
                            'additional_edu_status' => request::get_int('additional_edu_status'),
                            'additional_edu_admission' => mb_substr(
                                trim(request::get_string('additional_edu_admission')),
                                0,
                                20
                            ),
                            'additional_edu_graduation' => mb_substr(
                                trim(request::get_string('additional_edu_graduation')),
                                0,
                                20
                            ),
                            'another_edu' => trim(request::get_string('another_edu')),
                        ]
                    );
                } // work


                else {
                    if (request::get('type') === 'work') {
                        user_work_peer::instance()->update(
                            [
                                'user_id' => $user_id,
                                'last_work_country' => mb_substr(
                                    trim(request::get_string('last_work_country')),
                                    0,
                                    50
                                ),
                                'last_work_city' => mb_substr(trim(request::get_string('last_work_city')), 0, 80),
                                'last_work_name' => mb_substr(trim(request::get_string('last_work_name')), 0, 128),
                                'last_position' => mb_substr(trim(request::get_string('last_position')), 0, 128),
                                'last_work_admission' => mb_substr(
                                    trim(request::get_string('last_work_admission')),
                                    0,
                                    20
                                ),
                                'last_work_end' => mb_substr(trim(request::get_string('last_work_end')), 0, 20),
                                'work_country' => mb_substr(trim(request::get_string('work_country')), 0, 50),
                                'work_city' => mb_substr(trim(request::get_string('work_city')), 0, 80),
                                'work_name' => mb_substr(trim(request::get_string('work_name')), 0, 128),
                                'position' => mb_substr(trim(request::get_string('position')), 0, 128),
                                'work_admission' => mb_substr(trim(request::get_string('work_admission')), 0, 20),
                                'expert' => trim(request::get_string('expert')),
                                'specialty' => trim(request::get_string('specialty')),
                                'work_jobsearch' => request::get_int('jobsearch'),
                            ]
                        );
                    } // work


                    else {
                        if (request::get('type') === 'bio') {
                            user_bio_peer::instance()->update(
                                [
                                    'user_id' => $user_id,
                                    'birth_family' => request::get_string('birth_family'),
                                    'major_education' => request::get_string('major_education'),
                                    'work' => request::get_string('work'),
                                    'society' => request::get_string('society'),
                                    'politika' => request::get_string('politika'),
                                    'science' => request::get_string('science'),
                                    'additional_education' => request::get_string('additional_education'),
                                    'progress' => request::get_string('progress'),
                                    'other' => request::get_string('other'),
                                ]
                            );
                        } else {
                            if (request::get('type') === 'interests') {
                                user_data_peer::instance()->update(
                                    [
                                        'user_id' => $user_id,
                                        'interests' => request::get_string('interests'),
                                        'books' => request::get_string('books'),
                                        'films' => request::get_string('films'),
                                        'sites' => request::get_string('sites'),
                                        'music' => request::get_string('music'),
                                        'leisure' => request::get_string('leisure'),
                                    ]
                                );
                            } else {
                                if (request::get('type') === 'contacts') {
                                    $site = mb_substr(trim(request::get_string('site')), 0, 128);
                                    if (strlen($site) > 3 and mb_substr($site, 0, 7) !== 'http://') {
                                        $site = 'http://'.$site;
                                    }
                                    user_data_peer::instance()->update(
                                        [
                                            'user_id' => $user_id,
                                            'mobile' => mb_substr(trim(request::get_string('mobile')), 0, 35),
                                            'work_phone' => mb_substr(trim(request::get_string('work_phone')), 0, 35),
                                            'home_phone' => mb_substr(trim(request::get_string('home_phone')), 0, 35),
                                            'phone' => mb_substr(trim(request::get_string('phone')), 0, 35),
                                            'site' => $site,
                                            'icq' => mb_substr(trim(request::get_string('icq')), 0, 15),
                                            'skype' => mb_substr(trim(request::get_string('skype')), 0, 50),
                                            'contacts' => serialize(request::get('contacts')),
                                            'email' => request::get('email'),
                                            'about' => mb_substr(request::get('about'), 0, 8096),
                                        ]
                                    );
                                } else {
                                    if (request::get('type') === 'settings') {
                                        $email = trim(request::get('aemail'));
                                        if ($email && strpos(
                                                $email,
                                                '@'
                                            ) && ($email != $this->user['email']) && ! request::get_int('doonline')) {
                                            if (user_auth_peer::instance()->get_by_email($email, false)) {
                                                $this->json = ['errors' => ['aemail' => ['Этот email уже используется']]];
                                            } else {
                                                user_auth_peer::instance()->update(
                                                    [
                                                        'id' => $this->user['id'],
                                                        'email' => strtolower($email),
                                                    ]
                                                );
                                            }
                                        }

                                        if (request::get_int('doonline')) {
                                            if ($email && strpos($email, '@')) {
                                                if (user_auth_peer::instance()->get_by_email($email, false)) {
                                                    $this->json = ['errors' => ['aemail' => ['Этот email уже используется']]];
                                                } else {
                                                    if ( ! trim(request::get_string('new_password')) || trim(
                                                            request::get_string('new_password')
                                                        ) == 'offline') {
                                                        $this->json = ['errors' => ['new_password' => ['Введите пароль']]];
                                                    } else {
                                                        user_auth_peer::instance()->update(
                                                            [
                                                                'id' => $this->user['id'],
                                                                'email' => strtolower($email),
                                                                'password' => md5(
                                                                    trim(request::get_string('new_password'))
                                                                ),
                                                                'offline' => 0,
                                                            ]
                                                        );
                                                    }
                                                }
                                            } else {
                                                $this->json = ['errors' => ['aemail' => ['Неверный формат электронной почты']]];
                                            }
                                        }

                                        if (request::get_string('new_password') && ! request::get_int('doonline')) {
                                            user_auth_peer::instance()->update(
                                                [
                                                    'id' => $this->user['id'],
                                                    'password' => md5(request::get_string('new_password')),
                                                ]
                                            );
                                        }

                                        user_data_peer::instance()->update(
                                            [
                                                'user_id' => $user_id,
                                                'notify' => request::get_bool('notify'),
                                                'contact_access' => request::get_int('contact_access'),
                                                'share_users' => "{".implode(
                                                        ",",
                                                        (array)request::get('members')
                                                    )."}",
                                            ]
                                        );
                                        user_auth_peer::instance()->update(
                                            [
                                                'id' => $this->user['id'],
                                                'suslik' => request::get_int('suslik'),
                                            ]
                                        );
                                        user_mail_access_peer::instance()->update(
                                            [
                                                'user_id' => $user_id,
                                                'messages_compose' => request::get_int('messages_compose'),
                                                'blogs_comment' => request::get_int('blogs_comment'),
                                                'polls_comment' => request::get_int('polls_comment'),
                                                'messages_wall' => request::get_int('messages_wall'),
                                                'invites_add_group' => request::get_int('invites_add_group'),
                                                'invites_add_event' => request::get_int('invites_add_event'),
                                                'friends_make' => request::get_int('friends_make'),
                                                'admin_feed' => request::get_int('admin_feed'),
                                                'messages_spam' => request::get_int('messages_spam'),
                                                'profile_delete_process' => request::get_int('profile_delete_process'),
                                                'groups_join' => request::get_int('groups_join'),
                                                'profile_edit' => request::get_int('profile_edit'),
                                                'profile_invite' => request::get_int('profile_invite'),
                                                'groups_create' => request::get_int('groups_create'),
                                                'comment_comment' => request::get_int('comment_comment'),
                                                'eventreport_send' => request::get_int('eventreport_send'),
                                                'event_agitation' => request::get_int('event_agitation'),
                                                'admin_profile_delete' => request::get_int('admin_profile_delete'),
                                            ]
                                        );
                                    } else {
                                        if (request::get('type') === 'program') {
                                            candidates_peer::instance()->update(
                                                [
                                                    'user_id' => $user_id,
                                                    'program' => trim(request::get('program')),
                                                ]
                                            );

                                            $this->redirect(sprintf('/profile/edit?id=%s', $user_id));
                                        } else {
                                            if (request::get('type') === 'photo') {
                                                $user_auth = user_auth_peer::instance()->get_item($user_id);

                                                if (session::has_credential('admin') || in_array(
                                                        session::get_user_id(),
                                                        [request::get_int('id'), $user_auth['offline']],
                                                        true
                                                    )) {
                                                    $user_id = request::get_int('id');
                                                } else {
                                                    $user_id = session::get_user_id();
                                                    $this->json = ['errors' => 'No rights!'];
                                                }

                                                load::system('storage/storage_simple');
                                                load::view_helper('image');
                                                load::form('profile/profile_picture');
                                                $form = new profile_picture_form();
                                                $form->load_from_request();

                                                if ($form->is_valid()) {
                                                    $storage = new storage_simple();

                                                    $user_data = user_data_peer::instance()->get_item($user_id);
                                                    $old_salt = $user_data['new_photo_salt'] ?: $user_data['photo_salt'];

                                                    $salt = user_data_peer::instance()->regenerate_photo_salt(
                                                        $user_id,
                                                        "new_"
                                                    );
                                                    user_data_peer::instance()->update(
                                                        [
                                                            'user_id' => $user_id,
                                                            'photo_salt' => $salt,
                                                        ]
                                                    );

                                                    $key = 'profile/'.$user_id.$salt.'.jpg';
                                                    $storage->save_uploaded($key, request::get_file('file'));
                                                    $size = getimagesize($storage->get_path($key));

                                                    $W = $size[0];
                                                    $H = $size[1];

                                                    if ($W > $H * 0.7777) {
                                                        $width = $H * 0.77777;
                                                        $height = $H;
                                                    } else {
                                                        $width = $W;
                                                        $height = $W * 1.285;
                                                    }

                                                    $x = ($W - $width) / 2;
                                                    $y = ($H - $height) / 2;


                                                    # if(db_key::i()->exists('new_crop_coord_user_'.$user_id))
                                                    #        db_key::i()->delete('new_crop_coord_user_'.$user_id);

                                                    $redis_data = implode('-', [$x, $y, $width, $height]);
                                                    db_key::i()->set('new_crop_coord_user_'.$user_id, $redis_data);

                                                    $crop_key = 'user/'.$user_id.$salt.'.jpg';

                                                    $storage->img_crop(
                                                        $storage->get_path($key),
                                                        $crop_key,
                                                        $x,
                                                        $y,
                                                        $width,
                                                        $height
                                                    );
                                                    #$storage->save_from_path($crop_key, $storage->get_path($key));

                                                    if ($width < 200) {
                                                        load::model('messages/messages');
                                                        messages_peer::instance()->add(
                                                            [
                                                                'sender_id' => 31,
                                                                'receiver_id' => session::get_user_id(),
                                                                'body' => t(
                                                                    'Внимание! Вы загрузили очень маленькое фото, поэтому качество очень низкое. Пожалуйста, загрузите фото большего размера.'
                                                                ),
                                                            ]
                                                        );
                                                    }

                                                    // user_helper::photo_watermark($crop_key, $user_auth['status'], intval($user_auth['expert']));

                                                    $image_types = conf::get('image_types');

                                                    $this->json = context::get('image_server').user_helper::photo_path(
                                                            $user_id,
                                                            'p',
                                                            'profile'
                                                        );
                                                } else {
                                                    $this->json = ['errors' => $form->get_errors()];
                                                }
                                            } // admin_info
                                            else {
                                                if (request::get('type') === 'admin_info' && session::has_credential(
                                                        'admin'
                                                    )) {
                                                    $user = user_shevchenko_data_peer::instance()->get_item($user_id);
                                                    if ($user['user_id']) {
                                                        user_shevchenko_data_peer::instance()->update(
                                                            [
                                                                'user_id' => $user_id,
                                                                'fname' => mb_substr(
                                                                    trim(request::get_string('fname')),
                                                                    0,
                                                                    64
                                                                ),
                                                                'fathername' => mb_substr(
                                                                    trim(request::get_string('fathername')),
                                                                    0,
                                                                    64
                                                                ),
                                                                'sname' => mb_substr(
                                                                    trim(request::get_string('sname')),
                                                                    0,
                                                                    64
                                                                ),
                                                                'country' => request::get_int('country'),
                                                                'region' => request::get_int('region'),
                                                                'district' => request::get_int('district'),
                                                                'location' => mb_substr(
                                                                    trim(request::get_string('location')),
                                                                    0,
                                                                    128
                                                                ),
                                                                'age' => mb_substr(
                                                                    trim(request::get_string('age')),
                                                                    0,
                                                                    64
                                                                ),
                                                                'sex' => request::get_int('sex'),
                                                                'sfera' => mb_substr(
                                                                    trim(request::get_string('sfera')),
                                                                    0,
                                                                    128
                                                                ),
                                                                'activity' => request::get_int('activity'),
                                                                'activitya' => mb_substr(
                                                                    trim(request::get_string('activitya')),
                                                                    0,
                                                                    128
                                                                ),
                                                                'activity2' => request::get_int('activity2'),
                                                                'activitya2' => mb_substr(
                                                                    trim(request::get_string('activitya2')),
                                                                    0,
                                                                    128
                                                                ),
                                                                'about' => mb_substr(
                                                                    trim(request::get_string('about')),
                                                                    0,
                                                                    250
                                                                ),
                                                                'referer' => request::get_int('referer'),
                                                                'rsocial' => (request::get(
                                                                        'rsocial'
                                                                    ) != 'other') ? mb_substr(
                                                                    trim(request::get_string('rsocial')),
                                                                    0,
                                                                    128
                                                                ) : request::get('another_rsocial'),
                                                                'ranother' => (request::get_int(
                                                                        'referer'
                                                                    ) == 6) ? request::get('ranother') : "",
                                                                'influence' => mb_substr(
                                                                    trim(request::get_string('influence')),
                                                                    0,
                                                                    128
                                                                ),
                                                                'email_lang' => request::get_int('email_lang'),
                                                                'is_public' => request::get_int('is_public'),
                                                            ]
                                                        );
                                                    } else {
                                                        user_shevchenko_data_peer::instance()->insert(
                                                            [
                                                                'user_id' => $user_id,
                                                                'fname' => mb_substr(
                                                                    trim(request::get_string('fname')),
                                                                    0,
                                                                    64
                                                                ),
                                                                'fathername' => mb_substr(
                                                                    trim(request::get_string('fathername')),
                                                                    0,
                                                                    64
                                                                ),
                                                                'sname' => mb_substr(
                                                                    trim(request::get_string('sname')),
                                                                    0,
                                                                    64
                                                                ),
                                                                'country' => request::get_int('country'),
                                                                'region' => request::get_int('region'),
                                                                'district' => request::get_int('district'),
                                                                'location' => mb_substr(
                                                                    trim(request::get_string('location')),
                                                                    0,
                                                                    128
                                                                ),
                                                                'age' => mb_substr(
                                                                    trim(request::get_string('age')),
                                                                    0,
                                                                    64
                                                                ),
                                                                'sex' => request::get_int('sex'),
                                                                'sfera' => mb_substr(
                                                                    trim(request::get_string('sfera')),
                                                                    0,
                                                                    128
                                                                ),
                                                                'activity' => request::get_int('activity'),
                                                                'activitya' => mb_substr(
                                                                    trim(request::get_string('activitya')),
                                                                    0,
                                                                    128
                                                                ),
                                                                'activity2' => request::get_int('activity2'),
                                                                'activitya2' => mb_substr(
                                                                    trim(request::get_string('activitya2')),
                                                                    0,
                                                                    128
                                                                ),
                                                                'about' => request::get_string('about'),
                                                                'referer' => request::get_int('referer'),
                                                                'rsocial' => (request::get(
                                                                        'rsocial'
                                                                    ) != 'other') ? mb_substr(
                                                                    trim(request::get_string('rsocial')),
                                                                    0,
                                                                    128
                                                                ) : request::get('another_rsocial'),
                                                                'ranother' => (request::get_int(
                                                                        'referer'
                                                                    ) == 6) ? request::get('ranother') : "",
                                                                'influence' => mb_substr(
                                                                    trim(request::get_string('influence')),
                                                                    0,
                                                                    128
                                                                ),
                                                                'email_lang' => request::get_int('email_lang'),
                                                                'is_public' => request::get_int('is_public'),
                                                                'shevchenko_id' => request::get_int('shevchenko_id'),
                                                            ]
                                                        );
                                                    }

                                                    $user = user_novasys_data_peer::instance()->get_item($user_id);
                                                    if ($user['user_id']) {
                                                        user_novasys_data_peer::instance()->update(
                                                            [
                                                                'user_id' => $user_id,
                                                                'notates' => request::get_string('notates'),
                                                                'status' => request::get_int('status'),
                                                            ]
                                                        );
                                                    } else {
                                                        user_novasys_data_peer::instance()->insert(
                                                            [
                                                                'user_id' => $user_id,
                                                                'notates' => request::get_string('notates'),
                                                                'status' => request::get_int('status'),
                                                            ]
                                                        );
                                                    }

                                                    $ud = [
                                                        "user_id" => $user_id,
                                                        "why_join" => request::get("why_join"),
                                                        "can_do" => serialize(request::get("can_do")),
                                                        "can_do_text" => strip_tags(request::get("can_do_text")),
                                                    ];
                                                    user_data_peer::instance()->update($ud);
                                                } // contact_info
                                                else {
                                                    if (request::get(
                                                            'type'
                                                        ) === 'contact_info' && session::has_credential(
                                                            'admin'
                                                        )) {
                                                        $date = request::get('idate');
                                                        $type = request::get('types');
                                                        $who = request::get('who');
                                                        $description = request::get('description');

                                                        $items = user_contact_peer::instance()->get_user($user_id);
                                                        foreach ($items as $i) {
                                                            user_contact_peer::instance()->delete_item($i);
                                                        }

                                                        if (count($description) > 0) {
                                                            foreach ($description as $k => $v) {
                                                                if (trim($v) != '') {
                                                                    $time = explode('/', $date[$k]);
                                                                    if ( ! $time[0] && ! $time[1] && ! $time[2]) {
                                                                        $time = time();
                                                                    } else {
                                                                        $time = mktime(
                                                                            0,
                                                                            0,
                                                                            0,
                                                                            $time[1],
                                                                            $time[0],
                                                                            $time[2]
                                                                        );
                                                                    }
                                                                    user_contact_peer::instance()->insert(
                                                                        [
                                                                            'user_id' => $user_id,
                                                                            'contacter_id' => session::get_user_id(),
                                                                            'date' => $time,
                                                                            'type' => $type[$k],
                                                                            'who' => $who[$k],
                                                                            'description' => trim($v),
                                                                        ]
                                                                    );
                                                                }
                                                            }
                                                        }

                                                        user_data_peer::instance()->update(
                                                            [
                                                                'user_id' => $user_id,
                                                                'contact_status' => request::get_int('contact_status'),
                                                            ]
                                                        );

                                                        $novasys = user_novasys_data_peer::instance()->get_item(
                                                            $user_id
                                                        );
                                                        if ($novasys['user_id']) {
                                                            user_novasys_data_peer::instance()->update(
                                                                [
                                                                    'user_id' => $user_id,
                                                                    'all_contacts' => mb_substr(
                                                                        trim(request::get_string('all_contacts')),
                                                                        0,
                                                                        250
                                                                    ),
                                                                    'contacted' => request::get_int('contacted'),
                                                                ]
                                                            );
                                                        } else {
                                                            user_novasys_data_peer::instance()->insert(
                                                                [
                                                                    'user_id' => $user_id,
                                                                    'all_contacts' => mb_substr(
                                                                        trim(request::get_string('all_contacts')),
                                                                        0,
                                                                        250
                                                                    ),
                                                                    'contacted' => request::get_int('contacted'),
                                                                ]
                                                            );
                                                        }

                                                        if (request::get_int('sendcontact') && in_array(
                                                                request::get_int('contacted'),
                                                                [1, 2, 3, 4, 5, 6, 7, 8, 9, 11]
                                                            )) {
                                                            load::model('user/user_desktop');
                                                            $user_data = user_data_peer::instance()->get_item($user_id);

                                                            switch (request::get_int('contacted')) {
                                                                //@todo айдишники заменить на статусы
                                                                case 1:
                                                                    $coordinators = [5]; // IAS
                                                                    break;

                                                                case 2:
                                                                    $coordinators = [4]; // Худолій
                                                                    break;
                                                                case 3:
                                                                    $coordinators = [3949]; // Стрижко
                                                                    break;

                                                                case 4:
                                                                    $coordinators = user_desktop_peer::instance(
                                                                    )->get_regional_coordinators(
                                                                        $user_data['region_id']
                                                                    );
                                                                    break;

                                                                case 5:
                                                                    $coordinators = user_desktop_peer::instance(
                                                                    )->get_raion_coordinators(
                                                                        $user_data['city_id']
                                                                    );
                                                                    break;

                                                                case 6:
                                                                    $coordinators = [2641]; // Чаплига
                                                                    break;


                                                                case 7:
                                                                    $coordinators = [1299]; // Шульцева
                                                                    break;

                                                                case 8:
                                                                    $coordinators = [2]; // Коломіець
                                                                    break;
                                                                case 11:
                                                                    $coordinators = [2464]; // Predstavitel` VNZ
                                                                    break;
                                                            }
                                                            if (count($coordinators) > 0) {
                                                                load::action_helper('user_email', false);
                                                                $options = [
                                                                    '%message%' => '',
                                                                    '%name%' => strip_tags(
                                                                        user_helper::full_name($user_id)
                                                                    ),
                                                                    '%link%' => '<a href="http://'.conf::get(
                                                                            'server'
                                                                        ).'/profile-'.$user_id.'">http://'.conf::get(
                                                                            'server'
                                                                        ).'/profile-'.$user_id.'</a>',
                                                                ];
                                                                $txt = trim(
                                                                    strip_tags(request::get('contactedtext'))
                                                                );
                                                                if ($txt != '') {
                                                                    $options['%message%'] = $txt;
                                                                }
                                                                foreach ($coordinators as $coordinator) {
                                                                    $options['%coordinator%'] = strip_tags(
                                                                        user_helper::full_name($coordinator)
                                                                    );
                                                                    user_email_helper::send_sys(
                                                                        'contacts_binding',
                                                                        $coordinator,
                                                                        false,
                                                                        $options
                                                                    );
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        if (request::get(
                                                                'type'
                                                            ) === 'update_stat' && session::has_credential('admin')) {
                                                            $user_id = request::get_int('id');

                                                            if ($user_id) {
                                                                user_data_peer::instance()->update(
                                                                    [
                                                                        'user_id' => $user_id,
                                                                        'contact_status' => request::get_int('status'),
                                                                    ]
                                                                );
                                                            }
                                                        } else {
                                                            if (request::get(
                                                                    'type'
                                                                ) === 'admin_contact' && session::has_credential(
                                                                    'admin'
                                                                )) {
                                                                $user = user_novasys_data_peer::instance()->get_item(
                                                                    $user_id
                                                                );
                                                                if ($user['user_id']) {
                                                                    user_novasys_data_peer::instance()->update(
                                                                        [
                                                                            'user_id' => $user_id,
                                                                            'phone' => mb_substr(
                                                                                trim(request::get_string('phone')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'email0' => mb_substr(
                                                                                trim(request::get_string('email0')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'site' => mb_substr(
                                                                                trim(request::get_string('site')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'mphone1' => mb_substr(
                                                                                trim(request::get_string('mphone1')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'mphone1a' => mb_substr(
                                                                                trim(request::get_string('mphone1a')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'fax1' => mb_substr(
                                                                                trim(request::get_string('fax1')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'email1' => mb_substr(
                                                                                trim(request::get_string('email1')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'email1a' => mb_substr(
                                                                                trim(request::get_string('email1a')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'site1' => mb_substr(
                                                                                trim(request::get_string('site1')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'skype1' => mb_substr(
                                                                                trim(request::get_string('skype1')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'icq1' => mb_substr(
                                                                                trim(request::get_string('icq1')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'phone2' => mb_substr(
                                                                                trim(request::get_string('phone2')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'fax2' => mb_substr(
                                                                                trim(request::get_string('fax2')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'email2' => mb_substr(
                                                                                trim(request::get_string('email2')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'site2' => mb_substr(
                                                                                trim(request::get_string('site2')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'name3' => mb_substr(
                                                                                trim(request::get_string('name3')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'lname3' => mb_substr(
                                                                                trim(request::get_string('lname3')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'mname3' => mb_substr(
                                                                                trim(request::get_string('mname3')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'phone3' => mb_substr(
                                                                                trim(request::get_string('phone3')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'hphone3' => mb_substr(
                                                                                trim(request::get_string('hphone3')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'mphone3' => mb_substr(
                                                                                trim(request::get_string('mphone3')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'email3' => mb_substr(
                                                                                trim(request::get_string('email3')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'skype3' => mb_substr(
                                                                                trim(request::get_string('skype3')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'icq3' => mb_substr(
                                                                                trim(request::get_string('icq3')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                        ]
                                                                    );
                                                                } else {
                                                                    user_novasys_data_peer::instance()->insert(
                                                                        [
                                                                            'user_id' => $user_id,
                                                                            'phone' => mb_substr(
                                                                                trim(request::get_string('phone')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'email0' => mb_substr(
                                                                                trim(request::get_string('email0')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'site' => mb_substr(
                                                                                trim(request::get_string('site')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'mphone1' => mb_substr(
                                                                                trim(request::get_string('mphone1')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'mphone1a' => mb_substr(
                                                                                trim(request::get_string('mphone1a')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'fax1' => mb_substr(
                                                                                trim(request::get_string('fax1')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'email1' => mb_substr(
                                                                                trim(request::get_string('email1')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'email1a' => mb_substr(
                                                                                trim(request::get_string('email1a')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'site1' => mb_substr(
                                                                                trim(request::get_string('site1')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'skype1' => mb_substr(
                                                                                trim(request::get_string('skype1')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'icq1' => mb_substr(
                                                                                trim(request::get_string('icq1')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'phone2' => mb_substr(
                                                                                trim(request::get_string('phone2')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'fax2' => mb_substr(
                                                                                trim(request::get_string('fax2')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'email2' => mb_substr(
                                                                                trim(request::get_string('email2')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'site2' => mb_substr(
                                                                                trim(request::get_string('site2')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'name3' => mb_substr(
                                                                                trim(request::get_string('name3')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'lname3' => mb_substr(
                                                                                trim(request::get_string('lname3')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'mname3' => mb_substr(
                                                                                trim(request::get_string('mname3')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'phone3' => mb_substr(
                                                                                trim(request::get_string('phone3')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'hphone3' => mb_substr(
                                                                                trim(request::get_string('hphone3')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'mphone3' => mb_substr(
                                                                                trim(request::get_string('mphone3')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'email3' => mb_substr(
                                                                                trim(request::get_string('email3')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'skype3' => mb_substr(
                                                                                trim(request::get_string('skype3')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                            'icq3' => mb_substr(
                                                                                trim(request::get_string('icq3')),
                                                                                0,
                                                                                250
                                                                            ),
                                                                        ]
                                                                    );
                                                                }
                                                            } else {
                                                                if (request::get(
                                                                        'type'
                                                                    ) === 'political' && session::has_credential(
                                                                        'admin'
                                                                    )) {
                                                                    $ppo = request::get('ppo', []);
                                                                    $keys = ['private', 'city', 'region'];

                                                                    db::exec(
                                                                        'delete from ppo_members where user_id = :userId',
                                                                        ['userId' => $user_id]
                                                                    );
                                                                    foreach ($keys as $key) {
                                                                        $ppoId = array_key_exists(
                                                                            $key,
                                                                            $ppo
                                                                        ) ? (int)$ppo[$key] : 0;

                                                                        if ($ppoId > 0) {
                                                                            ppo_members_peer::instance()->add(
                                                                                $ppoId,
                                                                                $user_id
                                                                            );
                                                                            break;
                                                                        }
                                                                    }

                                                                    user_auth_peer::instance()->update(
                                                                        [
                                                                            'id' => $user_id,
                                                                            'ppo' => json_encode($ppo),
                                                                        ]
                                                                    );

                                                                    $pname = request::get('pname');
                                                                    $psite = request::get('psite');
                                                                    $ppost = request::get('ppost');
                                                                    $pstatus = request::get('pstatus');
                                                                    $pstart = user_helper::dateval('pstart', true);
                                                                    $pend = user_helper::dateval('pend', true);
                                                                    $pacting = request::get('pacting');

                                                                    $eyear = request::get('eyear');
                                                                    $etype = request::get('etype');
                                                                    $estatus = request::get('estatus');
                                                                    $eregion = request::get('eregion');
                                                                    $ecity = request::get('ecity');
                                                                    $elocation = request::get('elocation');

                                                                    $astart = user_helper::dateval('astart', true);
                                                                    $aend = user_helper::dateval('aend', true);
                                                                    $apost = request::get('apost');
                                                                    $aregion = request::get('aregion');
                                                                    $acity = request::get('acity');
                                                                    $alocation = request::get('alocation');
                                                                    $aname = request::get('aname');

                                                                    user_work_party_peer::instance()->del_user(
                                                                        $user_id
                                                                    );
                                                                    if (is_array(request::get('pname'))) {
                                                                        foreach ($pname as $k => $v) {
                                                                            if ( ! $v) {
                                                                                $this->json[] = [
                                                                                    'section' => '$pname',
                                                                                    'context' => [
                                                                                        '$pname' => json_encode($pname),
                                                                                    ],
                                                                                ];
                                                                                continue;
                                                                            }
                                                                            user_work_party_peer::instance()->insert(
                                                                                [
                                                                                    'user_id' => $user_id,
                                                                                    'name' => mb_substr(
                                                                                        trim($pname[$k]),
                                                                                        0,
                                                                                        128
                                                                                    ),
                                                                                    'site' => mb_substr(
                                                                                        trim($psite[$k]),
                                                                                        0,
                                                                                        128
                                                                                    ),
                                                                                    'post' => mb_substr(
                                                                                        trim($ppost[$k]),
                                                                                        0,
                                                                                        128
                                                                                    ),
                                                                                    'status' => intval($pstatus[$k]),
                                                                                    'start' => date(
                                                                                        "Y-m-d",
                                                                                        $pstart[$k]
                                                                                    ),
                                                                                    'end' => date(
                                                                                        "Y-m-d",
                                                                                        $pend[$k]
                                                                                    ),
                                                                                    'acting' => mb_substr(
                                                                                        trim($pacting[$k]),
                                                                                        0,
                                                                                        128
                                                                                    ),
                                                                                ]
                                                                            );
                                                                        }
                                                                    }

                                                                    user_work_election_peer::instance()->del_user(
                                                                        $user_id
                                                                    );
                                                                    if (is_array(request::get('elocation'))) {
                                                                        foreach ($elocation as $k => $v) {
                                                                            if ( ! $v || ! $eregion[$k] || ! $ecity[$k]) {
                                                                                $this->json[] = [
                                                                                    'section' => '$elocation',
                                                                                    'context' => [
                                                                                        '$elocation' => json_encode(
                                                                                            $elocation
                                                                                        ),
                                                                                    ],
                                                                                ];
                                                                                continue;
                                                                            }
                                                                            user_work_election_peer::instance()->insert(
                                                                                [
                                                                                    'user_id' => $user_id,
                                                                                    'year' => $eyear[$k],
                                                                                    'type' => $etype[$k],
                                                                                    'status' => $estatus[$k],
                                                                                    'region' => $eregion[$k],
                                                                                    'city' => $ecity[$k],
                                                                                    'location' => mb_substr(
                                                                                        trim($elocation[$k]),
                                                                                        0,
                                                                                        128
                                                                                    ),
                                                                                ]
                                                                            );
                                                                        }
                                                                    }

                                                                    user_work_action_peer::instance()->del_user(
                                                                        $user_id
                                                                    );
                                                                    if (is_array(request::get('alocation'))) {
                                                                        foreach ($apost as $k => $v) {
                                                                            if ( ! $v) {
                                                                                $this->json[] = [
                                                                                    'section' => '$apost',
                                                                                    'context' => [
                                                                                        '$apost' => json_encode($apost),
                                                                                    ],
                                                                                ];
                                                                                continue;
                                                                            }
                                                                            user_work_action_peer::instance()->insert(
                                                                                [
                                                                                    'user_id' => $user_id,
                                                                                    'start' => date(
                                                                                        "Y-m-d",
                                                                                        $astart[$k]
                                                                                    ),
                                                                                    'end' => date(
                                                                                        "Y-m-d",
                                                                                        $aend[$k]
                                                                                    ),
                                                                                    'post' => $apost[$k],
                                                                                    'region' => mb_substr(
                                                                                        trim($aregion[$k]),
                                                                                        0,
                                                                                        128
                                                                                    ),
                                                                                    'city' => mb_substr(
                                                                                        trim($acity[$k]),
                                                                                        0,
                                                                                        128
                                                                                    ),
                                                                                    'location' => mb_substr(
                                                                                        trim($alocation[$k]),
                                                                                        0,
                                                                                        128
                                                                                    ),
                                                                                    'name' => mb_substr(
                                                                                        trim($aname[$k]),
                                                                                        0,
                                                                                        128
                                                                                    ),
                                                                                ]
                                                                            );
                                                                        }
                                                                    }
                                                                } else {
                                                                    if (request::get('type') === 'map') {
                                                                        $user_id = (session::has_credential(
                                                                                'admin'
                                                                            ) && request::get_int('id')) ? request::get(
                                                                            'id'
                                                                        ) : session::get_user_id();
                                                                        user_data_peer::instance()->update(
                                                                            [
                                                                                'user_id' => $user_id,
                                                                                'locationlat' => request::get_string(
                                                                                    'LocationLat'
                                                                                ),
                                                                                'locationlng' => request::get_string(
                                                                                    'LocationLng'
                                                                                ),
                                                                                'MapZoom' => request::get_string(
                                                                                    'MapZoom'
                                                                                ),
                                                                                'onmap' => request::get_int(
                                                                                    'onmap'
                                                                                ),
                                                                            ]
                                                                        );
                                                                        if ($user_data['LocationLat']) {
                                                                            $this->redirect(
                                                                                '/profile/edit?id='.request::get_int(
                                                                                    'id'
                                                                                ).'&tab=map'
                                                                            );
                                                                        } else {
                                                                            $this->redirect(
                                                                                '/search?smap=1&distance=10&submit=1'
                                                                            );
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (request::get_int('ustatus') && request::get_int('ustatus') != $user['status']) {
                load::action_helper('membership', false);
                membership_helper::change_status($user_id, request::get_int('ustatus'));
            }
        }
    }

    protected function check_empty_array($array)
    {
        if ( ! is_array($array) || count($array) == 0) {
            $array = [''];
        }

        return $array;
    }

    /**
     * @param $user
     * @return array
     */
    private function get_user_work_experience($user)
    {
        $list = [];

        return $list;
    }
}
