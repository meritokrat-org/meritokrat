<?php

load::model('user/user_visits_log');

/**
 * @property string json
 */
class sign_in_action extends frontend_controller
{
    public function execute()
    {
        $this->set_renderer('ajax');

        if (session::is_authenticated()) {
            return;
        }

        $email    = strtolower(trim(request::get('email')));
        $password = request::get('password');
        $user     = user_auth_peer::instance()->get_by_email($email);

        if (
            !$email ||
            !$password ||
            !$user ||
            $user['password'] !== md5($password)
        ) {
            $this->json = $this->failure();

            return;
        }

        $this->json = $this->success($user);
    }

    private function failure()
    {
        header('HTTP/1.1 401 Unauthorized');
        header('Content-Type: application/json');

        return [
            'error' => t('Неверный email либо пароль')
        ];
    }

    private function success($user)
    {
        error_log(sprintf('SIGNIN: %s from ip %s', $user['id'], $_SERVER['REMOTE_ADDR']));

        $referer = session::get('referer');
        session::set('referer', null);

        if (!$user['active']) {
            user_auth_peer::instance()->activate($user['id']);
            $inviter = $user['invited_by'] ?: 0;
            $inviter = $user['recomended_by'] ?: $inviter;

            if ($inviter) {
                rating_helper::updateRating($inviter, 'status');
            }
        }

        session::set_user_id($user['id'], explode(',', $user['credentials']));
        cookie::set(
            'auth',
            sprintf("%s|%s", $user['email'], $user['password']),
            time() + 60 * 60 * 24 * 31,
            '/',
            sprintf('.%s', context::get('server'))
        );

        $user_data = user_data_peer::instance()->get_item($user['id']);
        session::set('language', $user_data['language'] ?: 'ua');

        user_visits_log_peer::instance()->insert(
            [
                'user_id'  => session::get_user_id(),
                'time_out' => date('Y-m-d H:i:s'),
            ]
        );

        if (!$user['ip']) {
            user_auth_peer::instance()->update(
                [
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'id' => $user['id'],
                ]
            );
        }

        return [
            'referer' => $referer
        ];
    }
}
