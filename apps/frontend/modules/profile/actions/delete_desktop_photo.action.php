<?
class profile_delete_desktop_photo_action extends frontend_controller
{
	public function execute()
	{
		load::model('user/user_desktop');

		$this->set_renderer('ajax');
		$this->json = array();

		$this->desktop = user_desktop_peer::instance()->get_item(request::get_int('user_id'));
		if (request::get_string('salt') and (request::get_int('user_id')== session::get_user_id() or session::has_credential('admin')))
		{
                    $avtonumbers=unserialize($this->desktop['information_avtonumbers_photos']);
                    foreach ($avtonumbers as $key=>$avtonumber) {
                        if ($avtonumber['salt']==request::get_string('salt')) unset($avtonumbers[$key]);
                    }
                    user_desktop_peer::instance()->update(array(
                            'user_id' => request::get_int('user_id'),
                            'information_avtonumbers_photos' => serialize($avtonumbers)
                    ));
                    rating_helper::updateRating($this->desktop['user_id'], 'autonumber');
                    rating_helper::updateRating($this->desktop['user_id'], 'magnet');
		}

	}
}
