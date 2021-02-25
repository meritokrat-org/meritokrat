<table width="100%" style="color: black;" class="fs12">
	<? $pay = array() ?>
	
    <? if(count($payments)>0){ ?>
    <? $paytypes = user_helper::get_payment_types() ?>
    <? foreach($payments as $p){ ?>
    <? $item = user_payments_peer::instance()->get_item($p) ?>
    <? $pay[$item['type']][] = $item ?>
    <? }} ?>

    <? if($user_auth['status']==20 || $user_auth['ban']==20){ ?>

    <tr style="background: transparent url(/static/images/common/desktop_line.png);">
            <td class="bold cbrown" rel="info"><?=t('Членские ежемесячные')?></td>
            <td class="fs11 aright"><? if (session::has_credential('admin') or session::get_user_id()==$user_desktop['user_id']) { ?><a class="cbrown" href="/profile/desktop_edit?id=<?=$user_desktop['user_id']?>&tab=payments"><?=t('Редактировать')?> &rarr;</a><? } ?></td>
    </tr>
    <tr>
       <td colspan="2">
            <?
            if($membership['invdate'])
            {
                $pp = $pms = array();
                if(count($pay[2])>0)
                {
                    foreach($pay[2] as $pm)
                    {
                        $pp[$pm['period']] = $pm;
                    }
                }

                #взносы считаем с даты принятия в члены (если после 15 числа текущ месяца то чел не платит взнос за этот мес - считаем со следующего)
                if(date('j',$membership['invdate'])<=15)
                {
                    $date = mktime(0, 0, 0, date('n',$membership['invdate']), 1, date('Y',$membership['invdate']));
                }
                else
                {
                    $date = nextmonth($membership['invdate']);
                    //$date = mktime(0, 0, 0, date('n',$membership['invdate']), 1, date('Y',$membership['invdate']))+(86400*date('t',$membership['invdate']));
                }
                #создаем массив со всеми месяцами начиная с даты принятия и по текущий месяц (присваеваем платежи если есть)
                $curdate = mktime(0, 0, 0, date('n'), 1, date('Y'));
                for($i=$date;$i<=$curdate;$i=nextmonth($i))
                {
                    $pms[$i] = $pp[$i];
                }
                #если есть какие то платежи, но они не входят в предыдущий массив то удаляем их из массива
                foreach($pp as $k=>$v){
                    if($pms[$k])unset($pp[$k]);
                }
                #складываем массивы
                $pms = $pp + $pms;
                krsort($pms);
                foreach($pms as $k=>$p)
                {
                    $total[2] += $p['summ'];
                    include 'apayment.php';
                }
            }
            ?>
       </td>
    </tr>
    <tr>
            <td class="bold"><?=t('Всего')?>: <?=intval($total[2])?> грн.</td>
            <td class="bold aleft"></td>
    </tr>
	
    <tr style="background: transparent url(/static/images/common/desktop_line.png);">
            <td class="bold cbrown" rel="info"><?=t('Членский вступительный')?></td>
            <td class="fs11 aright"><? if (session::has_credential('admin') or session::get_user_id()==$user_desktop['user_id']) { ?><a class="cbrown" href="/profile/desktop_edit?id=<?=$user_desktop['user_id']?>&tab=payments"><?=t('Редактировать')?> &rarr;</a><? } ?></td>
    </tr>
    <? if(count($pay[1])>0){ ?>
    <? foreach($pay[1] as $p){ ?>
    <? $total[1] += $p['summ'] ?>
    <tr>
       <td colspan="2">
           <? include 'payment.php'; ?>
       </td>
    </tr>
    <? }}else{ ?>
    <tr>
        <td colspan="2">
            <table style="color: black;" class="fs12 mb5">
            <tr>
                <td width="60" class="bold acenter fs11" style="vertical-align:middle"></td>
                <td style="vertical-align:middle"><i style="color:gray"><?=t('Взнос не сделан')?></i></td>
            </tr>
            </table>
        </td>
    </tr>
    <? } ?>
    <tr>
            <td class="bold"><?=t('Всего')?>: <?=intval($total[1])?> грн.</td>
            <td class="bold aleft"></td>
    </tr>

    <? } ?>

	
	<tr style="background: transparent url(/static/images/common/desktop_line.png);">
            <td class="bold cbrown" rel="info"><?=t('Взнос в избирательный фонд')?></td>
            <td class="fs11 aright"><? if (session::has_credential('admin') or session::get_user_id()==$user_desktop['user_id']) { ?><a class="cbrown" href="/profile/desktop_edit?id=<?=$user_desktop['user_id']?>&tab=payments"><?=t('Редактировать')?> &rarr;</a><? } ?></td>
    </tr>
    <? if(count($pay[5])>0){ ?>
    <? foreach($pay[5] as $p){ ?>
    <? $total[5] += $p['summ'] ?>
    <tr>
       <td colspan="2">
           <? include 'payment.php'; ?>
       </td>
    </tr>
    <? }}else{ ?>
    <tr>
       <td colspan="2">
       </td>
    </tr>
    <? } ?>
	
    <tr style="background: transparent url(/static/images/common/desktop_line.png);">
            <td class="bold cbrown" rel="info"><?=t('Благотворительные')?></td>
            <td class="fs11 aright"><? if (session::has_credential('admin') or session::get_user_id()==$user_desktop['user_id']) { ?><a class="cbrown" href="/profile/desktop_edit?id=<?=$user_desktop['user_id']?>&tab=payments"><?=t('Редактировать')?> &rarr;</a><? } ?></td>
    </tr>
    <? if(count($pay[3])>0){ ?>
    <? foreach($pay[3] as $p){ ?>
    <? $total[3] += $p['summ'] ?>
    <tr>
       <td colspan="2">
           <? include 'payment.php'; ?>
       </td>
    </tr>
    <? }}else{ ?>
    <tr>
       <td colspan="2">
       </td>
    </tr>
    <? } ?>
    <tr>
            <td class="bold"><?=t('Всего')?>: <?=intval($total[3])?> грн.</td>
            <td class="bold aleft"></td>
    </tr>

    <tr style="background: transparent url(/static/images/common/desktop_line.png);">
            <td class="bold cbrown" rel="info"><?=t('Целевые (местные) благотворительные')?></td>
            <td class="fs11 aright"><? if (session::has_credential('admin') or session::get_user_id()==$user_desktop['user_id']) { ?><a class="cbrown" href="/profile/desktop_edit?id=<?=$user_desktop['user_id']?>&tab=payments"><?=t('Редактировать')?> &rarr;</a><? } ?></td>
    </tr>
    <? if(count($pay[4])>0){ ?>
    <? foreach($pay[4] as $p){ ?>
    <? $total[4] += $p['summ'] ?>
    <tr>
       <td colspan="2">
           <? include 'payment.php'; ?>
       </td>
    </tr>
    <? }}else{ ?>
    <tr>
       <td colspan="2">
       </td>
    </tr>
    <? } ?>
    <tr>
            <td class="bold"><?=t('Всего')?>: <?=intval($total[4])?> грн.</td>
            <td class="bold aleft"></td>
    </tr>

