<?php
class deskhelp_index_action extends frontend_controller
{
        protected $authorized_access = true;
	
        public function execute()
	{
            if(!session::has_credential('admin')) throw new public_exception ('Розділ тимчасово не доступний');
            $need=request::get('need',0);
            $page=request::get('page',1);
            $type=request::get('type',0);
            $this->need = $need;
            load::model('user/user_desktop_help_types');
            if(request::get('submit')) {
                $update = array('id'=>  request::get_int('oldtype'), 'name'=>  request::get('newtype'));
                user_desktop_help_types_peer::instance()->update($update);
            }
            
            if($need==1) {
                $this->help_button_title = "Переглянути запит";
                $this->help_list_title = "Запросы о помощи";
            }
            else
            {
                $this->help_button_title = "Переглянути пропозицію";
                $this->help_list_title = "Предлогают помощь";
            }

            load::action_helper('pager');
            load::model('user/user_desktop_help');
            
            if($type!=0) {
                $all_help_ids = user_desktop_help_peer::instance()->get_list(array('need'=>$need,'type'=>$type));
                $this->type_cnt = count($all_help_ids);
            }
            else
                $all_help_ids = user_desktop_help_peer::instance()->get_list(array('need'=>$need));
            $help_user_ids = array();

            foreach($all_help_ids as $id=>$help_id) {
                $res = user_desktop_help_peer::instance()->get_item($help_id);
                $help_user_ids[] = $res['user_id'];
            }
            $this->need_help_count = count(user_desktop_help_peer::instance()->get_list(array('need'=>1)));
            $this->prov_help_count = count(user_desktop_help_peer::instance()->get_list(array('need'=>0)));
            
            $help_user_ids = array_unique($help_user_ids);
            $this->ids = $help_user_ids;
            $this->pager = pager_helper::get_pager($this->ids, $page, 10);
            $this->ids = $this->pager->get_list();

            
                load::model('user/user_desktop');
                
                load::model('user/user_sessions');
                load::model('user/user_auth');
                load::model('bookmarks/bookmarks');
                load::model('user/user_data');
                load::model('parties/members');
                load::model('parties/parties');

        }
}

?>
