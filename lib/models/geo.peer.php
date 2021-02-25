<?php

class geo_peer extends db_peer_postgre
{
    protected $table_name = 'districts';

    public function get_regions($id)
    {
        $cache_key = 'regions.' . $id;
        if (!mem_cache::i()->exists($cache_key)) {
            $sql     = 'SELECT * FROM regions WHERE country_id = :id';
            $bind    = ['id' => $id];
            $regions = db::get_rows($sql, $bind, $this->connection_name);

            $data[0] = '&mdash;';
            foreach ($regions as $one_region) {
                $data[$one_region['id']] = $one_region['name_' . translate::get_lang()];
            }

            mem_cache::i()->set($cache_key, $data);
        } else {
            $data = mem_cache::i()->get($cache_key);
        }

        if (0 === count($data)) {
            $data = [];
        }

        return $data;
    }

    public function insert_region($data)
    {
        foreach ($data as $key => $value) {
            $values_sql[] = "\"{$key}\"";
            $insert_sql[] = "'{$value}'";
        }

        return db::get_scalar(
            'INSERT INTO regions(' . implode(',', $values_sql) . ') VALUES(' . implode(',', $insert_sql) . ') RETURNING id'
        );
    }

    public function get_region_name($id)
    {
        $cache_key = 'region_name_' . $id;
        if (!mem_cache::i()->exists($cache_key)) {
            $sql         = 'SELECT name_' . session::get('language', 'ua') . ' FROM regions WHERE id = :id LIMIT 1';
            $bind        = ['id' => $id];
            $data        = db::get_row($sql, $bind);
            $region_name = $data['name_' . session::get('language', 'ua')];
            mem_cache::i()->set($cache_key, $region_name);
        } else {
            $region_name = mem_cache::i()->get($cache_key);
        }

        return $region_name;
    }

    public function get_country_name($id)
    {
        $cache_key = 'country_name_' . $id;
        if (!mem_cache::i()->exists($cache_key)) {
            $sql          = 'SELECT name_' . session::get('language', 'ua') . ' FROM countries WHERE id = :id LIMIT 1';
            $bind         = ['id' => $id];
            $data         = db::get_row($sql, $bind);
            $country_name = $data['name_' . session::get('language', 'ua')];
            mem_cache::i()->set($cache_key, $country_name);
        } else {
            $country_name = mem_cache::i()->get($cache_key);
        }

        return $country_name;
    }

    public function get_countries()
    {
        $sql       = 'SELECT * FROM countries ORDER BY priority';
        $bind      = [];
        $countries = db::get_rows($sql, $bind, $this->connection_name);

        $list = [];
        foreach ($countries as $country) {
            $list[$country["id"]] = $country['name_' . translate::get_lang()];
        }

        return $list;
    }

    public function get_cities($id, $other = false)
    {
        $cache_key = 'cities.' . $id; //$id - region_id
        if (!mem_cache::i()->exists($cache_key)) {
            if ($other) {
                $cities = db::get_rows("SELECT * FROM cities WHERE region_id = :id", ["id" => $id]);
            } else {
                $sql  = 'SELECT * FROM districts WHERE region_id = :id AND id<700 ORDER BY name_ua ASC';
                $sql2 = 'SELECT * FROM districts WHERE region_id = :id AND id>700 ORDER BY name_ua ASC';

                $bind = ['id' => $id];
                foreach (db::get_rows($sql, $bind, $this->connection_name) as $city) {
                    $cities[] = $city;
                }

                foreach (db::get_rows($sql2, $bind, $this->connection_name) as $city) {
                    $cities[] = $city;
                }
            }

            $data[0] = '&mdash;';
            if (count($cities) > 0) {
                foreach ($cities as $one_city) {
                    $data[$one_city["id"]] = $one_city['name_' . translate::get_lang()];
                }
            } else {
                $data = [];
            }

            mem_cache::i()->set($cache_key, $data);
        } else {
            $data = mem_cache::i()->get($cache_key);
        }

        if (count($data) < 1) {
            $data = [];
        }

        return $data;
    }

    public function get_city($id, $other = false)
    {
        if (!$other) {
            $city = geo_peer::instance()->get_item($id);
        } else {
            $city = db::get_row("SELECT * FROM cities WHERE id = :id", ["id" => $id]);
        }

        $region = geo_peer::instance()->get_region($city['region_id']);

        $city['region_name_ru'] = $region['name_ru'];
        $city['region_name_ua'] = $region['name_ua'];

        return $city;
    }

    /**
     * @return geo_peer
     */
    public static function instance()
    {
        return parent::instance('geo_peer');
    }

    public function get_region($id)
    {
        $cache_key = 'region.' . $id;
        if (!mem_cache::i()->exists($cache_key)) {
            $sql  = 'SELECT * FROM regions WHERE id = :id LIMIT 1';
            $bind = ['id' => $id];
            $data = db::get_row($sql, $bind, $this->connection_name);
            mem_cache::i()->set($cache_key, $data);
        } else {
            $data    = mem_cache::i()->get($cache_key);
            $country = geo_peer::instance()->get_country($region['country_id']);

            $data['country_name_ru'] = $country['name_ru'];
            $data['country_name_ua'] = $country['name_ua'];
        }

        return $data;
    }

    public function get_country($id)
    {
        $cache_key = 'country.' . $id;
        if (!mem_cache::i()->exists($cache_key)) {
            $sql  = 'SELECT * FROM countries WHERE id = :id LIMIT 1';
            $bind = ['id' => $id];
            $data = db::get_row($sql, $bind, $this->connection_name);
            mem_cache::i()->set($cache_key, $data);
        } else {
            $data = mem_cache::i()->get($cache_key);
        }

        return $data;
    }

    public function get_city_name($id)
    {
        $cache_key = 'city_name_' . $id;
        if (!mem_cache::i()->exists($cache_key)) {
            $sql       = 'SELECT name_' . session::get('language', 'ua') . ' FROM districts WHERE id = :id LIMIT 1';
            $bind      = ['id' => $id];
            $data      = db::get_row($sql, $bind);
            $city_name = $data['name_' . session::get('language', 'ua')];
            mem_cache::i()->set($cache_key, $city_name);
        } else {
            $city_name = mem_cache::i()->get($cache_key);
        }

        return $city_name;
    }

    public function insert_city($data)
    {
        parent::insert($data);
    }

    public function get_by_key($key)
    {
        $sql = 'SELECT DISTINCT id FROM ' . $this->table_name . ' WHERE name_ru ILIKE :key OR name_ua ILIKE :key LIMIT 10';

        return db::get_cols($sql, ['key' => $key . '%'], $this->connection_name);
    }

    public function get_country_by_key($key)
    {
        $sql = 'SELECT DISTINCT id FROM countries WHERE name_ru ILIKE :key OR name_ua ILIKE :key LIMIT 10';

        return db::get_cols($sql, ['key' => $key . '%'], $this->connection_name);
    }

    public function get_region_by_key($key)
    {
        $sql = 'SELECT DISTINCT id FROM regions WHERE name_ru ILIKE :key OR name_ua ILIKE :key OR name_en ILIKE :key LIMIT 10';

        return db::get_cols($sql, ['key' => $key . '%'], $this->connection_name);
    }
}