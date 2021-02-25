<?

class shop_items_peer extends db_peer_postgre
{
	protected $table_name = 'shop_items';

	/**
	 * @return shop_items_peer
	 */
	public static function instance()
	{
		return parent::instance('shop_items_peer');
	}

	public function generate_photo_salt($id) {
		$salt = substr(md5(microtime(true)), 0, 8);

		$this->update(array('photo' => $salt, 'id' => $id));
		return $salt;
	}
}