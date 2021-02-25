<? foreach($list as $payment){ ?>
<? $p = user_payments_peer::instance()->get_item($payment) ?>
<? if(!$p['approve']){ ?>
<div id="payholder<?=$p['id']?>">

    <div class="hide paydata">
       <span class="pid"><?=$p['id']?></span>
       <span class="ptype"><?=$p['type']?></span>
       <span class="pdate"><?=date("d-m-Y",$p['date'])?></span>
       <span class="psumm"><?=$p['summ']?></span>
       <span class="pmethod"><?=$p['method']?></span>
       <span class="pway"><?=$p['way']?></span>
       <span class="pmonth"><?=date('n',$p['period'])?></span>
       <span class="pyear"><?=date('Y',$p['period'])?></span>
       <span class="ptext"><?=$p['text']?></span>
    </div>
    <div class="payview" style="color:black;padding: 5px 10px 10px 80px;">
        <div class="left bold" style="width:80px"><i><?=date('d.m.Y',$p['date'])?></i></div>
        <b><?=$p['summ']?></b> грн.,
        <? if($p['type']==2){ ?>
           за
           <?=user_helper::get_months(date('n',$p['period'])).' '.date('Y',$p['period'])?>
           ,
        <? } ?>
        <?=$paytypes[0][$p['method']]?>
        <? if($p['way']){ ?>, <?=$paytypes[$p['method']][$p['way']]?><? } ?>
        <? if($p['text']){ ?>, <?=$p['text']?><? } ?>
        <a href="javascript:;" class="button_gray p5 ml15 editpayment" rel="<?=$p['id']?>"><?=t('Редактировать')?></a>
    </div>

</div>
<? }else{ ?>

<div id="payholder<?=$p['id']?>" class="apay<?=$p['type']?>">

    <div class="payview" style="color:black;padding: 5px 10px 10px 80px;">
        <div class="left bold" style="width:80px"><i><?=date('d.m.Y',$p['date'])?></i></div>
        <b><?=$p['summ']?></b> грн.,
        <? if($p['type']==2){ ?>
           за
           <?=user_helper::get_months(date('n',$p['period'])).' '.date('Y',$p['period'])?>
           ,
        <? } ?>
        <?=$paytypes[0][$p['method']]?>
        <? if($p['way']){ ?>, <?=$paytypes[$p['method']][$p['way']]?><? } ?>
        <? if($p['text']){ ?>, <?=$p['text']?><? } ?>
    </div>

</div>

<? } ?>
<? } ?>