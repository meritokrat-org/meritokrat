<?

class polls_answers_peer extends db_peer_postgre
{
	protected $table_name = 'polls_answers';

	/**
	 * @return polls_answers_peer
	 */
	public static function instance()
	{
		return parent::instance( 'polls_answers_peer' );
	}

	public function get_by_poll( $poll_id )
	{
		return $this->get_list( array('poll_id' => $poll_id) );
	}

        public function delete_by_poll( $poll_id )
	{
                //переделал чтобы вопросы не удалялись а исчезали из опроса и велись для статистики
		return db::exec('UPDATE polls_answers SET  poll_id=0 WHERE poll_id = '.$poll_id);
                //return db::exec('DELETE FROM polls_answers WHERE poll_id = '.$poll_id);
	}
}