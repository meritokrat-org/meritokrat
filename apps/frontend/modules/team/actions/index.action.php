<?php

load::app('modules/team/controller');
load::model('bookmarks/bookmarks');
load::action_helper('pager');

/**
 * @property int cur_citi
 */
class team_index_action extends team_controller
{
    protected $authorized_access = true;

    public function execute()
    {
        $this->cur_type = request::get_int('type');
        $this->cur_list = request::get_int('list');
        $this->cur_ptype = request::get_int('ptype');
        $this->cur_status = request::get_int('status');
        $this->cur_region = request::get_int('region');
        $this->cur_citi = request::get_int('city');

        $where = [];
        if (!session::has_credential('admin'))
            $where = array_merge(['active' => 1]);

        if (!request::get_int('category'))
            $_REQUEST['category'] = 777;

        $this->cur_category = request::get_int('category');

        if (request::get_int('user_id') > 0) {
            $this->hot = team_members_peer::instance()->get_team(request::get_int('user_id'));
        } elseif (request::get_int('app')) {
            if (session::has_credential('admin')) {
                $applicants = db::get_cols('SELECT DISTINCT(group_id) FROM team_applicants');
            } else {
                load::model('team/applicants');
                $applicants = array();
                $creator = db::get_cols('SELECT id FROM team WHERE user_id=' . session::get_user_id(), array(), null, true);
                $team_artem_negodyai = db::get_cols('SELECT DISTINCT(group_id) FROM team_applicants');
                foreach ($team_artem_negodyai as $id) {
                    if (team_peer::instance()->is_moderator($id, session::get_user_id()) || in_array($id, $creator))
                        $applicants = array_merge($applicants, team_applicants_peer::instance()->get_by_group($id));
                }
            }
            $this->hot = $applicants;
        }

        if (request::get('hot') || request::get('type') || request::get('teritory') || request::get('level') || request::get('category') || request::get_int('status')) {
            $this->hot = team_peer::instance()->get_hot(
                $this->cur_category,
                $this->cur_ptype,
                $this->cur_status,
                $this->cur_region,
                $this->cur_citi
            );
        }

        $all_regions = geo_peer::instance()->get_regions(1);
        $bind = team_peer::instance()->hot_where;

        $sql = str_replace("AND city_id = :city_id", "", team_peer::instance()->hot_sql);
        $sql = str_replace("AND region_id = :region_id", "", $sql);

        foreach ($all_regions as $region_id => $title) {
            $bind['region_id'] = $region_id;
            unset($bind['city_id']);
            $count_users = db::get_scalar('SELECT count(*) FROM team WHERE region_id=:region_id AND ' . $sql, $bind);
            $this->az_regions[] = array('id' => $region_id, 'title' => $title, 'count' => $count_users);
            $this->rate_regions[$count_users . ($region_id)] = array('id' => $region_id, 'title' => $title, 'count' => $count_users);
            ksort($this->rate_regions);
        }

        if (request::get_int('region')) {
            $all_regions = geo_peer::instance()->get_cities(request::get_int('region'));
            $bind['region_id'] = request::get_int('region');
            foreach ($all_regions as $region_id => $title) {
                $bind['city_id'] = $region_id;

                $count_users = db::get_scalar('SELECT count(*) FROM team WHERE city_id=:city_id AND region_id=:region_id AND ' . $sql, $bind);
                $this->az_cities[] = array('id' => $region_id, 'title' => $title, 'count' => $count_users);
                $this->rate_cities[$count_users . ($region_id)] = array('id' => $region_id, 'title' => $title, 'count' => $count_users);
                ksort($this->rate_cities);
            }
        }

        foreach ($this->hot as $teamid) {
            $cntarr[$teamid] = count(team_members_peer::instance()->get_members($teamid, false, team_peer::instance()->get_item($teamid)));
        }
        $this->cntarr = $cntarr;
        if (is_array($cntarr)) {
            arsort($cntarr);
            foreach ($cntarr as $ak => $a)
                $tlist[] = $ak;
        }

        $this->hot = db::get_cols("SELECT id FROM team WHERE id IN (" . implode(", ", (array)$tlist) . ") ORDER BY category DESC");
        $this->count = count($this->hot);

        $this->pager = pager_helper::get_pager($this->hot, request::get_int('page'), 15);
        $this->hot = $this->pager->get_list();
    }
}