<?php

class buildings_peer extends db_peer_postgre
{
    protected $table_name = "buildings";
    
    /**
     * @return buildings_peer
     */
    public static function instance()
    {
        return parent::instance('buildings_peer');
    }
}