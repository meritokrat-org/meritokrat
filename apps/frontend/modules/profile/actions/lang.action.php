<?

class profile_lang_action extends frontend_controller
{
	public function execute()
	{
		$codes = array('ua', 'ru', 'en');
		$code = request::get('code');
		if ( !in_array($code, $codes) )
		{
			$code = 'ua';
		}

		session::set('prof_lang', $code);
		$this->redirect($_SERVER['HTTP_REFERER']);
	}
}