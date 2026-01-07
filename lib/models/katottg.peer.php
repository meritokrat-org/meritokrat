<?php

class katottg_peer extends db_peer_postgre
{
    public static function instance($peer = self::class)
    {
        return parent::instance($peer);
    }

    public function collect($id = null)
    {
        $cache_key = sprintf('katottg.%s', $id);

        if (!mem_cache::i()->exists($cache_key)) {
            $bind = [];
            $query = <<<SQL
select ch.id,
       case
           when ch.category = 'O' and ch.id != 1 then format('%s обл.', ch.name)
           when ch.category in ('P', 'B') then format('%s р-н.', ch.name)
           when ch.category = 'H' then format('%s т.г.', ch.name)
           when ch.category in ('K', 'M') then format('м. %s', ch.name)
           when ch.category in ('T', 'C', 'X') then format('с. %s', ch.name)
           else ch.name
           end as "name"
from katottg ch
where ch.level1 is not null
  and ch.level2 is null;
SQL;

            if ($id !== null) {
                $bind['id'] = $id;
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
                       (k.category = 'P' and k.level1 = p.level1)
                           or (k.category = 'M' and k.level3 = p.level1)

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
            }

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
}