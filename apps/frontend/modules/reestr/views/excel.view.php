<?
function getnumber($number)
{
    if(!$number)
    {
        return '&mdash;';
    }
    else
    {
        $num = 4 - strlen($number);
        if($num>0)
        {
            for($i=0;$i<$num;$i++)
            {
                $str .= '0';
            }
        }
        return $str.$number;
    }
}
?>
<?
header('Content-Type: text/x-csv; charset=utf-8');
header("Content-Disposition: attachment;filename=".date("d-m-Y")."-export.xls");
header("Content-Transfer-Encoding: binary ");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?=t('Реестр')?></title>
</head>
<body>

<? if(is_array($list) && count($list)>0){ ?>

<table border="1">
<tr>
    <th>Партiйний квиток № (номер пiд ембосування з позолотою)</th>
    <th>ПIБ</th>
    <th>Мiсце проживання (область, район, населений пункт)</th>
    <th>Член партії з</th>
    <th>Дiйсний до</th>
    <th>№ фото</th>
</tr>

<? foreach($list as $id){ ?>

    <?
    $udata = user_data_peer::instance()->get_item($id);
    $membership = user_membership_peer::instance()->get_user($id);
    ?>

    <tr>
        <td><?=getnumber($membership['kvnumber'])?></td>
        <td><?=$udata['last_name'].' '.$udata['first_name'].' '.$udata['father_name']?></td>
        <td>
            <? if($udata['region_id']){ ?>
                <? $region = geo_peer::instance()->get_region($udata['region_id']) ?>
                <? $regionname = $region['name_' . translate::get_lang()] ?>
                <?=(in_array($udata['region_id'],array(2,13))?'м.'.$regionname:str_replace('область','обл.',$regionname))?>
            <? } ?>
            <? if($udata['city_id']){ ?>
                <? $city = geo_peer::instance()->get_city($udata['city_id']) ?>
                <? $cityname = $city['name_' . translate::get_lang()] ?>
                <?=', '.(($udata['city_id']>700)?'м.'.$cityname:str_replace('район','р-н',$cityname))?>
            <? } ?>
            <? if(!in_array($udata['region_id'],array(2,13)) && $udata['city_id']<700 && $udata['location']){ ?>
                <?=', '.stripslashes(htmlspecialchars($udata['location']))?>
            <? } ?>
        </td>
        <td><?=($membership['invdate'])?date('d.m.Y',$membership['invdate']):'&mdash;'?></td>
        <td>31.12.2012</td>
        <td><?=$id?></td>
    </tr>

<? } ?>

</table>

<? } ?>

</body>
</html>