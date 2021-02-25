<?php
load::app('modules/admin/controller');
class admin_update_user_location_action extends admin_controller {
    
    public function execute() {        
        $districts = db::get_rows("SELECT * FROM districts WHERE id>700 AND id<2000");
        $newDist = array();
        
        foreach ($districts as $key => $value) 
            $newDist[$value['id']] =$value['name_ua'];
        
        $cnt = 0;
        $res_arr = array();
        
        foreach($newDist as $id=>$name) {
            $cnt++;
            $res_arr[] = array($id."=>".$name."<br/>");
            $res_arr[] = 'UPDATE user_data SET <b>location='.$name.'</b> WHERE <b>city_id='.$id."</b><br/>";
            $res = db::exec('UPDATE user_data SET location=:loc WHERE city_id=:cId', array('loc'=>$name, 'cId'=>$id));
            $res_arr[] = $res;
            $res_arr[] = "<br/>";
            if($cnt>10) continue;
        }   
        $res_arr[] = "Total updated rows = ".$cnt;
        $this->res_arr = $res_arr;
    }
}