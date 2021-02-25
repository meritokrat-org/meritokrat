<?php
class profile_user_invites_action extends frontend_controller {
    public function execute() {
        
        $id = request::get('uid') ? request::get('uid') : session::get_user_id();
        $uData = user_data_peer::instance()->get_item($id);
        $uAuth = user_auth_peer::instance()->get_item($id);
        $active = request::get_int('active') ? true : false;
        
        if(!$uAuth || !$uData) throw new public_exception ('Пользователь не найден');
        
        $orderField = request::get('order','last_invite');
        $direct = request::get('direct','DESC');
        $order = $orderField.' '.$direct;
        
        
        switch(request::get_int('active')) {
            case 1:
                $invites = user_auth_peer::instance()->get_all_recomended_by_user($id, true, false, $order);
                break;
            case 0:
                $invites = user_auth_peer::instance()->get_all_recomended_by_user($id,false,true,$order);
                break;
            case 2:
                $invites = user_auth_peer::instance()->get_all_recomended_by_user($id,false,false,$order);
                break;
            default:
                $invites = user_auth_peer::instance()->get_all_recomended_by_user($id,false,false,$order);
                break;
        }
            
        
        if( ! empty($invites))
            foreach ($invites as $k => $v) 
                $this->invites[] = user_data_peer::instance()->get_item($v);
        
        if( ! empty($this->invites)) {
            load::action_helper('pager');
            $this->pager = pager_helper::get_pager($this->invites, request::get_int('page'), 20);
            $this->invites = $this->pager->get_list();
        }
    }
}
?>
