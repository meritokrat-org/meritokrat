<?php
class seo_tags_peer extends db_peer_postgre {
    
    protected $table_name = 'seo_tags';
    
    const NONE_TYPE = 1;
    const STATIC_TYPE = 2;
    const DYNAMIC_TYPE = 3;
    
    public static function instance() {
        return parent::instance('seo_tags_peer');
    }
    public static function get_module_tags($module) {
        return db::get_row("SELECT * FROM seo_tags WHERE module=:mod AND action=:act AND ttype='2'",array('mod'=>$module,'act'=>'module_title'));
    }
    public static function get_action_tags($module,$action) {
        return db::get_row("SELECT * FROM seo_tags WHERE module=:mod AND action=:act",array('mod'=>$module,'act'=>$action.'.action.php'));
    }
}
?>
