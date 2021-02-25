<?

load::app('modules/groups/controller');
class groups_news_action extends groups_controller
{
	public function execute()
	{
		$this->group = groups_peer::instance()->get_item(request::get_int('id'));
		$this->list = groups_news_peer::instance()->get_by_group($this->group['id']);

		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 10);
		$this->list = $this->pager->get_list();
	}
}