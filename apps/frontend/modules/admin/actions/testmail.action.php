<?

load::app('modules/admin/controller');

class admin_testmail_action extends frontend_controller
{
    public function execute()
    {
            $this->set_renderer('ajax');

            if(!request::get_int('id'))
                die('error');

            load::model('email/email');
            $data = email_peer::instance()->get_item(request::get_int('id'));

            load::model('user/user_data');
            $user = user_data_peer::instance()->get_item(session::get_user_id());

            load::action_helper('user_email',false);

            $options = array(
                '%name%' => $user['first_name'].' '.$user['last_name'],
                '%inviter%' => $user['first_name'].' '.$user['last_name'],
                '%first_name%' => $user['first_name'],
                '%last_name%' => $user['last_name'],
                '%from%' => $user['first_name'].' '.$user['last_name'],
                '%fullname%' => $user['first_name'].' '.$user['last_name'],
                '%email%' => 'test@meritokrat.org',
                '%password%' => 'Pa55w@rd',
                '%text%'=> 'Test-text-here',
                '%title%'=> 'Test-title-here',
                '%link%' =>  'https://' . context::get('host') . '/',
                '%image%' => 'https://' . context::get('host') . '/static/images/logos/logo.png',
                '%profile%' => 'https://'.conf::get('server').'/group'.$user['user_id'],
                '%message%' => 'Test-message-here',
                '%newname%' => 'Barak Obama',
                '%recommender%' => $user['first_name'].' '.$user['last_name'],
                '%recommend%' => 'Test-recommend-here',
                '%posada%' => 'Big Boss',
                '%member_name%' => 'Username',
                );

            user_email_helper::send_sys($data['alias'],session::get_user_id(),session::get_user_id(),$options);
//            die('ok');

        /* $ppos=db::get_cols("SELECT id FROM ppo WHERE active=1 AND (svidnum=0 OR svidnum IS NULL)");
         foreach($ppos as $ppo){ echo $ppo['id'].'<br/>';
                     $scount=db::get_scalar("SELECT MAX(svidnum)
                                 FROM ppo
                                 WHERE active=1");
                     $snumber=$scount+1;
                     db::exec("UPDATE ppo SET svidnum=$snumber WHERE id=$ppo");
         }die('ok');
         */
        /*
        $ppos=db::get_rows("SELECT * FROM ppo");
        foreach($ppos as $ppo){
                    if($ppo['svidnum']!='')db::exec("UPDATE ppo SET svidnum_new=".$ppo['svidnum']." WHERE id=".$ppo['id']);
        }die('ok');*/
//        $ppos = db::get_rows("SELECT * FROM ppo WHERE category>1");
//        foreach ($ppos as $ppo) {
//            $this->update_geo($ppo['id']);
//        }
//        die('ok');
    }

    public function update_geo($id)
    {
        load::model('ppo/ppo');
        $ppo_data = ppo_peer::instance()->get_item($id);
        $key = 'geoloc_'.$ppo_data["region_id"].'_'.$ppo_data["city_id"];
        if (!$redis = db_key::i()->get($key)) {
            load::model('geo');
            $country_arr = geo_peer::instance()->get_country(1);
            $country_name = $country_arr['name_ru'];

            $region_arr = geo_peer::instance()->get_region($ppo_data["region_id"]);
            $region_name = $region_arr['name_ru'];

            $city_arr = geo_peer::instance()->get_city($ppo_data["region_id"]);
            $city_name = $city_arr['name_ru'];

            $Address = $country_name.", ".$region_name.", ".$city_name;
            $XML_URL = "http://maps.google.com/maps/geo?q=".urlencode($Address)."&output=xml&oe=utf8\&sensor=true";
            $Point_XML = "";
            if (!($fp = @fopen($XML_URL, "r"))) {
                error_log('GEO: from ip '.$_SERVER['REMOTE_ADDR']." ".$country." ".$region);
            }
            while ($data = fread($fp, 4096)) {
                $Point_XML .= $data;
            }
            $xml = simplexml_load_string($Point_XML);
            if ($xml->Response->Placemark->Point->coordinates) {
                foreach ($xml->Response->Placemark->Point->coordinates as $Point) {
                    list($updata['geolocationlng'], $updata['geolocationlat']) = explode(',', $Point, 2);
                    break;
                }
                $updata['geolocationlng'] = current(explode(",", $updata['geolocationlng']));
                $updata['geolocationlat'] = current(explode(",", $updata['geolocationlat']));
                db_key::i()->set($key, $updata['geolocationlng']."_".$updata['geolocationlat']);
            }
        } else {
            list($updata['geolocationlng'], $updata['geolocationlat']) = explode('_', $redis, 2);
        }
        $updata['id'] = $id;
        if ($updata['geolocationlng'] && $updata['geolocationlat']) {
            ppo_peer::instance()->update($updata);
        }
    }
}
