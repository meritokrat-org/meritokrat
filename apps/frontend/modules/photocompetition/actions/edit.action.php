<?

class photocompetition_edit_action extends frontend_controller
{
	public function execute()
	{

                $this->photo = photo_competition_peer::instance()->get_item(request::get_int('photo_id'));
                if (session::has_credential('admin') || session::get_user_id()==$this->photo['user_id'])
                {
			if (request::get('submit') && request::get('photo_id') && request::get('title') && request::get('text'))
                        {
                            photo_peer::instance()->update(array(
                                'id' => request::get_int('photo_id'),
                                'title' => request::get_string('title'),
                                'text' => request::get_string('text')
                            ));
                        }
		}
	}
}
