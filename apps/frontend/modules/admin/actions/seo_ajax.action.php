<?php
load::app('modules/admin/controller');
class admin_seo_ajax_action extends admin_controller {
    public function execute() {
       
       load::model('seo_text');
       load::model('seo_tags');
       
       $this->disable_layout();
       $this->set_renderer('ajax');
       $type = request::get('type');
       switch ($type) {
           case 'get_actions':
               $module = request::get('value');
               
               $directory = conf::get('project_root')."/apps/frontend/modules/".$module.'/actions/';
               if($module) {
                    $dir=opendir($directory);
                    if(is_dir($directory)) {
                        while ($d=readdir($dir)){
                            if($d!='.' && $d!='..' && $d!='' && $d!='.svn')
                                if(!is_dir($directory.$d)) {
                                    $actions['success'] = 1;
                                    $actions['actions'][] = $d;
                                }
                            }
                        closedir($dir);
                        if($actions) sort($actions['actions']);
                    }
                    if(in_array(request::get('language'),array('ua','ru','en'))) {
                        $tmp = seo_tags_peer::get_module_tags($module);
                        $actions['module_tags']['id']=$tmp['id'];
                        $actions['module_tags']['title']=$tmp['title_'.request::get('language')];
                    }
                    $this->json = $actions;
               }
               else 
                    $this->json = array('success'=>0, 'reason'=>'Input data error');
               break;
           case 'get_action_data':
               $module = request::get('modname');
               $action = request::get('value');
               $lang = request::get('language');
               if($module && $action && in_array($lang, array('ua','en','ru'))) {
                    $actData = seo_tags_peer::get_action_tags($module, str_replace('.action.php', '', $action));
                    $retArr['id']=$actData['id'];
                    $retArr['kwds']=$actData['keywords_'.$lang];
                    $retArr['desc']=$actData['description_'.$lang];
                    $retArr['ttype']=$actData['ttype'];
                    if($actData['ttype']=='2')
                        $retArr['title']=$actData['title_'.$lang];
                    $this->json = array_merge(array('success'=>1),$retArr);
               }
               else 
                   $this->json = array('success'=>0,'reason'=>'Incomming data error');
               break;
           case 'add_text':
               $alias = request::get('alias');
               $text = request::get('seo_text');
               $lang = request::get('lang');
               if($alias && $text && in_array($lang[0], array('ua','en','ru'))) {
                   
                   $insert_array = array(
                            'alias'=>$alias,
                            'text_'.$lang[0]=>$text,
                            'hidden'=>  request::get_bool('dynamic'),
                            'name'=>  request::get('name'),
                            'module'=>  request::get('module'),
                            'action'=> str_replace('.action.php', '', request::get('action'))
                   );
                   
                   if($upd_id = request::get_int('edit_id')) {
                       $update_array = array_merge(array('id'=>$upd_id),$insert_array);
                       seo_text_peer::instance()->update($update_array);
                       $this->json = $update_array;
                   }
                   else {
                       $insId = seo_text_peer::instance()->insert($insert_array);
                       $this->json = ($insId) ? array('id'=>$insId,'success'=>1) : array('success'=>0, 'reason'=>"Insert data error",'data'=>$insert_array);
                   }
               }
               break;
            case 'get_text_by_lang':
                $lang = request::get('lang');
                $recId = request::get_int('id');
                if(in_array($lang,array('ua','ru','en')) && $recId) {
                    $textData = seo_text_peer::instance()->get_item($recId);
                    if($textData) 
                        $this->json = array('content'=>$textData['text_'.$lang],'success'=>1);
                    else 
                        $this->json = array('reason'=>'Get data error', 'success'=>0);
                }
                else
                    $this->json = array('reason'=>'Incoming data error', 'success'=>0, 'data'=>array('lang'=>$lang, 'id'=>$recId));
                break;
           case 'delete_text':
                $delId = request::get_int('id');
                if($item = seo_text_peer::instance()->get_item($delId)) {
                    seo_text_peer::instance ()->delete_item ($delId);
                    $this->json = array('success'=>1);
                }
                else 
                    $this->json = array('success'=>0,'reason'=>'Incoming data error','data'=>array('id'=>$delId));
                break;
           case 'add_tags':
               
               /////////////////UPDATE ID
               $modId = request::get_int('mod_tag_id');
               $actId = request::get_int('act_tag_id');
               
               
               //////////////PARAMS
               $ttype = request::get('ttype');
               $lang = request::get('lang');
               $mname = request::get('modname');
               $aname = request::get('actname');
               $mod_title = request::get('module_title');
               $act_title = request::get('action_title');
               $kwd= request::get('action_kw');
               $desc = request::get('action_desc');
               
               
               
               
               //////////////////TITLE FOR all MODULE
               if($mname && $mod_title && in_array($lang[0], array('ua','ru','en'))) {
                    $module_data = array(
                                        'title_'.$lang[0] => $mod_title,
                                        'module'=>$mname,
                                        'action'=>'module_title',
                                        'ttype'=>2
                                        );
                   
                   if($modId) {
                       if($modData = seo_tags_peer::instance()->get_item($modId)) {
                           $update_module_data = array_merge(array('id'=>$modId),$module_data);
                           seo_tags_peer::instance()->update($update_module_data);
                       }
                       else
                           $this->json = array('success'=>0,'reason'=>'No record with posted Id','data'=>array_merge(array('id'=>$modId),$module_data));
                   }
                   else 
                        $mIns = seo_tags_peer::instance()->insert($module_data);
                   
                   
                   
                   
                   /////////////////////KEYWORDS, DESC && TITLES FOR CURRENT ACTION
                   if($aname && $ttype && $kwd && $desc) {
                        $action_data = array(
                                        'module'=>$mname,
                                        'keywords_'.$lang[0]=>$kwd,
                                        'description_'.$lang[0]=>$desc,
                                        'action'=>$aname,
                                        'ttype'=>$ttype[0]
                                        );
                        if($ttype[0]=='2' && $act_title) 
                            $action_data['title_'.$lang[0]]=$act_title;
                        if($actId) {
                           if($modData = seo_tags_peer::instance()->get_item($actId)) {
                               $update_action_data = array_merge(array('id'=>$actId),$action_data);
                               seo_tags_peer::instance()->update($update_action_data);
                           }
                           else
                               $this->json = array('success'=>0,'reason'=>'No record with posted Id','data'=>array_merge(array('id'=>$actId),$action_data));
                        }
                        else 
                            $aIns = seo_tags_peer::instance()->insert($action_data);
                   }
                   
                   
                   
                   
                   $this->json = array('success'=>1);
                   
               }
               else {
                   $this->json = array('success'=>1,'reason'=>'Помилка вхідних даних');
               }
               break;
           default :
                $incData = request::get_all();
                unset($incData['module']);
                unset($incData['action']);
                $this->json = array('success'=>0,'reason'=>'Incoming data error','data'=>$incData);
                break;
       }
    }
}
?>
