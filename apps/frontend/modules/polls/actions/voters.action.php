<?

load::app('modules/polls/controller');
class polls_voters_action extends polls_controller
{
	protected $authorized_access = true;
	protected $credentials = array('admin');

	public function execute()
	{
		$this->poll = polls_peer::instance()->get_item(request::get_int('id'));

		if ( request::get_int('answer') )
		{
			$this->voters = polls_votes_peer::instance()->get_voters( request::get_int('id'), request::get_int('answer') );
		}
		else
		{
			$this->voters = polls_custom_peer::instance()->get_voters(request::get_int('id'), request::get_int('answer') );
		}
	}
}