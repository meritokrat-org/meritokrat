<?php

class profile_invite_action extends frontend_controller
{
    protected $authorized_access = true;

    public function execute()
    {
        if (!session::has_credential('admin') && !user_auth_peer::instance()->is_inviter(session::get_user_id())) {
            throw new public_exception(t('У вас недостаточно прав'));
        }

        if (request::get('submit')) {
            $first_name = trim(request::get('first_name'));
            $last_name  = trim(request::get('last_name'));
            $email      = strtolower(trim(request::get('email')));
            $sql        = 'SELECT * FROM user_data WHERE  first_name = :first_name AND last_name = :last_name LIMIT 1';

            $user1 = db::get_row($sql, ['first_name' => $first_name, 'last_name' => $last_name]);
            $user2 = user_auth_peer::instance()->get_by_email($email);

            if (!request::get('ignore') && $user1 or $user2) {
                $this->redirect('/profile-' . $user2['id']);
            } else if (db::get_row('SELECT * FROM user_recomendations WHERE name=:name AND last_name=:last_name LIMIT 1', ['name' => $first_name, 'last_name' => $last_name])) {
                $this->doublerecomendation = 1;
            } else if (user_auth_peer::instance()->get_by_email(strtolower($email))) {
                throw new public_exception('Помилка, користувач з таким email вже існуе <a href="' . $_SERVER['HTTP_REFERER'] . '">назад</a>');
            } //elseif (count(db::get_rows("SELECT * FROM user_recomendations WHERE user_id = :user_id AND created_ts > :created_ts", array('user_id' => session::get_user_id(),'created_ts' => ($_SERVER['REQUEST_TIME']-24*60*60))))>=5) throw new public_exception('Вибачте, але Ви не можете запрошувати більше 5 людей за добу');
            else if ($first_name && $last_name && $email && request::get_string('recomendation')) {
                load::action_helper('user_email', false);
                load::model('user/user_recomendations');

                $recomendation_id = user_recomendations_peer::instance()->insert([
                    'user_id'       => session::get_user_id(),
                    'name'          => $first_name,
                    'last_name'     => $last_name,
                    'recomendation' => request::get('recomendation'),
                    'created_ts'    => time(),
                    'email'         => $email,
                    'gender'        => request::get_string('gender', 'm'),
                    'country_id'    => request::get_int('country', 1),
                    'region_id'     => request::get_int('region', 0),
                    'city_id'       => request::get_int('city', 0),
                    'language'      => request::get('language', 'ua'),
                    'suslik'        => request::get_int('suslik', 0),
                ]);

                //$receivers=array(29);
                foreach (user_auth_peer::get_admins() as $receiver) {
                    /*user_email_helper::send($receiver,
                        session::get_user_id(),
                        array(
                                'subject' => 'Рекомендація учасника '.$first_name.' '.$last_name.' від '.user_helper::full_name(session::get_user_id(),false),
                                'body' => user_helper::full_name(session::get_user_id(),false).' рекомендує '.$first_name.' '.$last_name.'

Рекомендація: '.request::get('recomendation').'<br>
<br>
Щоб схвалити рекомендацію натисніть на посилання: <a href="http://'.conf::get('server').'/admin/users_create?submit=1&first_name='.$first_name.'&last_name='.$last_name.'&email='.$email.'&gender='.trim(request::get('gender')).'&to_ns=1&from='.session::get_user_id().'">http://'.conf::get('server').'/admin/users_create?submit=1&first_name='.$first_name.'&last_name='.$last_name.'&email='.$email.'&gender='.trim(request::get('gender')).'&to_ns=1&from='.session::get_user_id().'</a> (працює для авторизованих адміністраторів на meritokrat.org)<br>'
                        ),
                            true
                    );*/
                    $options = [
                        '%name%'        => $last_name . ' ' . $first_name,
                        '%recommender%' => user_helper::full_name(session::get_user_id(), false),
                        '%recommend%'   => request::get('recomendation'),
                        '%link%'        => 'http://' . conf::get('server') . '/admin/approve_recomendation?id=' . $recomendation_id,
                        '%settings%'    => 'http://' . context::get('host') . '/profile/edit?id=' . $receiver . '&tab=settings',
                    ];


                    user_email_helper::send_sys('profile_invite', $receiver, session::get_user_id(), $options);
                }
                $this->recomendation = 1;
            }
        }
    }
}