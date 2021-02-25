<?php

class rating_helper
{

    protected $access = false;


    public static function init()
    {

        load::model('user/user_desktop');
        load::model('user/user_rating');
        load::model('user/user_rating_cost');
        load::model('user/user_rating2region');
        load::model('user/user_desktop_event');
        load::model('user/user_rating_admin_points');
        load::model('user/user_desktop_signature_fact');

    }

    public static function update_regional_ratio($uid)
    {
        $user_rating = db::get_row("SELECT id,regional_ratio FROM user_rating WHERE id=:uid", ['uid' => $uid]);
        if ($user_rating) {
            $user_rating['regional_ratio'] = self::get_user_region_rate($uid);
            user_rating_peer::instance()->update($user_rating);
        }
    }

    public static function get_user_region_rate($uid)
    {
        load::model('ppo/members');
        load::model('ppo/ppo');
        $ppo_ids = ppo_members_peer::instance()->get_ppo($uid);
        if (!empty($ppo_ids)) {
            $ppo_data         = ppo_peer::instance()->get_item($ppo_ids[0]);
            $region_ratio     = self::get_region_ratio();
            $regional_centers = self::get_regional_centers();
            $city             = geo_peer::instance()->get_city($ppo_data['city_id']);
            if (
                ($ppo_data['city_id'] >= 700 && !in_array($city['name_ru'], $regional_centers)) ||
                ($ppo_data['city_id'] >= 700 && !in_array($city['region_name_ru'], $regional_centers))
            ) {
                $alias = 'obl_city';
            }
            if ($ppo_data['city_id'] < 700) {
                $alias = 'district';
            }
            if (in_array($city['name_ru'], $regional_centers) || in_array($city['region_name_ru'], $regional_centers)) {
                $alias = 'obl_center';
            }
            if ($ppo_data['region_id'] == 13) {
                $alias = 'kiev';
            }
        } else {
            $alias = 'no_ppo';
        }

        return floatval(self::get_region_ratio($alias));

    }

    public static function get_region_ratio($alias = false)
    {


        if ($alias) {
            return db::get_scalar("SELECT rate FROM user_rating2region_ratio WHERE alias=:alias", ['alias' => $alias]);
        } else {
            $list = user_rating2region_peer::instance()->get_list();
            if (!empty($list)) {
                foreach ($list as $k => $v) {
                    $data[] = user_rating2region_peer::instance()->get_item($v);
                }

                return $data;
            } else {
                return false;
            }
        }
    }

    public static function get_regional_centers()
    {
        return [
            "Симферополь",
            "Винница",
            "Луцк",
            "Днепропетровск",
            "Донецк",
            "Житомир",
            "Ужгород",
            "Запорожье",
            "Ивано-Франковск",
            "Кировоград",
            "Луганск",
            "Львов",
            "Николаев",
            "Одесса",
            "Полтава",
            "Ровно",
            "Севастополь",
            "Сумы",
            "Тернополь",
            "Харьков",
            "Херсон",
            "Хмельницкий",
            "Черкассы",
            "Чернигов",
            "Черновцы",
        ];
    }

