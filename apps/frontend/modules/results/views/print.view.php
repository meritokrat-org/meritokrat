<? header("Content-Type: text/html; charset=utf-8"); ?>
<html>
<title>Друк статистики</title>
<head>
<style type="text/css">
BODY{FONT-SIZE: 9pt;COLOR: black;FONT-FAMILY: Arial;BACKGROUND-COLOR: white}
table#ppstat {font-size: 12px;border:1px solid black;border-collapse: collapse;}
#ppstat td {padding: 4px 2px 4px 2px;background-color:#FFFFFF;padding: 2px;text-align:center;vertical-align:middle;border:1px solid black;border-collapse: collapse;}
</style>
</head>
<body>

<div id="print"><a href="javascript:viod(0)" onclick="document.getElementById('print').style.display = 'none';print();">ДРУКУВАТИ</a></div>

<? $agitarray = user_helper::get_agimaterials() ?>

<? if ( !$list ) { ?>
<div class="acenter fs11 quiet m10 p10"><?=t('Тут еще никого нет')?>...</div>
<? } else { ?>
<table class="acenter" id="ppstat"  cellspacing="0" cellpadding="0" style="">

<? if(!request::get('view') OR request::get('view')=='peoples'){ ?>
    <tr>
        <td><?=t('ФИО')?></td>
        <? if(!request::get_int('type')){ ?>
        <td><?=t('Тип')?></td>
        <? } ?>
        <td><?=t('Получил')?></td>
        <td><?=t('Передал')?></td>
        <td><?=t('Вручил')?></td>
        <td><?=t('Остаток')?></td>
    </tr>
    <? if(!request::get_int('type')){ ?>
    <tr>
        <td colspan="6" height="5" style="background-color:#EEEEEF"></td>
    </tr>
    <? } ?>
    <? foreach ( $list as $id ) { ?>
    <?
    if(!request::get_int('type'))
        $user_stat = user_agitmaterials_peer::instance()->get_user_by_type($id);
    else
        $user_stat = user_agitmaterials_peer::instance()->get_user($id,request::get_int('type'));
    $user_data = user_data_peer::instance()->get_item($id);
    ?>
        <? if(request::get_int('type')){ ?>
        <tr>
            <td class="fs11">
                <a href="/profile/desktop_edit?id=<?=$id?>&tab=information">
                    <?=user_helper::full_name($id,false,array('class'=>'bold'),false)?>
                </a>
                <br/>
                <? if ( $user_data['country_id'] ) { ?>
                    <? if ( $user_data['region_id'] ) { ?>
                        <? $region = geo_peer::instance()->get_region($user_data['region_id']) ?>
                        <?=$region['name_' . translate::get_lang()]?>
                    <? } ?>
                    <? if ( $user_data['city_id'] ) { ?>
                        <? $city = geo_peer::instance()->get_city($user_data['city_id']) ?>
                        /<?=$city['name_' . translate::get_lang()]?>
                    <? } ?>
                <? } ?>
            </td>
            <td><?=intval($user_stat['receive'])?></td>
            <td><?=intval($user_stat['given'])?></td>
            <td><?=intval($user_stat['presented'])?></td>
            <td>
                <? $left = intval($user_stat['receive']) - intval($user_stat['given']) - intval($user_stat['presented']) ?>
                <?=($left>0)?$left:0?>
            </td>
        </tr>
        <? }else{ ?>
        <? $num = 0 ?>
        <? foreach($user_stat as $stat){ ?>
            <tr>
                <? if(!$num){ ?>
                <td class="fs11" style="vertical-align:middle" rowspan="<?=count($user_stat)?>">
                    <a href="/profile/desktop_edit?id=<?=$id?>&tab=information">
                        <?=user_helper::full_name($id,false,array('class'=>'bold'),false)?>
                    </a>
                    <br/>
                    <? if ( $user_data['country_id'] ) { ?>
                        <? if ( $user_data['region_id'] ) { ?>
                            <? $region = geo_peer::instance()->get_region($user_data['region_id']) ?>
                            <?=$region['name_' . translate::get_lang()]?>
                        <? } ?>
                        <? if ( $user_data['city_id'] ) { ?>
                            <? $city = geo_peer::instance()->get_city($user_data['city_id']) ?>
                            /<?=$city['name_' . translate::get_lang()]?>
                        <? } ?>
                    <? } ?>
                </td>
                <? } ?>
                <td><?=$agitarray[$stat['type']]?></td>
                <td><?=intval($stat['receive'])?></td>
                <td><?=intval($stat['given'])?></td>
                <td><?=intval($stat['presented'])?></td>
                <td>
                    <? $left = intval($stat['receive']) - intval($stat['given']) - intval($stat['presented']) ?>
                    <?=($left>0)?$left:0?>
                </td>
            </tr>
            <? $num++ ?>
        <? } ?>
        <tr>
            <td colspan="6" height="5" style="background-color:#EEEEEF"></td>
        </tr>
    <? }} ?>
<? }elseif(request::get('view')=='regions'){ ?>
    <tr>
        <td><?=t('Регион')?></td>
        <td><?=t('Тип')?></td>
        <td><?=t('Получено')?></td>
        <td><?=t('Передано')?></td>
        <td><?=t('Вручено')?></td>
        <td><?=t('Остаток')?></td>
    </tr>
    <tr>
        <td colspan="6" height="5" style="background-color:#EEEEEF"></td>
    </tr>
    <? foreach ( $list as $k=>$v ) { ?>
        <? if($k){ ?>
        <? $region = geo_peer::instance()->get_region($k) ?>
        <? $num = 0 ?>
        <? foreach($v as $key=>$val){ ?>
            <tr>
                <? if(!$num){ ?>
                <td class="fs11" style="vertical-align:middle" rowspan="<?=count($v)?>"><?=$region['name_' . translate::get_lang()]?></td>
                <? } ?>
                <td><?=$agitarray[$key]?></td>
                <td><?=intval($val['receive'])?></td>
                <td><?=intval($val['given'])?></td>
                <td><?=intval($val['presented'])?></td>
                <td>
                    <? $left = intval($val['receive']) - intval($val['given']) - intval($val['presented']) ?>
                    <?=($left>0)?$left:0?>
                </td>
            </tr>
            <? $num++ ?>
        <? }} ?>
        <tr>
            <td colspan="6" height="5" style="background-color:#EEEEEF"></td>
        </tr>
    <? } ?>
<? }elseif(request::get('view')=='types'){ ?>
    <tr>
        <td><?=t('Тип')?></td>
        <td><?=t('Получено')?></td>
        <td><?=t('Передано')?></td>
        <td><?=t('Вручено')?></td>
        <td><?=t('Остаток')?></td>
    </tr>
    <? foreach ( $list as $k=>$v ) { ?>
    <?
        $user_stat = user_agitmaterials_peer::instance()->get_by_type($k);
    ?>
    <tr>
        <td class="fs11"><?=$v?></td>
        <td><?=intval($user_stat['receive'])?></td>
        <td><?=intval($user_stat['given'])?></td>
        <td><?=intval($user_stat['presented'])?></td>
        <td>
            <? $left = intval($user_stat['receive']) - intval($user_stat['given']) - intval($user_stat['presented']) ?>
            <?=($left>0)?$left:0?>
        </td>
    </tr>
    <? } ?>
<? } ?>
</table>
<? } ?>

</body>
</html>