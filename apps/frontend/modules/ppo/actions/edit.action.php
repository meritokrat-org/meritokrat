<?php

load::app('modules/ppo/controller');

/**
 * @property array group
 */
class ppo_edit_action extends ppo_controller
{
    protected $authorized_access = true;

    public function execute()
    {
        $this->group = ppo_peer::instance()->get_item(request::get_int('id'));
        if (!ppo_peer::instance()->is_moderator($this->group['id'], session::get_user_id()) && !session::has_credential(
                'designer'
            )) {
            $this->redirect('/');
        }

        client_helper::register_variable('groupId', $this->group['id']);

        $this->moderators = ppo_peer::instance()->get_moderators($this->group['id']);

        if (session::has_credential('admin') && 3 == $this->group['category']) {
            $this->finances = ppo_finance_peer::instance()->get_by_region($this->group['region_id']);
        }

        load::model('ppo/applicants');
        $this->applicants = ppo_applicants_peer::instance()->get_by_group($this->group['id']);


        load::model('user/party_inventory');
        //$this->inv_owners = ppo_members_peer::instance()->get_members($this->group['id'],false,$this->group,false);
        $this->inv_owners     = ppo_peer::instance()->get_all_children($this->group['id']);
        $this->inv_owners[]   = $this->group['id'];
        $this->inventory_type = db::get_cols(
            'SELECT inventory_type FROM party_inventory WHERE ppo_id IN ('.implode(
                ',',
                $this->inv_owners
            ).') GROUP BY inventory_type'
        );

        //if($this->inv_owners)
        //$this->inventory_type = db::get_cols("SELECT inventory_type FROM party_inventory WHERE user_id IN (".implode(",",$this->inv_owners).") GROUP BY inventory_type");

        if (request::get('submit')) {
            $this->set_renderer('ajax');
            $this->json = ['id' => $this->group['id']];

            if ('common' === request::get('type')) {
                #request::get_int('category')==4 ? $hidden=1 : $hidden=0;
                $upp_arr = [
                    'id'          => $this->group['id'],
                    'title'       => htmlspecialchars(request::get_string('title')),
                    'description' => htmlspecialchars(request::get_string('description')),
                    'location'    => htmlspecialchars(request::get_string('location')),
                    'teritory'    => htmlspecialchars(request::get_string('teritory')),
                    'ptype'       => request::get_int('ptype'),
                    'glava_id'    => request::get_int('glavaid'),
                    'secretar_id' => request::get_int('secretarid'),
                    'region_id'   => (int) request::get_int('region'),
                    'city_id'     => (int) request::get_int('city'),
                    'map_lat'     => request::get('map_lat'),
                    'map_lon'     => request::get('map_lon'),
                    'map_zoom'    => (int) request::get('map_zoom'),
                    'coords'      => request::get('coords'),
                    'level'       => request::get_int('level'),
                ];
                if (request::get_int('category') > 0) {
                    $upp_arr['category'] = request::get_int('category');
                }
                ppo_peer::instance()->update($upp_arr);
                parent::update_geo($this->group['id']);
            } else {
                if ('inventory' === request::get('type')) {


//                            PPO INVENTORY
                    $owner_id  = request::get('inventory_owner');
                    $inv_type  = request::get('inventory_type');
                    $inv_count = request::get('inventory_count');
                    $inv_date  = request::get('inventory_date');
                    $inv_id    = request::get('id');
                    $inv_cost  = request::get('inventory_cost');
//                            var_dump(request::get_all());
//                            exit;

                    if ('delete' === request::get('act') && request::get_int('id')) {
                        user_party_inventory_peer::instance()->delete_item(request::get_int('id'));
                        $this->json = ['success' => 1, 'delete' => 'ok'];
                    } else {
                        if (request::get('edit')) {
                            if (count($inv_id) === count($inv_count) && count($inv_count) === count($inv_date)) {
                                foreach ($inv_id as $k => $v) {
                                    if (($new_date = (int) strtotime(
                                            $inv_date[$k]
                                        )) && ($item_update = user_party_inventory_peer::instance()->get_item($v))) {
                                        if ((int) $inv_count[$k] > 0) {
                                            $item_update['date_ts']         = $new_date;
                                            $item_update['inventory_count'] = $inv_count[$k];
                                            $item_update['cost']            = $inv_cost[$k];
                                            //var_dump($item_update);
                                            user_party_inventory_peer::instance()->update($item_update);
                                        } else {
                                            user_party_inventory_peer::instance()->delete_item($inv_id[$k]);
                                        }
                                    }
                                }
                            }
                            $this->json = ['success' => 1, 'edit' => 'ok'];
                        } else {
                            if ($owner_id && $inv_count && $inv_date && inv_type) {
                                $insert_data = [
                                    'user_id'         => $owner_id,
                                    'inventory_type'  => $inv_type,
                                    'inventory_count' => $inv_count,
                                    'cost'            => $inv_cost,
                                    'date_ts'         => (int) strtotime($inv_date),
                                ];

                                $exists_record = user_party_inventory_peer::instance()->get_list(
                                    [
                                        'user_id'        => $owner_id,
                                        'inventory_type' => $inv_type,
                                        'date_ts'        => (int) strtotime($inv_date),
                                    ]
                                );
                                if (!empty($exists_record)) {
                                    $exists_info                    = user_party_inventory_peer::instance()->get_item(
                                        $exists_record[0]
                                    );
                                    $insert_data['inventory_count'] = (int) ($exists_info['inventory_count'] + $inv_count);
                                    $insert_data['id']              = $exists_info['id'];
                                    user_party_inventory_peer::instance()->update($insert_data);
                                } else {
                                    $insert_data['ppo_id'] = $this->get_po($owner_id);
                                    user_party_inventory_peer::instance()->insert($insert_data);
                                }
                                $this->json = ['success' => 1, 'add' => 'ok'];
                            }
                        }
                    }


                } else {
                    if ('more' === request::get('type')) {
                        $scount = db::get_scalar(
                            'SELECT MAX(svidnum) 
                                    FROM ppo 
                                    WHERE active=1'
                        );
                        if (request::get_int('svidnum') <= $scount && $this->group['svidnum'] != request::get_int(
                                'svidnum'
                            )) {
                            $this->json = ['error' => "Вказан невiрний номер свiдоцтва. Максимальний номер на данний час - $scount"];

                            return;
                        }
                        $array = [
                            'id'            => $this->group['id'],
                            'number'        => request::get('number'),
                            'adres'         => htmlspecialchars(request::get_string('adres')),
                            'dzbori'        => (int) strtotime(str_replace('/', '-', request::get('dzbori'))),
                            'duhval'        => (int) strtotime(str_replace('/', '-', request::get('duhval'))),
                            'doviddate'     => (int) strtotime(str_replace('/', '-', request::get('doviddate'))),
                            'vkldate'       => (int) strtotime(str_replace('/', '-', request::get('vkldate'))),
                            'protokolsdate' => request::get_int('protokolsdate'),
                            'dovidsdate'    => request::get_int('dovidsdate'),
                            'svidvig'       => request::get_int('svidvig'),
                            'svidvruch'     => request::get_int('svidvruch'),
                            'svidcom'       => htmlspecialchars(request::get_string('svidcom')),
                            'dovidnum'      => htmlspecialchars(request::get_string('dovidnum')),
                            'uhvalnum'      => htmlspecialchars(request::get_string('uhvalnum')),
                            'svidnum'       => htmlspecialchars(request::get_string('svidnum')),
                            'svidcopy'      => request::get_int('svidcopy'),
                            'zayava'        => request::get_int('zayava'),
                            'vklnumber'     => htmlspecialchars(request::get_string('vklnumber')),
                        ];
                        if ('' == request::get_string('svidnum')) {
                            unset($array['svidnum']);
                        }
                        if (is_array(request::get('members'))) {
                            $array['uchasniki'] = '{'.implode(',', request::get('members')).'}';
                        }
                        $vklnumbers = db::get_cols('SELECT vklnumber FROM ppo');
                        /*if(in_array(htmlspecialchars(request::get_string('vklnumber')), $vklnumbers)) {
                            $this->json = array('error' => "Вказаний номер Рiшення Голови про включення в структуру вже існує");
                            return;
                        }*/
                        ppo_peer::instance()->update($array);
                    } else {
                        if ('leadership' === request::get('type')) {
                            load::model('ppo/members_history');
                            load::action_helper('user_email', false);

                            $options = [
                                '%title%' => $this->group['title'],
                                '%link%'  => 'http://'.context::get(
                                        'host'
                                    ).'/ppo'.$this->group['id'].'/'.$this->group['number'].'/',
                            ];

                            if (request::get_int('function1id') > 0) {
                                $function1id = (int) ppo_members_peer::instance()->get_user_by_function(
                                    1,
                                    $this->group['id']
                                );
                                db::exec(
                                    'UPDATE '.ppo_members_peer::instance()->get_table_name().' SET function=0 
                                WHERE function=1 AND group_id='.$this->group['id']
                                );
                                if ($function1id !== request::get_int('function1id')) {
                                    ppo_members_history_peer::instance()->end_function(
                                        $this->group['id'],
                                        $function1id,
                                        1
                                    );
                                }
                                ppo_members_peer::instance()->set_function(
                                    $this->group['id'],
                                    request::get_int('function1id'),
                                    1
                                );
                                ppo_members_history_peer::instance()->set_function(
                                    $this->group['id'],
                                    request::get_int('function1id'),
                                    1
                                );

                                user_email_helper::send_sys(
                                    'ppo_leadership',
                                    request::get_int('function1id'),
                                    31,
                                    array_merge(
                                        $options,
                                        [
                                            '%posada%'      => t('Главы'),
                                            '%member_name%' => strip_tags(
                                                user_helper::full_name(request::get_int('function1id'))
                                            ),
                                        ]
                                    )
                                );
                            }

                            if (request::get_int('function2id') > 0) {
                                $function2id = (int) ppo_members_peer::instance()->get_user_by_function(
                                    2,
                                    $this->group['id']
                                );
                                db::exec(
                                    'UPDATE '.ppo_members_peer::instance()->get_table_name().' SET function=0 
                                WHERE function=2 AND group_id='.$this->group['id']
                                );
                                if ($function2id !== request::get_int('function2id')) {
                                    ppo_members_history_peer::instance()->end_function(
                                        $this->group['id'],
                                        $function2id,
                                        2
                                    );
                                }
                                ppo_members_peer::instance()->set_function(
                                    $this->group['id'],
                                    request::get_int('function2id'),
                                    2
                                );
                                ppo_members_history_peer::instance()->set_function(
                                    $this->group['id'],
                                    request::get_int('function2id'),
                                    2
                                );

                                user_email_helper::send_sys(
                                    'ppo_leadership',
                                    request::get_int('function2id'),
                                    31,
                                    array_merge(
                                        $options,
                                        [
                                            '%posada%'      => t('Ответственного секретаря'),
                                            '%member_name%' => strip_tags(
                                                user_helper::full_name(request::get_int('function2id'))
                                            ),
                                        ]
                                    )
                                );
                            }

                            if (request::get_int('function5id') > 0) {
                                $function = (int) ppo_members_peer::instance()->get_user_by_function(
                                    ppo_members_peer::ACTING_HEAD,
                                    $this->group['id']
                                );
                                $sql      = sprintf(
                                    'UPDATE %s SET "function" = 0 WHERE "function" = :function AND group_id = :ppoId',
                                    ppo_members_peer::instance()->get_table_name()
                                );
                                db::exec(
                                    $sql,
                                    [
                                        'ppoId'    => $this->group['id'],
                                        'function' => ppo_members_peer::ACTING_HEAD,
                                    ]
                                );
                                if ($function !== request::get_int('function5id')) {
                                    ppo_members_history_peer::instance()->end_function(
                                        $this->group['id'],
                                        $function,
                                        ppo_members_peer::ACTING_HEAD
                                    );
                                }
                                ppo_members_peer::instance()->set_function(
                                    $this->group['id'],
                                    request::get_int('function5id'),
                                    ppo_members_peer::ACTING_HEAD
                                );
                                ppo_members_history_peer::instance()->set_function(
                                    $this->group['id'],
                                    request::get_int('function5id'),
                                    1
                                );

                                user_email_helper::send_sys(
                                    'ppo_leadership',
                                    request::get_int('function5id'),
                                    31,
                                    array_merge(
                                        $options,
                                        [
                                            '%posada%'      => ppo_members_peer::FUNCTIONS[ppo_members_peer::ACTING_HEAD],
                                            '%member_name%' => strip_tags(
                                                user_helper::full_name(request::get_int('function5id'))
                                            ),
                                        ]
                                    )
                                );
                            }

                            if (request::get_int('function6id') > 0) {
                                $function2id = (int) ppo_members_peer::instance()->get_user_by_function(
                                    ppo_members_peer::ACTING_MANAGER,
                                    $this->group['id']
                                );
                                db::exec(
                                    'UPDATE '.ppo_members_peer::instance()->get_table_name().' SET function=0 
                                WHERE function=6 AND group_id='.$this->group['id']
                                );
                                if ($function2id !== request::get_int('function6id')) {
                                    ppo_members_history_peer::instance()->end_function(
                                        $this->group['id'],
                                        $function2id,
                                        ppo_members_peer::ACTING_MANAGER
                                    );
                                }
                                ppo_members_peer::instance()->set_function(
                                    $this->group['id'],
                                    request::get_int('function6id'),
                                    ppo_members_peer::ACTING_MANAGER
                                );
                                ppo_members_history_peer::instance()->set_function(
                                    $this->group['id'],
                                    request::get_int('function6id'),
                                    ppo_members_peer::ACTING_MANAGER
                                );

                                user_email_helper::send_sys(
                                    'ppo_leadership',
                                    request::get_int('function6id'),
                                    31,
                                    array_merge(
                                        $options,
                                        [
                                            '%posada%'      => ppo_members_peer::FUNCTIONS[ppo_members_peer::ACTING_MANAGER],
                                            '%member_name%' => strip_tags(
                                                user_helper::full_name(request::get_int('function6id'))
                                            ),
                                        ]
                                    )
                                );
                            }

                            $managers = $_REQUEST['managers'] ?: [4 => [], 3 => []];
                            array_map(
                                function ($function, $managers) {
                                    db::exec(
                                        sprintf(
                                            'update %s set "function" = 0 where group_id = :ppo and "function" = :function',
                                            ppo_members_peer::instance()->get_table_name()
                                        ),
                                        [
                                            'ppo'      => $this->group['id'],
                                            'function' => $function,
                                        ]
                                    );
                                    foreach ($managers as $manager) {
                                        ppo_members_peer::instance()->upsertMember(
                                            $this->group['id'],
                                            $manager,
                                            $function
                                        );
                                    }
                                },
                                array_keys($managers),
                                array_values($managers)
                            );

                            //if (request::get_int('function3id') > 0) {
                            //    $function3id = (int) ppo_members_peer::instance()->get_user_by_function(3, $this->group['id']);
                            //    db::exec('UPDATE ' . ppo_members_peer::instance()->get_table_name() . ' SET function=0
                            //                WHERE function=3 AND group_id=' . $this->group['id']);
                            //    if ($function3id !== request::get_int('function31id')) {
                            //        ppo_members_history_peer::instance()->end_function($this->group['id'], $function3id, 3);
                            //    }
                            //    ppo_members_peer::instance()->set_function($this->group['id'], request::get_int('function3id'), 3);
                            //    ppo_members_history_peer::instance()->set_function($this->group['id'], request::get_int('function3id'), 3);
                            //
                            //    user_email_helper::send_sys('ppo_leadership', request::get_int('function3id'), 31,
                            //        array_merge($options, ['%posada%'      => t('члена Контрольно-ревизионной комисии'),
                            //                               '%member_name%' => strip_tags(user_helper::full_name(request::get_int('function3id')))]));
                            //}
                            //
                            //$function4id = (array) ppo_members_peer::instance()->get_users_by_function(4, $this->group['id']);
                            //db::exec('UPDATE ' . ppo_members_peer::instance()->get_table_name() . ' SET function=0
                            //                WHERE function=4 AND group_id=' . $this->group['id']);
                            //foreach ($function4id as $f4) {
                            //    if (!in_array($f4, (array) request::get('function4'))) {
                            //        ppo_members_history_peer::instance()->end_function($this->group['id'], $f4, 4);
                            //    }
                            //}
                            //foreach ((array) request::get('function4') as $f) {
                            //    if ($f > 0) {
                            //        ppo_members_peer::instance()->set_function($this->group['id'], $f, 4);
                            //        ppo_members_history_peer::instance()->set_function($this->group['id'], $f, 4);
                            //
                            //        user_email_helper::send_sys('ppo_leadership', $f, 31,
                            //            array_merge($options, ['%posada%'      => t('Члена Совета'),
                            //                                   '%member_name%' => strip_tags(user_helper::full_name($f))]));
                            //    }
                            //}

                            $this->json = ['id' => $this->group['id']];
                        } else {
                            if ('photo' === request::get('type')) {
                                load::system('storage/storage_simple');

                                load::form('group/picture');
                                $form = new group_picture_form();
                                $form->load_from_request();

                                if ($form->is_valid()) {
                                    $storage = new storage_simple();

                                    $salt = ppo_peer::instance()->regenerate_photo_salt($this->group['id']);
                                    $key  = 'ppo/'.$this->group['id'].$salt.'.jpg';
                                    $storage->save_uploaded($key, request::get_file('file'));
                                    $this->json = context::get('image_server').user_helper::ppo_photo_path(
                                            $this->group['id'],
                                            'p',
                                            $salt
                                        );
                                } else {
                                    $this->json = ['errors' => $form->get_errors()];
                                }
                            } else {
                                if ('news' === request::get('type')) {
                                    $this->id = ppo_news_peer::instance()->insert(
                                        [
                                            'group_id'   => $this->group['id'],
                                            'created_ts' => time(),
                                            'text'       => trim(request::get_string('text')),
                                        ]
                                    );
                                } else {
                                    if ('delete_inventory' === request::get('type')) {
                                        $del_id = request::get_int('delete_id');
                                        $item   = user_party_inventory_peer::instance()->get_item($del_id);
                                        if ($item) {
                                            user_party_inventory_peer::instance()->delete_item($del_id);
                                            $count      = db::get_scalar(
                                                'SELECT sum(inventory_count) FROM party_inventory WHERE inventory_type=:itype AND user_id IN ('.implode(
                                                    ',',
                                                    $this->inv_owners
                                                ).')',
                                                ['itype' => $item['inventory_type']]
                                            );
                                            $this->json = ['success' => 1, 'count' => $count];
                                        } else {
                                            $this->json = ['success' => 0, 'error' => 'Record not found'];
                                        }


                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function get_po($user_id)
    {
        $ppo = ppo_peer::instance()->get_user_ppo($user_id);
        if (!$ppo['id']) {
            $ppo = ppo_peer::instance()->get_user_ppo($user_id, 2);
            if (!$ppo['id']) {
                $ppo = ppo_peer::instance()->get_user_ppo($user_id, 3);
                if (!$ppo['id']) {
                    $ppo['id'] = ppo_peer::instance()->get_by_user_data(
                        user_data_peer::instance()->get_item($user_id),
                        2
                    );
                    if (!$ppo['id']) {
                        $ppo['id'] = ppo_peer::instance()->get_by_user_data(
                            user_data_peer::instance()->get_item($user_id),
                            3
                        );
                    }
                }
            }
        }

        return intval($ppo['id']);
    }
}