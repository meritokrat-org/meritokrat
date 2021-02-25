<? include 'partials/sub_menu.php' ?>
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

<div class="mt5 mb10 form_bg" style="width:750px">
<h1 class="column_head mb10"><?=t('Отчеты')?> &rarr; <?=t('Поиск')?></h1>
    <form method="GET" action="/eventreport/search">
        <table id="searchform">
            <tr>
                <td class="aright fs11" width="200"><?=t('Название')?></td>
                <td>
                    <input name="name" style="width:187px;" class="text" type="text" value="<?=addslashes(htmlspecialchars(request::get_string('name')))?>" />
                </td>
            </tr>
            <tr>
                <td class="aright fs11"><?=t('Организатор')?></td>
                <td>
                    <?=tag_helper::select('ppo',array('&mdash;','ППО','МПО','РПО'),array('value'=>request::get_int('ppo'),'style'=>'width:193px'))?>
                </td>
            </tr>
            <tr>
                <td class="aright fs11"><?=t('Период')?></td>
                <td>
                    <? if(request::get_int('start_day') && request::get_int('start_month') && request::get_int('start_year')){
                        $start = mktime(0, 0, 0, request::get_int('start_month'), request::get_int('start_day'), request::get_int('start_year'));
                    } ?>
                    <? if(request::get_int('end_day') && request::get_int('end_month') && request::get_int('end_year')){
                        $end = mktime(0, 0, 0, request::get_int('end_month'), request::get_int('end_day'), request::get_int('end_year'));
                    } ?>
                    <div class="mb5"><?=user_helper::datefields('start',intval($start),false,array(),true)?></div>
                    <br/>
                    <div><?=user_helper::datefields('end',intval($end),false,array(),true)?></div>
                </td>
            </tr>
            <tr>
                <td class="aright fs11"><?=t('Статус')?></td>
                <td>
                    <?=tag_helper::select('status',array(0=>'&mdash;',99=>t('Новые отчеты'),1=>t('Отчеты на утверждение'),2=>t('Отчеты на доработке'),3=>t('Утвержденные'),4=>t('Не состоявшиеся')),array('value'=>request::get_int('status'),'style'=>'width:193px'))?>
                </td>
            </tr>
            <tr>
                <td class="aright fs11"></td>
                <td>
                        <input type="submit" value="Пошук" class="button" name="submit" style="margin-right:5px" />
                </td>
            </tr>
            <? if(request::get('submit')){ ?>
            <tr>
                <td class="aleft fs11">

                </td>
                <td class="quiet fs12">
                    <? if(!(is_array($list) && count($list)>0)){ ?>
                        <?=t('Ничего не найдено')?>
                    <? }else{ ?>
                        <?=t('Найдено')?>: <?=$total?>
                    <? } ?>
                </td>
            </tr>
            <? } ?>
        </table>

    </form>

</div>

<div class="left" style="width:750px">

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
                <? $event=events_peer::instance()->get_item($item['event_id']) ?>
                <? $region = geo_peer::instance()->get_region($event['region_id']) ?><?=$region['name_' . translate::get_lang()]?>,
                <? $city = geo_peer::instance()->get_city($event['city_id']) ?><?=$city['name_' . translate::get_lang()]?>
            </td>
        </tr>
        <? }} ?>
        </table>

	<div class="bottom_line_d mb10"></div>
	<div class="right pager"><?=pager_helper::get_full($pager)?></div>
</div>