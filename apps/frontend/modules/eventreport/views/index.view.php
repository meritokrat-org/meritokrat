<? if($has_access){ ?>
<? include 'partials/sub_menu.php' ?>
<? } ?>
<style>
    table.sortable{border:1px solid gray;border-collapse: collapse}
    th{text-align:center;vertical-align:middle;font-size:11px;padding:1px 10px;background:#913D3E;color:#fc6;border:1px solid gray;padding:5px}
    th a{color:#fc6;}
    .sortable td{text-align:center;vertical-align:middle;font-size:11px;color:black;border:1px solid gray;padding:5px}
    th a{text-decoration:underline}
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
<div class="left" style="width:750px">
	<h1 class="column_head mb10 <?=($has_access)?'':'mt10'?>">
            <?=t('Отчеты')?>
	</h1>

        <table class="sortable">
        <tr>
            <th><?=t('Название')?></th>
            <th><?=t('Организатор')?></th>
            <th><?=t('Дата проведения')?></th>
            <th><?=t('Место проведения')?></th>
        </tr>
        <? if ($list) { ?>
        <? foreach ($list as $id) { ?>
        <tr>
            <td>
                <? $item=eventreport_peer::instance()->get_item($id) ?>
                <b><a href="/eventreport/view&id=<?=$item['id']?>"><?=stripslashes($item['name'])?></a></b>
            </td>
            <td>
                <? $ppo=ppo_peer::instance()->get_item($item['po_id']) ?>
                <a href="/ppo<?=$item['po_id']?>/"><?=stripslashes($ppo['title'])?></a>
            </td>
            <td>
                <?=date("d.m.Y H:i",$item['start'])?>
            </td>
            <td>
                <? $event=events_peer::instance()->get_small_item($item['event_id']) ?>
                <? $region = geo_peer::instance()->get_region($event['region_id']) ?><?=$region['name_' . translate::get_lang()]?>,
                <? $city = geo_peer::instance()->get_city($event['city_id']) ?><?=$city['name_' . translate::get_lang()]?>
            </td>
        </tr>
        <? }} ?>
        </table>
	<div class="bottom_line_d mb10"></div>
        <div class="left quiet fs12"><?=t('Отчетов')?>: <?=$total?>&nbsp;&nbsp;&nbsp;<?=t('Страниц')?>: <?=ceil($total/10)?></div>
	<div class="right pager"><?=pager_helper::get_full($pager)?></div>
</div>