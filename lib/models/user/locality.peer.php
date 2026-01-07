<?php

class user_locality_peer extends db_peer_postgre
{
    protected $table_name = 'user_localities';
    protected $primary_key = 'user_id';

    public static function instance($peer = self::class)
    {
        return parent::instance($peer);
    }

    public function synchronize($user_id, $locality_id)
    {
        try {
            $sql = <<<SQL
insert into user_localities (user_id, locality_id)
values (:user_id, :locality_id)
on conflict (user_id) do update set locality_id = EXCLUDED.locality_id  
SQL;
            db::exec($sql, ['user_id' => $user_id, 'locality_id' => $locality_id], $this->connection_name);
        } catch (Exception $e) {
        }
    }

}