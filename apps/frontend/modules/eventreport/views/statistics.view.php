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
<h1 class="column_head mb10"><?=t('Отчеты')?> &rarr; <?=t('Статистика')?></h1>
    <form method="GET" action="/eventreport/statistics">
        <table id="searchform">
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
                <td class="aright fs11"></td>
                <td>
                        <input type="submit" value="Статистика" class="button" name="submit" style="margin-right:5px" />
                </td>
            </tr>
        </table>

    </form>

</div>

<div class="left" style="width:750px">

        <table class="sortable">
        <tr>
            <th><?=t('Количество агитационных событий')?></th>
            <th><?=t('Количество проинформированных людей')?></th>
            <th><?=t('Количество розданных агитационных материалов')?></th>
        </tr>
        <? if ($list) { ?>
        <tr>
            <td>
                <?=$list['total']?>
            </td>
            <td>
                <?=$list['informed']?>
            </td>
            <td>
                <? $agitation = user_helper::get_agimaterials() ?>
                <? foreach($agitation as $k=>$v){ ?>
                    <? if($agitation[$k]){ ?>
                        <?=$agitation[$k]?> (<?=intval($list['agitation'][$k])?>)<br/>
                    <? } ?>
                <? } ?>
            </td>
        </tr>
        <? } ?>
        </table>

	<div class="bottom_line_d mb10"></div>
</div>