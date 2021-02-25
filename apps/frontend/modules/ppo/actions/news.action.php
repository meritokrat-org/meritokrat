<?

load::app('modules/ppo/controller');
class ppo_news_action extends ppo_controller
{
	public function execute()
	{
		$this->group = ppo_peer::instance()->get_item(request::get_int('id'));
		$this->list = ppo_news_peer::instance()->get_by_group($this->group['id']);

		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 10);
		$this->list = $this->pager->get_list();
	}
}