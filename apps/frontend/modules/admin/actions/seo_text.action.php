<?php
load::app('modules/admin/controller');
class admin_seo_text_action extends admin_controller {
    public function execute() {
        
        load::model('seo_text');
        load::view_helper('seo');
        
        $dir = conf::get('project_root')."/apps/frontend/modules/";
        $this->modules = seo_helper::getModules($dir);
        $list = seo_text_peer::instance()->get_list();
        if(!empty($list)) 
            foreach ($list as $k => $v) 
                $this->texts[] = seo_text_peer::instance()->get_item($v);
            
        
     
    }
}
?>
