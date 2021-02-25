<?php
load::app('modules/admin/controller');
class admin_seo_text_edit_action extends admin_controller {
    public function execute() {
        
        load::model('seo_text');
        load::view_helper('seo');
        
        $id = request::get_int('id');
        $this->edit_text = seo_text_peer::instance()->get_item($id);
        if(!$this->edit_text)
                throw new public_exception ("Record with ID=".$id." does not exist");
            
//        var_dump($this->seo_text);
        $this->modules = seo_helper::getModules(conf::get('project_root')."/apps/frontend/modules/");
        
    }
}
?>
