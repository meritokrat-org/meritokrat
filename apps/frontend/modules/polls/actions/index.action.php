<?

load::app('modules/polls/controller');
class polls_index_action extends polls_controller
{
	public function execute()
	{
		if(request::get('bookmark'))
        {
            load::model('bookmarks/bookmarks');
        }
        $this->list = polls_peer::instance()->get_newest();

		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 10);
		$this->list = $this->pager->get_list();
	}
}