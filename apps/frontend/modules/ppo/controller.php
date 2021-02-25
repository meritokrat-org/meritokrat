<?

abstract class ppo_controller extends frontend_controller
{
    #protected $authorized_access = true;
    public function init()
    {
        parent::init();
        load::model('library/files');
        load::model('ppo/ppo');
        load::model('ppo/members');
        load::model('ppo/members_history');
        load::model('ppo/news');
        load::model('ppo/topics');
        load::model('ppo/topics_messages');
        load::model('ppo/positions');
        load::model('ppo/positions_messages');
        load::model('ppo/proposal');
        load::model('ppo/proposal_messages');
        load::model('user/user_desktop');

        load::model('groups/groups');
        load::view_helper('group');

        load::model('ppo/finance');
        if (session::has_credential('admin')) {
            load::model('ppo/finance_log');
        }

        load::action_helper('pager', true);
        $this->set_slot('context', 'partials/about');

        client_helper::set_title(t('Партийные организации').' | '.conf::get('project_name'));

        $this->is_regional_coordinator = user_desktop_peer::instance()->is_regional_coordinator(session::get_user_id());
        $this->is_raion_coordinator = user_desktop_peer::instance()->is_raion_coordinator(session::get_user_id());
        $this->allow_create = (session::has_credential('admin') || $this->is_regional_coordinator
            || $this->is_raion_coordinator) ? true : false;

        $this->ppo = ppo_peer::instance()->get_item(request::get_int('id'));

        if ($this->ppo['id']) {
            $this->allow_edit = (session::has_credential('admin') ||
                ppo_members_peer::instance()->allow_edit(session::get_user_id(), $this->ppo)) ? true : false;
        }

        if ($this->is_raion_coordinator) {
            $this->user_level = 2;
        }
        if (session::has_credential('admin') || $this->is_regional_coordinator) {
            $this->user_level = 3;
        }
    }

    public function post_action()
    {
        parent::post_action();

        $this->selected_menu = '/ppo';
    }

    public function update_geo($id)
    {
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
            $XML_URL = "https://maps.google.com/maps/geo?q=".urlencode($Address)."&output=xml&oe=utf8\&sensor=true";
            $Point_XML = "";
//            if (!($fp = @fopen($XML_URL, "r"))) {
//                error_log('GEO: from ip '.$_SERVER['REMOTE_ADDR']." ".$country." ".$region);
//            }
//            while ($data = fread($fp, 4096)) {
//                $Point_XML .= $data;
//            }
//            $xml = simplexml_load_string($Point_XML);
//            if ($xml->Response->Placemark->Point->coordinates) {
//                foreach ($xml->Response->Placemark->Point->coordinates as $Point) {
//                    list($updata['geolocationlng'], $updata['geolocationlat']) = explode(',', $Point, 2);
//                    break;
//                }
//                $updata['geolocationlng'] = current(explode(",", $updata['geolocationlng']));
//                $updata['geolocationlat'] = current(explode(",", $updata['geolocationlat']));
//                db_key::i()->set($key, $updata['geolocationlng']."_".$updata['geolocationlat']);
//            }
        } else {
            list($updata['geolocationlng'], $updata['geolocationlat']) = explode('_', $redis, 2);
        }
        $updata['id'] = $id;
        if ($updata['geolocationlng'] && $updata['geolocationlat']) {
            ppo_peer::instance()->update($updata);
        }
    }

}
