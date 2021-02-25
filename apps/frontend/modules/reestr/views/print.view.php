<? header("Content-Type: text/html; charset=utf-8"); ?>
<html>
<title>Друк результатів пошуку</title>
<head>
<style type="text/css">
BODY
{
    FONT-SIZE: 9pt;
    COLOR: black;
    FONT-FAMILY: Arial;
    BACKGROUND-COLOR: white
}
table
{
    border-collapse:collapse;
    border:1px solid black;
}
td, th
{
    FONT-SIZE: 9pt;
    vertical-align:middle;
    text-align:center;
    padding:5px;
    border:1px solid black;
}
</style>
</head>
<body>

<div id="print"><a href="javascript:viod(0)" onclick="document.getElementById('print').style.display = 'none';print();">ДРУКУВАТИ</a></div>

<? if(is_array($list) && count($list)>0){ ?>

<table id="searchresults">
<tr>
    <th <? if($ft['vne'] OR $ft['ris'] OR $ft['sta'] OR $ft['zay']){ ?>rowspan="2"<? } ?>>№</th>
    <? if($ft['fio']){ ?><th <? if($ft['vne'] OR $ft['ris'] OR $ft['sta'] OR $ft['zay']){ ?>rowspan="2"<? } ?>><?=t('Ф.И.О.')?></th><? } ?>
    <? if($ft['reg']){ ?><th <? if($ft['vne'] OR $ft['ris'] OR $ft['sta'] OR $ft['zay']){ ?>rowspan="2"<? } ?>><?=t('Регион')?></th><? } ?>
    <? if($ft['ppo']){ ?><th <? if($ft['vne'] OR $ft['ris'] OR $ft['sta'] OR $ft['zay']){ ?>rowspan="2"<? } ?> class="ppo"><?=t('Текущее ППО')?></th><? } ?>
    <? if($ft['his']){ ?><th <? if($ft['vne'] OR $ft['ris'] OR $ft['sta'] OR $ft['zay']){ ?>rowspan="2"<? } ?> class="ppolog"><?=t('История переходов')?></th><? } ?>
    <? if($ft['dol']){ ?><th <? if($ft['vne'] OR $ft['ris'] OR $ft['sta'] OR $ft['zay']){ ?>rowspan="2"<? } ?> class="dolg"><?=t('Долг')?></th><? } ?>
    <? if($ft['vne']){ ?><th colspan="3" class="payments"><?=t('Уплаченные взносы')?></th><? } ?>
    <? if($ft['rec']){ ?><th <? if($ft['vne'] OR $ft['ris'] OR $ft['sta'] OR $ft['zay']){ ?>rowspan="2"<? } ?> class="recomend"><?=t('Рекомендация')?></th><? } ?>
    <? if($ft['num']){ ?><th <? if($ft['vne'] OR $ft['ris'] OR $ft['sta'] OR $ft['zay']){ ?>rowspan="2"<? } ?> class="number"><?=t('№ партбилета')?></th><? } ?>
    <? if($ft['ris']){ ?><th colspan="2" class="rishenna"><?=t('Решение о приеме')?></th><? } ?>
    <? if($ft['sta']){ ?><th colspan="2" class="status"><?=t('Присвоение статуса')?></th><? } ?>
    <? if($ft['zay']){ ?><th colspan="2" class="zayava"><?=t('Заявление о вступлении')?></th><? } ?>
</tr>
<? if($ft['vne'] OR $ft['ris'] OR $ft['sta'] OR $ft['zay']){ ?>
<tr>
    <? if($ft['vne']){ ?><th class="payments"><?=t('Вступ')?>.</th><? } ?>
    <? if($ft['vne']){ ?><th class="payments"><?=t('Ежемес')?>.</th><? } ?>
    <? if($ft['vne']){ ?><th class="payments"><?=t('Благ')?>.</th><? } ?>
    <? if($ft['ris']){ ?><th class="rishenna"><?=t('Дата')?></th><? } ?>
    <? if($ft['ris']){ ?><th class="rishenna"><?=t('Номер')?></th><? } ?>
    <? if($ft['sta']){ ?><th class="status"><?=t('Кто')?></th><? } ?>
    <? if($ft['sta']){ ?><th class="status"><?=t('Когда')?></th><? } ?>
    <? if($ft['zay']){ ?><th class="zayava"><?=t('Заявление')?></th><? } ?>
    <? if($ft['zay']){ ?><th class="zayava"><?=t('Дата подачи')?></th><? } ?>
</tr>
<? } ?>

<? $num = 1 ?>
<? foreach($list as $id){ ?>

<? if($ft['fio']){ $udata = user_data_peer::instance()->get_item($id); } ?>
<? if($ft['ris'] OR $ft['dol'] OR $ft['num']){ $membership = user_membership_peer::instance()->get_user($id); } ?>
<? if($ft['ppo']){ $ppomember = ppo_members_peer::instance()->get_ppo($id); } ?>
<? if($ft['his']){ $ppohistory = ppo_members_history_peer::instance()->get_member_history($id); } ?>
<? if($ft['ppo']){
	if(count($ppomember)){
		foreach($ppomember as $ppomem){
			$pop = ppo_peer::instance()->get_item($ppomem);
			if($pop["category"] != 1)
				continue;
			$curppo = $pop;
		}
	} else {
		unset($curppo);
	}
}?>
<? if($ft['sta']){ $statuslog = user_status_log_peer::instance()->get_last($id,20); } ?>
<? if($ft['zay']){ $zayava = user_zayava_peer::instance()->get_user_zayava($id); } ?>
<? if($ft['rec']){ $recommend = user_recommend_peer::instance()->get_recommenders($id,20); } ?>
<? if($ft['vne']){ $payments = user_payments_peer::instance()->get_total($id); } ?>

<tr>
    <td><?=$num?></td>
    <? if($ft['fio']){ ?><td><?=user_helper::full_name($id,true,array(),false)?></td><? } ?>
    <? if($ft['reg']){ ?><td>
        <? if($udata['region_id']){ ?>
            <? $region = geo_peer::instance()->get_region($udata['region_id']) ?>
            <?=$region['name_' . translate::get_lang()]?>
        <? } ?>
    </td><? } ?>
    <? if($ft['ppo']){ ?><td class="ppo">
			<?=($curppo)?$curppo['title']:'&mdash;'?>
		</td><? } ?>
    <? if($ft['his']){ ?><td class="ppolog">
        <?
        if(count($ppohistory))
        {
            foreach($ppohistory as $hist)
            {
                $pop = ppo_peer::instance()->get_item($hist['group_id']);
								if($pop["category"] != 1)
									continue;
                ?>
                <?=date('d.m.Y',$hist['date_start'])?> - <?=t('Вступление')?> в <?=$pop['title']?> <?=($pop['reason'])?'('.$pop['reason'].')':''?>
                <br/>
                <?
            }
        }
        else
        {
            echo '&mdash;';
        }
        ?>
    </td><? } ?>
    <? if($ft['dol']){ ?><td class="dolg"><?=$membership['debt']?></td><? } ?>
    <? if($ft['vne']){ ?><td class="payments"><?=($payments[1])?$payments[1]:0?></td><? } ?>
    <? if($ft['vne']){ ?><td class="payments"><?=($payments[2])?$payments[2]:0?></td><? } ?>
    <? if($ft['vne']){ ?><td class="payments"><?=($payments[3])?$payments[3]:0?></td><? } ?>
    <? if($ft['rec']){ ?><td class="recomend">
        <? if(count($recommend)){ ?>
        <? $arr = array() ?>
        <? foreach($recommend as $id){ ?>
            <? $arr[] = user_helper::full_name($id,true,array(),false) ?>
        <? } ?>
        <?=(count($arr)>0)?implode(', ',$arr):'&mdash;'?>
        <? unset($arr) ?>
        <? } ?>
    </td><? } ?>
    <? if($ft['num']){ ?><td class="number"><?=($membership['kvnumber'])?$membership['kvnumber']:'&mdash;'?></td><? } ?>
    <? if($ft['ris']){ ?><td class="rishenna"><?=($membership['invdate'])?date('d.m.Y',$membership['invdate']):'&mdash;'?></td><? } ?>
    <? if($ft['ris']){ ?><td class="rishenna"><?=($membership['invnumber'])?$membership['invnumber']:'&mdash;'?></td><? } ?>
    <? if($ft['sta']){ ?><td class="status"><?=($statuslog['id'])?user_helper::full_name($statuslog['who'],true,array(),false):'&mdash;'?></td><? } ?>
    <? if($ft['sta']){ ?><td class="status"><?=($statuslog['id'])?date('d.m.Y',$statuslog['date']):'&mdash;'?></td><? } ?>
    <? if($ft['zay']){ ?><td class="zayava"><?=($zayava['id'])?'<a target="_blank" href="/zayava&id='.$zayava['id'].'">id'.$zayava['id'].'</a>':'&mdash;'?></td><? } ?>
    <? if($ft['zay']){ ?><td class="zayava"><?=($zayava['id'])?date('d.m.Y',$zayava['date']):'&mdash;'?></td><? } ?>
</tr>

<? $num++;} ?>

</table>

<? } else { ?>
<div class="screen_message acenter quiet"><?=t('Ничего не найдено')?></div>
<? } ?>
</body>
</html>