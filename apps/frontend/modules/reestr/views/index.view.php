<?
$to = request::get_int('to');
$order = request::get('order');
if(!$page = request::get_int('page'))$num=1;
else $num = ($page-1)*$limit+1;
$ppofield = db_key::i()->get('reestr_'.session::get_user_id().'_ppo');
$ppologfield = db_key::i()->get('reestr_'.session::get_user_id().'_ppolog');
$dolgfield = db_key::i()->get('reestr_'.session::get_user_id().'_dolg');
$paymentsfield = db_key::i()->get('reestr_'.session::get_user_id().'_payments');
$recomendfield = db_key::i()->get('reestr_'.session::get_user_id().'_recomend');
$numberfield = db_key::i()->get('reestr_'.session::get_user_id().'_number');
$rishennafield = db_key::i()->get('reestr_'.session::get_user_id().'_rishenna');
$statusfield = db_key::i()->get('reestr_'.session::get_user_id().'_status');
$zayavafield = db_key::i()->get('reestr_'.session::get_user_id().'_zayava');
?>

<?
function nextmonth($data)
{
    $next = date('n',$data)+1;
    $year = date('Y',$data);
    if($next>12)
    {
        $next=1;
        $year+=1;
    }
    return mktime(0, 0, 0, $next, 1, $year);
}?>

