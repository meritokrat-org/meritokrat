<?
load::app('modules/eventreport/controller');
class eventreport_search_action extends eventreport_controller
{
        protected $credentials = array('admin');

        public function execute()
	{
            $this->list = eventreport_peer::instance()->search();
            $this->total = count($this->list);
            $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 15);
	}
}