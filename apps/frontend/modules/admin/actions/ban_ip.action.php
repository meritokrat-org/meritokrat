<?php
load::app('modules/admin/controller');
class admin_ban_ip_action extends admin_controller {
    public function execute() {
        load::model('ban_ip');
        
        if(request::get('submit')) {
            $this->set_renderer('ajax');
            
            $action = request::get('operation');
            $id = request::get_int('id');
            switch($action) {
                case 'ban_ip':
                    $ip = request::get('ip');
                    if(!filter_var($ip,FILTER_VALIDATE_IP)) $this->json = array('success'=>0, 'reason'=>'Вхідні данні некоректні');
                    elseif(!ban_ip_peer::instance()->check_ip($ip)) {
                        ban_ip_peer::instance ()->insert (array('ip'=>trim($ip)));
                        $this->json = array('success'=>1);
                    }
                    else $this->json = array('success'=>0, 'reason'=>'Цей IP вже у списку');
                    break;
//                case 'delete_ban':
//                    $item = ban_ip_peer::instance()->get_item($id);
//                    if($item) {
//                        ban_ip_peer::instance()->delete_item($id);
//                        $this->json = array('success'=>1);
//                    }
//                    else $this->json = array('success'=>0, 'reason'=>'Вхідні данні некоректні');
//                    break;
//                case 'get_banned_ip':
//                    
//                    break;
                default :
                    $this->json = array('success'=>0, 'reason'=>'Вхідні данні некоректні');
                    break;
            }
            
        }
    }
}
?>
