<?php

load::app('modules/api/controller');
load::model('geo');

/**
 * Class GeoApi Controller
 */
class api_geo_action extends api_controller
{
    /**
     * Initialization
     */
    public function init()
    {
        parent::init();

        $this
            ->registerDirection('getRegions')
            ->registerDirection('getCities');
    }

    /**
     * Get regions
     *
     * @param integer $country
     * @return array
     */
    protected function getRegions($country)
    {
        $sql = 'SELECT id, name_ua FROM regions WHERE country_id = :country_id';
        $bind = ['country_id' => (int)$country];

        $list = db::get_rows($sql, $bind);

        foreach ($list as &$item) {
            $item['title'] = $item['name_ua'];
            unset($item['name_ua']);
        }

        return $list;
    }

    /**
     * Get cities
     *
     * @param $region
     * @return array
     */
    protected function getCities($region)
    {
        $list = [];

//        $sql = 'SELECT id, name_ua FROM districts WHERE region_id = :region_id AND id %s 700 ORDER BY name_ua ASC';
//        $bind = ['region_id' => (int)$region];
//
//        foreach (['<', '>'] as $operator) {
//            $list = $list + db::get_rows(sprintf($sql, $operator), $bind);
//        }

        foreach (geo_peer::instance()->get_cities($region) as $id => $title) {
            $list[] = [
                'id' => $id,
                'title' => $title
            ];
        }

        return $list;
    }
}