</table>

<div id="payformholder">
<form action="/" method="post" class="hide" id="payform" style="background:#EEEEEF;margin-top:5px;padding:5px;">
<table border="0" cellpadding="0" cellspacing="0" style="margin-bottom:0">
        <tr>
                <td class="aright" width="100"><?=t('Сумма')?></td>
                <td>
                    <input type="hidden" name="id" class="pid" value="" />
                    <input type="hidden" name="type" class="ptype" value="" />
                    <input style="width:150px" name="summ" class="text psumm" type="text" value="" />
                </td>
        </tr>
        <tr>
                <td class="aright"><?=t('Дата сплати')?></td>
                <td>
                    <input style="width:150px" name="date" class="text pdate" type="text" value="" />
                </td>
        </tr>

        <tr id="payperiod" class="hide">
                <td class="aright"><?=t('Период платежа')?></td>
                <td>
                    <? $monthsarr = user_helper::get_months() ?>
                    <? unset($monthsarr[0]) ?>
                    <?=tag_helper::select('month', $monthsarr, array('class'=>'pmonth'))?>
                    <?=tag_helper::select('year', user_helper::get_years(2011,(date('Y')+1)), array('class'=>'pyear','value'=>date('Y')))?>
                </td>
        </tr>
        <tr id="paytext" class="hide">
                <td class="aright"><?=t('Назначение')?></td>
                <td>
                    <input type="text" name="text" class="text ptext" value="" />
                </td>
        </tr>

        <tr class="paytypes">
                <td class="aright"><?=t('Вид')?></td>
                <td>
                    <input type="checkbox" name="method" class="pmethod pmethod1" value="1" />&nbsp;<?=$paytypes[0][1]?>
                    <input type="checkbox" name="method" class="pmethod pmethod2" value="2" />&nbsp;<?=$paytypes[0][2]?>
                </td>
        </tr>
        <tr class="method1 mth hide paytypes">
                <td class="aright"></td>
                <td>
                    <input type="checkbox" name="way" class="pway pway1" value="1" />&nbsp;<?=$paytypes[1][1]?>
                    <input type="checkbox" name="way" class="pway pway2" value="2" />&nbsp;<?=$paytypes[1][2]?>
                </td>
        </tr>
        <!--tr class="method2 mth hide">
                <td class="aright"></td>
                <td>
                    <input type="checkbox" name="way" class="pway pway1" value="1" />&nbsp;<?=$paytypes[2][1]?>
                    <input type="checkbox" name="way" class="pway pway2" value="2" />&nbsp;<?=$paytypes[2][2]?>
                </td>
        </tr-->
        
        <tr>
                <td class="aright"></td>
                <td>
                    <input type="button" id="payedit" class="button" value="<?=t('Сохранить')?>" />
                    <input type="button" id="payhide" class="button_gray" value="<?=t('Отмена')?>" />
                    <img id="paymwait" class="hidden ml10" width="15" align="absmiddle" src="https://s1.meritokrat.org/common/loading.gif" />
                </td>
        </tr>
</table>
</form>
</div>

