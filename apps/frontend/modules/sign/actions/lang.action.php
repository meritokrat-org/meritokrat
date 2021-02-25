<?

class sign_lang_action extends frontend_controller
{
	public function execute()
	{
		$codes = array('ua', 'ru', 'en');
		$code = request::get('code');
                //yfty
		if ( !in_array($code, $codes) )
		{
			$code = 'ua';
		}
		/*if ( session::is_authenticated() )
		{
			user_data_peer::instance()->update(array(
				'user_id' => session::get_user_id(),
				'language' => $code
			));
		}*/

		session::set('language', $code);
		$this->redirect($_SERVER['HTTP_REFERER']);
	}
}