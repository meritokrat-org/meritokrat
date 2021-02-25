<?

load::app('modules/polls/controller');
class polls_hot_action extends polls_controller
{
	public function execute()
	{
		$this->list = polls_peer::instance()->get_hot();

		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 10);
		$this->list = $this->pager->get_list();
	}
}