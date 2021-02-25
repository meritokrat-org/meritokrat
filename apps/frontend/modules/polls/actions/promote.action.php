<?

load::app('modules/polls/controller');
class polls_promote_action extends polls_controller
{
	protected $authorized_access = true;
	protected $credentials = array('moderator');

	public function execute()
	{
		if ( request::get_int('id') )
		{
			polls_peer::instance()->update(array(
				'id' => request::get_int('id'),
				'promoted' => true
			));

			$this->redirect('/polls');
		}
	}
}