<?php
load::app('modules/admin/controller');
class admin_referals_action extends admin_controller {
    
    public function execute() {
        
        load::model('user/user_shevchenko_data');
        
        $users = db::get_rows("SELECT referer,user_id FROM user_shevchenko_data WHERE user_id>0 AND referer IN (3,5,6,7,8)");
        $this->facebook = db::get_scalar("SELECT count(user_id) FROM user_shevchenko_data WHERE user_id>0 AND referer=:ref AND rsocial=:rsoc",array('ref'=>3,'rsoc'=>'facebook'));
        $this->vk = db::get_scalar("SELECT count(user_id) FROM user_shevchenko_data WHERE user_id>0 AND referer=:ref AND rsocial=:rsoc",array('ref'=>3,'rsoc'=>'vkontakte'));
        $this->other = db::get_scalar("SELECT count(user_id) FROM user_shevchenko_data WHERE user_id>0 AND referer=:ref AND ranother!=''",array('ref'=>6));
        
        
        
        $this->all_users = count($users);
        $this->no_info = 0;
        $this->total_count=array();
        
        if(!empty($users)) 
            foreach ($users as $k=>$data) {
                if($data['referer']) {
                    $result[$data['referer']][]=$data['user_id'];
                    $this->total_count[$data['referer']]++;
                }
            }
        
//        echo "<pre>";
//        var_dump($this->total_count);
//        exit;
        $this->results = $result;
        
    }
}
?>
