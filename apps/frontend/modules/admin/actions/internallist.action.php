<?php
load::app('modules/admin/controller');

class admin_internallist_action extends admin_controller
{
    public function execute()
    {
        load::action_helper('user_email', false);
        load::system('email/email');
        load::model('messages/messages');
        load::model('internal_mailing');
        load::model('groups/groups');
        load::model('lists/lists');
        load::model('geo');

        if (request::get_int('send')) {
            $body_tpl = trim(request::get_string('body'));
            $this->add_to_archive($body_tpl);
        }
        if (request::get('getbody')) {
            $tmp = internal_mailing_peer::instance()->get_item(request::get_int('recId'));
            die($tmp['body']);
        }
        if (request::get_int('del')) {
            internal_mailing_peer::instance()->delete_item(request::get_int('del'));
            die();
        }
        if (1 === request::get_int('get_statistic')) {
            switch (request::get('stat_type')) {
                case 'mode_act_sends':
                    $list = internal_mailing_peer::getactive(0);
                    break;
                case 'mode_user_sends':
                    $list = internal_mailing_peer::getactive(1);
                    break;
            }

            if (count($list)) {
                $echo = '<table id="send_table" style="width: 450px;" >
                        <tbody style="border: 1px solid #cccccc;"><tr>
                        <td>Інформація</td></tr>
                        ';
                foreach ($list as $id => $l) {
                    $finfo = $this->getFilterinfo($l['filters']);

                    $user_info = user_data_peer::instance()->get_item($l['sender_id']);
                    $user_auth = user_auth_peer::instance()->get_item($l['sender_id']);

                    $i = 0;

                    $echo .= '<tr';
                    if ($id > 9) {
                        $echo .= ' class="hidden" ';
                    }
                    $echo .= ' id="mailing'.$l['id'].'"><td valign="top" class="p0"><table style="width: 450px;">
                            <tbody>
                            <tr><td class="td'.($i + 1).'" valign="top">Дата:</td><td class="td'.($i + 1).'" valign="top">'.date(
                            'd.m.Y H:i',
                            $l['date']
                        ).'</td></tr>
                            <tr><td valign="top" width="20%" class="td'.$i.'" width="60">Вiд:</td><td class="td'.$i.'" valign="top">'.$user_info['first_name'].' '.$user_info['last_name'].'</td></tr>
                            <tr><td width="20%" class="td'.($i + 1).'" valign="top">E-mail:</td><td class="td'.($i + 1).'" valign="top">'.$user_auth['email'].'</td></tr>
                            <tr><td class="td'.$i.'" valign="top">Текст повідомлення:</td><td class="td'.$i.'" id="bdy_'.$l['id'].'" valign="top">'.tag_helper::get_short(
                            strip_tags(html_entity_decode($l['body'])),
                            75
                        ).'<a style="display: block; text-align: right;" href="javascript:;" onclick="viewBody(\''.$l['id'].'\');">Переглянути</a></td></tr>
                            <tr><td class="td'.($i + 1).'" valign="top">Фільтр:</td><td class="td'.($i + 1).'" valign="top">'.$finfo[0].'</td></tr>
                            <tr><td class="td'.$i.'" valign="top">Id:</td><td class="td'.$i.'" valign="top">'.$finfo[1].'</td></tr>
                                
                            <tr><td class="td'.($i + 1).'" valign="top">Загальна кількість:</td><td class="td'.($i + 1).'" valign="top">'.$l['count'].'</td></tr>
                            <tr><td class="td'.$i.'" valign="top">Розіслано:</td><td class="td'.$i.'" valign="top">'.$l['sended'].'</td></tr>
                            <tr>
                            <td>
                            
                                </td>
                                <td>
                                <input type="button" class="right button" onClick="javascript:delMailing(\''.$l['id'].'\');" value="Видалити">
                            </td>
                            </tr>';
                    $echo .= '</tbody></table><hr style="width: 450px;">

                            </td>
                            </tr>
                            ';
                }
                $echo .= '</tbody></table>';
                $echo .= '<a href="javascript:;" onClick="$(&quot;#send_table&quot;).find(&quot;tr[class=&apos;hidden&apos;]&quot;).show();" style="display: block;">Показати всі</a>';
                die($echo);
            } else {
                die('Нема.');
            }
        }

    }

    public function add_to_archive($body_tpl)
    {
        $sender_id = session::get_user_id();

        $filter_name   = request::get('filter');
        $filter_values = request::get($filter_name);

        if ('district' === $filter_name) {
            $filter_values = request::get('city');
        }

        if ('common' === $filter_name) {
            $filter_values = [1 => '1'];
        }

        $fid = 0;

        $filters[$fid] = $filter_name.':';
        foreach ($filter_values as $vkey => $vvalue) {
            if (isset($filter_values[$vkey + 1])) {
                $filters[$fid] .= $vvalue.',';
            } else {
                $filters[$fid] .= $vvalue;
            }
        }
        $filters     = implode($filters);
        $insert_data = [
            'sender_id' => $sender_id,
            'filters'   => $filters,
            'body'      => $body_tpl,
        ];
        $options     = [
            '%body%' => $body_tpl,
            '%link%' => 'https://'.context::get('host').'/messages/view?id='.$sender_id,
        ];
        user_email_helper::send_sys('user_internallist', 5968, 5968, $options);

        internal_mailing_peer::instance()->insert($insert_data);
    }

    public function getFilterInfo($filters)
    {

        $f_arr = explode(':', $filters);

        $data        = [];
        $filter_name = internal_mailing_peer::getFilters($f_arr[0]);

        $data[0] = (isset($filter_name)) ? $filter_name : $f_arr[0];

        $fval_arr = explode(',', $f_arr[1]);
        $tmp      = [];

        switch ($f_arr[0]) {
            case 'common':
                $data[1] = 'Усім';
                break;
            case 'group':
                foreach ($fval_arr as $key => $value) {
                    $tmp1  = groups_peer::instance()->get_item($value);
                    $tmp[] = $tmp1['title'];
                }
                $data[1] = implode(',', $tmp);
                break;
            case 'status':
                foreach ($fval_arr as $key => $value) {
                    $tmp[] = user_auth_peer::instance()->get_status($value);
                }
                $data[1] = implode(',', $tmp);

                break;
            case 'func':
                foreach ($fval_arr as $key => $value) {
                    $tmp[] = user_auth_peer::instance()->get_function($value);
                }
                $data[1] = implode(',', $tmp);
                break;
            case 'region':
                foreach ($fval_arr as $key => $value) {
                    $tmp1  = geo_peer::instance()->get_region($value);
                    $tmp[] = $tmp1['name_'.session::get('language')];
                }
                $data[1] = implode(',', $tmp);
                break;
            case 'district':
                foreach ($fval_arr as $key => $value) {
                    $tmp1  = geo_peer::instance()->get_city($value);
                    $tmp[] = $tmp1['name_'.session::get('language')];
                }
                $data[1] = implode(',', $tmp);
                break;
            case 'sferas':
                foreach ($fval_arr as $key => $value) {
                    $tmp[] = user_auth_peer::instance()->get_segment($value);
                }
                $data[1] = implode(',', $tmp);
                break;
            case 'visit':
                $data[1] = $f_arr[1];
                break;
            case 'lists':
                foreach ($fval_arr as $key => $value) {
                    foreach (lists_peer::instance()->get_item($value) as $lkey => $lvalue) {
                        if ($lvalue === $value) {
                            $tmp[] = $lvalue['title'];
                        }
                    }
                }
                $data[1] = implode(',', $tmp);
                break;
            default:
                $data[1] = '?';
                break;

        }

//                  if(isset ($fval_arr[$key+1]))
//                    $data[1] .= $value.",";
//                else
//                    $data[1] .= $value;
        return $data;

    }
}