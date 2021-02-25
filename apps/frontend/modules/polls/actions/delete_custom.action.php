<?

load::app('modules/polls/controller');
class polls_delete_custom_action extends polls_controller
{
	protected $authorized_access = true;
	protected $credentials = array('admin');

	public function execute()
	{
		$poll_id = request::get_int('id');
		$this->list = polls_custom_peer::instance()->delete( $poll_id, request::get_string('answer') );

		$this->redirect('/poll' . $poll_id);
	}
}