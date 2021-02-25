<style type="text/css">
    .fs13{
        font-size:13px;
    }
    div.pay_logo {
        margin:1px 5px;
    }
    div.pay_logo:hover
    {
        padding: 0px;
        background-color: white;
        border: 1px solid #d3d3d3;
        -webkit-box-shadow: 0px 0px 4px #999999;
        -moz-box-shadow: 0px 0px 4px #999999;
        -box-shadow: 0px 0px 4px #999999;
        z-index:101;
        height:auto;
        margin: 0 4px !important;
        box-shadow: 0px 0px 4px #999999;

    }    
</style>
<table class="mt10 ml15" style="color:black; width: 730px;">
<tbody><tr id="ps_header">
    <td colspan="2"><div style="text-decoration: none;text-transform: uppercase" class="acenter cbrown bold fs18"><?=t('Оплата взносов')?></div>
        <div class="fs14 mt10">
           <?=t('Вы можете оплатить вступительный, ежемесячный и благотворительный взнос в МПУ одним из следующих способов')?>:<br><br>
        </div>
    </td>
</tr>

<tr id="tr_cc" class="tr_ps" style="background:#eee;">
    <td style="width: 150px;">
        <img class="ml15" src="http://m-p-u.org/img/icons/cards_icon.png" alt="">
    </td>
    <td>
        <div class="cbrown bold fs16 mt10"><?=t('Банковские карты')?></div>
        <div id="cc_text" class="fs13 mt15"><?=t('Сделайте взнос платежной картой Visa или MasterCard')?></div>
        <br>
        <a href="/vnesky/bank" class="cbrown" style="font-size:12px;color:#757575;text-decoration: underline;"><?=t('Перейти к оплате')?> &rarr;</a>

    </td>
</tr>
<tr>
    <td>
    </td>
</tr>
<tr id="ps" style="background:#eee;">
    <td width="150px" id="rowspan" rowspan="2">
        <img class="ml15" src="http://m-p-u.org/img/icons/ps_icon.png" alt="">
    </td>
    <td>
        <div onclick="slideToggle('network_payment')" id="network_payment" class="mt10 pointer cbrown bold fs16" style="text-decoration: none;"><?=t('Платежные системы')?></div>
                <div class="fs13 mt15">
                    <?=t('Воспользуйтесь одной из платежных систем в сети Интернет')?>
                </div>
        <br>
        <a href="javascript:slideToggle('network_payment')" style="font-size:12px;color:#757575;text-decoration: underline;" id="network_payment_lnk"><?=t('Выберите платежную систему')?> &rarr;</a>
    </td>
</tr>
<tr class="tr_ps" style="background:#eee;">
    <td>
        <div class="hide" id="network_payment_box">
        <div id="privat" class="left pay_logo ">
            <a href="/vnesky/privat"><img class=" pointer" src="http://m-p-u.org/img/icons/_privat.png" alt=""></a>
        </div>
        <div id="liqpay" class="left pay_logo">
            <a href="/vnesky/liqpay"><img class="pointer" src="http://m-p-u.org/img/icons/_liqpay.png" alt=""></a>
        </div>
        <div id="interkassa" class="left pay_logo">
            &nbsp;<a href="/vnesky/interkassa" style="font-size: 13px; margin: 17px 5px 13px 7px;text-decoration: underline;" class="cbrown bold pointer left"><?=t('Другие платежные системы')?></a>
        </div>
        </div>
    </td>
</tr>
<tr>
    <td>
    </td>
</tr>
<tr class="tr_ps" style="background:#eee;">
    <td>
        <img class="ml15" src="http://m-p-u.org/img/icons/bank_icon.png" alt="">
    </td>
    <td>
        <div onclick="slideToggle('bank_trans')" class="mt10 pointer cbrown bold fs16"><?=t('Банковский перевод')?></div>
        <div class="fs13 mt15">
            <?=t('Перечислите взнос через кассу банка')?><br><br>
            <a href="javascript:slideToggle('bank_trans')"  style="font-size:12px;color:#757575;text-decoration: underline;" class="cbrown" id="bank_trans_lnk"><?=t('Подробнее')?> &rarr;</a>
            <div class="hide" id="bank_trans_box">
