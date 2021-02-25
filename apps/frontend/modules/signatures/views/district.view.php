<style type="text/css">
#people_table {font:12px Verdana,Arial; margin:25px auto; background:#EAEAEA <? /*url(/static/images/icons/bg.gif) repeat-x */?>;color:#091f30}
#hidden_th {display:none;}
.sortable {width:740px; border-left:1px solid #c6d5e1; border-top:1px solid #c6d5e1; border-bottom:none; margin:0 auto 15px}
.sortable th {background:#913D3E<?/*url(/static/images/icons/header-bg.gif) */?>; text-align:left; color:#fc6; border:1px solid #EAEAEA; border-right:none}
.sortable th {font-size:12px; padding:6px 8px 8px; text-align:center; vertical-align:middle;}
.sortable td {padding:4px 6px 6px; border-bottom:1px solid #c6d5e1; border-right:1px solid #c6d5e1}

</style>
<div id="district_table">
<div class="mt15 ml15"><a href="/signatures" class="cbrown bold fs12">&larr; <?=t('К таблице по регионам')?></a></div>
<table cellpadding="0" cellspacing="0" border="0" id="people_table" class="sortable">
		<thead>
			<tr>
				<th><b><?=t('Регион')?></b></th>
				<th><b><?=t('Кол-во подписей')?></b></th>
			</tr>
		</thead>
                <tbody>
                <? 
                foreach ($districts as $district_id=>$values)
                {
                        $district = geo_peer::instance()->get_city($district_id); ?>
                        <tr>
                        <td><b id="<?=$district_id?>" class="one_district"><?=$district['name_'.session::get('language')]?></b></td>
                        <td>
                        <?$sum_signatures[$district_id]=db::get_scalar("SELECT sum(fact) as sum FROM user_desktop_signatures_fact WHERE city_id=$district_id")?>
                        <a href="/signatures/district_peoples?id=<?=$district_id?>"><?=$sum_signatures[$district_id] ? $sum_signatures[$district_id] : 0 ?></a>
                        </td>
                        </tr>
                <? } ?>
                </tbody>
</table>
<div class="bold mb5 aright" style="margin-right:20px;"><?=t('Всего подписей')?> &nbsp; &nbsp; <? $all_signatures=db::get_scalar('SELECT sum("fact") FROM "public"."user_desktop_signatures_fact" WHERE region_id='.request::get_int('region_id',0))?>
<?=$all_signatures ? $all_signatures : 0?></div>
</div>