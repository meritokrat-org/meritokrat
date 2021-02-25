<? $paytypes = user_helper::get_payment_types() ?>

<div id="payments_form" class="form mt10 hide">

<table width="100%" class="fs12" id="payments_table">

<tr>
	<td class="aright" width="150"></td>
	<td style="padding-top:20px">
		<b><?=t('Взнос в избирательный фонд');?></b>
		<? if($zayava['id'] && $zayava['bvnesok']){ ?>
			<span>(<?=t('Запланировано')?> <?=$zayava['bvnesok']?> грн.)</span>
		<? } ?>
	</td>
</tr>
<tr>
	<td colspan="2" class="p10 type5">
	<? if(is_array($payments[5]) && count($payments[5])>0){ ?>
		<? $list = $payments[5] ?>
		<? include 'payment.php' ?>
	<? } ?>
	</td>
</tr>
<tr class="av av5">
	<td class="aright"></td>
	<td>
		<a href="javascript:;" class="addvnesok button p5" rel="5"><?=t('Добавить взнос')?></a>
	</td>
</tr>
	
<? if($user['status']==20 || $user['ban']==20){ ?>

    <? if(!($zayava['id'] && !$zayava['kvitok'])){ ?>
        <? if(is_array($payments[1]) && count($payments[1])>0){ ?>
        <? $p = user_payments_peer::instance()->get_item($payments[1][0]) ?>
        <? } ?>
            <? if(!$p['approve']){ ?>
            <tr>
                <td class="aright" width="150"></td>
                <td style="padding-top:20px">
                    <b><?=t('Членский вступительный')?></b>
                    <? if($zayava['id']){ ?>
                        <span>(<?=t('Запланировано')?> 30 грн.)</span>
                    <? } ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="p10 type1">
                <? if(is_array($payments[1]) && count($payments[1])>0){ ?>
                    <? $list = $payments[1] ?>
                    <? include 'payment.php' ?>
                <? } ?>
                </td>
            </tr>
            <? if(!(is_array($payments[1]) && count($payments[1])>0)){ ?>
            <tr class="av av1">
                <td class="aright"></td>
                <td>
                    <a href="javascript:;" class="addvnesok button p5" rel="1"><?=t('Добавить взнос')?></a>
                </td>
            </tr>
            <? } ?>
        <? } ?>
    <? } ?>

    <tr>
        <td class="aright"></td>
        <td style="padding-top:20px">
            <b><?=t('Членский ежемесячный')?></b>
            <? if($zayava['id']){ ?>
                <span>(<?=t('Запланировано')?> <?=$zayava['vnesok']?> грн.)</span>
            <? } ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="p10 type2">
        <? if(is_array($payments[2]) && count($payments[2])>0){ ?>
            <? $list = $payments[2] ?>
            <? include 'payment.php' ?>
        <? } ?>
        </td>
    </tr>
    <tr class="av av2">
        <td class="aright"></td>
        <td>
            <a href="javascript:;" class="addvnesok button p5" rel="2"><?=t('Добавить взнос')?></a>
        </td>
    </tr>

<? } ?>

    <tr>
        <td class="aright" width="150"></td>
        <td style="padding-top:20px">
            <b><?=t('Благотворительный');?></b>
            <? if($zayava['id'] && $zayava['bvnesok']){ ?>
                <span>(<?=t('Запланировано')?> <?=$zayava['bvnesok']?> грн.)</span>
            <? } ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="p10 type3">
        <? if(is_array($payments[3]) && count($payments[3])>0){ ?>
            <? $list = $payments[3] ?>
            <? include 'payment.php' ?>
        <? } ?>
        </td>
    </tr>
    <tr class="av av3">
        <td class="aright"></td>
        <td>
            <a href="javascript:;" class="addvnesok button p5" rel="3"><?=t('Добавить взнос')?></a>
        </td>
    </tr>

    <tr>
        <td class="aright" width="150"></td>
        <td style="padding-top:20px">
            <b><?=t('Целевой (местный) благотворительный');?></b>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="p10 type4">
        <? if(is_array($payments[4]) && count($payments[4])>0){ ?>
            <? $list = $payments[4] ?>
            <? include 'payment.php' ?>
        <? } ?>
        </td>
    </tr>
    <tr class="av av4">
        <td class="aright"></td>
        <td>
            <a href="javascript:;" class="addvnesok button p5" rel="4"><?=t('Добавить взнос')?></a>
        </td>
    </tr>

</table>

<form id="payments_conf_form">
    <input type="hidden" name="type" value="payments" />
