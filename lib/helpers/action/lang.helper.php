<?php

class lang_helper
{
    public static function get_lang_cols($lang_cols, $data, $session = 'language')
    {
        load::model('user/user_auth');
        $lang = session::get($session);
        $pref = $lang === 'en' ? 'en_' : '';

        foreach ($lang_cols as $c) {
            if (!$data[$pref.$c] && in_array($c, ["first_name", "last_name"])) {
                $user = user_auth_peer::instance()->get_item($data['user_id']);
                if ($user['en']) {
                    $data[$c] = $data['en_'.$c];
                }
            } else {
                $data[$c] = $data[$pref.$c];
            }
        }

        return $data;
    }

    public static function set_lang_cols($lang_cols, $data)
    {
        $lang = session::get("prof_lang");
        $pref = $lang == 'en' ? 'en_' : '';
        #if(!in_array($lang, array("ua","ru"))){
        foreach ($lang_cols as $c) {
            if ($data[$c]) {
                $data[$pref.$c] = $data[$c];
                if ($pref) {
                    unset($data[$c]);
                }
            }
        }

        #}
        return $data;
    }
}