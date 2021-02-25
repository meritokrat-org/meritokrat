<?php
class ajax_user_search_action extends frontend_controller
{
       public function execute()
	{
                load::model('user/user_desktop_received_help');
                load::model('geo');
                
                $this->disable_layout();
                $this->set_renderer(null);
                if(request::get_int('approved')) {
                    if(request::get_int('help_id'))
                       user_desktop_received_help_peer::instance()->update(array(
                           'id'=>  request::get_int('help_id'),
                           'approved'=>request::get_int('approved'))
                           );
                }
                if(request::get('review')) {
                    if(request::get_int('help_id'))
                       user_desktop_received_help_peer::instance()->update(array(
                           'id'=>  request::get_int('help_id'),
                           'review'=>request::get('review'))
                           );

                }

		if($params = request::get('fio')) {

                    if(isset($params)) {
                        $fio = explode(' ', $params);
                        
                        if(isset($fio[1]))
                            $sql = "SELECT user_id, first_name, last_name, city_id FROM user_data WHERE (first_name ILIKE '".$fio[0]."%' AND last_name ILIKE '".$fio[1]."%') OR (last_name ILIKE '".$fio[0]."%' AND first_name ILIKE '".$fio[1]."%')  LIMIT 10";
                        else
                            $sql = "SELECT user_id, first_name, last_name, city_id FROM user_data WHERE first_name ILIKE '".$fio[0]."%' OR last_name ILIKE '".$fio[0]."%' LIMIT 10";
                        
                        if ($data = db::get_rows($sql))
                        {
                            $good_data = array();
                            
                            foreach($data as $id=>$item){
                                //if(session::get_user_id()!=$item['user_id']) {
                                    $location = geo_peer::get_city($item['city_id']);
                                    $good_data[$item['user_id']] = array($item['first_name'].' '.$item['last_name'], empty($location['region_name_ua'])? '-': $location['region_name_ua'], context::get('image_server').user_helper::photo_path($item['user_id'], 'ssm'));
                                //}
                            }
                            echo json_encode($good_data);
                            return true;
                        }
                        else {}
                    }
                
                }
                else {}
	}
}


?>
