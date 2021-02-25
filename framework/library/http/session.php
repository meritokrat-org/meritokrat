<?php

class session
{
    public static function start()
    {
        session_start();
    }

    public static function is_exists($name)
    {
        return array_key_exists($name, $_SESSION);
    }

    public static function unset_user()
    {
        session::set_user_id(null);
        self::set('credentials', array());
        self::set("inquirer_popup", false);
    }

    public static function set_user_id($id = 0, $credentials = array())
    {
        self::set('user_id', $id);
        self::set_credentials($credentials);
    }

    public static function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public static function set_credentials($credentials)
    {
        $set = (array)self::get('credentials');

        foreach ((array)$credentials as $credential) {
            $set[] = $credential;
        }

        self::set('credentials', $set);
    }

    public static function get($name, $default = null)
    {
        if (@array_key_exists($name, $_SESSION)) {
            return $_SESSION[$name];
        }

        return $default;
    }

    public static function is_authenticated()
    {
        return (bool)self::get_user_id();
    }

    public static function get_user_id()
    {
        return self::get('user_id');
    }

    public static function has_credentials($credentials)
    {
        foreach ($credentials as $credential) {
            if (self::has_credential($credential)) {
                return true;
            }
        }

        return false;
    }

    public static function has_credential($credential)
    {
        if (!self::get_user_id()) {
            return false;
        }

        $user = user_auth_peer::i()->get_item(self::get_user_id());

        return in_array($credential, explode(',', $user['credentials']), true);
    }
}
