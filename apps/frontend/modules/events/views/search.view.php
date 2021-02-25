<? $sub_menu = '/events/search'; load::model('groups/groups');  ?>
<? include 'partials/sub_menu.php' ?>
<? include 'partials/datepicker.php' ?>
<? load::view_helper('group') ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
jQuery('input#start','div#copyFromHistory form');
jQuery('input#end','div#copyFromHistory form');
$('input#start').datepicker();
$('input#end').datepicker();
    });
</script>
<div class="form_bg mt10 mr10">
	<h1 class="column_head"><?=t('Поиск')?></h1>
	<form method="get" action="/events/search" class="mr10 ml10">
		<div id="search_advanced" class="mt10 fs11">
                    <table width="100%" class="fs12">
		<tr>
			<td width="21%" class="aright"><?=t('Название')?> </td>
                        <td><input rel="<?=t('Введите название мероприятия')?>" style="width: 350px;" value="<?=stripslashes(htmlspecialchars(request::get('name')))?>" name="name" type="text" class="text" /></td>
		</tr>
               <tr>
			<td class="aright"><?=t('Уровень')?> </td>
                        <td>
                        <?=tag_helper::select_first_epmty('level',events_peer::get_levels(),array("value"=>request::get_int('level')))?><br/>
                            <!--<input rel="<?#t('Введите название организации')?>" id="catname" class="hidden" style="width: 350px;" value="<?#request::get('catname')?>" name="catname" type="text" class="text" />-->
                        </td>
		</tr>
                <tr>
			<td class="aright"><?=t('Вид')?> </td>
                        <td><?=tag_helper::select_first_epmty('section',events_peer::get_types(),array("value"=>request::get_int('section')))?></td>
		</tr>
                  <tr>
			<td class="aright"><?=t('Организатор')?> </td>
                        <td><?=tag_helper::select_first_epmty('content_type',array(t('Оргкоммитет'),t('Сообщество'),t('Участник'),t('Другая организация')),array("value"=>request::get_int('content_type')))?></td>
		</tr>
                <tr>
			<td class="aright"><?=t('Дата начала')?> </td>
                        <td>
                            <? if(request::get_int('start_day') && request::get_int('start_month') && request::get_int('start_year')){
                                $start = mktime(0, 0, 0, request::get_int('start_month'), request::get_int('start_day'), request::get_int('start_year'));
                            } ?>
                            <?=user_helper::datefields('start',intval($start),false,array(),true)?>
                        </td>
		</tr>
                <tr>
			<td class="aright"><?=t('Дата окончания')?> </td>
                        <td>
                            <? if(request::get_int('end_day') && request::get_int('end_month') && request::get_int('end_year')){
                                $end = mktime(0, 0, 0, request::get_int('end_month'), request::get_int('end_day'), request::get_int('end_year'));
                            } ?>
                            <?=user_helper::datefields('end',intval($end),false,array(),true)?>
                        </td>
		</tr>
                <tr>
			<td class="aright"><?=t('Место проведения')?> </td>
                        <td>
                            <?$regns=geo_peer::instance()->get_regions(1);$regns[0]="Виберіть регiон";ksort($regns);?>
                            <?=tag_helper::select_first_epmty('region_id',$regns, array('use_values' => false, 'value' => request::get_int('region_id'), 'id'=>'region', 'rel'=>t('Выберите регион'))); ?><br/>
                        </td>
		</tr>
                <tr>
			<td class="aright"></td>
                        <td>
                            <? if (request::get_int('region_id')>0 and request::get_int('region_id')!=9999) $cities = geo_peer::instance()->get_cities(request::get_int('region_id'));
                            elseif(request::get_int('country_id')>1) $cities['9999']='закордон';
                            else $cities=array(""=>"Виберіть місто"); ?>
                            <?=tag_helper::select('city_id', $cities , array('use_values' => false, 'value' => request::get_int('city_id'),'id'=>'city', 'class'=>'city', 'rel'=>t('Выберите город/район'))); ?>
                        </td>
		</tr>
                <tr>
			<td class="aright"><?=t('Стоимость')?> </td>
                        <td><?=tag_helper::select_first_epmty('price',array(1 => t('Бесплатное'), 2 => t('Платное')),array("value"=>request::get_int('price')))?></td>
		</tr>
               <tr>
			<td class="aright"><?=t('Я участвую')?> </td>
                        <td><?=tag_helper::select_first_epmty('status',array(1 => t('Да'),2 => t('Нет'),3 => t('Возможно')),array("value"=>request::get_int('status')))?></td>
		</tr>
                        <tr>
                            <td class="aright">
                                <input type="submit" name="submit" class="button" value=" <?=t('Найти')?> &raquo; ">
                            </td>
                            <td></td>
                        </tr>
                        <? if ($count_found>0) { ?>
                        <tr>
                            <td class="aright">
                                <?=t('Найдено')?>:
                            </td>
                            <td><b><?=$count_found?></b> <?=t('')?></td>
                        </tr>
                        <? } ?>
	</table>
		</div>

	</form><br />
</div>

<br />
<div class="mr10">
	<? if ($events) {
            $cats=events_peer::get_cats();
            $sections=events_peer::get_types();
            load::view_helper('image');
            load::view_helper('date');
        foreach ( $events as $event) {?> <div class="mb10 p10 box_content"> <?include 'partials/event.php';?></div><? }
        ?><div class="bottom_line_d mb10"></div>
	<div class="right pager"><?=pager_helper::get_full($pager)?></div><?
        } else { ?>
		<div class="screen_message acenter quiet"><?=t('Ничего не найдено')?></div>
	<? } ?>
</div>
