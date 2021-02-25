<?php

class sign_up_action extends frontend_controller
{
    public function execute()
    {
	    load::model('user/user_auth');
	    if (request::get('submit')) {
		    load::action_helper('user_email', false);

		    $first_name = trim(request::get('first_name'));
		    $last_name = trim(request::get('last_name'));
		    $email = strtolower(trim(request::get('email')));
		    $phone = strtolower(trim(request::get('phone')));
		    $password = substr(md5(microtime(true)), 0, 8);

		    $this->set_renderer('ajax');

		    load::form('sign/signup');
		    $form = new signup_form();
		    $form->load_from_request();


		    if (!$form->is_valid())
			    $this->json = array('errors' => $form->get_errors());
		    elseif ($first_name && $last_name && $email) {
			    $status = request::get('eco100') ? 6 : 1;
			    load::model('user/user_data');
			    $id = user_auth_peer::instance()->insert(
				    $email,
				    $password,
				    $status,
				    false
			    );

			    if (request::get('language') == 'en')
				    $insdata = array('en_first_name' => $first_name,
					    'en_last_name' => $last_name);
			    else $insdata = array('first_name' => $first_name,
				    'last_name' => $last_name);

			    user_data_peer::instance()->insert(array_merge($insdata, array(
				    'user_id' => $id,
				    'country_id' => request::get_int('country', '1'),
				    'region_id' => request::get_int('region', 9999),
				    'city_id' => request::get_int('city', 9999),
				    'region' => request::get_string('region', ""),
				    'city' => request::get_string('city', ""),
				    'language' => request::get('language', 'ua'),
				    'phone' => $phone,
				    'gender' => ''
			    )));

			    if (request::get('language') == 'en')
				    user_auth_peer::instance()->update(array('id' => $id, "en" => true));
			    else
				    user_auth_peer::instance()->update(array('id' => $id, "ru" => true));

			    load::model('user/user_bio');

			    user_bio_peer::instance()->insert(array(
				    'user_id' => $id
			    ));
			    load::model('user/user_work');
			    user_work_peer::instance()->insert(array(
				    'user_id' => $id
			    ));
			    load::model('user/user_education');
			    user_education_peer::instance()->insert(array(
				    'user_id' => $id
			    ));
			    load::model('user/user_desktop');
			    user_desktop_peer::instance()->insert(array(
				    'user_id' => $id
			    ));
			    $from_user_id == 5 ? $contacted = 1 : $contacted = 0;
			    load::model('user/user_novasys_data');
			    user_novasys_data_peer::instance()->insert(array(
				    'user_id' => $user['id'],
				    'phone' => $phone,
				    'email0' => $email,
				    'email1' => $email,
				    'contacted' => $contacted
			    ));

			    load::model('user/user_shevchenko_data');
			    user_shevchenko_data_peer::instance()->insert(array(
				    'user_id' => $id,
				    'shevchenko_id' => 0,
				    'fname' => $first_name,
				    'sname' => $last_name,
				    'country' => request::get_int('country', 1),
				    'region' => request::get_int('region'),
				    'district' => request::get_int('city'),
				    'referer' => request::get_int('referer'),
				    'ranother' => request::get('ranother'),
				    'rsocial' => request::get('rsocial')

			    ));

			    load::model('mailing');

			    mailing_peer::instance()->add_maillists_user($email, $first_name, $last_name, array(116, 101, 102));

			    $this->json = array();

			    load::action_helper('user_email', false);
			    $options = array(
				    '%fullname%' => $first_name . " " . $last_name,
				    '%email%' => $email,
				    '%password%' => $password
			    );
			    $options['%inviter%'] = user_helper::full_name($from_user_id, false);

			    user_email_helper::send_sys('registration_list',$id,false,$options);
		    }
	    }
    }
}