<table width="100%" class="fs12">
    <tr>
        <td class="aright"></td>
        <td style="padding-top:10px">
            <b><?=t('Конфиденциальность')?></b>
        </td>
    </tr>
    <tr>
            <td width="150" class="aright"></td>
            <td><?=tag_helper::select('confidence', user_helper::get_payment_access(), array('value'=>$user_desktop['confidence']))?></td>
    </tr>
    <tr>
            <td></td>
            <td style="padding-top:20px">
                    <input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
                    <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                    <?=tag_helper::wait_panel('payments_wait') ?>
                    <div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
                    <div class="error hidden mr10 mt10"><?=t('Вы заполнили не все поля. Часть информации может быть не сохранена')?></div>
            </td>
    </tr>
</table>
</form>

<div id="payformholder">
<form action="/" method="post" class="hide form" id="payform" style="margin-top:5px;padding:5px 5px 0 5px;">
<table border="0" cellpadding="0" cellspacing="0" style="margin-bottom:0">
        <tr>
                <td class="aright" width="135"><?=t('Сумма')?></td>
                <td>
                    <input type="hidden" name="id" class="pid" value="" />
                    <input type="hidden" name="new" class="new" value="0" />
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
        <tr class="paytext hide">
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
        <tr class="paytypes method1 mth hide">
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

</div>


