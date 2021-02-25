<?

class candidates_forecast_peer extends db_peer_postgre
{
	protected $table_name = 'candidates_forecast';
	protected $primary_key = null;

	/**
	 * @return candidates_forecast_peer
	 */
	public static function instance()
	{
		return parent::instance( 'candidates_forecast_peer' );
	}

	public function add( $forecast, $user_id )
	{
		db::exec('DELETE FROM ' .  $this->get_table_name() . ' WHERE user_id = :user_id', array('user_id' => $user_id));

		foreach ( $forecast as $data )
		{
			$data['user_id'] = $user_id;
			parent::insert($data);
		}
	}

	public function get_by_user( $user_id )
	{
		return db::get_rows('SELECT * FROM ' . $this->get_table_name() . ' WHERE user_id = :id ORDER BY place ASC', array('id' => $user_id));
	}
}