<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker-uk.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($){
    var opt = {
        changeMonth: true,
        changeYear: true,
         autoSize: true,
        showOptions: {direction: 'left' },
        dateFormat: 'dd-mm-yy',
        shortYearCutoff: 90,
        yearRange: '2011:<?=date('Y')?>',
        firstDay: true,
        minDate: new Date(2011, 1 - 1, 1)
    };

    $('#payform').find('input.pdate').datepicker(opt);
    radiocheckbox($('#payform'));
    methodcheckbox($('#payform'));
    bindLinks();

    $('#payedit').click(function(){
        var error = 0;
        $('#paymwait').show();
        $.post('/profile/payment_check',{
            'id':$('#payform').find('input.pid').val(),
            'type':$('#payform').find('input.ptype').val(),
            'month':$('#payform').find('select.pmonth').val(),
            'year':$('#payform').find('select.pyear').val()
        },function(response){
            if(response=='error'){
                alert('<?=t('Ошибка! Взнос за этот период уже существует')?>');
                $('#payform').hide();
                return false;
            }else{
                $.post('/profile/payment',{
                    'id':$('#payform').find('input.pid').val(),
                    'type':$('#payform').find('input.ptype').val(),
                    'summ':$('#payform').find('input.psumm').val(),
                    'text':$('#payform').find('input.ptext').val(),
                    'method':$('#payform').find('input.pmethod:checked').val(),
                    'way':$('#payform').find('input.pway:visible:checked').val(),
                    'month':$('#payform').find('select.pmonth').val(),
                    'year':$('#payform').find('select.pyear').val(),
                    'date':$('#payform').find('input.pdate').val(),
                    'has_access':<?=intval($has_access)?>
                },function(response){
                    if(response==1){
                        alert('<?=t('все поля обязательны для заполнения')?>');
                    }else{
                        var $parent = $('#payform').parent();
                        $('#payform').hide();
                        $('#payformholder').append($('#payform'));
                        $parent.html(response);
                        bindLinks();
                    }
                    $('#paymwait').hide();
                });
            }
        });

    });

    $('#payhide').click(function(){
        $('#payform').hide();
        $('#payformholder').append($('#payform'));
    });


});
function bindLinks(){
    $('a.editpayment').unbind('click').click(function(){
        var $this = $(this).parent().parent().parent().parent().parent();
        var $holder = $this.find('div.pdataholder');
        var $payform = $('#payform');

        if($holder.find('span.ptype').html()=='2'){
            $('#payform').find('#payperiod').removeClass('hide');
        }else{
            $('#payform').find('#payperiod').addClass('hide');
        }

        $('#payform').find('input:text, input:hidden, select').not('input:button, input:checkbox').each(function(){
            $(this).val($holder.find('span.p'+$(this).attr('name')).html());
        });
        $('#payform').find('.mth').hide();
        $('#payform').find('input:checkbox').removeAttr('checked');
        var method = $holder.find('span.pmethod').html();
        var way = $holder.find('span.pway').html();
        $payform.find('input.pmethod'+method).attr('checked',true);
        $payform.find('.method'+method).show().find('input.pway'+way).attr('checked',true);

        if($holder.find('span.ptype').html()=='4'){
            $('#payform').find('#paytext').removeClass('hide');
            $('#payform').find('.paytypes').hide();
        }else{
            $('#payform').find('#paytext').addClass('hide');
            $('#payform').find('.paytypes').show();
        }

        $this.append($payform);
        $payform.show();
    });

    $('a.delpayment').unbind('click').click(function(){
        var $parent = $(this).parent().parent().parent().parent().parent();
        if(confirm('Ви впевненi, що хочете видалити цей внесок?')){
            $.post('/profile/payment_delete',{
                'id':$parent.find('div.pdataholder').find('span.pid').html()
            },function(response){
                $parent.parent().remove();
            });
        }
    });

    $('a.approvepayment').unbind('click').click(function(){
        var $parent = $(this).parent().parent().parent().parent().parent();
        $.post('/profile/payment',{
            'id':$parent.find('div.pdataholder').find('span.pid').html(),
            'approve':1,
            'has_access':<?=intval($has_access)?>
        },function(response){
            if(response){
                $('#payform').hide();
                $('#payformholder').append($('#payform'));
                $parent.html(response);
                bindLinks();
            }
        });
    });
}
function radiocheckbox(obj){
    obj.find('input:checkbox').click(function(){
        if(!$(this).is(':checked')){
            $(this).siblings().attr('checked',true);
            $(this).removeAttr('checked');
        }else{
            $(this).siblings().removeAttr('checked');
            $(this).attr('checked',true);
        }
    });
}
function methodcheckbox(obj){
    obj.find('input[name^="method"]').click(function(){
        if($(this).is(':checked')){
            var val = $(this).val();
        }else{
            var val = $(this).siblings().val();
        }
        $(this).parent().parent().parent().find('tr.mth').hide().find('input:checkbox').removeAttr('checked');
        $(this).parent().parent().parent().find('tr.method'+val).show().find('input:checkbox:first').attr('checked',true);
    });
}
</script>

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
}
?>