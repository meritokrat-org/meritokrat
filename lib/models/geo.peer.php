<?php

class geo_peer extends db_peer_postgre
{
    protected $table_name = 'districts';

    public function get_regions($id)
    {
        $cache_key = sprintf('regions.%s', $id);

        if (!mem_cache::i()->exists($cache_key)) {
            $query = '
select id,
       case
           when category = \'K\' then concat_ws(\' \', \'м\', name)
           when category = \'O\' and id != 1 then concat_ws(\' \', name, \'обл\')
           else name end as name
from katottg
where category in (\'O\', \'K\')
order by level1
';
            $bind = [];

            if ($id !== 1) {
                $query = sprintf(
                    'select id, name_%s as name from regions where country_id = :id',
                    translate::get_lang()
                );
                $bind['id'] = $id;
            }

            mem_cache::i()->set(
                $cache_key,
                array_reduce(
                    db::get_rows($query, $bind, $this->connection_name),
                    function ($accumulator, $item) {
                        $accumulator[$item['id']] = $item['name'];

                        return $accumulator;
                    },
                    ['&mdash;']
                )
            );
        }

        return mem_cache::i()->get($cache_key);
    }

    public function insert_region($data)
    {
        foreach ($data as $key => $value) {
            $values_sql[] = "\"{$key}\"";
            $insert_sql[] = "'{$value}'";
        }

        return db::get_scalar(
            'INSERT INTO regions('.implode(',', $values_sql).') VALUES('.implode(',', $insert_sql).') RETURNING id'
        );
    }

    public function get_region_name($id)
    {
        $cache_key = 'region_name_'.$id;
        if (!mem_cache::i()->exists($cache_key)) {
            $sql = 'SELECT name_'.session::get('language', 'ua').' FROM regions WHERE id = :id LIMIT 1';
            $bind = ['id' => $id];
            $data = db::get_row($sql, $bind);
            $region_name = $data['name_'.session::get('language', 'ua')];
            mem_cache::i()->set($cache_key, $region_name);
        } else {
            $region_name = mem_cache::i()->get($cache_key);
        }

        return $region_name;
    }

    public function get_country_name($id)
    {
        $cache_key = 'country_name_'.$id;
        if (!mem_cache::i()->exists($cache_key)) {
            $sql = 'SELECT name_'.session::get('language', 'ua').' FROM countries WHERE id = :id LIMIT 1';
            $bind = ['id' => $id];
            $data = db::get_row($sql, $bind);
            $country_name = $data['name_'.session::get('language', 'ua')];
            mem_cache::i()->set($cache_key, $country_name);
        } else {
            $country_name = mem_cache::i()->get($cache_key);
        }

        return $country_name;
    }

    public function get_countries()
    {
        $sql = 'SELECT * FROM countries ORDER BY priority';
        $bind = [];
        $countries = db::get_rows($sql, $bind, $this->connection_name);

        $list = [];
        foreach ($countries as $country) {
            $list[$country["id"]] = $country['name_'.translate::get_lang()];
        }

        return $list;
    }

    /**
     * @return array
     */
    public function get_cities($id, $other = false)
    {
        $cache_key = sprintf('cities.%s', $id); //$id - region_id

        if (!mem_cache::i()->exists($cache_key)) {
            $query = <<<SQL
select ch.id,
       case
           when ch.category = 'O' then format('%s обл.', ch.name)
           when ch.category in ('P', 'B') then format('%s р-н.', ch.name)
           when ch.category = 'H' then format('%s т.г.', ch.name)
           when ch.category in ('K', 'M') then format('м. %s', ch.name)
           when ch.category in ('T', 'C', 'X') then format('с. %s', ch.name)
           else ch.name
           end as "name"
from katottg as p,
     lateral (
         select *
         from katottg k
         where case
                   when p.category = 'O' then
                       (k.category = 'P' and k.level1 = p.level1) or
                       (k.category = 'M' and k.level3 = p.level1)

                   when p.category = 'P' then
                       k.category = 'H' and
                       k.level2 = p.level2 and
                       k.level3 is not null

                   when p.category = 'H' then
                       k.category in ('M', 'T', 'C', 'X') and
                       k.level3 = p.level3 and
                       k.level4 is not null

                   when p.category = 'M' then
                       k.category = 'B' and
                       k.level4 = p.level4 and
                       k.level5 is not null

                   when p.category = 'K' then
                       k.category = 'B' and
                       k.level4 = p.level1 and
                       k.level5 is not null
                   end
         ) as ch
where p.id = :id
SQL;
            $bind = ['id' => $id];

            mem_cache::i()->set(
                $cache_key,
                array_reduce(
                    db::get_rows($query, $bind, $this->connection_name),
                    function ($accumulator, $item) {
                        $accumulator[$item['id']] = $item['name'];

                        return $accumulator;
                    },
                    [0 => htmlentities('-')]
                )
            );
        }

        return mem_cache::i()->get($cache_key);
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
    public static function instance($peer = 'geo_peer')
    {
        return parent::instance($peer);
    }

    public function get_region($id)
    {
        $cache_key = 'region.'.$id;
        if (!mem_cache::i()->exists($cache_key)) {
            $sql = 'select * from regions where id = :id limit 1';
            $bind = ['id' => $id];
            $data = db::get_row($sql, $bind, $this->connection_name);
            mem_cache::i()->set($cache_key, $data);
        } else {
            $data = mem_cache::i()->get($cache_key);
            $country = geo_peer::instance()->get_country($data['country_id']);

            $data['country_name_ru'] = $country['name_ru'];
            $data['country_name_ua'] = $country['name_ua'];
        }

        return $data;
    }

    public function get_country($id)
    {
        $cache_key = 'country.'.$id;
        if (!mem_cache::i()->exists($cache_key)) {
            $sql = 'SELECT * FROM countries WHERE id = :id LIMIT 1';
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
        $cache_key = 'city_name_'.$id;
        if (!mem_cache::i()->exists($cache_key)) {
            $sql = 'SELECT name_'.session::get('language', 'ua').' FROM districts WHERE id = :id LIMIT 1';
            $bind = ['id' => $id];
            $data = db::get_row($sql, $bind);
            $city_name = $data['name_'.session::get('language', 'ua')];
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
        $sql = 'SELECT DISTINCT id FROM '.$this->table_name.' WHERE name_ru ILIKE :key OR name_ua ILIKE :key LIMIT 10';

        return db::get_cols($sql, ['key' => $key.'%'], $this->connection_name);
    }

    public function get_country_by_key($key)
    {
        $sql = 'SELECT DISTINCT id FROM countries WHERE name_ru ILIKE :key OR name_ua ILIKE :key LIMIT 10';

        return db::get_cols($sql, ['key' => $key.'%'], $this->connection_name);
    }

    public function get_region_by_key($key)
    {
        $sql = 'SELECT DISTINCT id FROM regions WHERE name_ru ILIKE :key OR name_ua ILIKE :key OR name_en ILIKE :key LIMIT 10';

        return db::get_cols($sql, ['key' => $key.'%'], $this->connection_name);
    }
}