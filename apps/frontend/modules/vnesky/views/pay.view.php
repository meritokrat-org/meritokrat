<div style="background-color: #eee;" class="ml5 mt10">
    
<table class="mt10">
<tbody>
<tr>
    <td width="260">
        <span class="fs16 bold ml15"><?=$way_title ? t('Платежная система').' '.$way_title : t('Банковские карты')?></span><br>
        <a href="/vnesky" class="ml15 fs11">&larr; назад</a><br>
        &nbsp;<img style="margin-left:40px" src="http://m-p-u.org/img/icons/_<?=$way?>.png" alt="<?=$way?>" />
    </td>
    <td>
    <div class="contact_all_box">
        <form id="ajax_post_form">
            <input type="hidden" value="1" name="submit">
            <input type="hidden" value="<?=request::get('way')?>" id="way" name="way">
            
            <? if (!$is_opened && user_auth_peer::get_rights(session::get_user_id(), 20)) { ?>
                <div class="mt10 fs14">
                    <input type="checkbox" class="input_payment" name="type[]" id="opening_checkbox" value="opening" onclick="Application.ShowHide('opening_div')"> <strong><?=t('Вступительный')?></strong>
                    <div class="hide" id="opening_div"  style="margin-left: 48px;">
											<table style="width: 272px">
												<tr>
													<td width="50%" class="fs12 cgray"><?=t('Сумма')?></td>
													<td class="fs12 cgray">
														<input type="text" name="opening" id="opening" value="30" style="border:1px solid #C1C1C1; width: 68px; padding: 2px" />&nbsp;грн.
													</td>
												</tr>
											</table>
                    </div>
                </div>
            <? } ?>
            <? if (user_auth_peer::get_rights(session::get_user_id(), 20)) { ?>
						<style type="text/css">
						</style>
            <div class="mt10 fs14">
                <input type="checkbox" class="input_payment" name="type[]" value="monthly" id="monthly_checkbox" onclick="Application.ShowHide('monthly_div')" /> <strong><?=t('Ежемесячный')?></strong>
                <div class="hide" id="monthly_div" style="margin-left: 48px;">
									<? $month_nums = range(0, 12); ?>
                  <? unset($month_nums[0]); ?>
									<table style="width: 272px" style="border: 1px solid #AAA">
										<tr>
											<td width="50%" class="fs12 cgray"><?=t('Кол-во месяцев')?></td>
											<td>
												<?=tag_helper::select('months',  $month_nums, array('id'=>'months','style'=>'border:1px solid #C1C1C1; width: 72px; padding: 2px'))?>
											</td>
										</tr>
										<tr>
											<td class="fs12 cgray"><?=t('Сумма')?></td>
											<td class="fs12 cgray">
												<input type="text" name="monthly" id="monthly" value="0<?//=$vnesok?>" style="border:1px solid #C1C1C1; width: 68px; padding: 2px" /> грн.
											</td>
										</tr>
										<tr>
											<td class="fs12 cgray"><?=t('Ваши последние взносы')?></td>
											<td class="fs12 cgray">
												<? $last_payments=db::get_rows("SELECT DISTINCT on (summ) summ FROM user_payments WHERE user_id=".session::get_user_id()." and type=2 GROUP by summ ORDER BY summ DESC LIMIT 5"); ?>
												<? foreach($last_payments as $payment) { ?>
													<a class="pointer bold payments" style="background:white;padding:4px;text-decoration:underline;" onclick="$('#monthly').val(<?=$payment['summ']?>)"><?=$payment['summ']?></a>
												<? } ?>
												<? if( ! count($last_payments)){ ?>
													<?=strtolower(t("Нет взносов"))?>
												<? } ?>
											</td>
										</tr>
									</table>
                </div>
            </div>
            <? } ?>
            <div class="mt10 fs14">
                <input type="checkbox" class="input_payment" name="type[]" value="donate" id="donate_checkbox" onclick="Application.ShowHide('donate_div');$('#donate').val(0)" <?=user_auth_peer::get_rights(session::get_user_id(), 20) ? '' : 'checked="checked"'?> /> <strong><?=t('Благотворительный')?></strong>
                <div class="<?=user_auth_peer::get_rights(session::get_user_id(), 20) ? 'hide' : ''?>" id="donate_div"  style="margin-left: 48px;">
									<table style="width: 272px" style="border: 1px solid #AAA">
										<tr>
											<td width="50%" class="fs12 cgray"><?=t('Сумма')?></td>
											<td class="fs12 cgray">
												<input type="text" name="donate" id="donate" value="" style="border:1px solid #C1C1C1; width: 68px; padding: 2px" /> грн.
											</td>
										</tr>
									</table>
								</div>
            </div>

            <div class="mt10">
                <div class="cbrown fs14"><?=t('Общая сумма взноса')?> <span id="all_pay_count" class="bold">0</span> грн.</div>
                <div class="cgray fs11"><?=t('Коммисия')?> <span id="commission"><?=$commission?></span>% <span id="total_commission">0</span> грн.</div>
            </div>

            <div class="mt10">
                <div class="cbrown fs14"><?=t('К оплате')?> <span id="all_pay" class="bold">0</span> грн. </div>
                <div class="cgray fs11">(<?=t('с комиссией банка')?>)</div>
            </div>

                <input type="hidden" name="total" id="pay_count">
                <input type="hidden" name="total_fees" id="pay_count_fees">
        </form>
    </div>
          <div class="mt10">  
                <input type="button" value="Перейти до сплати" class="button_submit button input_payment" id="submit_button">
                <img align="absmiddle" width="15" id="wait" class="hide ml10" alt="loading" src="https://s1.meritokrat.org/common/loading.gif">
          </div>
    
        
    <!-- liqpay start -->
    <div style="float: left; width: 100%; background: none repeat scroll 0% 0% rgb(230, 239, 194); color: red; line-height: 50px; display: none; text-align: center;" id="errors"></div>
    <form id="send_form" method="POST" action="https://www.liqpay.com/?do=clickNbuy">
          <input type="hidden" value="" name="operation_xml">
          <input type="hidden" value="" name="signature">
          <input type="sumbit" style="visibility: hidden;" id="sbtn">
    </form>
    <!-- liqpay end -->
    
    <!-- privat24 start -->    
    <form id="privat24_form" method="POST" action="https://api.privatbank.ua:9083/p24api/ishop">
            <input type="hidden" value="10" id="amt" name="amt">
            <input type="hidden" value="UAH" id="ccy" name="ccy">
            <input type="hidden" value="63061" name="merchant">
            <input type="hidden" value="" id="order_id" name="order">
            <input type="hidden" value="Партийные взносы" name="details">
            <input type="hidden" value="" name="ext_details">
            <input type="hidden" value="privat24" name="pay_way">
            <input type="hidden" value="https://meritokrat.org/vnesky/thanks" name="return_url">
            <input type="hidden" value="https://meritokrat.org/vnesky/p24" name="server_url">
            <input type="submit" value="Оплатить" id="sbtn" class="hide">
    </form>
    <!-- privat24 end -->
    
    <!-- interkassa start -->
    <form id="interkassa_form" accept-charset="utf-8" enctype="application/x-www-form-urlencoded" method="post" action="https://interkassa.com/lib/payment.php" name="payment">
            <input type="hidden" value="3E192755-6391-F9DD-1E5E-C758CFC69DBF" name="ik_shop_id">
            <input type="hidden" value="1.00" id="ik_payment_amount" name="ik_payment_amount">
            <input type="hidden" value="" id="ik_payment_id" name="ik_payment_id">
            <input type="hidden" value="<?=t('Партийные взносы')?>" name="ik_payment_desc">
            <input type="submit" class="hide" value="Оплатить" name="process">
    </form>
    <!-- privat24 end -->
    

