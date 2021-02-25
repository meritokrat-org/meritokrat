<?php
class admin_seo_tags_action extends frontend_controller {
    public function execute() {
        load::model('seo_tags');
        load::view_helper('seo');
        
        $dir = conf::get('project_root')."/apps/frontend/modules/";
        $this->modules = seo_helper::getModules($dir);
    }
}
?>
