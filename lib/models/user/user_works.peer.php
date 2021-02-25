<?php

/**
 * Class user_works_peer
 */
class user_works_peer extends db_peer_postgre
{
    protected $table_name = 'user_works';
    protected $primary_key = 'id';

    /**
     * @return user_works_peer
     */
    public static function instance()
    {
        load::action_helper('lang', false);
        return parent::instance('user_works_peer');
    }
}
