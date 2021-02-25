<? header("Content-Type: text/html; charset=utf-8");
 $options = array(
                                '%name%' => $data['first_name'],
                                '%father_name%' => $data['father_name'],
                                '%last_name%' => $data['last_name'],
                                '%country%' => geo_peer::instance()->get_country_name(($data['country_id'] ? $data['country_id'] : 1 )),
                                '%region%' => geo_peer::instance()->get_region_name($data['region_id']),
                                '%city%' => geo_peer::instance()->get_city_name($data['city_id']),
                                '%location%' => $data['location'],
                                '%birthday%' => $data['birthday'],
                                '%email%' => $user_auth['email'],
                                '%phone%' => $data['phone'],
                                '%sfera%' => user_auth_peer::get_segment($data['segment']),
                                '%additional_sfera%' => user_auth_peer::get_segment($data['additional_segment']),
                                '%about%' =>  $data['about'],
                                '%why_join%' =>  $data['why_join'],
                                '%can_do%' => user_data_peer::get_can_do($data['can_do']),
                                '%lang%' => session::get('language'),
                                '%link%' => 'http://' . context::get('host') . '/profile-' . $user_auth['id']
                                        );
if($data['can_do']==4)
{
    $options['%can_do%'] = $data['can_do_text'];
}
$template='<p align="left" class="fs12" style="color:black"><b>ПІБ:</b> %last_name% %name% %father_name%<br />
    <b>Місце проживання:</b> %country% / %region% / %city% / %location%<br />
    <b>Дата народження:</b> %birthday%<br />
    <b>Е-пошта:</b> %email%<br />
    <b>Контактний телефон:</b> %phone%<br />
    <b>Сфери діяльності:</b> %sfera%, %additional_sfera%<br />
    <b>Коротко про себе:</b> %about%<br />
    <b>Чому приєднались до мережі "Мерітократ":</b> %why_join%<br />
    <b>Чим Ви можете допомогти Мерiтократичному руху:</b> %can_do%<br />
    <b>Мова:</b> %lang%<br /></p>';
echo str_replace(array_keys($options), $options, $template);
