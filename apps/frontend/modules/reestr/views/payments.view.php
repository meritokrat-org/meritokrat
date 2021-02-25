<?
if(!$page = request::get_int('page'))$num=1;
else $num = ($page-1)*$limit+1;
$paytypes = user_helper::get_payment_types();
?>

<style>
    #left{display:none}
    table{border:1px solid gray;border-collapse: collapse;line-height: 1;}
    th{height: 50px;text-align:center;vertical-align:middle;font-size:11px;padding:1px 10px;background:#913D3E;color:#fc6;border:1px solid gray;}
    th a{color:#fc6;}
    td{text-align:center;vertical-align:middle;font-size:11px;color:black;border:1px solid gray;padding:1px 5px;}
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

<!--<div class="sub_menu mt10 mb5" style="width:1000px">
    <a style="text-decoration:underline" href="/zayava/list"><?=t('Заявления')?></a>
    <a style="text-decoration:underline" href="/reestr"><?=t('Реестр')?></a>
    <b class="fs12"><?=t('Взносы')?></b>
    <? if(session::has_credential('admin')){ ?>
        <a class="right" style="text-decoration:underline" href="/reestr/search">*<?=t('Расширенный поиск')?></a>
    <? } ?>
    <? if(intval(db_key::i()->get('schanger'.session::get_user_id())) OR in_array(session::get_user_id(),array(2,5,29,1360,3949))){ ?>
        <a class="right" style="text-decoration:underline" href="javascript:;" onclick="Application.doMembership()">*<?=t('Решение о принятии')?></a>
    <? } ?>
</div>-->

<div class="mt10">

<div style="width:1000px">
<h1 class="column_head mb10" style="background: url(/static/images/common/bg-header.jpg) repeat-x 0 -75px;height:21px">
    <?//=t('Реестр членов МПУ')?>  <?=t('Взносы')?>
    <? if(session::has_credential('admin')){ ?>
    <a class="right" href="/admin">*<?=t('Админка')?></a>
    <? } ?>
</h1>
</div>
    <div class="left ml5 mb5 fs11 quiet">
        <form action="" method="get">
        <?=t('Способ платежа')?>:
        <?=tag_helper::select_first_epmty('metod',$paytypes[0],array('id'=>'metod','value'=>request::get_int('metod')))?>
        <?=tag_helper::select_first_epmty('way1',$paytypes[1],array('id'=>'way1','class'=>request::get_int('metod')==1?'way':'hide way','value'=>request::get_int('way1')))?>
        <?=tag_helper::select_first_epmty('way2',$paytypes[2],array('id'=>'way2','class'=>request::get_int('metod')==2?'way':'hide way','value'=>request::get_int('way2')))?>
        <input type="submit" value="<?=t('Фильтровать')?>"/>
        </form>
    </div>
<div id="tableholder" style="width:995px;overflow:auto;min-height:500px">

<? 
if(count($list)==0){ ?>

    <div class="screen_message acenter quiet">
        <?=t('Ничего не найдено')?>
        <br/>
        <a href="/reestr/"><?=t('Вернуться в реестр')?></a>
    </div>

<? }else{ ?>

<table class="sortable">
<tr>
    <th>№</th>
    <th><?=t('Ф.И.О.')?></th>
    <th><?=t('Регион')?></th>
    <th><?=t('Тип')?></th>
    <th><?=t('Сумма')?></th>
    <th><?=t('Общая сумма')?></th>
    <th><?=t('Период')?></th>
    <th><?=t('Дата')?></th>
    <th><?=t('Способ')?></th>
    <th><?=t('Система')?></th>
    <th><?=t('ID')?></th>
    <th><?=t('Подтверждение')?></th>
    <? if(in_array(session::get_user_id(),array(2,5,1360,5968,3949,2546))) { ?>
    <th><?=t('Действия')?></th>
    <? } ?>
</tr>

<? $flag = true; ?>

<? foreach($list as $id){ ?>

<? $flag = $flag ? false : true; ?>

<?
$udata = user_data_peer::instance()->get_item($id);
$payments = user_payments_peer::instance()->get_user($id,false,1);
$paytotal = 0;
foreach($payments as $p)
{
    $curr = user_payments_peer::instance()->get_item($p);
    $paytotal += intval($curr['summ']);
    $pay[] = $curr;
}
$cnt = count($pay);
$cur = 0;
?>

<? foreach($pay as $p){ ?>
<tr style="background: #<?= $flag ? "EEE": "FFF" ?>">
    <? if(!$cur){ ?>
        <td class="constant" <?=($cnt>1)?'rowspan="'.$cnt.'"':''?>><?=$num?></td>
        <td class="constant bold" width="195" <?=($cnt>1)?'rowspan="'.$cnt.'"':''?>>
            <a target="_blank" href="<?=user_helper::profile_link($id)?>">
            <?='<span style="text-transform:uppercase">'.$udata['last_name'].'</span> '.$udata['first_name'].' '.$udata['father_name']?>
            </a>
        </td>
        <? $region = geo_peer::instance()->get_region($udata['region_id']) ?>
        <td class="constant" <?=($cnt>1)?'rowspan="'.$cnt.'"':''?>><?=$region['short']?></td>
    <? } ?>
        <td>
            <?=($p['type']==1)?t('Вступительный'):''?>
            <?=($p['type']==2)?t('Ежемесячный'):''?>
            <?=($p['type']==3)?t('Благотворительный'):''?>
        </td>
        <td>
            <?=$p['summ']?>
        </td>
        <? if(!$cur){ ?>
            <td <?=($cnt>1)?'rowspan="'.$cnt.'"':''?>>
                <?//= db::get_scalar("SELECT SUM(summ) FROM user_payments WHERE approve=0 AND user_id=:user_id",array("user_id"=>$p['user_id'])); ?>
                <?=$paytotal?>
            </td>
        <? } ?>
        <td width="78">
            <?=($p['period'])?user_helper::get_months(date('n',$p['period'])).' '.date('Y',$p['period']):'&mdash;'?>
        </td>
        <td>
            <?=date('d.m.Y',$p['date'])?>
        </td>
        <td>
            <?=$paytypes[0][$p['method']]?><?=($p['way'])?', '.$paytypes[$p['method']][$p['way']]:''?>
        </td>
        <td>
            <?=$p['ps']?>
        </td>
        <td>
            <?=$p['transaction_id']?>
        </td>
        <td>
            <? $paylog = user_payments_log_peer::instance()->get_payment($p['id'],2) ?>
            <? if(count($paylog)>0){ ?>
            <? foreach($paylog as $log){ ?>
                <? $logitem = user_payments_log_peer::instance()->get_item($log) ?>
                <?=user_helper::full_name($logitem['who'],true,array(),false).'<br/>'?>
            <? } ?>
            <? }else{ ?>
                &mdash;
            <? } ?>
        </td>
        <? if(in_array(session::get_user_id(),array(2,5,1360,5968,3949,2546))) { ?>
        <td width="70">
            
            <a target="_blank" href="/profile/desktop?id=<?=$id?>&tab=payments" rel="<?=$p['id']?>" class="dib icoedt mr5"></a>
            <a href="javascript:;" class="approvepayment dib icoapprove mr5" rel="<?=$p['id']?>"></a>
            <a href="javascript:;" class="delpayment dib icodel" rel="<?=$p['id']?>"></a>
            
        </td>
        <? } ?>
</tr>
<? $cur++ ?>
<? } ?>

<? unset($pay) ?>
<? $num++ ?>
<? } ?>

</table>

<? } ?>

</div>

<? if($total>$limit){ ?>
<div style="width:1000px">
    <div class="left ml5 mt10 fs11 quiet">
        <?=t('Записей на странице')?>:
        <?=tag_helper::select('limit',array('10'=>10,'25'=>25,'50'=>50,'100'=>100),array('id'=>'limit','value'=>db_key::i()->get('reestr_'.session::get_user_id().'_limit')))?>
    </div>
    <div class="left mt10 fs11 quiet" style="margin-left:310px">
        <?=t('Страниц')?>: <?=ceil($total/$limit)?>
    </div>
    <div class="right pager mr5 mt10"><?=pager_helper::get_long($pager)?></div>
</div>
<? } ?>

</div>

<script type="text/javascript">
jQuery(document).ready(function($){

    $('#limit').change(function(){
        $.post('/reestr/settings',{'key':'limit','value':$(this).val()},function(){
            window.location = 'http://'+window.location.hostname+'/reestr/payments';
        });
    });
     $('#metod').change(function(){
         $('.way').hide();
         $('#way'+$(this).val()).show();
    });  
    $('a.delpayment').unbind('click').click(function(){
        if(confirm('Ви впевненi, що хочете видалити цей внесок?')){
            var id = $(this).attr('rel');
            var $parent = $(this).parent().parent();
            $.post('/profile/payment_delete',{
                'id':id
            },function(response){
                $parent.find('td').not('.constant').each(function(){
                    $(this).html('&mdash;');
                });
            });
        }
    });

    $('a.approvepayment').unbind('click').click(function(){
        var id = $(this).attr('rel');
        var $this = $(this);
        var $parent = $(this).parent().parent();
        if(confirm('Ви впевненi, що хочете пiдтвердити цей внесок?')){
            $.post('/profile/payment',{
                'id':id,
                'approve':1,
                'has_access':1
            },function(response){
                $parent.find('td').not('.constant').each(function(){
                    $(this).html('&mdash;');
                });
            });
        }
    });

});
</script>