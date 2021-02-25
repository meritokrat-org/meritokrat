<?php

/**
 * @property array list
 */
class people_index_action extends frontend_controller
{
    #protected $authorized_access = true;
    public function execute()
    {
        load::model('user/user_desktop');
        load::model('user/user_sessions');

        if (request::get_bool('sort')) {
            $__success  = true;
            $__priority = 1;

            user_auth_peer::instance()->clear_sort();

            if (is_array(request::get('list')) && count(request::get('list')) > 0) {
                foreach (request::get('list') as $__id) {
                    $__data = [
                        'id'       => $__id,
                        'priority' => $__priority,
                    ];

                    user_auth_peer::instance()->update($__data);
                    $__user = user_auth_peer::instance()->get_item($__id);

                    if ($__user['priority'] != $__priority) {
                        $__success = false;
                    }

                    $__priority++;
                }
            }

            echo json_encode($__success);

            $this->disable_layout();
            $this->set_renderer(null);

            return;
        }

        $this->sortable_list = user_auth_peer::instance()->get_sortable_list();

        if (request::get('bookmark')) {
            load::model('bookmarks/bookmarks');
        }

        $this->cur_type       = request::get_int('type', -1);
        $this->cur_status     = request::get_int('status', -1);
        $this->identification = request::get_string('identification');

        if (request::get('online')) {
            $this->list = user_sessions_peer::instance()->who_online();
        }
        if (request::get('offline')) {
            if ('all' === request::get('offline')) {
                $this->list = db::get_cols('SELECT id FROM user_auth WHERE offline > 0 AND del=0');
            } else if ((int) request::get('offline') > 0) {
                $this->list = db::get_cols(
                    'SELECT id FROM user_auth WHERE offline = ' . request::get('offline') . ' AND del=0'
                );
            }
        } else if (request::get('del')) {
            $this->list = db::get_cols('SELECT id FROM user_auth WHERE del != 0 ORDER BY del_ts DESC');
        } else if (1 === request::get_int('famous')) {
            $this->list = user_auth_peer::instance()->get_famous_people();
        } else if (1 === request::get_int('suslik')) {
            $this->list = user_auth_peer::instance()->get_suslik_people();
        } else if (1 === request::get_int('expert')) {
            $this->list = db::get_cols("SELECT id FROM user_auth WHERE expert != '0' AND expert != ''");
        } else if (1 === request::get_int('meritokrat')) {
            $this->list = db::get_cols(
                'SELECT id FROM user_auth WHERE (status >= 10 OR ban >= 10) AND del=0 AND active = TRUE'
            );
        } else if (request::get_int('function')) {
            $this->list = db::get_cols(
                'SELECT user_id FROM user_desktop WHERE functions && :function ORDER BY user_id ASC',
                ['function' => '{' . request::get_int('function') . '}']
            );
        } else if (request::get_int('target')) {
            $this->list = db::get_cols(
                'SELECT user_id FROM user_data WHERE target && :target',
                ['target' => '{' . request::get_int('target') . '}']
            );
        } else if (request::get_int('admintarget') && session::has_credential('admin')) {
            $this->list = db::get_cols(
                'SELECT user_id FROM user_data WHERE admin_target && :admintarget',
                ['admintarget' => '{' . request::get_int('admintarget') . '}']
            );
        } else if (request::get_int('list')) {
            $this->list = db::get_cols(
                'SELECT user_id FROM lists2users WHERE list_id=:list_id AND type=0',
                ['list_id' => request::get_int('list')]
            );
            if (request::get_int('print')) {
                load::model('user/user_desktop');
                load::model('user/user_novasys_data');
                load::model('user/user_contact');
                load::model('geo');
                $this->set_layout('');
                $this->set_template('print');
            }
        } else if ('check' === $this->identification) {
            $this->list = user_auth_peer::instance()->get_by_identification();
        } else if (request::get_int('status') && request::get_int('region')) {
            $this->list = db::get_cols(
                'SELECT user_id FROM user_data WHERE region_id = ' . request::get_int(
                    'region'
                ) . ' AND user_id IN (SELECT id FROM user_auth WHERE status = ' . request::get_int(
                    'status'
                ) . ' AND active=true)'
            );
        } else if (request::get_int('region')) {
            $this->list = db::get_cols(
                'SELECT user_id FROM user_data WHERE region_id = :region_id',
                ['region_id' => request::get_int('region')]
            );

            $this->region_coordinators = user_desktop_peer::instance()->get_regional_coordinators(
                request::get_int('region')
            );

            $raions_ar = geo_peer::instance()->get_cities(
                request::get_int('region')
            );

            foreach ($raions_ar as $rid => $k) {
                $raions[] = $rid;
            }

            $this->raion_coordinators = db::get_cols(
                'SELECT user_id FROM user_desktop_funct
                        WHERE function_id=6 AND city_id IN (' . implode(',', $raions) . ')'
            );

            $this->logistic_coordinators = db::get_cols(
                'SELECT user_id FROM user_desktop_funct
                        WHERE function_id=18 AND city_id IN (' . implode(',', $raions) . ')'
            );

            $this->all_coordinators = [];
            $raion_c                = array_diff(
                $this->raion_coordinators,
                $this->region_coordinators
            );
            $logis_c                = array_diff(
                $this->logistic_coordinators,
                $raion_c,
                $this->region_coordinators
            );
            $this->all_coordinators = array_merge(
                $this->region_coordinators,
                $raion_c,
                $logis_c
            );
            $this->all_coordinators = array_unique(
                $this->all_coordinators
            );
        } else if (request::get_int('activate')) {
            $status = request::get_int('status', null);

            if(!$status) {
                $this->redirect('/people');
            }

            $this->list = db::get_cols(
                // 'SELECT id FROM user_auth WHERE activated_ts IS NOT NULL ORDER BY activated_ts DESC'
                'SELECT id FROM user_auth WHERE status = :status order by id desc',
                ['status' => $status]
            );
        } else if (array_key_exists('filter', $_REQUEST)) {
            $filter = $_REQUEST['filter'];
            if (array_key_exists('ppo', $filter)) {
                $ppo = $filter['ppo'];

                if (array_key_exists('function', $ppo)) {
                    $sql = <<<PGSQL
select pm.user_id
from ppo_members pm
right join ppo p on p.id = pm.group_id
where pm.function = :function;
PGSQL;

                    $this->list = db::get_cols($sql, [
                        'function' => $ppo['function'],
                    ]);
                }
            }
        } else {
            $order = 'a.priority ASC';
            if (request::get_int('activate')) {
                $order = 'a.activated_ts DESC';
            }

            $this->list = user_auth_peer::instance()->get_by_status(
                $this->cur_status,
                $order,
                0,
                request::get('chronology', 'old_to_recent')
            );
        }

        load::action_helper('pager');
        $this->total = count($this->list);

        $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 10);
        $this->list  = $this->pager->get_list();

        load::model('parties/members');
        load::model('parties/parties');

        $this->selected_menu = '/people';

        load::model('lists/lists');
        load::model('lists/lists_users');
        $this->own_lists  = lists_peer::instance()->own_lists(session::get_user_id());
        $this->edit_lists = lists_users_peer::instance()->get_lists_by_user(session::get_user_id(), 2);
        $this->view_lists = lists_users_peer::instance()->get_lists_by_user(session::get_user_id(), 1);
        $all              = array_merge($this->edit_lists, $this->view_lists);
        $all              = array_diff($all, $this->own_lists);
        $this->lists      = array_merge($this->own_lists, $all);
        if (!session::has_credential('admin')) {
            $susliki    = db::get_cols(
                'SELECT id FROM user_auth WHERE suslik=1 AND offline!=:uid',
                ['uid' => session::get_user_id()]
            );
            $this->list = array_diff($this->list, $susliki);
        }
        $my                = user_data_peer::instance()->get_item(session::get_user_id());
        $this->locationlat = $my['locationlat'];
        $this->locationlng = $my['locationlng'];

        client_helper::set_title(t('Люди') . ' | ' . conf::get('project_name'));
    }
}
