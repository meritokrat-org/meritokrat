<?
load::app('modules/admin/controller');
class admin_recommend_status_action extends admin_controller
{
	public function execute()
	{
            load::model('user/user_recommend');
            
            if (!request::get('all'))
            {
                $this->list=user_recommend_peer::instance()->get_list(array('status'=>(request::get_int('status') ? request::get_int('status') : 20),'checked'=>(request::get_int('checked') ? 1 : 0)),array(),array('accept_ts DESC','ts DESC'));
                
                //сортировка по фамилии
                //if (request::get_int('checked')!=1) $this->list=db::get_cols("SELECT user_id FROM user_data WHERE user_id in (".implode(',',$this->list).") ORDER by last_name ASC");
                load::action_helper('pager');
                $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 40);
                $this->recomended_users = $this->pager->get_list();
            }
	}
}