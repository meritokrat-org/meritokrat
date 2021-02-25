<?
class photocompetition_vote_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		load::model('photo/photo_competition');
		if ( $photo_data = photo_competition_peer::instance()->get_item( request::get_int('id') ) )
		{
			if ($photo_data['user_id']!=session::get_user_id() && !photo_competition_peer::instance()->has_voted($photo_data['id'], session::get_user_id()))
			{
				photo_competition_peer::instance()->update( array(
					'id' => $photo_data['id'],
					'votes' => $photo_data['votes'] + 1,
					'voters' => str_replace('{,','{',str_replace('}',','.session::get_user_id().'}',$photo_data['voters']))
				) );
			}
		}
		$this->set_renderer('ajax');
		$this->json = array();
	}
}