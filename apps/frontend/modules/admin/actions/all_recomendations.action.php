<?
load::app('modules/admin/controller');
class admin_all_recomendations_action extends admin_controller
{
	public function execute()
	{
            load::model('user/user_recomendations');
            request::get('all') ?
            $this->list=user_recomendations_peer::instance()->get_list(array(),array(),array('id DESC')) :
            $this->list=db::get_cols("SELECT id FROM user_recomendations WHERE accepted_user_id<1 ORDER by id DESC");
		
            load::action_helper('pager');
            $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 40);
            $this->recomended_users = $this->pager->get_list();
        }
}