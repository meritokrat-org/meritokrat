<?php

class user_rating_cost_peer extends db_peer_postgre
{

    protected $table_name = 'user_rating_cost';

    /**
     * @param string $peer
     * @return user_rating_cost_peer
     */
    public static function instance($peer = 'user_rating_cost_peer')
    {
        return parent::instance($peer);
    }

}

