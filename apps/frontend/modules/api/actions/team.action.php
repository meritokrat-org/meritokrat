<?php

load::app('modules/api/controller');
load::model('team/team');

/**
 * Class Team Api Controller
 */
class api_team_action extends api_controller
{
    /**
     * Initialization
     */
    public function init()
    {
        parent::init();

        $this
            ->registerDirection('get')
            ->registerDirection('find')
            ->registerDirection('save')
            ->registerDirection('getBranch');
    }

    /**
     * Get team
     *
     * @param integer $id
     *
     * @return array
     */
    protected function get($id)
    {
        if (
            !($id > 0)
            || !($row = db::get_row('SELECT * FROM team WHERE id = :id', ['id' => (int) $id]))
        ) {
            return ['success' => false, 'error' => 'Entity not found'];
        }

        $entry = [
            'id'             => $row['id'],
            'level'          => $row['category'],
            'title'          => $row['title'],
            'location'       => $this->_getLocation($row),
            'avatar'         => $this->_getTeamAvatar($row),
            'dateOfAssembly' => date('Y-m-d H:i:s', $row['dzbori']),
            'branch'         => $this->_getBranch($row),
            'stat'           => $this->_getSummaryStat($row),
            'buildings'      => $row['buildings'],
        ];

        return [
            'success' => true,
            'entry'   => $entry,
            /** @deprecated */
            '_entry'  => $row + $entry + ['votersCount' => $row['voters_count']],
        ];
    }

    /**
     * Get list of active teams by required conditions
     *
     * @param $query
     * @param $offset
     * @param $limit
     *
     * @return array
     */
    protected function find($query, $offset, $limit)
    {
        $list = [];

        $offset = intval($offset);
        $limit  = intval($limit);
        if ($limit < 8 || $limit > 32) {
            $limit = 100;
        }

        $sql            = 'SELECT %s FROM team'
            .' WHERE active = 1%s%s%s';
        $sqlSelect      = 'id, title, category, creator_id, photo_salt';
        $sqlOrder       = ' ORDER BY id DESC';
        $sqlLimitOffset = ' LIMIT :limit OFFSET :offset';
        $sqlInject      = '';

        $bind            = [];
        $bindLimitOffset = [
            'offset' => $offset,
            'limit'  => $limit,
        ];

        if (is_array($query)) {
            $expressions = [];

            if (isset($query['category'])) {
                $expressions[]    = 'category = :category';
                $bind['category'] = intval($query['category']);
            }

            if (isset($query['region'])) {
                $expressions[]  = 'region_id = :region';
                $bind['region'] = intval($query['region']);
            }

            if (isset($query['city'])) {
                $expressions[] = 'city_id = :city';
                $bind['city']  = intval($query['city']);
            }

            if (!empty($expressions)) {
                $sqlInject = ' AND '.implode(' AND ', $expressions);
            }
        }

        foreach (db::get_rows(sprintf($sql, $sqlSelect, $sqlInject, $sqlOrder, $sqlLimitOffset), $bind + $bindLimitOffset) as $team) {
            $list[] = [
                'id'       => $team['id'],
                'title'    => $team['title'],
                'category' => $team['category'],
                'avatar'   => $this->_getTeamAvatar($team),
                'creator'  => $this->_getTeamCreator($team),
            ];
        }

        return [
                'data'   => $list,
                'offset' => $offset,
                'limit'  => $limit,
            ] + db::get_row(sprintf($sql, 'COUNT(id)', $sqlInject, '', ''), $bind);
    }

