<?
load::app('modules/docs/controller');
class docs_index_action extends docs_controller
{
	protected $authorized_access = true;
	protected $credentials = array('admin');
        
        public function execute()
	{
            $this->list = docs_peer::instance()->get_list();
            $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 20);
            $this->list = $this->pager->get_list();
	}
}