<!--				<p>
                    <u><?=t("Реквизиты фонда для перечисления взносов в ЦИК")?>:</u>
                </p>
                <p>
                    <?=t("Получатель платежа: Политичиская партия \"Меритократичская партия Украины\" код ЕГРПОУ: 37726859 счет № 26000364272 в АТ \"Райфазен Банк Аваль\" МФО: 380805<br>Назначение платежа: \"Благотворительный взнос\"")?>
                </p>-->
                <p>
                    <u><?=t('Реквизиты для перечисления взносов')?>:</u>
                </p>
                <p>
                    <?=t('Получатель платежа: Политическая партия "Меритократическая партия Украины", код ЕДРПОУ: 37726859 счет № 26001336005 в АО "Райфазен Банк Аваль" МФО: 380805')?>
                    <br>
                    <b><?=t('Внимание')?>!</b>
                    <?=t('В поле "Назначение платежа" четко указывайте целевое назначение взноса и период платежа. Например: "Ежемесячный взнос (50 грн.) За 2 месяца и благотворительный взнос (30 грн.)"')?>
                </p>
            </div>
        </div>
    </td>
</tr>
<tr>
    <td>
    </td>
</tr>
<tr class="tr_ps" style="background:#eee;">
    <td>
        <img alt="" src="http://m-p-u.org/img/icons/other_icon.png" class="ml15">
    </td>
    <td>
        <div onclick="slideToggle('other_ways')" class="mt10 pointer cbrown bold fs16 "><?=t('Другие способы платежей')?></div>
        <div class="fs13  mt15">
            <?=t('Узнайте о других способах оплаты взносов')?><br><br>
            <a href="javascript:slideToggle('other_ways')" style="font-size:12px;color:#757575;text-decoration: underline;" class="cbrown" id="other_ways_lnk"><?=t('Подробнее')?> &rarr;</a>
            <div class="hide" id="other_ways_box">
                <ul>
                    <li class="mb10">
                        <?=t('Наличными Председателю, Секретарю или специально определенной уполномоченному лицу в Вашей первичной партийной организации.')?>   
                    </li> 
                    <li class="mb10">
                        <?=t('Наличными непосредственно в')?>
                        <a href="http://maps.yandex.ru/?text=%D0%A3%D0%BA%D1%80%D0%B0%D0%B8%D0%BD%D0%B0%2C%20%D0%9A%D0%B8%D0%B5%D0%B2%2C%20%D0%9F%D0%BE%D0%B4%D0%BE%D0%BB%D1%8C%D1%81%D0%BA%D0%B8%D0%B9%20%D1%80%D0%B0%D0%B9%D0%BE%D0%BD%2C%20%D0%9A%D0%BE%D0%BD%D1%81%D1%82%D0%B0%D0%BD%D1%82%D0%B8%D0%BD%D0%BE%D0%B2%D1%81%D0%BA%D0%B0%D1%8F%20%D1%83%D0%BB%D0%B8%D1%86%D0%B0%2C%202%D0%B0&sll=30.515662%2C50.465082&sspn=0.008211%2C0.005241"><?=t('офисе Секретариата МПУ')?></a>
                        <?=t('(г. Киев, ул. Константиновская 2а, 5-й этаж)')?>    
                    </li> 
                </ul>
            </div>
        </div>
    </td>
</tr>
</tbody></table>

<script type="text/javascript">

    function slideToggle(id) { 
    $('#'+id+'_box').slideToggle(300); 
    
    if($('#'+id+'_lnk').is(':visible')) 
        $('#'+id+'_lnk').hide();
    else 
        $('#'+id+'_lnk').show();
}

jQuery(document).ready(function($){
        $('.under').hover(
            function() {$(this).css('text-decoration','underline')},
            function() {$(this).css('text-decoration','none')}
        );

});

</script>