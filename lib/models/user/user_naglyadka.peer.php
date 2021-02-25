<?
class user_naglyadka_peer extends db_peer_postgre
{
	protected $table_name = 'user_naglyadka';

	/**
	 * @return user_naglyadka_peer
	 */
	public static function instance()
	{
            return parent::instance( 'user_naglyadka_peer' );
	}

	public function get_by_user($id,$type=1)
	{
          return db::get_rows( 'SELECT * FROM ' . $this->table_name . ' 
                WHERE user_id = ' . $id . ' AND type = ' . $type);
	}
}