<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker-uk.js"></script>
<script type="text/javascript">
var $payform = $('#payform');
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

    $payform.find('input.pdate').datepicker(opt);
    radiocheckbox($payform);
    methodcheckbox($payform);
    bindLinks($('#payments_table'));

    $('#payedit').click(function(){
        var error = 0;
        $('#paymwait').show();
        $.post('/profile/payment_check',{
            'id':$payform.find('input.pid').val(),
            'type':$payform.find('input.ptype').val(),
            'month':$payform.find('select.pmonth').val(),
            'year':$payform.find('select.pyear').val(),
            'user_id':'<?=$user['id']?>'
        },function(response){
            if(response=='error'){
                alert('<?=t('Ошибка! Взнос за этот период уже существует')?>');
                hideForm();
                return false;
            }else{
                $.post('/profile/payment_edit',{
                    'id':$payform.find('input.pid').val(),
                    'type':$payform.find('input.ptype').val(),
                    'summ':$payform.find('input.psumm').val(),
                    'method':$payform.find('input.pmethod:checked').val(),
                    'way':$payform.find('input.pway:visible:checked').val(),
                    'month':$payform.find('select.pmonth').val(),
                    'year':$payform.find('select.pyear').val(),
                    'date':$payform.find('input.pdate').val(),
                    'text':$payform.find('input.ptext').val(),
                    'editview':1,
                    'new':$payform.find('input.new').val(),
                    'user_id':'<?=$user['id']?>'
                },function(response){
                    if(response==1){
                        alert('<?=t('все поля обязательны для заполнения')?>');
                    }else{
                        var $parent = $payform.parent();
                        var tp = $payform.find('input.ptype').val();
                        hideForm();
                        $parent.html(response);
                        if(tp==1){
                            $('.av'+tp).remove();
                        }
                        $('.av').show();
                        bindLinks($parent);
                    }
                    $('#paymwait').hide();
                });
            }
        });
        
    });

    $('#payhide').click(function(){
        hideForm();
    });

    $('a.addvnesok').unbind('click').click(function(){
        $rel = $(this).attr('rel');
        $('.av').show();
        $('.type1, .type2, .type3').children().show();
        $('.av'+$rel).hide();
        $('.type'+$rel).children().hide().end().append($payform);

        if($rel=='2'){
            $payform.find('#payperiod').removeClass('hide');
        }else{
            $payform.find('#payperiod').addClass('hide');
        }

        if($rel=='4'){
            $('.paytypes').hide();
            $('.paytext').show();
        }else{
            $('.paytypes').show();
            $('.paytext').hide();
        }

        $payform.find('input:text, input:hidden, select').not('input:button, input:checkbox').each(function(){
            $(this).val('');
            $(this).removeAttr('disabled');
        });
        if($rel==1){
            $payform.find('input.psumm').val('30').attr('disabled',true);
        }
        $payform.find('input:checkbox').each(function(){
            $(this).removeAttr('checked');
        });
        $('#payedit').val('<?=t('Добавить')?>');
        $payform.find('.mth').hide();
        $payform.find('input.new').val('1');
        $payform.find('input.ptype').val($rel);
        $payform.find('input.pmethod1').attr('checked',true);
        if($rel!='4'){
            $payform.find('.method1').show();
        }
        $payform.find('input.pway1:first').attr('checked',true);
        $payform.show();
    });

});
function bindLinks($obj){
    $obj.find('a.editpayment').unbind('click').click(function(){
        var $this = $('div#payholder'+$(this).attr('rel'));
        var $holder = $this.find('div.paydata');


        if($holder.find('span.ptype').html()=='2'){
            $payform.find('#payperiod').removeClass('hide');
        }else{
            $payform.find('#payperiod').addClass('hide');
        }
        
        $payform.find('input:text, input:hidden, select').not('input:button, input:checkbox').each(function(){
            $(this).val($holder.find('span.p'+$(this).attr('name')).html());
            $(this).removeAttr('disabled');
        });
        if($holder.find('span.ptype').html()=='1'){
            $payform.find('input.psumm').attr('disabled',true);
        }
        
        $payform.find('.mth').hide();
        $payform.find('input:checkbox').removeAttr('checked');
        var method = $holder.find('span.pmethod').html();
        var way = $holder.find('span.pway').html();
        $('#payedit').val('<?=t('Сохранить')?>');

        $payform.find('input.pmethod'+method).attr('checked',true);
        $payform.find('.method'+method).show().find('input.pway'+way).attr('checked',true);
        $('.type1, .type2, .type3').children().show();
        $this.parent().children().hide();
        $this.parent().append($payform);
        $('.av').show();
        $('.av'+$holder.find('span.ptype').html()).hide();
        $payform.show();
    });
}
function hideForm(){
    $('#payform').parent().children().show();
    $('#payform').hide();
    $('#payformholder').append($('#payform'));
    $('.av').show();
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

<? /* ?>
<form>
    <? if ( session::has_credential('admin') ) { ?>
            <input type="hidden" name="id" value="<?=$user_desktop['user_id']?>">
    <? } ?>
    <input type="hidden" name="type" value="payments">
    <table width="100%" class="fs12" id="payments_table">

        <? $payments_cnt['total']=0 ?>
        <? foreach($arr as $key=>$val){ ?>
        <? if(count($payments[$key])==0)$payments[$key][] = 0 ?>
        <? if(!($key==1 && ($zayava['id'] && !$zayava['kvitok']))){ ?>
        <tr <?=($key==1)?'class="vstup"':''?>>
            <td class="aright"></td>
            <td style="padding-top:20px">
                <b><?=$val?></b>
                <? if($zayava['id']){ ?>
                    <span>(<?=t('Запланировано')?> 
                    <? if($key==1){ ?>
                        30
                    <? }elseif($key==2){ ?>
                        <?=$zayava['vnesok']?> 
                    <? }else{ ?>
                        <?=$zayava['bvnesok']?> 
                    <? } ?>
                    грн.)</span>
                <? } ?>
                <br class="mb10" />
                <span class="fs11 mt">*<?=t('Все поля обязательны для заполнения')?></span>
            </td>
        </tr>
        <tr <?=($key==1)?'class="vstup"':''?>>
            <td colspan="2" id="payments_<?=$key?>_holder">
            <? $payments_cnt[$key] = 0 ?>
            <? foreach($payments[$key] as $payment){ ?>
            <? if($payment)$pm = user_payments_peer::instance()->get_item($payment) ?>

            <? if(!($key==1 && db::get_scalar('SELECT id FROM user_payments WHERE user_id = '.$user_desktop['user_id'].' AND approve != 0 AND  type = 1'))){ ?>
            <div class="paymentsdiv">
            <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom:0">
                <tr>
                        <td class="aright" width="195"><?=t('Сумма')?></td>
                        <td>
                            <input type="hidden" class="typ" name="typ[]" value="<?=$key?>" />
                            <input type="hidden" name="previd[]" value="<?=$pm['id']?>" />
                            <input style="width:150px" name="summ[]" class="text <?=($key==1)?'hide':''?>" type="text" value="<?=intval($pm['summ'])?>" />
                            <? if($key==1){ ?>
                            <?='30 грн.'?>
                            <? }else{ ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="button" name="cleanfields" class="button_gray cleanfields" value=" <?=t('Очистить')?> ">
                            <input type="button" name="clear" class="button_gray <?=($payments_cnt[$key]==0)?'hide':''?> clear" value=" <?=t('Убрать')?> ">
                            <? } ?>
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Дата сплати')?></td>
                        <td>
                            <input style="width:150px" name="date[]" id="pm_data_<?=$payments_cnt['total']?>" class="text pm_data" type="text" value="<?=($pm['date'])?date("d-m-Y",$pm['date']):''?>" />
                        </td>
                </tr>

                <tr <?=($key!=2)?'class="hide"':''?>>
                        <td class="aright"><?=t('Период платежа')?></td>
                        <td>
                            <? $months = user_helper::get_months() ?>
                            <? unset($months[0]); ?>
                            <?=tag_helper::select('month[]', $months, array('value'=>($pm['period'])?date("n",$pm['period']):''))?>
                            <?=tag_helper::select('year[]', user_helper::get_years(2011),array('value'=>($pm['period'])?date("Y",$pm['period']):''))?>
                        </td>
                </tr>

                <? $paytypes = user_helper::get_payment_types() ?>
                <tr>
                        <td class="aright"><?=t('Вид')?></td>
                        <td>
                            <input type="checkbox" name="method[]" <?=(!$pm['method'] || $pm['method']==1)?'checked="checked"':''?> value="1" />&nbsp;<?=$paytypes[0][1]?>
                            <input type="checkbox" name="method[]" <?=($pm['method']==2)?'checked="checked"':''?> value="2" />&nbsp;<?=$paytypes[0][2]?>
                        </td>
                </tr>
                <tr class="method1 mth <?=(!$pm['method'] || $pm['method']==1)?'':'hide'?>">
                        <td class="aright"></td>
                        <td>
                            <input type="checkbox" name="way[]" <?=((!$pm['method'] || $pm['method']==1) && (!$pm['way'] || $pm['way']==1))?'checked="checked"':''?> value="1" <?=($pm)?>/>&nbsp;<?=$paytypes[1][1]?>
                            <input type="checkbox" name="way[]" <?=((!$pm['method'] || $pm['method']==1) && $pm['way']==2)?'checked="checked"':''?> value="2" />&nbsp;<?=$paytypes[1][2]?>
                        </td>
                </tr>
                <tr class="method2 mth <?=($pm['method']==2)?'':'hide'?>">
                        <td class="aright"></td>
                        <td>
                            <input type="checkbox" name="way[]" <?=($pm['method']==2 && (!$pm['way'] || $pm['way']==1))?'checked="checked"':''?> value="1" />&nbsp;<?=$paytypes[2][1]?>
                            <input type="checkbox" name="way[]" <?=($pm['method']==2 && $pm['way']==2)?'checked="checked"':''?> value="2" />&nbsp;<?=$paytypes[2][2]?>
                        </td>
                </tr>
            </table>
            </div>
            <? $payments_cnt[$key]++ ?>
            <? $payments_cnt['total']++ ?>
            <? } ?>

            <? unset($pm) ?>
            <? } ?>
            </td>
        </tr>

        <? if($key!=1){ ?>
        <tr>
                <td width="200"></td>
                <td><input type="button" name="pm_add" rel ="<?=$key?>" class="button pm_add" value=" <?=t('Добавить еще один взнос')?> "></td>
        </tr>
        <? } ?>
        <? } ?>

        <? } ?>

        <tr>
                <td></td>
                <td></td>
        </tr>
        <tr>
                <td width="200" style="padding-top:15px" class="aright"><?=t('Конфиденциальность')?></td>
                <td style="padding-top:15px"><?=tag_helper::select('confidence', user_helper::get_payment_access(), array('value'=>$user_desktop['confidence']))?></td>
        </tr>
        <tr>
                <td></td>
                <td style="padding-top:20px">
                        <input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
                        <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                        <?=tag_helper::wait_panel('payments_wait') ?>
                        <div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
                        <div class="error hidden mr10 mt10"><?=t('Вы заполнили не все поля. Часть информации может быть не сохранена')?></div>
                </td>
        </tr>
    </table>
</form>

    
</div>

<script type="text/javascript">
    var cnt = <?=$payments_cnt['total']?>;
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

jQuery(document).ready(function($){
    if(!$('tr.vstup:last').children().children().length)
        $('tr.vstup').hide();

    $('.pm_data').each(function(){$('#'+$(this).attr('id')).datepicker(opt);});
    $('#payments_table').find('.cleanfields').click(function(){inputclean($(this).parent().parent().parent())});
    $('#payments_table').find('.clear').click(function(){inputclear($(this).parent().parent().parent())});

    $('.pm_add').click(function(){
        cnt = cnt + 1;
        var $place = $('#payments_'+$(this).attr('rel')+'_holder div.paymentsdiv:first').clone();
        $('#payments_'+$(this).attr('rel')+'_holder').append($place);
        inputclean($place);
        $place.find('.cleanfields').click(function(){inputclean($place)});
        $place.find('.clear').removeClass('hide').click(function(){inputclear($place)});
        $place.find('.pm_data').attr('id','pm_data_'+cnt);
        $('#pm_data_'+cnt).removeClass('hasDatepicker').datepicker(opt);
        radiocheckbox($place);
        methodcheckbox($place);
    });

    radiocheckbox($('#payments_form'));
    methodcheckbox($('#payments_form'));
});
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
function inputclear(obj){
    obj.remove();
}
function inputclean(obj){
    obj.find('input').not('.clear').not(':checkbox').not('.typ').not('.cleanfields').val('');
    obj.find('textarea').val('');
    obj.find('select').find('option').each(function(){this.selected=false}).find(':first').attr('selected',true);
    obj.find('select.city').html('<option value="0">&mdash;</option>');
}
function _intv(val)
{
    var val = parseInt(val);
    if(isNaN(val))
        return 0;
    else
        return val;
}
</script>
<? */ ?>