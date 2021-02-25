<?

class email_peer extends db_peer_postgre
{
    protected $table_name = 'email_system';

    /**
     * @return email_peer
     */
    public static function instance()
    {
        return parent::instance('email_peer');
    }

    public function get_mail($alias)
    {
        return db::get_row('SELECT * FROM '.$this->table_name.' WHERE alias = :alias', ['alias' => $alias]);
    }

}