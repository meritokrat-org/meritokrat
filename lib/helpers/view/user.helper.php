<?

load::model('geo');
load::view_helper('tag', true);
load::model('user/user_sessions');

class user_helper
{
    public static function photo(
        $id,
        $size = 'p',
        $options = [],
        $linked = true,
        $folder = 'user',
        $new = '',
        $full_name = false,
        $rating = true
    ) {
        if (isset($options['without-stats']) && true === $options['without-stats']) {
            $without_stats = true;
            unset($options['without-stats']);
        } else {
            $without_stats = false;
        }

        $without_stats = true;

        $sizes = [
            'p' => '200',
            'm' => '160',
            'r' => '90',
            'mm' => '200',
            'mp' => '300',
            'ma' => '160',
            't' => '70',
            's' => '50',
            'sm' => '50',
            'ssm' => '30',
            'f' => '640',
        ];
        $html = tag_helper::image(
            self::photo_path($id, $size, $folder, $new),
            $options,
            context::get('image_server')
        );
        $user_auth = user_auth_peer::instance()->get_item($id);
        if (!user_data_peer::instance()->get_item($id)) {
            $linked = false;
        }

        if ($linked) {
            $data = user_data_peer::instance()->get_item($id);
            $user = user_auth_peer::instance()->get_item($id);
            $title = user_auth_peer::get_status($user['status']).' '.htmlspecialchars(
                    $data['first_name'].' '.$data['last_name']
                );

            //$html = "<a title=\"{$title}\" href=\"http://" . context::get('host') . "/profile-{$data['user_id']}\">{$html}</a>";
            $html = sprintf(
                '<a title="%s" href="http://%s/profile-%s" class="btn btn-link p-0">%s</a>',
                $title,
                context::get('host'),
                $data['user_id'],
                $html
            );
        }

        if ($full_name) {
            $html .= self::full_name($id);
        }

        if ($user_auth['status'] >= 20 && !$without_stats) {
            $sizes = conf::get('image_types');
            $wh = explode("x", $sizes[$size]['size']);
            $style = '';
            // href='/admin/rating?page=".ceil(rating_helper::get_user_rank($id)/50)."'
            if (context::get_controller()->get_module() === "profile" && context::get_controller()->get_action(
                ) === "index") {
                $style = 'width: 100%';
            }

            if ($rating) {
                $html = "<div class='left acenter' style='$style'>".$html."
                                    <div class='rating_info acenter' style='font-size: ".$sizes[$size]['fs']."px; width: ".$wh[0]."px'>
                                        <span>".t('Рейтинг')."</span>&nbsp;".(session::is_authenticated(
                    ) ? "<a href='/admin/rating?page=".ceil(
                            rating_helper::get_user_rank($id) / 50
                        )."'>" : "<b>").((floatval(
                        rating_helper::get_rating_by_id($id)
                    )) ? rating_helper::get_user_rank($id) : " - ").(session::is_authenticated() ? "</a>" : "</b>")."<br/>
                                        <span>".t('Баллы')."</span>&nbsp;".(session::is_authenticated(
                    ) ? "<a href='/profile-".$id."?atab=rating'>" : "<b>").((floatval(
                        rating_helper::get_rating_by_id($id)
                    )) ? number_format(
                        floatval(rating_helper::get_rating_by_id($id)),
                        0,
                        '.',
                        ' '
                    ) : " - ").(session::is_authenticated() ? "</a>" : "</b>").
                    "</div></div>";
            } else {
                $html = sprintf('<div class="left acenter" style="%s">%s</div>', $style, $html);
                //$html = "<div class='left acenter' style='$style'>".$html."</div>";
            }
        }

        return $html;
    }

    public static function photo_path($id, $size = 'p', $folder = 'user', $new = '')
    {
        $data = user_data_peer::instance()->get_item($id);
        if ($data[$new.'photo_salt']) {
            return "{$size}/{$folder}/{$id}{$data[$new.'photo_salt']}.jpg";
        } else {
            return "{$size}/{$folder}/0.jpg";
        }
    }

    public static function get_status($num)
    {
        $arr = self::get_statuses();

        return $arr[$num];
    }

    public static function get_statuses()
    {
        return [
            t('Контакта не было'),
            t('На контроле'),
            t('Контакт завершен'),
            t('Запрос телефона'),
            //t('Контакт передан')
        ];
    }

    public static function full_name(
        $id,
        $linked = true,
        $options = [],
        $online_icon = true,
        $reverse = false,
        $father_name = false
    ) {
        $data = user_data_peer::instance()->get_item($id);

        $full_name = $reverse ? htmlspecialchars(
            stripslashes("{$data['last_name']} {$data['first_name']}")
        ) : htmlspecialchars(stripslashes("{$data['first_name']} {$data['last_name']}"));

        if ($father_name) {
            $full_name = $full_name.' '.$data['father_name'];
        }

        if (!trim($full_name)) {
            $linked = false;
            $full_name = t('Неизвестный пользователь');
        }

        if ($online_icon && user_sessions_peer::instance()->is_online($id)) {
            //$img='<img src="/static/images/common/user_online.png" alt="online" class="left" style="margin-top:2px;margin-left:-2px;" />';
            $img = '<span class="dib icouseronline"></span>';
        }
        if (!$linked) {
            return $full_name;
        }

        //return $img."<a " . tag_helper::get_options_html($options) . " href=\"http://" . context::get('server') . "/profile-{$data['user_id']}\">{$full_name}</a>";
        return "<a ".tag_helper::get_options_html($options)." href=\"http://".context::get(
                'host'
            )."/profile-{$data['user_id']}\">{$img}{$full_name}</a>";
    }

    public static function ppo_photo_path($id, $size = 'p', $photo_salt = '')
    {
        if (!$photo_salt) {
            load::model('ppo/ppo');
            $data = ppo_peer::instance()->get_item($id);
            $photo_salt = $data['photo_salt'];
        }
        if ($photo_salt) {
            return "{$size}/ppo/{$id}{$photo_salt}.jpg";
        } else {
            return "{$size}/{$folder}/0.jpg";
        }
    }

    public static function ppo_photo($path, $options = [])
    {
        $options['id'] = 'photo';

        return tag_helper::image($path, $options, context::get('image_server'));
    }

    public static function reform_photo_path($id, $size = 'p', $photo_salt = '')
    {
        if (!$photo_salt) {
            load::model('reform/reform');
            $data = reform_peer::instance()->get_item($id);
            $photo_salt = $data['photo_salt'];
        }
        if ($photo_salt) {
            return "{$size}/reform/{$id}{$photo_salt}.jpg";
        } else {
            return "{$size}/{$folder}/0.jpg";
        }
    }

    public static function reform_photo($path, $options = [])
    {
        $options['id'] = 'photo';

        return tag_helper::image($path, $options, context::get('image_server'));
    }

    public static function team_photo_path($id, $size = 'p', $photo_salt = '')
    {
        if (!$photo_salt) {
            load::model('team/team');
            $data = team_peer::instance()->get_item($id);
            $photo_salt = $data['photo_salt'];
        }
        if ($photo_salt) {
            return "{$size}/team/{$id}{$photo_salt}.jpg";
        } else {
            return "{$size}/{$folder}/0.jpg";
        }
    }

    public static function team_photo($path, $options = [])
    {
        $options['id'] = 'photo';

        return tag_helper::image($path, $options, context::get('image_server'));
    }

    public static function avtophoto_path($id, $photo_id = '0', $size = 'p')
    {
        $salt = substr(md5($photo_id), 0, 8);

        return "{$size}/avtonumber/{$id}{$salt}.jpg";
    }

    public static function change_photo_from_status($storage, $uid, $user_data, $new_salt)
    {
        $user_key_new = 'user/'.$uid.$new_salt.'.jpg';
        $prof_key_new = 'profile/'.$uid.$new_salt.'.jpg';
        $user_key_old = 'user/'.$uid.$user_data['photo_salt'].'.jpg';
        $prof_key_old = 'profile/'.$uid.$user_data['photo_salt'].'.jpg';

        list(
            $x, $y, $width, $height
            ) =
            explode("-", db_key::i()->get('crop_coord_user_'.$uid));

        if (!is_file($storage->get_path($prof_key_old))) {
            return false;
        }
        $size = getimagesize($storage->get_path($prof_key_old));

        $W = $size[0];
        $H = $size[1];

        if ($W > $H * 0.7777) {
            $width = ceil($H * 0.77777);
            $height = $H;
        } else {
            $width = $W;
            $height = ceil($W * 1.285);
        }

        $x = ceil(($W - $width) / 2);
        $y = ceil(($H - $height) / 2);

        $storage->img_crop($storage->get_path($prof_key_old), $user_key_new, $x, $y, $width, $height);

        $storage->move_from_path($prof_key_new, $storage->get_path($prof_key_old));

        return $user_key_new;
    }

    public static function crop_photo($storage, $user_data, $new = '')
    {
        $selection_x = request::get_int('xcor');
        $selection_y = request::get_int('ycor');
        $selection_h = request::get_int('height');
        $selection_w = request::get_int('width');

        $redis_data = implode('-', [$selection_x, $selection_y, $selection_w, $selection_h]);

        $small_image_w = request::get_int('img_w');
        $small_image_h = request::get_int('img_h');


        $image_types = conf::get('image_types');

        $old_salt = $user_data['new_photo_salt'] ? $user_data['new_photo_salt'] : $user_data['photo_salt'];

        $prof_key_old = 'profile/'.request::get('id').$old_salt.'.jpg';
        $user_key_old = 'user/'.request::get('id').$old_salt.'.jpg';
        if (is_file($storage->get_path($prof_key_old))) {
            $image_real_size = getimagesize($storage->get_path($prof_key_old));
        }

        $real_w = $image_real_size[0];
        $real_h = $image_real_size[1];

        $crop_h = ($real_h * $selection_h) / $small_image_h;
        $crop_w = ($real_w * $selection_w) / $small_image_w;

        $crop_h = ($crop_h >= $real_h) ? $real_h : $crop_h;
        $crop_w = ($crop_w >= $real_w) ? $real_w : $crop_w;

        $crop_x = ($real_w * $selection_x) / $small_image_w;
        $crop_y = ($real_h * $selection_y) / $small_image_h;

        $crop_x = ($crop_x >= ($real_w - $crop_w)) ? ceil($real_w - $crop_w) : $crop_x;
        $crop_y = ($crop_y >= ($real_h - $crop_h)) ? ceil($real_h - $crop_h) : $crop_y;

        $new_salt = user_data_peer::instance()->regenerate_photo_salt(request::get('id'), $new);

        $user_key_new = 'user/'.request::get('id').$new_salt.'.jpg';
        $prof_key_new = 'profile/'.request::get('id').$new_salt.'.jpg';

        #$image_real_size = getimagesize($storage->get_path($prof_key_old));

        if (is_file($storage->get_path($prof_key_old))) {
            $storage->img_crop($storage->get_path($prof_key_old), $user_key_old, $crop_x, $crop_y, $crop_w, $crop_h);
        }

        db_key::i()->set($new.'crop_coord_user_'.request::get('id'), $redis_data);

        $storage->move_from_path($user_key_new, $storage->get_path($user_key_old));

        if (is_file($storage->get_path($prof_key_old))) {
            $storage->move_from_path($prof_key_new, $storage->get_path($prof_key_old));
        }

        $user_auth = user_auth_peer::instance()->get_item(
            (session::has_credential('admin')) ? request::get('id') : session::get_user_id()
        );
        user_helper::photo_watermark(
            $user_key_new,
            $user_auth['status'],
            intval($user_auth['expert'])
        );

        /*foreach ($image_types as $key => $value) {
           if(is_file($storage->get_path($key.'/user/'.  request::get('id').$user_data['photo_salt'].'.jpg')))
               unlink ($storage->get_path($key.'/user/'.  request::get('id').$user_data['photo_salt'].'.jpg'));
          if(is_file($storage->get_path($key.'/profile/'.  request::get('id').$user_data['photo_salt'].'.jpg')))
               unlink ($storage->get_path($key.'/profile/'.  request::get('id').$user_data['photo_salt'].'.jpg'));
       }      */

        return $new_salt;
    }

    public static function photo_watermark($orkey, $status, $expert)
    {
//            $wat_path=conf::get('project_root').'/www/static/images/watemarks/';
//            if(in_array($status, array(5,15,10,20)));
//            load::system('storage/storage_simple');
//            $storage = new storage_simple();
//            $opath=$storage->get_path($orkey);
//            exec("composite -gravity southwest {$wat_path}{$status}.png {$opath} {$opath}");
//            if($expert>0)exec("composite -gravity southeast {$wat_path}expert.png {$opath} {$opath}");


        /*load::system('storage/storage_simple');
        $storage = new storage_simple();
        $opath=$storage->get_path($orkey);

        $size = conf::get('image_types');
    $image_size = getimagesize($opath);


    foreach($size as $k=>$size_params){
if ( !$size_params['exact'] ) $resize_opt = '\>';
    $file_path=$storage->get_path($k."/".$orkey);
    error_log($k."_file_path_".$file_path);
$storage->prepare_path( $file_path );

if ( $size_params['crop'] )
{
    $wat_path = conf::get('project_root') . '/www/static/images/watemarks/';
    if (in_array($status, array(5, 15, 10, 20))) ;
    load::system('storage/storage_simple');
    $storage = new storage_simple();
    $opath = $storage->get_path($orkey);
    exec("composite -gravity southwest {$wat_path}{$status}.png {$opath} {$opath}");
    if ($expert > 0) exec("composite -gravity southeast {$wat_path}expert.png {$opath} {$opath}");
    #die($path);

    /*load::system('storage/storage_simple');
    $storage = new storage_simple();
    $opath=$storage->get_path($orkey);

    $size = conf::get('image_types');
$image_size = getimagesize($opath);


foreach($size as $k=>$size_params){
if ( !$size_params['exact'] ) $resize_opt = '\>';
$file_path=$storage->get_path($k."/".$orkey);
error_log($k."_file_path_".$file_path);
$storage->prepare_path( $file_path );

if ( $size_params['crop'] )
{
$size_details = explode('x', $size_params['size']);

if ( $image_size[0] > $image_size[1] )
{
    $ratio = $image_size[0]/$image_size[1];
    $resizeTo = ceil($size_details[0] * $ratio) . 'x' . $size_details[1];
}
else
{
    $ratio = $image_size[1]/$image_size[0];
    $resizeTo = $size_details[0] . 'x' . ceil($size_details[1] * $ratio);
}

        list($x,$y,$width,$height) = explode("-", db_key::i()->get('crop_coord_user_' . $uid));

$cmd = "convert {$opath} -resize {$resizeTo}{$resize_opt} {$file_path}";
error_log($k."_1_".$cmd);
exec($cmd);
        $cmd="convert {$file_path} -gravity Center -crop {$size_params['size']}+0+0 {$file_path}";
exec($cmd);
        error_log($k."_2_".$cmd);
}
else
{
$cmd = "convert {$opath} -resize {$size_params['size']}{$resize_opt} {$file_path}";
error_log($k."_3_".$cmd);
exec($cmd);

        #$cmd2="composite -gravity southeast {$wat_path}{$status}.png {$file_path} {$file_path}";
        #exec($cmd2);
       # error_log($k."_4_".$cmd2);
}
exec("composite -gravity southwest {$wat_path}{$status}.png {$file_path} {$file_path}");
    }
    #$tm=time();
    #$wtnew=$watermark.$tm;
    #exec("convert -resize 10x10 $watermark $wtnew");
    ///////if($expert>0)exec("composite -gravity southeast {$wat_path}expert.png {$path} {$path}");
    #unlink($wtnew);
     *
     */
    }

    public static function profile_link($id)
    {
        return "http://".context::get('server')."/profile-{$id}";
    }

    public static function login_require($text, $href = '')
    {
        $html = '<div class="mt10 p5 acenter fs12" style="border: 1px solid #E4E4E4; background: #F7F7F7;">
				<a href="'.($href != '' ? $href : "/").'">'.$text.'</a>
			</div>';

        return $html;
    }

    public static function share_item($type, $id, $options = [])
    {
        $options['onclick'] = "Application.shareItem('{$type}', {$id})";
        $options['href'] = "javascript:;";
        $options['class'] = "share ".$options['class'];
        $type == 'group' ? $share = t('Пригласить') : $share = t('Поделиться');
        if ($type == 'group') {
            return "<a ".tag_helper::get_options_html(
                    $options
                )."><span".($type == 'group' ? ' class="fs18"' : '').">".$share."</span></a>";
        } else {
            return "<a ".tag_helper::get_options_html($options)."><b></b><span>".$share."</span></a>";
        }
    }

    public static function bookmark_item($type, $id, $options = [])
    {
        if (bookmarks_peer::instance()->is_bookmarked(session::get_user_id(), $type, $id)) {
            return '';
        }

        $options['onclick'] = "Application.bookmarkItem('{$type}', {$id})";
        $options['href'] = "javascript:;";
        $options['class'] = "bookmark ".$options['class'];

        return "<a ".tag_helper::get_options_html($options)."><b></b><span>".t('В закладки')."</span></a>";
    }

    public static function geo($id, $linked = false)
    {
        $data = user_data_peer::instance()->get_item($id);

        if (!$data['city_id']) {
            return '';
        }

        $city = geo_peer::instance()->get_city($data['city_id']);
        $region = geo_peer::instance()->get_region($city['region_id']);
        if ($linked) {
            return '<a href="/search?submit=1&region_id='.$city['region_id'].'">'.$region['name_'.translate::get_lang(
                )].'</a> / <a href="/search?submit=1&city='.$data['city_id'].'">'.$city['name_'.translate::get_lang(
                )].'</a>';
        } else {
            return $city['name_'.translate::get_lang()].', '.$region['name_'.translate::get_lang()];
        }
    }

    public static function get_func($id)
    {
        load::model('user/user_desktop');
        $data = user_desktop_peer::instance()->get_item($id);
        $user_functions = explode(',', str_replace(['{', '}'], ['', ''], $data['functions']));
        $arr = [];
        foreach (user_auth_peer::get_functions() as $function_id => $function_title) {
            if (in_array($function_id, $user_functions)) {
                $arr[] = $function_title;
            }
        }

        return implode(', ', $arr);
    }

    public static function birthday($id, $numeric = false)
    {
        $data = user_data_peer::instance()->get_item($id);

        $date = explode('-', $data['birthday']);
        if (intval($date[1]) < date("n")) {
            $cur_date = mktime(0, 0, 0, $date[1], $date[2], ($date[0] + 1));
        } else {
            $cur_date = mktime(0, 0, 0, $date[1], $date[2], $date[0]);
        }
        $cur_time = mktime(0, 0, 0, date("m"), date("d"), $date[0]);
        $range = $cur_date - $cur_time;

        if ($range < 86400) {
            return (!$numeric) ? t('Сегодня') : 0;
        } elseif ($range < 172800) {
            return (!$numeric) ? t('Завтра') : 1;
        } elseif ($range < 259200) {
            return (!$numeric) ? t('Послезавтра') : 2;
        } else {
            return (!$numeric) ? (t('Через').' '.ceil($range / 86400).' '.self::get_day_name(
                    ceil($range / 86400)
                )) : ceil($range / 86400);
        }
    }

    private static function get_day_name($num)
    {
        if ($num > 4) {
            return t('дней');
        } else {
            return t('дня');
        }
    }

    public static function get_inviters($id, $type, $obj, $date = false)
    {
        $result = invites_peer::instance()->get_by_user($id, $type, $obj);
        if (count($result) == 0) {
            return;
        }
        foreach ($result as $r) {
            $add = '';
            if ($date) {
                $add = ' ('.date("d.m", $r['created_ts']).')';
            }
            $arr[] = strip_tags(self::full_name($r['from_id'], false, [], false), '<a>').$add;
        }

        return implode(', ', $arr);
    }

    public static function com_date($date)
    {
        $months = [
            'ru' => [
                '',
                'января',
                'февраля',
                'марта',
                'апреля',
                'мая',
                'июня',
                'июля',
                'августа',
                'сентября',
                'октября',
                'ноября',
                'декабря',
            ],
            'uk' => [
                '',
                'січня',
                'лютого',
                'березня',
                'квітня',
                'травня',
                'червня',
                'липня',
                'серпня',
                'вересня',
                'жовтня',
                'листопада',
                'грудня',
            ],
        ];
        session::get('language') != 'ru' ? $lang = 'uk' : $lang = 'ru';
        $month = $months[$lang][date('n', $date)];
        if (date('jmY', $date) == date('jmY')) {
            return t('Сегодня').' '.date('H:i', $date);
        } elseif (date('jmY', $date) == date('jmY', (time() - 86400))) {
            return t('Вчера').' '.date('H:i', $date);
        } else {
            return date('j', $date).' '.$month.' '.date('Y', $date);
        }//.' '.date('H:i',$date);
    }

    public static function get_contact_type($num)
    {
        $arr = self::get_contact_types();

        return $arr[$num];
    }

    public static function get_contact_types()
    {
        return [
            '&mdash;',
            t('Встреча'),
            t('Звонок'),
            t('Письмо'),
            t('Сообщение в меритократе'),
        ];
    }

    public static function get_who($num)
    {
        $arr = self::get_whos();

        return $arr[$num];
    }

    public static function get_whos()
    {
        return [
            '&mdash;',
            t('Шевченко'),
            t('Руководитель'),
            t('Координатор'),
            t('Организатор'),
        ];
    }

    public static function get_targets()
    {
        return [
            1 => t('Cтудент'),
            2 => t('Учитель'),
            3 => t('Преподаватель'),
            4 => t('Ученый'),
            5 => t('Врач'),
            6 => t('Другой медицинский работник'),
            7 => t('Работник органов местного самоуправления'),
            8 => t('На государственной службе'),
            9 => t('На государственной выборной должности'),
            17 => t('Топ-менеджер'),
            18 => t('Руководитель среднего звена'),
            19 => t('Офисный работник'),
            11 => t('Военный'),
            10 => t('Пенсионер'),
            12 => t('Военный пенсионер'),
            13 => t('Крестьянин'),
            14 => t('Рабочий'),
            15 => t('Работник сферы услуг'),
            16 => t('Профессионал'),
            20 => t('Предприниматель'),
            21 => t('Журналист'),
            22 => t('Редактор СМИ'),
            23 => t('Ведущий на ТВ'),
            24 => t('Эксперт'),
        ];
    }

    public static function get_links($text, $clear = true)
    {
        $server = urlencode(conf::get('server'));

        if ($clear) {
            $text = stripslashes(nl2br(htmlspecialchars($text)));
        }
//            else
//                $text= preg_replace('#<a(?:.*)href="(.*)"(?:.*)>(.*)</a>#Uise', "'<a onclick=\"Application.checkLink(this);return false;\" href=\"https://meritokrat.org/ooops/leave?href='.urlencode('$1').'\" rel=\"$1\">$2</a>'", $text);

        $text = preg_replace(
            "/(^|[\n ])([\w]*?)((ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is",
            "'$1$2<a onclick=\"Application.checkLink(this);return false;\" href=\"https://meritokrat.org/ooops/leave?href='.urlencode('$3').'\" rel=\"$3\">$3</a>'",
            $text
        );
        $text = preg_replace(
            "/(^|[\n ])([\w]*?)((www|ftp)\.[^ \,\"\t\n\r<]*)/is",
            "'$1$2<a onclick=\"Application.checkLink(this);return false;\" href=\"https://meritokrat.org/ooops/leave?href='.urlencode('$3').'\" rel=\"http://$3\">$3</a>'",
            $text
        );
        $text = preg_replace(
            "/(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+)+)/i",
            "$1<a href=\"mailto:$2@$3\">$2@$3</a>",
            $text
        );
        $text = preg_replace(
            "/(^|[\n ])([\w\-]+\.(com|net|ru|ua|org|gov|mobi|info)+)/i",
            "'$1<a onclick=\"Application.checkLink(this);return false;\" href=\"https://meritokrat.org/ooops/leave?href='.urlencode('$2').'\" rel=\"http://$2\">$2</a>'",
            $text
        );

        //$text= preg_replace("/(^|[\n ])([\w]*?)((ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a onclick=\"Application.checkLink(this);return false;\" href=\"$3\" rel=\"$3\">$3</a>", $text);
        //$text= preg_replace("/(^|[\n ])([\w]*?)((www|ftp)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a onclick=\"Application.checkLink(this);return false;\" href=\"$3\" rel=\"http://$3\">$3</a>", $text);
        //$text= preg_replace("/(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+)+)/i", "$1<a href=\"mailto:$2@$3\">$2@$3</a>", $text);
        //$text= preg_replace("/(^|[\n ])([\w\-]+\.(com|net|ru|ua|org|gov|mobi|info)+)/i", "$1<a onclick=\"Application.checkLink(this);return false;\" href=\"$2\" rel=\"http://$2\">$2</a>", $text);

        return ($text);
    }

    public static function get_guest_access()
    {
        return [
            t('Никому'),
            t('Только друзьям'),
            t('Друзьям и друзьям друзей'),
            t('Всем зарегистрированным'),
            t('Всем'),
        ];
    }

    public static function get_profile_access()
    {
        return [
            0 => t('показывать').' '.t('всем'),
            1 => t('показывать только зарегистрированным'),
            //			10 => t('показывать') . ' ' . t('только') . ' ' . t('меритократам'),
            20 => t('показывать').' '.t('только').' '.t('членам МПУ'),
        ];
    }

    public static function get_payment_access()
    {
        return [
            1 => t('показывать зарегистрированным'),
            20 => t('показывать').' '.t('членам МПУ'),
            99 => t('не показывать никому'),
        ];
    }

    public static function get_agimaterials($key = false)
    {
        $arr = [
            //'1'=>t('Брошуры'),
            //'2'=>t('Листовки'),
            '3' => t('Буклет "МПУ" (рус)'),
            '4' => t('Буклет "МПУ" (укр)'),
            '5' => t('Брошура "Партия позитивных перемен" (рус)'),
            '6' => t('Брошура "Партiя позитивних змiн" (укр)'),
            '7' => t('Буклет').' "Меритократия" (рус)',
            '8' => t('Буклет').' "'.t('Меритократія').'" (укр)',
            '9' => t('Сборка текстов').' "'.t('Меритократия').'" (рус)',
            '10' => t('Сборка текстов').' "'.t('Меритократия').'" (укр)',
            '11' => t('Информ. визитки'),
            '12' => 'Газета №0',
            '13' => t('Листовка "День Рождения партии"'),
        ];
        if ($key) {
            return $arr[$key];
        } else {
            return $arr;
        }
    }

    public static function get_years($begin = 0, $end = false)
    {
        if (!intval($begin)) {
            $begin = 1940;
        }
        if (!$end) {
            $end = date("Y");
        }
        for ($i = $begin; $i <= $end; $i++) {
            $years[$i] = $i;
        }

        //$years = array_reverse($years,true);
        return $years;
    }

    public static function get_months($month = false)
    {
        $months = [
            'ru' => [
                '&mdash;',
                'январь',
                'февраль',
                'март',
                'апрель',
                'май',
                'июнь',
                'июль',
                'август',
                'сентябрь',
                'октябрь',
                'ноябрь',
                'декабрь',
            ],
            'uk' => [
                '&mdash;',
                'січень',
                'лютий',
                'березень',
                'квітень',
                'травень',
                'червень',
                'липень',
                'серпень',
                'вересень',
                'жовтень',
                'листопад',
                'грудень',
            ],
        ];
        session::get('language') != 'ru' ? $lang = 'uk' : $lang = 'ru';
        if ($month === false) {
            return $months[$lang];
        } else {
            return $months[$lang][$month];
        }
    }

    public static function get_payment_types()
    {
        return [
            [1 => t('наличными'), 2 => t('безналично')],
            [1 => t('через ППО'), 2 => t('в Центральный Секретариат')],
            [1 => t('через кассу банка'), 2 => t('он-лайн')],
        ];
    }

    public static function convert_distance($data, $ret_false = false)
    {
        $distance = round($data, 2);
        if ($distance == 0 && $ret_false) {
            return false;
        }
        if ($distance < 1) {
            return ($distance * 1000)." м";
        } else {
            return round($distance)." км";
        }
    }

    public static function get_political_post($key = false)
    {
        $arr = [
            '0' => '&mdash;',
            '1' => t('Председатель местного совета'),
            '2' => t('Депутат местного совета'),
            '3' => t('Народный депутат'),
            '4' => t('Президент'),
        ];
        if ($key !== false) {
            return $arr[$key];
        } else {
            return $arr;
        }
    }

    public static function get_political_rank($key = false)
    {
        $arr = [
            '0' => t('Рядовой'),
            '1' => t('Руководитель'),
            '2' => t('Функционер'),
        ];
        if ($key !== false) {
            return $arr[$key];
        } else {
            return $arr;
        }
    }

    public static function get_political_vibor($key = false)
    {
        $arr = [
            '0' => t('Местные (в местные советы)'),
            '1' => t('Местные (на должность руководителя)'),
            '2' => t('Парламентские'),
            '3' => t('Президентские'),
        ];
        if ($key !== false) {
            return $arr[$key];
        } else {
            return $arr;
        }
    }

    public static function get_political_status($key = false)
    {
        $arr = [
            '0' => t('Кандидат'),
            '1' => t('Доверенное лицо'),
            '2' => t('Наблюдатель'),
            '3' => t('Член ТВК'),
            '4' => t('Член ДВК'),
        ];
        if ($key !== false) {
            return $arr[$key];
        } else {
            return $arr;
        }
    }

    public static function createlurl($cur)
    {
        $urarray = ['region', 'ptype', 'status', 'category', 'city'];
        $urarray = array_diff($urarray, [$cur]);
        foreach ($urarray as $ur) {
            if (request::get($ur)) {
                $url .= sprintf('&%s=%s', $ur, request::get($ur));
            }
        }

        return $url;
    }

    public static function get_adds($title, $short = '', $text = '')
    {
        $short = trim(strip_tags($short));
        if ($short == '') {
            $text = trim(strip_tags($text));
            $length = mb_strlen($text, 'UTF-8');
            if ($length < 200) {
                $short = $text;
            } else {
                $short = mb_substr($text, 0, mb_strpos($text, ' ', 150, 'UTF-8'), 'UTF-8').'...';
            }
        }
        if (db_key::i()->get(
            'public_'.context::get_controller()->get_module().'_'.context::get_controller()->get_action(
            ).'_'.request::get_int('id')
        )) {
            return '
                <div class="addthis_toolbox addthis_default_style" addthis:title="'.htmlspecialchars(
                    $title
                ).'" addthis:description="'.htmlspecialchars($short).'" addthis:screenshot="https://meritokrat.org/static/images/logos/logo.png"><a class="addthis_button_facebook"></a>
                <a class="addthis_button_twitter"></a>
                <a class="addthis_button_vk"></a>
                <a class="addthis_button_livejournal"></a>
                <a class="addthis_button_mymailru"></a>
                <a class="addthis_button_odnoklassniki_ru"></a>

                <a class="addthis_button_compact"></a>
                <a class="addthis_counter addthis_bubble_style"></a>
                
                <a href="javascript:void(0)" onclick="doprint()" class="ml10"><img alt="print" src="/static/images/icons/print.gif"  style="margin-top:-3px" /></a>
                </div>
                <script type="text/javascript">
                    var addthis_config = {
                        data_track_clickback:true,
                        services_exclude:"print"
                    };
                </script>
                <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4e2974db61bc66ae"></script>
                ';
        } else {
            return '<a onclick="doprint();" href="javascript:viod(0)"><img src="/static/images/icons/print.gif" alt="print">'.t(
                    "напечатать"
                ).'</a>';
        }
    }

    public static function datefields(
        $name = '',
        $date = 0,
        $multiple = false,
        $options = [],
        $empty = false,
        $minYear = 1990
    ) {
        if ($empty) {
            $days[0] = "&mdash;";
            $months[0] = "&mdash;";
            $years[0] = "&mdash;";
        } else {
            if (!$date) {
                $date = time();
            }
        }
        $curday = ($date) ? date("j", $date) : 0;
        $curmonth = ($date) ? date("n", $date) : 0;
        $curyear = ($date) ? date("Y", $date) : 0;
        for ($d = 1; $d <= date("t", $date); $d++) {
            $days[$d] = $d;
        }
        $months = [
            1 => t('января'),
            2 => t('февраля'),
            3 => t('марта'),
            4 => t('апреля'),
            5 => t('мая'),
            6 => t('июня'),
            7 => t('июля'),
            8 => t('августа'),
            9 => t('сентября'),
            10 => t('октября'),
            11 => t('ноября'),
            12 => t('декабря'),
        ];

        $years = range(date('Y'), $minYear);
        $years = array_combine($years, $years);

        if ($multiple) {
            $multi = '[]';
        }

        return sprintf('<div style="width:220px">%s</div>', implode('', [
            tag_helper::select(
                "{$name}_day{$multi}",
                $days,
                array_merge(
                    $options,
                    [
                        'id' => "{$name}_day",
                        'class' => 'datefield',
                        'value' => $curday,
                    ]
                )
            ),
            tag_helper::select(
                "{$name}_month{$multi}",
                $months,
                array_merge(
                    $options,
                    [
                        'id' => "{$name}_month",
                        'class' => 'datefield',
                        'value' => $curmonth,
                        'onclick' => 'Calendar.checkdate(this)',
                    ]
                )
            ),
            tag_helper::select(
                "{$name}_year{$multi}",
                $years,
                array_merge(
                    $options,
                    [
                        'id' => "{$name}_year",
                        'class' => 'datefield',
                        'value' => $curyear,
                        'onclick' => 'Calendar.checkdate(this)',
                    ]
                )
            ),
        ]));
    }

    public static function dateval($field = '', $multiple = false)
    {
        if ($multiple) {
            $days = request::get($field.'_day');
            $months = request::get($field.'_month');
            $years = request::get($field.'_year');
            if (is_array($days) && is_array($months) && is_array($years)) {
                foreach ($days as $k => $v) {
                    $arr[$k] = mktime(0, 0, 0, $months[$k], $days[$k], $years[$k]);
                }

                return $arr;
            } else {
                return 0;
            }
        } else {
            return mktime(
                0,
                0,
                0,
                request::get_int($field.'_month'),
                request::get_int($field.'_day'),
                request::get_int($field.'_year')
            );
        }
    }

    public static function get_program_types($key = false)
    {
        $arr = [
            32 => 'Мерітократія',
            38 => 'Соціал-лібералізм',
            39 => "Сінгапур",
            40 => "Грузія",
            33 => 'Правопорядок',
            1 => 'Правосуддя',
            2 => 'Подолання корупції',
            3 => 'Державне управління',
            4 => 'Податкова система',
            34 => 'Економіка',
            5 => 'Підприємництво',
            35 => 'Промисловість',
            36 => 'Сільське господарство',
            6 => 'Вибори',
            7 => 'Місцеве самоврядування',
            8 => 'Державні фінанси',
            9 => 'Фінансова система',
            10 => 'Міжнародні відносини',
            11 => 'Соціальна політика',
            12 => 'Охорона здоров’я',
            37 => 'Медицина',
            13 => 'Освіта',
            14 => 'Екологія',
            15 => 'Інвестиції',
            16 => 'Наука та інновації',
            17 => 'Енергетика та енергозбереження',
            18 => 'Земля та природні ресурси',
            19 => 'ЖКГ та благоустрій',
            20 => 'Політична діяльність',
            21 => 'Армія',
            22 => 'Адміністративно-територіальний устрій',
            23 => 'Самоорганізація',
            24 => 'Суспільна мораль',
            25 => 'Культура',
            26 => 'Спорт',
            27 => 'ІТ та комунікації',
            28 => 'ЗМІ і реклама',
            29 => 'Релігія',
            30 => 'Мови',
            31 => 'Туризм',
        ];

        if ($key !== false) {
            return $arr[$key];
        } else {
            return $arr;
        }
    }

    public static function get_target_groups($key = false)
    {
        $arr = [
            1 => 'Середній клас',
            2 => 'Дрібні та середні підприємці',
            3 => 'Селяни',
            4 => 'Працівники бюджетної сфери',
            5 => 'Військовослужбовці',
            6 => 'Журналісти',
            7 => 'Молодь',
            8 => 'Студенти',
            9 => 'Науковці',
            10 => 'Пенсіонери',
            11 => 'Малозабезпечені',
        ];

        if ($key !== false) {
            return $arr[$key];
        } else {
            return $arr;
        }
    }
}
