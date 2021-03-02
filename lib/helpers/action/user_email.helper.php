<?php

load::system('email/email');

class user_email_helper
{
    public static function send(
        $receiver_id,
        $sender_id = null,
        $options = [],
        $html = false,
        $sender_mail = null,
        $sender_name = null
    ) {
        $receiver      = user_auth_peer::instance()->get_item($receiver_id);
        $receiver_data = user_data_peer::instance()->get_item($receiver_id);

        if (!$receiver['email'] || (false === strpos($receiver['email'], '@'))) {
            return;
        }

        if (!$receiver_data['notify']) {
            return;
        }

        if ($sender_id) {
            $sender      = user_auth_peer::instance()->get_item($sender_id);
            $sender_data = user_data_peer::instance()->get_item($sender_id);
        }

        $variables = [
            '%sender%'   => $sender_data['first_name'].' '.$sender_data['last_name'],
            '%receiver%' => $receiver_data['first_name'],
        ];

        $variables          = array_merge($variables, (array)$options['variables']);
        $options['body']    = str_replace(array_keys($variables), $variables, $options['body']);
        $options['subject'] = str_replace(array_keys($variables), $variables, $options['subject']);

        $email = new email();
        if ($sender_mail) {
            $email->setSender($sender_mail, $sender_name);
        }
        $email->setReceiver($receiver['email']);

        $body =
            $options['body'].
            "\n\n".
            t('Не отвечайте на это письмо, оно сгенерировано автоматически')."\n".
            context::get('host');

        $email->setBody($body);
        $email->setSubject($options['subject']);
        if ($html) {
            $email->isHTML();
        }
        $email->send();
    }

    public static function send_sys($template = 'default', $receiver_id, $sender_id = null, $options = [], $html = true)
    {
        load::model('email/email');
        load::model('user/user_mail_access');

        $data = email_peer::instance()->get_mail($template);

        if ($data['mail'] || 'messages_compose' === $template) {
            self::send_mail($template, $receiver_id, $sender_id, $options, $html, $data);
        } else {
            self::send_message($template, $receiver_id, $sender_id, $options, $html, $data);
        }
    }

    private static function send_mail(
        $template,
        $receiver_id,
        $sender_id = null,
        $options = [],
        $html = true,
        $data = []
    ) {
        $receiver      = user_auth_peer::instance()->get_item($receiver_id);
        $receiver_data = user_data_peer::instance()->get_item($receiver_id);

        if (!$receiver['email'] || (false === strpos($receiver['email'], '@')) || !$receiver_data['notify']) {
            return;
        }

        if (!user_mail_access_peer::instance()->check_access($receiver_id, $template)) {
            return;
        }

        $options['%receiver%'] = $receiver_data['first_name'].' '.$receiver_data['last_name'];

        if ($sender_id) {
            $sender              = user_auth_peer::instance()->get_item($sender_id);
            $sender_data         = user_data_peer::instance()->get_item($sender_id);
            $options['%sender%'] = $sender_data['first_name'].' '.$sender_data['last_name'];
        }

        $email = new email();
        if ('ru' !== session::get('language')) {
            $body    = str_replace(array_keys($options), $options, $data['body_ua']);
            $subject = str_replace(array_keys($options), $options, $data['title_ua']);
            $email->setSender($data['sender_mail'], $data['sender_name_ua']);
        } else {
            $body    = str_replace(array_keys($options), $options, $data['body_ru']);
            $subject = str_replace(array_keys($options), $options, $data['title_ru']);
            $email->setSender($data['sender_mail'], $data['sender_name_ru']);
        }

        $body    = stripslashes($body);
        $subject = stripslashes($subject);

        $email->setReceiver($receiver['email']);

        if ($data['has_footer']) {
            $footer = email_peer::instance()->get_mail('footer');
            $body   .= sprintf("\n\n%s", 'ru' !== session::get('language') ? $footer['body_ua'] : $footer['body_ru']);
        }

        $email->setBody($body);
        $email->setSubject($subject);
        if ($html) {
            $email->isHTML();
        }
        $email->send();
    }

    private static function send_message(
        $template,
        $receiver_id,
        $sender_id = null,
        $options = [],
        $html = true,
        $data = []
    ) {
        load::model('messages/messages');

        $receiver_data         = user_data_peer::instance()->get_item($receiver_id);
        $options['%receiver%'] = $receiver_data['first_name'].' '.$receiver_data['last_name'];

        if (!$sender_id) {
            $sender_id = 31;
        }
        $sender_data         = user_data_peer::instance()->get_item($sender_id);
        $options['%sender%'] = $sender_data['first_name'].' '.$sender_data['last_name'];

        if ('ru' !== session::get('language')) {
            $body = str_replace(array_keys($options), $options, $data['body_ua']);
        } else {
            $body = str_replace(array_keys($options), $options, $data['body_ru']);
        }
        $body = stripslashes($body);

        $message_id = messages_peer::instance()->add(
            [
                'sender_id'   => 31,
                'receiver_id' => $receiver_id,
                'body'        => $body,
            ],
            false,
            1,
            $html
        );

        // $options = [
        //     '%text%'     => tag_helper::get_short(trim(strip_tags($body)), 120),
        //     '%link%'     => 'https://'.context::get('host').'/messages/view?id='.$message_id,
        //     '%sender%'   => $sender_data['first_name'].' '.$sender_data['last_name'],
        //     '%settings%' => 'https://'.context::get('host').'/profile/edit?id='.$receiver_id.'&tab=settings',
        // ];

        // self::send_mail(
        //     'messages_compose',
        //     $receiver_id,
        //     $sender_id,
        //     $options,
        //     $html,
        //     email_peer::instance()->get_mail('messages_compose')
        // );
    }

}