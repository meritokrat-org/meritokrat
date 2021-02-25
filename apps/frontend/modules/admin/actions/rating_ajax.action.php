<?php
load::app('modules/admin/controller');

class admin_rating_ajax_action extends admin_controller
{

    public function execute()
    {

        $this->disable_layout();
        $this->set_renderer('ajax');

        $id     = request::get_int('id');
        $action = request::get('act');
        $type   = request::get('type');

        switch ($action) {
            case 'get_select':
                switch ($type) {
                    case 'cost':
                        $cost_data  = user_rating_cost_peer::instance()->get_item($id);
                        $this->json = ($cost_data) ? array_merge(['success' => 1], $cost_data) : [
                            'success' => 0,
                            'reason'  => 'Не корректный идентификатор',
                            'data'    => ['id' => $id],
                        ];
                        break;
                    case 'region_cost':
                        load::model('user/user_rating_cost');
                        $region_cost_data = user_rating2region_peer::instance()->get_item($id);
                        $this->json       = ($region_cost_data) ? array_merge(
                            ['success' => 1],
                            $region_cost_data
                        ) : ['success' => 0, 'reason' => 'Не корректный идентификатор', 'data' => ['id' => $id]];
                        break;
                    default :
                        $this->json = [
                            'success' => 0,
                            'reason'  => 'Не корректные входящие параметры',
                            'data'    => ['id' => $id],
                        ];
                        break;
                }

                break;
            case 'save_cost':
                $name_ru = request::get('cost_name_ru');
                $name_ua = request::get('cost_name_ua');

                $value = request::get('cost');
                $value = str_replace(',', '.', $value);
                $value = (float) $value;

                $cost_data = user_rating_cost_peer::instance()->get_item($id);

                if ($value && $cost_data && ($name_ru || $name_ua)) {
                    $cost_data['cost']    = $value;
                    $cost_data['name_ru'] = $name_ru;
                    $cost_data['name_ua'] = $name_ua;
                    user_rating_cost_peer::instance()->update($cost_data);
                    $this->json = ['success' => 1, 'data' => $cost_data];
                } else {
                    $this->json = [
                        'success' => 0,
                        'reason'  => 'Не корректні вхідні параметри',
                        'data'    => ['name' => $name, 'cost' => $i],
                    ];
                }
                break;
            case 'save_region_cost':
                $name  = request::get('region_cost_name');
                $value = request::get('region_cost');
                $value = str_replace(',', '.', $value);
                $value = (float) $value;

                $cost_data = user_rating2region_peer::instance()->get_item($id);

                if ($value && $name && $cost_data) {
                    $old_ratio         = $cost_data['rate'];
                    $cost_data['rate'] = $value;
                    $cost_data['name'] = $name;
                    user_rating2region_peer::instance()->update($cost_data);
                    db::exec(
                        'UPDATE user_rating SET regional_ratio=:new_rr WHERE regional_ratio=:old_rr',
                        ['new_rr' => $value, 'old_rr' => $old_ratio]
                    );
                    $this->json = ['success' => 1, 'data' => $cost_data];
                } else {
                    $this->json = [
                        'success' => 0,
                        'reason'  => 'Не корректні вхідні параметри',
                        'data'    => ['name' => $name, 'cost' => $i],
                    ];
                }

                break;
            case 'add_points':
                $count     = request::get('points_count');
                $reason    = request::get('add_reason');
                $count     = floatval($count);
                $user_auth = user_auth_peer::instance()->get_item($id);

                if ($count && $reason && $user_auth) {
                    if ($user_auth['status'] >= 20) {
                        $insert_data = [
                            'user_id'    => $id,
                            'points'     => $count,
                            'reason'     => $reason,
                            'created_ts' => time(),
                            'from'       => session::get_user_id(),
                        ];
                        $newId       = user_rating_admin_points_peer::instance()->insert($insert_data);
                        if ($rating = rating_helper::get_rating_by_id($id)) {
                            $upd_arr = ['id' => $id, 'rating' => (floatval($rating) + floatval($count))];
                            user_rating_peer::instance()->update($upd_arr);
                        }
                        $this->json = ['success' => 1, 'data' => $insert_data];
                    } else {
                        $this->json = [
                            'success' => 0,
                            'reason'  => 'Користувач не є членом партії',
                            'data'    => ['name' => $id],
                        ];
                    }
                } else {
                    $this->json = [
                        'success' => 0,
                        'reason'  => 'Не корректні вхідні параметри',
                        'data'    => ['name' => $id],
                    ];
                }
                break;
            case 'get_user_points':
                if (!$id) {
                    $next      = $prev = true;
                    $per_page  = 5;
                    $page      = request::get_int('p') ? request::get_int('p') : 1;
                    $offset    = $per_page * ($page - 1);
                    $list      = user_rating_admin_points_peer::instance()->get_list(
                        [],
                        [],
                        [],
                        $per_page.' OFFSET '.$offset
                    );
                    $next_item = user_rating_admin_points_peer::instance()->get_list(
                        [],
                        [],
                        [],
                        '1 OFFSET '.($offset + $per_page)
                    );
                    if (empty($next_item)) {
                        $next = false;
                    }
                    if ($page === 1) {
                        $prev = false;
                    }
                } else {
                    $next = $prev = false;
                    $list = user_rating_admin_points_peer::instance()->get_list(['user_id' => $id]);
                }

                if (!empty($list)) {
                    foreach ($list as $k => $v) {
                        $points_data[$k]               = user_rating_admin_points_peer::instance()->get_item($v);
                        $points_data[$k]['from']       = user_helper::full_name($points_data[$k]['from']);
                        $points_data[$k]['to']         = user_helper::full_name($points_data[$k]['user_id']);
                        $points_data[$k]['created_ts'] = date('d-m-Y H:i:s', $points_data[$k]['created_ts']);
                    }

                    $this->json = [
                        'success' => 1,
                        'data'    => $points_data,
                        'next'    => $next,
                        'prev'    => $prev,
                        'page'    => $page,
                    ];
                } else {
                    $this->json = ['success' => 0, 'reason' => 'Інформації не знайдено'];
                }
                break;
            case 'delete_admin_points':
                $item = user_rating_admin_points_peer::instance()->get_item($id);
                if ($item) {
                    user_rating_admin_points_peer::instance()->delete_item($id);

                    $rating = rating_helper::get_rating_by_id($item['user_id']);
                    if ($rating) {
                        $upd_arr = [
                            'id'     => $item['user_id'],
                            'rating' => (floatval($rating) - floatval($item['points'])),
                        ];
                        user_rating_peer::instance()->update($upd_arr);
                    }
                    $this->json = ['success' => 1];
                } else {
                    $this->json = ['success' => 0, 'reason' => 'Не корректний ідентифікатор'];
                }

                break;
            case 'get_invited_list':
                $this->set_renderer('html');
                $filter = [];
                $status = request::get_int('status', null);

                if (null !== $status) {
                    $filter['status'] = $status;
                }

                $this->list = user_auth_peer::getFilteredInvitationsOfUser(request::get_int('inviter'), $filter);
                break;

            default :
                $debug = request::get_all();
                unset ($debug['module']);
                unset ($debug['action']);
                $this->json = ['success' => 0, 'reason' => 'Не корректные входящие параметры', 'data' => $debug];
                break;
        }
    }
}

?>