</td>
</tr>
</tbody>
</table>
     
</div>

<script type="text/javascript">
function _intv(val)
{
    var val = parseInt(val);
    if(isNaN(val))
        return 0;
    else
        return val;
}
$(document).ready(function($){
    
    function change_total(){
        if($('#opening_checkbox').is(':checked')==false)
            {
                var opening = 0;            
            }
            else
            {
                var opening = _intv($('#opening').val());   
            }
    
        if($('#monthly_checkbox').is(':checked')==false)
            {
                var monthly = 0;            
            }
            else
            {
                var monthly = _intv($('#monthly').val())*_intv($('#months').val());  
            }
    
        if($('#donate_checkbox').is(':checked')==false)
            {
                var donate = 0;            
            }
            else
            {
                var donate = _intv($('#donate').val());  
            }
            
            
        var pay_count = opening+donate+monthly;    
        
        var commission = $('#commission').html();
        
        var commission = Math.ceil(pay_count * _intv(commission))/100;
        

        $('#all_pay_count').html(pay_count);
        $('#pay_count').val(pay_count);
        $('#pay_count_fees').val(pay_count+commission);
        $('#total_commission').html(commission);
        $('#all_pay').html(pay_count+commission);
        
        
    }
$('.input_payment').click(change_total);
$('.input_payment').change(change_total);
$('.input_payment').keydown(change_total);
$('.input_payment').mouseleave(change_total);
$('.payments').click(change_total);

        $('#submit_button').click(function(){
            var way=$('#way').val();
	    var amount = $('#pay_count').val();

            $('#wait').show();
            $.ajax({
                type: 'post',
                url: '/vnesky/add',
                data: $('#ajax_post_form').serialize(),
                beforeSend: function() {},
                success: function(resp) {
                   
                   $data = eval("("+resp+")");
                   if($data.error) {$('#errors').fadeIn(500, function(){ $(this).html($data.error); }).fadeOut(2500,function() {$(this).html('')});}
                   if (way=='privat' && resp>0)
                   {
                           $('#amt').val(amount);
                           $('#order_id').val(resp);
                           $('#privat24_form').submit();
                   }
                   else if (way=='interkassa' && resp>0)
                   {
                           $('#ik_payment_amount').val(amount);
                           $('#ik_payment_id').val(resp);
                           $('#interkassa_form').submit();
                   }
           <? /*if (session::has_credential('admin')) { ?>
                    else if (way=='bank')
                       {
                           window.location = 'http://test.robokassa.ru/Index.aspx?MrchLogin=meritokrat&OutSum=' + amount + '&InvId=' + $data.id + '&Desc=партийные взносы&SignatureValue=' + $data.crc + '';
                       }
           <? } */?>
                   else if($data.encoded_xml && $data.signature)
                   {
                           $('#send_form').find('input[name="operation_xml"]').val($data.encoded_xml);
                           $('#send_form').find('input[name="signature"]').val($data.signature);
                           $('#send_form').submit();
                   }
                   else { $('#errors').fadeIn(500, function(){ $(this).html('Сталася помилка... Спробуйте ще раз'); }).fadeOut(2500,function() {$(this).html('')});}
                   $('#wait').fadeOut(500);
                }
            });
            
        });

});
</script>