<?
class photocompetition_delete_action extends frontend_controller
{
	public function execute()
	{
		$this->set_renderer('ajax');
                $this->json = array();
                
		load::model('photo/photo_competition');

                $this->photo = photo_competition_peer::instance()->get_item(request::get_int('photo_id'));
                if (session::has_credential('admin') || session::get_user_id()==$this->photo['user_id'])
		{
			photo_competition_peer::instance()->delete_item(request::get_int('photo_id'));
		}
                $this->redirect('/photocompetition');
	}
}
