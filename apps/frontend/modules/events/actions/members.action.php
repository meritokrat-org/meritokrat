<?

load::app('modules/events/controller');
class events_members_action extends events_controller
{
	public function execute()
	{
		if ( $this->event = events_peer::instance()->get_item(request::get_int('id')))
		{
			$this->list = events_members_peer::instance()->get_members( $this->event['id'] );
			$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 16);
			$this->list = $this->pager->get_list();
		}
	}
}