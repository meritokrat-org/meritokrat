<?

class user_works_categories_peer extends db_peer_postgre
{
    protected $table_name = 'user_works_categories';
    protected $primary_key = 'id';

    /**
     * @return user_works_categories_peer
     */
    public static function instance()
    {
        return parent::instance('user_works_peer');
    }
}
