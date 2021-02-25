<?

load::app('modules/debates/controller');
class debates_index_action extends debates_controller
{
	public function execute()
	{
		$this->list = debates_peer::instance()->get_newest();

		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 10);
		$this->list = $this->pager->get_list();
	}
}