<style>
    #left{display:none}
    table{border:1px solid gray;border-collapse: collapse}
    th{text-align:center;vertical-align:middle;font-size:11px;padding:1px 10px;background:#913D3E;color:#fc6;border:1px solid gray;}
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

<div class="sub_menu mt10 mb5" style="width:1000px">
    <a style="text-decoration:underline" href="/zayava/list"><?=t('Заявления')?></a>
    <b class="fs12"><?=t('Члены МПУ')?></b>
    <?if(session::has_credential('admin')) {?>
        <?if(context::get_controller()->get_action()!='exmembers') {?>
            <a class="ml5" href='/reestr/exmembers'>
        <? } else {?>
            <b class="fs12">
        <? } ?>
        <?=t('Бывшие члены МПУ')?>
        <?if(context::get_controller()->get_action()!='exmembers') {?>
            </a>
        <? } else {?>
            </b>
        <? } ?>
    <? } ?>
<!--    <a class="ml10" style="text-decoration:underline" href="/reestr/payments"><?=t('Взносы')?></a>-->

    <? if(session::has_credential('admin')){ ?>
        <a class="right" style="text-decoration:underline" href="/reestr/search">*<?=t('Расширенный поиск')?></a>
    <? } ?>
    <? if(intval(db_key::i()->get('schanger'.session::get_user_id())) OR in_array(session::get_user_id(),array(2,5,29,1360,3949,5968))){ ?>
        <a class="right" style="text-decoration:underline" href="javascript:;" onclick="Application.doMembership()">*<?=t('Решение о принятии')?></a>
    <? } ?>
</div>
<div class="sub_menu mt5 mb10" style="width:1000px">
    <a href="javascript:;" id="ppo" class="<?=($ppofield)?'clicked':''?>"><?=t('Ппо')?></a>
    <a href="javascript:;" id="ppolog" class="<?=($ppologfield || $ppologfield!='0')?'clicked':''?>"><?=t('История переходов')?></a>
    <a href="javascript:;" id="dolg" class="<?=($dolgfield)?'clicked':''?>"><?=t('Долг')?></a>
    <a href="javascript:;" id="payments" class="<?=($paymentsfield)?'clicked':''?>"><?=t('Взносы')?></a>
    <a href="javascript:;" id="recomend" class="<?=($recomendfield)?'clicked':''?>"><?=t('Рекомендация')?></a>
    <a href="javascript:;" id="number" class="<?=($numberfield)?'clicked':''?>"><?=t('Партбилет')?></a>
    <a href="javascript:;" id="rishenna" class="<?=($rishennafield)?'clicked':''?>"><?=t('Решение о приеме')?></a>
    <a href="javascript:;" id="status" class="<?=($statusfield || $statusfield!='0')?'clicked':''?>"><?=t('Присвоение статуса')?></a>
    <a href="javascript:;" id="zayava" class="<?=($zayavafield || $zayavafield!='0')?'clicked':''?>"><?=t('Заявление')?></a>
    <div class="right">
    <form method="GET" action="/reestr" class="right">
        <input type="hidden" name="status" value="<?=request::get_int('status',1)?>" />
        <input type="text" style="width:120px" value="<?=request::get_string('req')?>" class="text" name="req" />
        <input type="submit" value="Пошук" class="button" name="submit" style="margin-right:5px" />
    </form>
    <a href="javascript:void(0)" onclick="doprint()" class="ml10 right icoprint"></a>
    </div>
</div>

<div class="mt10">

<div style="width:1000px">
<h1 class="column_head mb10" style="background: url(/static/images/common/bg-header.jpg) repeat-x 0 -75px;height:21px">
    <?=t('Реестр членов МПУ')?>
    <? if(session::has_credential('admin')){ ?>
    <a class="right" href="/admin">*<?=t('Админка')?></a>
    <? } ?>
</h1>
</div>

<div id="tableholder" style="width:995px;overflow:auto;min-height:500px">

<? if(count($list)==0){ ?>

    <div class="screen_message acenter quiet">
        <?=t('Ничего не найдено')?>
        <br/>
        <a href="/reestr/"><?=t('Вернуться в реестр')?></a>
    </div>

<? }else{ ?>
    
<table class="sortable">
<tr>
    <th rowspan="2" width="20">№</th>
    <th rowspan="2" class="name" width="300"><b><a href="/reestr&order=name<?=($order=='name'&&!$to)?'&to=1':''?>"><?=t('Ф.И.О.')?></a></b></th>
    <th rowspan="2" class="region" width="50"><b><a href="/reestr&order=region<?=($order=='region'&&!$to)?'&to=1':''?>"><?=t('Регион')?></a></b></th>
    <th rowspan="2" width="150" class="ppo <?=($ppofield)?'hide':''?>"><?=t('Текущее ППО')?></th>
    <th rowspan="2" width="100" class="ppolog <?=($ppologfield || $ppologfield!='0')?'hide':''?>"><?=t('История переходов')?></th>
    <th rowspan="2" width="50" class="dolg <?=($dolgfield)?'hide':''?>"><b><a href="/reestr&order=dolg<?=($order=='dolg'&&!$to)?'&to=1':''?>"><?=t('Долг')?></a></b></th>
    <th colspan="3" width="200" class="payments <?=($paymentsfield)?'hide':''?>"><?=t('Уплаченные взносы')?></th>
    <th rowspan="2" width="200" class="recomend <?=($recomendfield)?'hide':''?>"><?=t('Рекомендация')?></th>
    <th rowspan="2" width="10" class="number <?=($numberfield)?'hide':''?>"><b><a href="/reestr&order=number<?=($order=='number'&&!$to)?'&to=1':''?>"><?=t('№ партбилета')?></a></b></th>
    <th colspan="2" width="100" class="rishenna <?=($rishennafield)?'hide':''?>"><?=t('Решение о приеме')?></th>
    <th colspan="2" width="100" class="status <?=($statusfield || $statusfield!='0')?'hide':''?>"><?=t('Присвоение статуса')?></th>
    <th colspan="3" width="100" class="zayava <?=($zayavafield || $zayavafield!='0')?'hide':''?>"><?=t('Заявление о вступлении')?></th>
</tr>
<tr>
    <th class="payments <?=($paymentsfield)?'hide':''?>"><b><a href="/reestr&order=payments&type=1<?=($order=='payments'&&request::get_int('type')==1&&!$to)?'&to=1':''?>"><?=t('Вступ')?>.</a></b></th>
    <th class="payments <?=($paymentsfield)?'hide':''?>"><b><a href="/reestr&order=payments&type=2<?=($order=='payments'&&request::get_int('type')==2&&!$to)?'&to=1':''?>"><?=t('Ежемес')?>.</a></b></th>
    <th class="payments <?=($paymentsfield)?'hide':''?>"><b><a href="/reestr&order=payments&type=3<?=($order=='payments'&&request::get_int('type')==3&&!$to)?'&to=1':''?>"><?=t('Благ')?>.</a></b></th>
    <th class="rishenna <?=($rishennafield)?'hide':''?>"><b><a href="/reestr&order=rishenna<?=($order=='rishenna'&&!$to)?'&to=1':''?>"><?=t('Дата')?></a></b></th>
    <th class="rishenna <?=($rishennafield)?'hide':''?>"><?=t('Номер')?></th>
    <th class="status <?=($statusfield || $statusfield!='0')?'hide':''?>"><?=t('Кто')?></th>
    <th class="status <?=($statusfield || $statusfield!='0')?'hide':''?>"><?=t('Когда')?></th>
    <th class="zayava <?=($zayavafield || $zayavafield!='0')?'hide':''?>"><?=t('Заявление')?></th>
    <th class="zayava <?=($zayavafield || $zayavafield!='0')?'hide':''?>"><?=t('Дата подачи')?></th>
    <th class="zayava <?=($zayavafield || $zayavafield!='0')?'hide':''?>"><?=t('Оригинал заявления')?></th>
</tr>

<? foreach($list as $id){ ?>

<?
$udata = user_data_peer::instance()->get_item($id);
$membership = user_membership_peer::instance()->get_user($id);
$ppomember = ppo_members_peer::instance()->get_ppo($id);
$ppohistory = ppo_members_history_peer::instance()->get_member_history($id);
$curppo = ppo_peer::instance()->get_user_ppo($id);
$statuslog = user_status_log_peer::instance()->get_last($id,20);
$zayava = user_zayava_peer::instance()->get_user_zayava($id);
$recommend = user_recommend_peer::instance()->get_recommenders($id,20);
$payments = user_payments_peer::instance()->get_total($id);
?>

<tr>
    <td><?=$num?></td>
    <td class="name">
        <a target="_blank" href="<?=user_helper::profile_link($id)?>">
        <b><?='<span style="text-transform:uppercase">'.$udata['last_name'].'</span> '.$udata['first_name'].' '.$udata['father_name']?></b>
        </a>
    </td>
    <td class="region">
        <? if($udata['region_id']){ ?>
            <? $region = geo_peer::instance()->get_region($udata['region_id']) ?>
            <?=$region['short']?>
            <?//$region['name_' . translate::get_lang()]?>
        <? } ?>
    </td>
    <td class="ppo <?=($ppofield)?'hide':''?>">
			<? if($curppo){ ?>
				<a target="_blank" href="http://<?=conf::get('server')?>/ppo<?=$curppo['id']?>/">
					<?=$curppo['title']?>
				</a>
			<? } else { ?>
				&mdash;
			<? } ?>
		</td>
    <td class="ppolog <?=($ppologfield || $ppologfield!='0')?'hide':''?>">
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
    </td>
    <td class="dolg <?=($dolgfield)?'hide':''?>"><?=$debts[$id]["debt"]?></td>
    <td class="payments <?=($paymentsfield)?'hide':''?>"><?=($payments[1])?$payments[1]:0?></td>
    <td class="payments <?=($paymentsfield)?'hide':''?>"><?=($payments[2])?$payments[2]:0?></td>
    <td class="payments <?=($paymentsfield)?'hide':''?>"><?=($payments[3])?$payments[3]:0?></td>
    <td class="recomend <?=($recomendfield)?'hide':''?>">
        <? if(count($recommend)){ ?>
        <? $arr = array() ?>
        <? foreach($recommend as $id){ ?>
            <? $arr[] = user_helper::full_name($id,true,array(),false) ?>
        <? } ?>
        <?=(count($arr)>0)?implode(', ',$arr):'&mdash;'?>
        <? unset($arr) ?>
        <? } ?>
    </td>
    <td class="number <?=($numberfield)?'hide':''?>"><?=($membership['kvnumber'])?$membership['kvnumber']:'&mdash;'?></td>
    <td class="rishenna <?=($rishennafield)?'hide':''?>"><?=($membership['invdate'])?date('d.m.Y',$membership['invdate']):'&mdash;'?></td>
    <td class="rishenna <?=($rishennafield)?'hide':''?>"><?=($membership['invnumber'])?$membership['invnumber']:'&mdash;'?></td>
    <td class="status <?=($statusfield || $statusfield!='0')?'hide':''?>"><?=($statuslog['id'])?user_helper::full_name($statuslog['who'],true,array(),false):'&mdash;'?></td>
    <td class="status <?=($statusfield || $statusfield!='0')?'hide':''?>"><?=($statuslog['id'])?date('d.m.Y',$statuslog['date']):'&mdash;'?></td>
    <td class="zayava <?=($zayavafield || $zayavafield!='0')?'hide':''?>"><?=($zayava['id'])?'<a target="_blank" href="/zayava&id='.$zayava['id'].'">id'.$zayava['id'].'</a>':'&mdash;'?></td>
    <td class="zayava <?=($zayavafield || $zayavafield!='0')?'hide':''?>"><?=($zayava['id'])?date('d.m.Y',$zayava['date']):'&mdash;'?></td>
    <td class="zayava <?=($zayavafield || $zayavafield!='0')?'hide':''?>"><input type="checkbox" <?=(session::has_credential('admin')) ? "" : ' disabled'?>  name="real_app" <?=($zayava['real_app']) ? ' checked="1"' : ''?> value="real_app"  rel="<?=$zayava['user_id']?>"/></td>
</tr>
<? unset($curppo) ?>
<? $num++;} ?>

</table>
<? } ?>
</div>

<? if($total>$limit){ ?>
<div style="width:1000px" class="bottomholder">
    <div class="left ml5 mt10 fs11 quiet">
        <?=t('Записей на странице')?>:
        <?=tag_helper::select('limit',array('10'=>10,'25'=>25,'50'=>50,'100'=>100),array('id'=>'limit','value'=>db_key::i()->get('reestr_'.session::get_user_id().'_limit')))?>
    </div>
    <div class="left mt10 fs11 quiet" style="margin-left:290px">
        <?=t('Участников')?>: <?=$total?> &nbsp;&nbsp;&nbsp; <?=t('Страниц')?>: <?=ceil($total/$limit)?>
    </div>
    <div class="right pager mr5 mt10"><?=pager_helper::get_long($pager)?></div>
</div>
<? } ?>

</div>
<?if(session::has_credential('admin')) {?>
    <script>
        $('input[name="real_app"]').change(function(){
            if($(this).attr('checked')) real_app=2;
            else real_app=1;
            $.ajax({
                type: 'post',
                url: '/zayava/index',
                data: {
                   has_real: real_app,
                   id: $(this).attr('rel')
                },
                success: function(data) {
                    resp = eval("("+data+")");
                    $('#resp'+resp.success).fadeIn(300,function(){$(this).fadeOut(1000)});
                }
            });
        });
    </script> 
<?}?>
<script type="text/javascript">
jQuery(document).ready(function($){

    _tablewidth();
    $('tr:even').addClass('evenrow');
    $('tr:odd').addClass('oddrow');

    var order = '<?=trim(addslashes(htmlspecialchars(request::get_string('order'))))?>';
    var to = parseInt('<?=request::get_int('to')?>');

    if(order!=''){
        var $obj = $('td.'+order);
        if($obj.length != 0){
            if(to){
                $('th.'+order).addClass('desc');
            }else{
                $('th.'+order).addClass('asc');
            }
            $('tr:even').find('td.'+order).addClass('evenselected');
            $('tr:odd').find('td.'+order).addClass('oddselected');
        }
    }

    $('.sub_menu:last a').click(function(){
        if(!$(this).hasClass('clicked')){
            $('#tableholder').find('.'+$(this).attr('id')).hide();
            $(this).addClass('clicked');
            var val = 1;
        }else{
            $('#tableholder').find('.'+$(this).attr('id')).show();
            $(this).removeClass('clicked');
            var val = 0;
        }
        _tablewidth();
        $.post('/reestr/settings',{'key':$(this).attr('id'),'value':val});
    });

    $('#limit').change(function(){
        $.post('/reestr/settings',{'key':'limit','value':$(this).val()},function(){
            window.location = 'http://'+window.location.hostname+'/reestr';
        });
    });

});
function _tablewidth(){
    var width = 0;
    $('table.sortable tr:first').find('th:visible').each(function(){
        width += parseInt($(this).attr('width'));
    });
    if(width < 995)width = 995;
    $('table.sortable').css({'width':width});
}
function doprint(){
    $('#additional, #footer, #header, #left, #comment_form, #comments, .column_head, #vote_pane, .actionpanel, .sub_menu, .addit, .ppolnk, .bottomholder').remove();
    $('.root_container').css({width:'100%',margin:'none'});
    $('#tableholder').removeAttr("style");
    $('#leftcoll, div.addthis_toolbox').hide();
    $('.left').removeClass('left');
    window.print();
}
</script>