    /**
     * @param $entity
     *
     * @return array
     */
    protected function save($entity)
    {
        $data = [
            'title' => $entity['title'],

            'type'     => 0,
            'category' => $entity['level'],
            'ptype'    => $entity['type'],

            'region_id'   => (int) $entity['region'],
            'city_id'     => (int) $entity['city'],
            'district_id' => (int) $entity['district'],
            'adres'       => $entity['address'],

            'active'     => 1,
            'created_ts' => time(),

            'dzbori'       => strtotime($entity['dateOfAssembly']),
            'voters_count' => (int) $entity['votersCount'],
            'buildings'    => $entity['buildings'],
        ];

        $id = isset($entity['id']) ? (int) $entity['id'] : 0;
        if ($id > 0) {
            team_peer::instance()->update(array_merge($data, ['id' => $id]));
        } else {
            $id = team_peer::instance()->insert(array_merge($data, ['creator_id' => session::get_user_id()]));
        }

        return $this->get($id);
    }

    /**
     * Get statistic of related teams
     *
     * @param integer $id
     *
     * @return mixed
     */
    protected function getBranch($id)
    {
        if (
            !($id > 0)
            || !($row = db::get_row('SELECT * FROM team WHERE id = :id', ['id' => (int) $id]))
        ) {
            return ['success' => false, 'error' => 'Entity not found'];
        }

        return [
            'success' => true,
            'branch'  => $this->_getRelTeamsStat($team),
        ];
    }

    /**
     * @param $team
     *
     * @return string
     */
    private function _getTeamAvatar($team)
    {
        $avatar = 'p/group/0.jpg';
        if ($team['photo_salt'] != '') {
            $avatar = user_helper::team_photo_path($team['id'], 'p', $team['photo_salt']);
        }

        return '/'.$avatar;
    }

    /**
     * @param $team
     *
     * @return array
     */
    private function _getTeamCreator($team)
    {
        $creator = db::get_row(
            'SELECT user_id AS id, first_name, last_name FROM user_data WHERE user_id = :id',
            ['id' => $team['creator_id']]
        );

        return [
            'id'        => $creator['id'],
            'firstName' => $creator['first_name'],
            'lastName'  => $creator['last_name'],
            'fullName'  => implode(' ', [$creator['first_name'], $creator['last_name']]),
        ];
    }

    /**
     * Get team location
     *
     * @param $team
     *
     * @return array
     */
    private function _getLocation($team)
    {
        $location = ['title' => []];

        $region              = db::get_row(
            'SELECT id, name_ua AS title FROM regions WHERE id = :id',
            ['id' => $team['region_id']]
        );
        $location['region']  = $region;
        $location['title'][] = $region['title'];

        if ($team['city_id'] > 0) {
            $city                = db::get_row(
                'SELECT id, name_ua AS title FROM districts WHERE id = :id',
                ['id' => $team['city_id']]
            );
            $location['city']    = $region;
            $location['title'][] = $city['title'];
        }

        return ['title' => implode(' / ', $location['title'])] + $location;
    }

