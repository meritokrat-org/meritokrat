<?

load::app('modules/admin/controller');
class admin_attentions_action extends admin_controller
{
	public function execute()
	{
                load::model('attentions');

                load::action_helper('pager', true);
                $this->list=attentions_peer::instance()->get_list();
                $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 15);
		$this->list = $this->pager->get_list();
	}
}
