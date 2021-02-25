<?
load::app('modules/groups/controller');
class groups_search_action extends groups_controller
{
	public function execute()
	{
            $this->set_template('index');
            if(trim(request::get_string('req'))=='')
            {
                $this->redirect('/groups');
            }

            $this->hot = groups_peer::instance()->search_by_title(request::get_string('req'));
            
            load::action_helper('pager');
            $this->pager = pager_helper::get_pager($this->hot, request::get_int('page'), 15);
            $this->hot = $this->pager->get_list();
        }
}