    public static function get_user_rank($uid)
    {
        $rank = false;
        $data = db::get_rows("SELECT id, rating FROM user_rating ORDER BY rating DESC");
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                if ($v['id'] == $uid) {
                    return intval(($k + 1));
                }
            }
        }

        return false;
    }

    public static function updateRating($uid, $action, $params = [])
    {

        $user_desktop = user_desktop_peer::instance()->get_item($uid);
        $user_data    = user_data_peer::instance()->get_item($uid);
        $user_auth    = user_auth_peer::instance()->get_item($uid);
//        var_dump($uid);
//        var_dump($action);
//        var_dump($user_auth);
//        var_dump($user_data);
//        var_dump($user_desktop);
//        var_dump(self::has_access($uid));
//
//        exit;

        $upd_data = false;
        if ($user_auth && $user_data && $user_desktop && (self::has_access($uid) || $action == 'status'))
            switch ($action) {
                case 'avatarm':
                    $avatarm = unserialize($user_desktop['avatarm']);
                    if ($avatarm) {
                        foreach ($avatarm as $k => $v) {
                            if (!$v) {
                                unset($avatarm[$k]);
                            }
                        }
                        $avatarmRat = count($avatarm);
                    } else {
                        $avatarmRat = 0;
                    }
                    $upd_data = [
                        'id'    => $uid,
                        $action => $avatarmRat,
                    ];
                    break;
                case 'autonumber':
                    $photo_data = unserialize($user_desktop['information_avtonumbers_photos']);
                    if ($photo_data) {
                        foreach ($photo_data as $k => $v) {
                            if (!$v) {
                                unset($photo_data[$k]);
                            }
                            if (!intval($v['type'])) {
                                $autonumbers[] = $v;
                            }
                        }
                        $autonumbersRat = count($autonumbers);
                    } else {
                        $autonumbersRat = 0;
                    }
                    $upd_data = [
                        'id'    => $uid,
                        $action => $autonumbersRat,
                    ];
                    break;
                case 'magnet':
                    $photo_data = unserialize($user_desktop['information_avtonumbers_photos']);
                    if ($photo_data) {
                        foreach ($photo_data as $k => $v) {
                            if ($v) {
                                unset($photo_data[$k]);
                            }
                            if (intval($v['type'])) {
                                $autonumbers[] = $v;
                            }
                        }
                        $autonumbersRat = count($autonumbers);
                    } else {
                        $autonumbersRat = 0;
                    }
                    $upd_data = [
                        'id'    => $uid,
                        $action => $autonumbersRat,
                    ];
                    break;
                case 'banners':
                    $banners   = unserialize($user_desktop['information_banners']);
                    $bannerRat = 0;
                    if (is_array($banners) && count($banners) > 0) {
                        $bannerRat = count($banners);
                    }
                    $upd_data = [
                        'id'    => $uid,
                        $action => $bannerRat,
                    ];
                    break;
                case 'publications':
                    $publicationsData = unserialize($user_desktop['information_publications']);
                    $publications     = 0;
                    if (is_array($publicationsData) && count($publicationsData) > 0) {
                        $publications = count($publicationsData);
                    }
                    $upd_data = [
                        'id'    => $uid,
                        $action => $publications,
                    ];
                    break;
                case 'signatures':
                    $data['signatures'] = db::get_scalar(
                        "SELECT SUM(fact) FROM user_desktop_signatures_fact WHERE user_id=:uid",
                        ['uid' => $uid]
                    );
                    if (!$data['signatures']) {
                        $data['signatures'] = 0;
                    }
                    $upd_data = [
                        'id'    => $uid,
                        $action => $data['signatures'],
                    ];
                    break;
                case 'speach':
                    $speach_members = db::get_scalar(
                        "SELECT sum(member_count) FROM user_desktop_event WHERE user_id=:uid AND (part=2 OR part=0)",
                        ['uid' => $uid]
                    );
                    if (!$speach_members) {
                        $speach_members = 0;
                    }
                    $upd_data = [
                        'id'    => $uid,
                        $action => $speach_members,
                    ];
                    break;
                case 'tent':
                    $tent_data     = unserialize($user_desktop['information_tent']);
                    $hours_in_tent = 0;
                    if ($tent_data) {
                        foreach ($tent_data as $k => $v) {
                            $hours_in_tent += intval($v['hours']);
                        }
                    }
                    $upd_data = [
                        'id'    => $uid,
                        $action => $hours_in_tent,
                    ];
                    break;
                case 'inet':
                    $inet_data     = unserialize($user_desktop['information_inet']);
                    $hours_in_inet = 0;
                    if ($inet_data) {
                        foreach ($inet_data as $k => $v) {
                            $hours_in_inet += intval($v['hours']);
                        }
                    }
                    $upd_data = [
                        'id'    => $uid,
                        $action => $hours_in_inet,
                    ];
                    break;
                case 'status':
                    $guests      = db::get_scalar(
                        "SELECT count(id) FROM user_auth WHERE (((invited_by=:invBy OR recomended_by=:invBy) AND active=true) OR (offline=:invBy)) AND status<5",
                        ['invBy' => $uid]
                    );
                    $supporters  = db::get_scalar(
                        "SELECT count(id) FROM user_auth WHERE (((invited_by=:invBy OR recomended_by=:invBy) AND active=true) OR (offline=:invBy)) AND status>=5 AND status<10",
                        ['invBy' => $uid]
                    );
                    $meritokrats = db::get_scalar(
                        "SELECT count(id) FROM user_auth WHERE (((invited_by=:invBy OR recomended_by=:invBy) AND active=true) OR (offline=:invBy)) AND status>=10 AND status<20",
                        ['invBy' => $uid]
                    );
                    $mpu_members = db::get_scalar(
                        "SELECT count(id) FROM user_auth WHERE (((invited_by=:invBy OR recomended_by=:invBy) AND active=true) OR (offline=:invBy)) AND status>=20",
                        ['invBy' => $uid]
                    );

                    $upd_data = [
                        'id'          => $uid,
                        'supporters'  => $supporters,
                        'guest'       => $guests,
                        'meritokrats' => $meritokrats,
                        'mpu_members' => $mpu_members,
                    ];
                    break;
                case 'charitable':
                    $charitable = db::get_scalar(
                        "SELECT SUM(summ) FROM user_payments WHERE type=3 AND user_id=:uid AND del=0 AND approve!=0",
                        ['uid' => $uid]
                    );
                    $upd_data   = [
                        'id'    => $uid,
                        $action => $charitable,
                    ];
                    break;
                case 'membership':
                    $membership = db::get_scalar(
                        "SELECT SUM(summ) FROM user_payments WHERE type=1 AND user_id=:uid AND del=0 AND approve!=0",
                        ['uid' => $uid]
                    );
                    $upd_data   = [
                        'id'    => $uid,
                        $action => $membership,
                    ];
                    break;
                case 'regular':
                    $regular  = db::get_scalar(
                        "SELECT SUM(summ) FROM user_payments WHERE type=2 AND user_id=:uid AND del=0 AND approve!=0",
                        ['uid' => $uid]
                    );
                    $upd_data = [
                        'id'    => $uid,
                        $action => $regular,
                    ];
                    break;
                default:
                    break;
            }
        $Id = user_rating_peer::instance()->get_item($uid);
        if ($upd_data) {
            if (!empty($Id)) {
                user_rating_peer::instance()->update($upd_data);
            } else {
                $newId = user_rating_peer::instance()->insert($upd_data);
            }

            $fullRat = self::calculate_by_user($uid);
            user_rating_peer::instance()->update(['id' => $uid, 'rating' => $fullRat['full_rating']]);
        }


    }

    public static function has_access($uid)
    {
        $uAuth = user_auth_peer::instance()->get_item($uid);
        if ($uAuth['status'] >= 20 || session::has_credential('admin')) {
            return true;
        } else {
            return false;
        }
    }

    public static function calculate_by_user($uid)
    {
        $ratingData    = user_rating_peer::instance()->get_item($uid);
        $costs         = self::get_costs();
        $ratio         = self::get_region_ratio();
        $regional_rate = self::get_user_region_rate($uid);

        if (!$ratingData || !$costs) {
            return false;
        }
        $fullRating = 0;
        foreach ($ratingData as $key => $item) {
            if ($cost = self::get_cost_from_array($costs, $key)) {
                $ratingData[$key] = intval($ratingData[$key]) * (floatval($cost)) * $regional_rate;
                $fullRating       += $ratingData[$key];
            }
        }

        return array_merge($ratingData, ['full_rating' => $fullRating, 'regional_ratio' => $regional_rate]);

    }

    public static function get_costs($alias = false)
    {
        if ($alias) {
            return db::get_scalar('SELECT cost FROM user_rating_cost WHERE alias = :alias', ['alias' => $alias]);
        }

        $list = user_rating_cost_peer::instance()->get_list([
            'is_available' => true,
            'is_enabled' => true,
        ]);
        if ($list) {
            sort($list);
            foreach ($list as $k => $v) {
                $data[] = user_rating_cost_peer::instance()->get_item($v);
            }

            return $data;
        }

        return false;

    }

    public static function get_cost_from_array($costs, $alias)
    {
        if ($costs) {
            foreach ($costs as $k => $v) {
                if ($alias == $v['alias']) {
                    return $v['cost'];
                }
            }
        }

        return false;
    }

    public static function calculate_by_all_users($user = false)
    {

        $list         = db::get_rows(
            "SELECT * FROM user_rating u, user_auth a WHERE u.id=a.id AND (a.status=20 OR a.ban=20)"
        );
        $admin_points = db::get_rows("SELECT * FROM user_rating_admin_points");

        $costs = self::get_costs();
        $ratio = self::get_region_ratio();

        if ($user) {
            $list = db::get_rows("SELECT * FROM user_rating WHERE id=:uid", ['uid' => $user]);
        }
        foreach ($list as $i => $ratingData) {
            if (!$ratingData || !$costs) {
                continue;
            }
            $regional_rate = $ratingData['regional_ratio'];
            $fullRating    = 0;
            foreach ($ratingData as $key => $item) {
                if ($cost = self::get_cost_from_array($costs, $key)) {
                    $retArr[$ratingData['id']][$key] = intval($ratingData[$key]) * (floatval($cost)) * floatval(
                            $regional_rate
                        );
                    $fullRating                      += $retArr[$ratingData['id']][$key];
                }
            }
            $added_points              = self::get_admin_points_from_array($admin_points, $ratingData['id'], true);
            $retArr[$ratingData['id']] = array_merge(
                $retArr[$ratingData['id']],
                [
                    'added_points'   => $added_points,
                    'regional_ratio' => floatval($regional_rate),
                    'full_rating'    => ($fullRating + (floatval($added_points))),
                ]
            );
        }

        return $retArr;

    }

    public static function get_admin_points_from_array($points, $uid, $sum = false)
    {

        if (!empty($points) && $points) {
            if (!$sum) {
                $return_array = [];
                foreach ($points as $k => $v) {
                    if ($v['user_id'] == $uid) {
                        $return_array[] = $v;
                    }
                }
            } else {
                $return_array = 0;
                foreach ($points as $k => $v) {
                    if ($v['user_id'] == $uid) {
                        $return_array += $v['points'];
                    }
                }
            }

            return $return_array;
        } else {
            return false;
        }
    }

    public static function get_rating_by_id($id)
    {
        return db::get_scalar("SELECT rating FROM user_rating WHERE id=:id", ['id' => $id]);
    }

    public static function get_admin_points($uid, $sum = false)
    {
        if (!$sum) {
            $data = db::get_rows("SELECT * FROM user_rating_admin_points WHERE user_id=:uid", ['uid' => $uid]);

            return (empty($data)) ? false : $data;
        } else {
            $data = db::get_scalar(
                "SELECT SUM(points) FROM user_rating_admin_points WHERE user_id=:uid",
                ['uid' => $uid]
            );

            return (!$data) ? false : $data;
        }
    }

    public static function calculateUserRating($uid)
    {
        $user_desktop = user_desktop_peer::instance()->get_item($uid);
        $user_data    = user_data_peer::instance()->get_item($uid);
        $user_auth    = user_auth_peer::instance()->get_item($uid);
        if ($user_auth && $user_data && $user_desktop) {
            /////////////////////AVATAR WITH @M@
            $avatarm = unserialize($user_desktop['avatarm']);
            if ($avatarm) {
                foreach ($avatarm as $k => $v) {
                    if (!$v) {
                        unset($avatarm[$k]);
                    }
                }
                $data['avatarmRat'] = count($avatarm);
            } else {
                $data['avatarmRat'] = 0;
            }

            ////////////////////AUTONUMBERS
            $photo_data  = unserialize($user_desktop['information_avtonumbers_photos']);
            $autonumbers = [];
            if ($photo_data) {
                foreach ($photo_data as $k => $v) {
                    if (!$v) {
                        unset($photo_data[$k]);
                    }
                    if (intval($v['type']) == 0) {
                        $autonumbers[] = $v;
                    }
                }
                $data['autonumbersRat'] = count($autonumbers);
            } else {
                $data['autonumbersRat'] = 0;
            }


            ///////////////////MAGNETS
            $img_data = unserialize($user_desktop['information_avtonumbers_photos']);
            $magnets  = [];
            if ($img_data) {
                foreach ($img_data as $k => $v) {
                    if ($v) {
                        unset($img_data[$k]);
                    }
                    if (intval($v['type']) == 1) {
                        $magnets[] = $v;
                    }
                }
                $data['magnetsRat'] = count($magnets);
            } else {
                $data['magnetsRat'] = 0;
            }
            ///////////////////BANNERS

            $banners           = unserialize($user_desktop['information_banners']);
            $data['bannerRat'] = 0;
            if (is_array($banners) && count($banners) > 0) {
                $data['bannerRat'] = count($banners);
            }

            //////////////////PUBLICATIONS
            $publicationsData     = unserialize($user_desktop['information_publications']);
            $data['publications'] = 0;
            if (is_array($publicationsData) && count($publicationsData) > 0) {
                $data['publications'] = count($publicationsData);
            }


            //////////////////SPEACHES
            $data['speach_members'] = db::get_scalar(
                "SELECT sum(member_count) FROM user_desktop_event WHERE user_id=:uid AND (part=2 OR part=0)",
                ['uid' => $uid]
            );
            if (!$data['speach_members']) {
                $data['speach_members'] = 0;
            }

            ///////////////////TENT AGITATION
            $tent_data     = unserialize($user_desktop['information_tent']);
            $hours_in_tent = 0;
            if ($tent_data) {
                foreach ($tent_data as $k => $v) {
                    $data['hours_in_tent'] += intval($v['hours']);
                }
            }

            ////////////////INVITED
            $data['guests']      = db::get_scalar(
                "SELECT count(id) FROM user_auth WHERE ((invited_by=:invBy OR recomended_by=:invBy) AND active=true) OR (offline=:invBy) AND status<5",
                ['invBy' => $uid]
            );
            $data['supporters']  = db::get_scalar(
                "SELECT count(id) FROM user_auth WHERE (((invited_by=:invBy OR recomended_by=:invBy) AND active=true) OR (offline=:invBy))  AND status>=5 AND status<10",
                ['invBy' => $uid]
            );
            $data['meritokrats'] = db::get_scalar(
                "SELECT count(id) FROM user_auth WHERE (((invited_by=:invBy OR recomended_by=:invBy) AND active=true) OR (offline=:invBy)) AND status>=10 AND status<20",
                ['invBy' => $uid]
            );
            $data['mpu_members'] = db::get_scalar(
                "SELECT count(id) FROM user_auth WHERE (((invited_by=:invBy OR recomended_by=:invBy) AND active=true) OR (offline=:invBy)) AND status>=20",
                ['invBy' => $uid]
            );


            //////////////////PAYMENTS
            $data['mem'] = db::get_scalar(
                "SELECT SUM(summ) FROM user_payments WHERE type=1 AND user_id=:uid AND del=0 AND approve!=0",
                ['uid' => $uid]
            );
            $data['reg'] = db::get_scalar(
                "SELECT SUM(summ) FROM user_payments WHERE type=2 AND user_id=:uid AND del=0 AND approve!=0",
                ['uid' => $uid]
            );
            $data['cha'] = db::get_scalar(
                "SELECT SUM(summ) FROM user_payments WHERE type=3 AND user_id=:uid AND del=0 AND approve!=0",
                ['uid' => $uid]
            );


            ///////////////////GET POINTS
            $data['point_count'] = db::get_scalar(
                "SELECT SUM(points) FROM user_rating_admin_points WHERE user_id=:uid",
                ['uid' => $uid]
            );


            $insert_data = [
                'id'           => $uid,
                'avatarm'      => $data['avatarmRat'] ? $data['avatarmRat'] : 0,
                'autonumber'   => $data['autonumbersRat'] ? $data['autonumbersRat'] : 0,
                'magnet'       => $data['magentRat'] ? $data['magentRat'] : 0,
                'banners'      => $data['bannerRat'] ? $data['bannerRat'] : 0,
                'publications' => $data['publications'] ? $data['publications'] : 0,
                'speach'       => $data['speach_members'] ? $data['speach_members'] : 0,
                'tent'         => $data['hours_in_tent'] ? $data['hours_in_tent'] : 0,
                'guest'        => $data['guests'] ? $data['guests'] : 0,
                'supporters'   => $data['supporters'] ? $data['supporters'] : 0,
                'meritokrats'  => $data['meritokrats'] ? $data['meritokrats'] : 0,
                'mpu_members'  => $data['mpu_members'] ? $data['mpu_members'] : 0,
                'charitable'   => $data['cha'] ? $data['cha'] : 0,
                'membership'   => $data['mem'] ? $data['mem'] : 0,
                'regular'      => $data['reg'] ? $data['reg'] : 0,
            ];

            $insId = user_rating_peer::instance()->get_item($uid);
            if ($insId) {
//                        echo "<h1>------------UPDATING---------------</h1>";
//                        var_dump($insert_data);
//                        exit;
                user_rating_peer::instance()->update($insert_data);
            } else {
//                        echo "<h1>------------INSERTING---------------</h1>";
//                        var_dump($insert_data);
//                        exit;
                $newId = user_rating_peer::instance()->insert($insert_data);
            }

            $full = self::calculate_by_user($uid);
            if ($full) {
                user_rating_peer::instance()->update(
                    [
                        'id'             => $uid,
                        'rating'         => (floatval($full['full_rating']) + floatval($data['point_count'])),
                        'regional_ratio' => $full['regional_ratio'],
                    ]
                );
            }
        }

    }
}

?>
