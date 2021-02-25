<?php

class user_work_experience_peer extends db_peer_postgre
{
    protected $table_name = 'user_work_experience';

    /**
     * @return user_work_experience_peer
     */
    public static function instance()
    {
        return parent::instance('user_work_experience_peer');
    }
}