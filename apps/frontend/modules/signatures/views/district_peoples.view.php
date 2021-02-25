<style type="text/css">
#people_table {font:12px Verdana,Arial; margin:25px auto; background:#EAEAEA <? /*url(/static/images/icons/bg.gif) repeat-x */?>;color:#091f30}
#hidden_th {display:none;}
.sortable {width:540px; border-left:1px solid #c6d5e1; border-top:1px solid #c6d5e1; border-bottom:none; margin:0 auto 15px}
.sortable th {background:#913D3E<?/*url(/static/images/icons/header-bg.gif) */?>; text-align:left; color:#fc6; border:1px solid #EAEAEA; border-right:none}
.sortable th {font-size:12px; padding:6px 8px 8px; text-align:center;}
.sortable td {padding:4px 6px 6px; border-bottom:1px solid #c6d5e1; border-right:1px solid #c6d5e1}
</style>
<div class="mt15 ml15"><a href="/signatures" class="cbrown bold fs12">&larr; <?=t('К таблице по регионам')?></a></div>
<table cellpadding="0" cellspacing="0" border="0" id="people_table" class="sortable">
		<thead>
			<tr>
				<th><b><?=t('Активист')?></b></th>
				<th><b><?=t('Кол-во подписей')?></b></th>
			</tr>
		</thead>
                <tbody>
                <?
                foreach ($district_peoples as $one_people)
                { ?>
                        <tr>
                            <td><a href="/profile/desktop?id=<?=$one_people['user_id']?>&tab=tasks"><?=user_helper::full_name($one_people['user_id'], false, array(), false)?></a></td>
                        <td>
                        <?=$one_people['fact']?>
                        </td>
                        </tr>
                <? } ?>
                </tbody>
</table>