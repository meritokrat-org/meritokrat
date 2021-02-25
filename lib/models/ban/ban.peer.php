<?

class ban_peer extends db_peer_postgre
{
    protected $table_name = 'bans';

    /**
     * @return ban_peer
     */
    public static function instance()
    {
        return parent::instance('ban_peer');
    }

    public static function get_types()
    {
        return array(
            '' => '&mdash;',
            3 => 'на 3 дні',
            7 => 'на тиждень',
            30 => 'на місяць',
            9000 => 'назавжди',
        );
    }

    public function is_banned($user_id)
    {
        return db::get_scalar(
            "SELECT id FROM $this->table_name 
                WHERE user_id=:user_id AND (start_time+(days*86400))>:time AND active=1",
            array("user_id" => $user_id, "time" => time())
        );
    }

    public function get_ban_days($user_id)
    {
        return db::get_scalar(
            "SELECT days FROM {$this->table_name} 
                WHERE user_id=:user_id AND (start_time+(days*86400))>:time AND active=1",
            ['user_id' => $user_id, 'time' => time()]
        );
    }

    public function get_ban_user($user_id)
    {
        return db::get_rows(
            "SELECT * FROM $this->table_name 
                WHERE user_id=:user_id",
            array("user_id" => $user_id)
        );
    }

    public function get_active_bans()
    {
        return db::get_rows(
            "SELECT * FROM $this->table_name 
                WHERE (start_time+(days*86400))<=:time AND active=1",
            array("time" => time())
        );
    }

    public function get_end_time($user_id)
    {
        return db::get_scalar(
            "SELECT (start_time+(days*86400)) FROM $this->table_name 
                WHERE user_id=:user_id AND (start_time+(days*86400))>:time AND active=1",
            array("user_id" => $user_id, "time" => time())
        );
    }
}