    /**
     * Get summary statistic of team
     *
     * @param array $team
     *
     * @return array
     */
    private function _getSummaryStat($team)
    {
        $level    = $team['category'];
        $region   = $team['region_id'];
        $city     = $team['city_id'];
        $district = $team['id'];

        if (!($level > 1)) {
            return [
                'members' => db::get_scalar('SELECT COUNT(*) FROM team_members WHERE group_id = :id', ['id' => $team['id']]),
                'voters'  => db::get_scalar('SELECT voters_count FROM team WHERE id = :id', ['id' => $team['id']]),
            ];
        }

        $sql            = ['teams' => 'SELECT %s FROM team WHERE active = 1'];
        $sql['members'] = sprintf('SELECT COUNT(*) FROM team_members WHERE group_id IN (%s)', sprintf($sql['teams'], 'id').'%s');
        $sql['voters']  = sprintf($sql['teams'], 'SUM(voters_count)').'%s';
        $sqlBind        = [];
        $sqlInject      = [
            'teams'  => [],
            'people' => [],
        ];
        $sqlExpr        = [
            'region'   => ' AND region_id = :region',
            'city'     => ' AND city_id = :city',
            'district' => ' AND district_id = :district',
        ];

        if ($level > 4) {
            $sqlInject['teams'][]  = 'COUNT(NULLIF(category = 4, FALSE)) AS l4th';
            $sqlInject['people'][] = 'category = 4';
            $sqlExpr['region']     = null;
        }

        if ($level > 3) {
            $sqlCond               = sprintf('category = 3%s', $sqlExpr['region']);
            $sqlInject['teams'][]  = sprintf('COUNT(NULLIF(%s, FALSE)) AS l3rd', $sqlCond);
            $sqlInject['people'][] = $sqlCond;
            $sqlExpr['city']       = null;
        }

        if ($level > 2) {
            $sqlCond               = sprintf('category = 2%s%s', $sqlExpr['region'], $sqlExpr['city']);
            $sqlInject['teams'][]  = sprintf('COUNT(NULLIF(%s, FALSE)) AS l2nd', $sqlCond);
            $sqlInject['people'][] = $sqlCond;
            $sqlExpr['district']   = null;
        }

        if ($level > 1) {
            $sqlCond               = sprintf('category = 1%s%s%s', $sqlExpr['region'], $sqlExpr['city'], $sqlExpr['district']);
            $sqlInject['teams'][]  = sprintf('COUNT(NULLIF(%s, FALSE)) AS l1st', $sqlCond);
            $sqlInject['people'][] = $sqlCond;
        }

        foreach (['region' => $region, 'city' => $city, 'district' => $district] as $key => $val) {
            if (empty($sqlExpr[$key])) {
                continue;
            }

            $sqlBind[$key] = $val;
        }

        $members = 0;
        $voters  = 0;
        foreach ($sqlInject['people'] as $inject) {
            $members += db::get_scalar(sprintf($sql['members'], ' AND '.$inject), $sqlBind);
            $voters  += db::get_scalar(sprintf($sql['voters'], ' AND '.$inject), $sqlBind);
        }

        return [
            'teams'   => db::get_row(sprintf($sql['teams'], implode(', ', $sqlInject['teams'])), $sqlBind),
            'members' => $members,
            'voters'  => $voters,
        ];
    }

    /**
     * Get team branch
     *
     * @param $team
     *
     * @return array
     */
    private function _getBranch($team)
    {
        $list = [];

        $sql     = 'SELECT * FROM team WHERE active = 1 AND category = :category';
        $sqlBind = ['category' => $team['category'] - 1];

        if ($team['category'] < 5) {
            $sql               .= ' AND region_id = :region';
            $sqlBind['region'] = $team['region_id'];
        }

        if ($team['category'] < 4) {
            $sql             .= ' AND city_id = :city';
            $sqlBind['city'] = $team['city_id'];
        }

        if ($team['category'] < 3) {
            $sql                 .= ' AND district_id = :district';
            $sqlBind['district'] = $team['id'];
        }

        foreach (db::get_rows($sql, $sqlBind) as $row) {
            $sql     = 'SELECT COUNT(id) FROM team WHERE active = 1 AND category = :category';
            $sqlBind = ['category' => $row['category'] - 1];

            if ($row['category'] < 5) {
                $sql               .= ' AND region_id = :region';
                $sqlBind['region'] = $row['region_id'];
            }

            if ($row['category'] < 4) {
                $sql             .= ' AND city_id = :city';
                $sqlBind['city'] = $row['city_id'];
            }

            if ($row['category'] < 3) {
                $sql                 .= ' AND district_id = :district';
                $sqlBind['district'] = $row['id'];
            }

            $list[] = [
                'id'        => $row['id'],
                'category'  => $row['category'],
                'title'     => $row['title'],
                'region'    => $row['region_id'],
                'city'      => $row['city_id'],
                'district'  => $row['district_id'],
                'nextLevel' => [
                    'teams'   => db::get_scalar($sql, $sqlBind),
                    'members' => db::get_scalar('SELECT COUNT(*) FROM team_members WHERE group_id = :id', ['id' => $row['id']]),
                ],
            ];
        }

        return $list;
    }
}

