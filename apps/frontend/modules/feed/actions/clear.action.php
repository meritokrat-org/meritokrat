<?

load::app('modules/feed/controller');
class feed_clear_action extends feed_controller
{
	public function execute()
	{
		feed_peer::instance()->clear_by_user(session::get_user_id());
		$this->redirect('/feed');
	}
}