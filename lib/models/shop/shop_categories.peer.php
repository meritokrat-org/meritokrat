<?

class shop_categories_peer extends db_peer_postgre
{
	protected $table_name = 'shop_categories';

	/**
	 * @return shop_categories_peer
	 */
	public static function instance()
	{
		return parent::instance('shop_categories_peer');
	}

	public function generate_photo_salt($id) {
		$salt = substr(md5(microtime(true)), 0, 8);

		$this->update(array('photo' => $salt, 'id' => $id));
		return $salt;
	}
}