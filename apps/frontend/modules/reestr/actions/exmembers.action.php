<?php
load::app('modules/reestr/controller');
class reestr_exmembers_action extends reestr_controller {
    public function execute() {
        
        load::action_helper('pager');
        load::action_helper('membership',false);
        $this->list = db::get_cols("SELECT id FROM membership WHERE remove_type>0 AND remove_type<=3 AND user_id NOT IN (SELECT id FROM user_auth WHERE status = 20) ORDER BY removedate DESC");
//        $this->pager = pager_helper::get_pager($list, request::get('page'), 10);
//        $this->list = $this->pager->get_list();
    }
}
?>
