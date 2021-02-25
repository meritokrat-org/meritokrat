<?php

class profile_get_select_action extends basic_controller
{
    #protected $authorized_access = true;
    public function execute()
    {
        header('Access-Control-Allow-Origin: *');

        translate::set_lang(session::get('language', 'ua'));
        load::model('geo');
        $list = [];

        if (isset($_POST['country_id'])) {
            $country_id = @intval($_POST['country_id']);
            $sql        = 'select * from regions where country_id = 0 or country_id = :id order by id asc ';
            $bind       = ['id' => $country_id];
            if ($data = db::get_rows($sql, $bind, $this->connection_name)) {
                //mem_cache::i()->set($cache_key, $data);
                foreach ($data as $region) {
                    $regions[] = ['id' => $region['id'], 'title' => $region['name_' . translate::get_lang()]];
                }
                $result = ['type' => 'success', 'regions' => $regions];
            } else {
                $result = ['type' => 'error'];
            }
        } else {
            $region = @intval($_POST['region']);
            /* $sql = 'SELECT * FROM districts WHERE region_id = :id';
        $bind = array('id' => $region);
            if ($data = db::get_rows( $sql, $bind, $this->connection_name ))*/

            if ($region > 28) {
                $data = db::exec("select id, name_" . translate::get_lang() . " from cities where region_id = :region_id", ["region_id" => $region]);
                foreach ($data as $city) {
                    $cities[] = ['id' => $city['id'], 'title' => $city['name_' . translate::get_lang()]];
                }
                $result = ['type' => 'success', 'cities' => $cities];
            } else {
                if ($data = geo_peer::instance()->get_cities($region)) {
                    //mem_cache::i()->set($cache_key, $data);
                    foreach ($data as $key => $city) {
                        $cities[] = ['id' => $key, 'title' => $city];
                    }
                    $result = ['type' => 'success', 'cities' => $cities];
                } else {
                    $result = ['type' => 'error'];
                }
            }
        }

        /*
         * Упаковываем данные с помощью JSON
         */
        print json_encode($result);
        die();
    }
}

/*
 * public function execute()
	{
		load::model('geo');
		$list = array();

                /*
                if (isset($_POST['country_id']))
                    {
                $country_id = @intval($_POST['country_id']);


		if ( $regions = geo_peer::instance()->get_regions($country_id) )
                {
                    foreach($regions as $id=>$value)
                        { ?>
<option value="<?=$id?>"> <?=$value?></option><?
                        }
                        die();
                      $result = array('type'=>'success', 'regions'=>$regions);
                }
                else {
                        $result = array('type'=>'error');
                }
                    }
                else
                    {
                $region = @intval($_POST['region']);
		if ( $cities = geo_peer::instance()->get_cities($country_id) )
                {
                    foreach($cities as $id=>$value)
                        { ?>
                        <option value="<?=$id?>"> <?=$value?></option> <?
                        }
                        die();
                       $result = array('type'=>'success', 'cities'=>$regions);
                }
                else {
                        $result = array('type'=>'error');
                }
                }

                /*
                 * Упаковываем данные с помощью JSON
                 
                print json_encode($result);
die();
	}
 */