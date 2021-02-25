<?php
class profile_ajax_action extends frontend_controller {
    
    protected $authorized_access = true;
    
    public function execute() {
        
        $this->disable_layout();
        $this->set_renderer('ajax');
        
        load::action_helper('membership',false);
        load::model('ppo/exmembers');
        
        $id = request::get_int('id');
        $action = request::get('operation');
        $type = request::get('type');
        
        ///////debug
        $incomming_data = request::get_all();
        unset($incomming_data['module']);
        unset($incomming_data['action']);
        
        switch($action) {
            case 'get_party_off_reason':
                switch($id) {
                    case 0:
                    case 1:     $this->json = array('success'=>1); break;
                    case 2:     $this->json = array_merge(array('success'=>1),array('data'=>membership_helper::get_party_off_auto_reason()));break;
                    case 3:     $this->json = array_merge(array('success'=>1),array('data'=>membership_helper::get_party_off_except_reason()));break;
                    case 100:   $this->json = array('success'=>1);break;
                    default:$this->json = array('success'=>0,'reason'=>'Некоректні вхідні параметри','data'=>$incomming_data);break;
                }
                break;
            default:$this->json = array('success'=>0,'reason'=>'Некоректні вхідні параметри','data'=>$incomming_data);break;
        }
        
    }
}
?>
