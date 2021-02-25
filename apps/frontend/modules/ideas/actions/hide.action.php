<?

load::app('modules/ideas/controller');
class ideas_hide_action extends ideas_controller
{
	protected $authorized_access = true;
	protected $credentials = array('moderator');

	public function execute()
	{
		if ( request::get_int('id') )
		{
			ideas_peer::instance()->update(array(
				'id' => request::get_int('id'),
				'visible' => false
			));

			$this->redirect('/ideas');
		}
	}
}