<? if(request::get_int('city')){ ?>
    <? $current = geo_peer::instance()->get_city(request::get_int('city')) ?>
<? }elseif(request::get_int('region')){ ?>
    <? $current = geo_peer::instance()->get_region(request::get_int('region')) ?>
<? } ?>

<style>
    table.sortable{border:1px solid gray;border-collapse: collapse}
    .sortable th, td.total{text-align:center;vertical-align:middle;font-size:11px;padding:1px 8px;background:#913D3E;color:#fc6;border:1px solid gray;}
    .sortable th a{color:#fc6;}
    .sortable td{text-align:center;vertical-align:middle;font-size:11px;color:black;border:1px solid gray;padding:1px 5px;}
    .sortable th a{text-decoration:underline}
    .sub_menu a{text-decoration:none}
    .sub_menu a.clicked{color:grey}
    .sortable th b {background:url(/static/images/icons/sort.gif) 0 center no-repeat; cursor:pointer; padding-left:10px}
    .sortable .desc, .sortable .asc {background:#6f191C;}
    .sortable .desc b {background:url(/static/images/icons/desc.gif) 0 center no-repeat; cursor:pointer; padding-left:10px}
    .sortable .asc b {background:url(/static/images/icons/asc.gif) 0  center no-repeat; cursor:pointer; padding-left:10px}
    .sortable th a:hover {color:#EAEAEA}
    .sortable .evenrow td {background:#F7F7F7}
    .sortable .oddrow td {background:#F2F2F2}
    .sortable td.evenselected {background:#F2F2F2}
    .sortable td.oddselected {background:#EAEAEA}
</style>

<!--h1 class="column_head mb10 mt10"><?=t('Взносы')?></h1-->
<div class="mt15 fs12">
    <div>
    <? if(request::get_int('region')){ ?>
    <a href="/results/payments">- <?=t('назад к таблице по регионам')?></a>
    <? } ?>
    <? if(request::get_int('region'))$url='&region='.request::get_int('region') ?>
    <? if(request::get_int('city'))$url.='&city='.request::get_int('city') ?>
    <? if(request::get_int('ppo'))$url.='&ppo='.request::get_int('ppo') ?>
    <a class="right mr5 <?=(request::get_int('allyears'))?'bold':''?>" href="/results/payments<?=$url?>&allyears=1">усi роки</a>
    <? for($i=date('Y');$i>=2011;$i--){ ?>
        <a class="right mr5 <?=($i==$year)?'bold':''?>" href="/results/payments<?=$url?>&year=<?=$i?>"><?=$i?></a>
    <? } ?>
    </div>
    <div class="clear"></div>

    <? if(request::get_int('city') && request::get_int('ppo')){ ?>
    <a class="right mr5" href="/results/payments&region=<?=request::get_int('region')?>&city=<?=request::get_int('city')?>"><?=t('Показать людей')?></a>
    <? }elseif(request::get_int('city')){ ?>
    <a class="right mr5" href="/results/payments&region=<?=request::get_int('region')?>&city=<?=request::get_int('city')?>&ppo=1"><?=t('Показать ППО')?></a>
    <? } ?>

    <? if(request::get_int('region') && request::get_int('people')){ ?>
    <a class="right mr5" href="/results/payments&region=<?=request::get_int('region')?>"><?=t('Показать районы/города')?></a>
    <? }elseif(request::get_int('region') && !request::get_int('city')){ ?>
    <a class="right mr5" href="/results/payments&region=<?=request::get_int('region')?>&people=1"><?=t('Показать людей')?></a>
    <? } ?>

    <? if(request::get_int('city')){ ?>
    <a href="/results/payments&region=<?=request::get_int('region')?>">- <?=t('назад к таблице по районам/городам')?></a>
    <? } ?>
</div>

<? if($current){ ?>
<div class="clear"></div>
<div class="fs12 bold acenter">
    <?=$current['name_' . translate::get_lang()]?>
</div>
<? } ?>

<div id="tableholder" class="mt15" style="width:760px;">

<? if(count($list)==0){ ?>

    <div class="screen_message acenter quiet">
        <?=t('Ничего не найдено')?>
    </div>

<? }else{ ?>

<table class="sortable">

<? if(!request::get_int('region')){ ?>
<tr>
    <th rowspan="2" width="200">
        <?=t('Регион')?>
    </th>
    <th rowspan="2">
        <?=t('РПО/МПО/ППО')?>
    </th>
    <th rowspan="2"><?=t('Благотворительные взносы')?></th>
    <th rowspan="2"><?=t('Целевые взносы')?></th>
    <th rowspan="2"><?=t('Вступительные взносы')?></th>
    <? if($year && $year!=2011){ ?>
    <th colspan="4"><?=t('Членские взносы')?></th>
    <? }else{ ?>
    <th colspan="3"><?=t('Членские взносы')?></th>
    <? } ?>
    <th rowspan="2"><?=t('Средний ежемесячный')?></th>
</tr>
<tr>
    <th><?=t('Собрано')?></th>
    <th><?=t('Потрачено')?></th>
    <? if($year && $year!=2011){ ?>
    <th><?=t('Остаток с')?> <?=($year-1)?></th>
    <th><?=t('Общий остаток')?></th>
    <? }else{ ?>
    <th><?=t('Остаток')?></th>
    <? } ?>
</tr>
<? }else{ ?>
<tr>
    <th width="200">
        <? if(request::get_int('city') && request::get_int('ppo')){ ?>
        <?=t('ППО')?>
        <? }elseif(request::get_int('city') || (request::get_int('region') && request::get_int('people'))){ ?>
        <?=t('Ф.И.О.')?>
        <? }else{ ?>
        <?=t('Город/район')?>
        <? } ?>
    </th>
    <? if(!request::get_int('city') && !(request::get_int('region') && request::get_int('people'))){ ?>
    <th>
        <?=t('МПО/ППО')?>
    </th>
    <? } ?>
    <th><?=t('Благотворительные взносы')?></th>
    <th><?=t('Целевые взносы')?></th>
    <th><?=t('Вступительные взносы')?></th>
    <th><?=t('Членские взносы')?></th>
    <th><?=t('Средний ежемесячный')?></th>
</tr>
<? } ?>

<? $num = 0 ?>

<? foreach($list as $k => $v){ ?>

<? //if($k){ ?>

<?
if(request::get_int('city') && request::get_int('ppo'))
{
    $ppo = ppo_peer::instance()->get_item($k);
    $link = '<a target="_blank" href="/ppo'.$k.'/">'.$ppo['title'].'</a>';
}
elseif(request::get_int('city') || (request::get_int('region') && request::get_int('people')))
{
    $user = user_data_peer::instance()->get_item($k);
    $link = '<a target="_blank" class="bold" href="'.user_helper::profile_link($k).'"><span style="text-transform:uppercase">'.$user['last_name'].'</span> '.$user['first_name'].'</a>';
}
elseif(request::get_int('region'))
{
    $mpo = db::get_scalar("SELECT COUNT(*) FROM ppo WHERE active = 1 AND category = 2 AND city_id = ".$k);
    $mpoc +=$mpo;
    $ppo = db::get_scalar("SELECT COUNT(*) FROM ppo WHERE active = 1 AND category = 1 AND city_id = ".$k);
    $ppoc += $ppo;
    $city = geo_peer::instance()->get_city($k);
    $link = '<a href="/results/payments&region='.request::get_int('region').'&city='.$k.'">'.str_replace('район','р-н',$city['name_' . translate::get_lang()]).'</a>';
}
else
{
    $rpo = db::get_scalar("SELECT COUNT(*) FROM ppo WHERE active = 1 AND category = 3 AND city_id != 0 AND region_id = ".$k);
    $rpoc += $rpo;
    $mpo = db::get_scalar("SELECT COUNT(*) FROM ppo WHERE active = 1 AND category = 2 AND city_id != 0 AND region_id = ".$k);
    $mpoc +=$mpo;
    $ppo = db::get_scalar("SELECT COUNT(*) FROM ppo WHERE active = 1 AND category = 1 AND city_id != 0 AND region_id = ".$k);
    $ppoc += $ppo;
    $region = geo_peer::instance()->get_region($k);
    if($k)
        $link = '<a href="/results/payments&region='.$k.'">'.str_replace('область','обл.',$region['name_' . translate::get_lang()]).'</a>';
    else
        $link = 'Без регiону';
}
?>

<tr>
    <td><?=$link?></td>
    <? if(!request::get_int('city') && !(request::get_int('region') && request::get_int('people'))){ ?>
    <td>
        <? if(request::get_int('region')){ ?>
            <?=$mpo.'/'.$ppo?>    
        <? }else{ ?>
            <?=$rpo.'/'.$mpo.'/'.$ppo?>
        <? } ?>
    </td>
    <? } ?>
    <?
    $sum[1] = (is_array($v))?intval($v[3]['sum']):0;
    $sum[2] = (is_array($v))?intval($v[2]['sum']):0;
    $sum[3] = (is_array($v))?intval($v[2]['sum'])+intval($v[3]['sum']):0;
    $sum[4] = intval(db::get_scalar("SELECT SUM(summ) FROM ppo_finance WHERE region_id = $k $period"));
    $sum[5] = (is_array($v))?intval($sum[3])-intval($sum[4]):0;
    $sum[6] = (is_array($v))?intval($v[1]['sum']):0;
    $sum[7] = (is_array($v) && $v[2]['count'])?ceil(intval($v[2]['sum'])/$v[2]['count']):0;
    $sum[8] = (is_array($v))?intval($v[4]['sum']):0;
    if($year && $year!=2011)
    {
        $sum2[2] = (is_array($list2[$k]))?intval($list2[$k][2]['sum']):0;
        $sum2[4] = intval(db::get_scalar("SELECT SUM(summ) FROM ppo_finance WHERE region_id = $k $prevperiod"));
        $sumc2[2] += $sum2[2];
        $sumc2[4] += $sum2[4];
    }
    foreach($sum as $n=>$s)
    {
        $sumc[$n] += $s;
        if($n==7 && $s!=0)
        {
            $num++;
        }
    }
    ?>
    <td><?=$sum[1]?></td>
    <td><?=$sum[8]?></td>
    <td><?=$sum[6]?></td>
    <? if(request::get_int('region')){ ?>
    <td><?=$sum[2]?></td>
    <? }else{ ?>
    <td><?=$sum[2]?></td>
    <td><?=$sum[4]?></td>
    <? if($year && $year!=2011){ ?>
    <td><?=$sum2[2]-$sum2[4]?></td>
    <td><?=$sum[2]-$sum[4]+($sum2[2]-$sum2[4])?></td>
    <? }else{ ?>
    <td><?=$sum[2]-$sum[4]?></td>
    <? } ?>
    <? } ?>
    <td><?=$sum[7]?></td>
</tr>

<? //} ?>

<? } ?>

<tr>
    <td class="total bold" style="color:#fc6;background:#913D3E;"><?=t('Вобщем')?></td>
    <? if(!request::get_int('city') && !(request::get_int('region') && request::get_int('people'))){ ?>
    <td>
        <? if(request::get_int('region')){ ?>
            <?=$mpoc.'/'.$ppoc?>
        <? }else{ ?>
            <?=$rpoc.'/'.$mpoc.'/'.$ppoc?>
        <? } ?>
    </td>
    <? } ?>
    <td><?=$sumc[1]?></td>
    <td><?=$sumc[8]?></td>
    <td><?=$sumc[6]?></td>
    <? if(request::get_int('region')){ ?>
    <td><?=$sumc[2]?></td>
    <? }else{ ?>
    <td><?=$sumc[2]?></td>
    <td><?=$sumc[4]?></td>
    <? if($year && $year!=2011){ ?>
    <td><?=$sumc2[2]-$sumc2[4]?></td>
    <td><?=$sumc[2]-$sumc[4]+($sumc2[2]-$sumc2[4])?></td>
    <? }else{ ?>
    <td><?=$sumc[2]-$sumc[4]?></td>
    <? } ?>
    <? } ?>
    <td><?=($num)?ceil($sumc[7]/$num):0?></td>
</tr>

</table>

<? } ?>

</div>

<? if($pager){ ?>
<div class="right pager mr5 mt10"><?=pager_helper::get_long($pager)?></div>
<? } ?>

<script type="text/javascript">
jQuery(document).ready(function($){

    $('table.sortable tr:even').addClass('evenrow');
    $('table.sortable tr:odd').addClass('oddrow');

});
</script>