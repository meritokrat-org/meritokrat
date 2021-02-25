<?

load::app('modules/feed/controller');
class feed_delete_action extends feed_controller
{
	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		if ( $this->item = feed_peer::instance()->get_item(request::get_int('id')) )
		{
			if ( $this->item['user_id'] == session::get_user_id() )
			{
				feed_peer::instance()->delete_item($this->item['id']);
			}
		}
	}
}