<?

class banners_items_peer extends db_peer_postgre
{
    protected $table_name = 'banners';

    /**
     * @return banners_peer
     */
    public static function instance()
    {
        return parent::instance( 'banners_items_peer' );
    }


}