<?php
load::app('modules/admin/controller');
class admin_rating_cost_action extends admin_controller {
    public function execute() {
        $this->costs = rating_helper::get_costs();
        $this->region_costs = rating_helper::get_region_ratio();
        $admin_points = user_rating_admin_points_peer::instance()->get_list();
        if($admin_points)
            foreach ($admin_points as $k => $v) 
                $this->admin_points[] = user_rating_admin_points_peer::instance()->get_item($v);
            
        
    }
}
?>
