
<tr>
<td colspan="2">

<? if(is_array($p)){ ?>

    <div class="hide pdataholder">
       <span class="pid"><?=$p['id']?></span>
       <span class="ptype"><?=$p['type']?></span>
       <span class="pdate"><?=date("d-m-Y",$p['date'])?></span>
       <span class="psumm"><?=$p['summ']?></span>
       <span class="pmethod"><?=$p['method']?></span>
       <span class="pway"><?=$p['way']?></span>
       <span class="pmonth"><?=date('n',$p['period'])?></span>
       <span class="pyear"><?=date('Y',$p['period'])?></span>
    </div>
    <table style="color: black;" class="fs12 mb5">
       <td width="60" class="bold acenter fs11" style="vertical-align:middle"><i><?=user_helper::get_months(date('n',$p['period']))?> <?=(date('Y',$p['period'])!=date('Y'))?date('Y',$p['period']):''?></i></td>
       <td style="vertical-align:middle">
           <b><?=$p['summ']?></b> грн.,
           <?=user_helper::com_date($p['date'])?>,
           <? if(session::has_credential('admin') || $has_access){ ?>
           , <?=$paytypes[0][$p['method']]?>
           <? if($p['way']){ ?>, <?=$paytypes[$p['method']][$p['way']]?><? } ?>
           <? } ?>
       </td>
       <? if(session::has_credential('admin') || ($has_access && $p['method']==1 && $p['way']==1)){ ?>
       <td width="50" class="acenter" style="vertical-align:middle">
           <? if(!($p['approve']==2 && !session::has_credential('admin'))){ ?>
           <a href="javascript:;" class="fs11 editpayment dib icoedt" title="<?=t('Редактирование')?>"></a>
           <? } ?>
           <? if(in_array(session::get_user_id(), array(2,5,8,29,1360,2546))){ ?>
           <a href="javascript:;" class="fs11 delpayment dib icodel" title="<?=t('Удалить')?>"></a>
           <? } ?>
           <? if($p['approve']!=2 && session::has_credential('admin')){ ?>
           <a href="javascript:;" class="fs11 approvepayment dib icoapprove" title="<?=t('Подтвердить')?>"></a>
           <? } ?>
       </td>
       <? } ?>
    </table>

    <? if(session::has_credential('admin') || $has_access){ ?>
    <? $paylog = user_payments_log_peer::instance()->get_payment($p['id']) ?>
    <? if(count($paylog)>0){ ?>
    <? foreach($paylog as $log_id){ ?>
        <? $log = user_payments_log_peer::instance()->get_item($log_id) ?>
        <p style="color:gray;padding:5px 10px 5px 80px;margin:0">
        <i><?=($log['type']==1)?t('Отредактировано'):t('Подтверждено')?></i>: <?=user_helper::full_name($log['who'], true, array(), false)?> <?=date('d.m.Y',$log['date'])?>.
        <? if($log['payment']){ ?>
            <? $old = unserialize($log['payment']) ?>
            <i><?=t('Предыдущая запись')?></i>: <?=date("d.m.Y",$old['date']).' - '.$old['summ'].' грн., '?>
            <? if($old['type']==2){ ?>
               за
               <?=user_helper::get_months(date('n',$old['period'])).' '.date('Y',$old['period'])?>
               ,
           <? } ?>
           <?=$paytypes[0][$old['method']].', '.$paytypes[$old['method']][$old['way']]?>
        <? } ?>
        </p>
    <? }}}else{ ?>
    <? if($p['approve']==2){ ?>
        <p style="color:gray;padding:5px 10px 5px 80px;margin:0">
        <i><?=t('Подтверждено')?> <?=t('Секретариатом')?></i>
        </p>
    <? } ?>
    <? } ?>

<? }else{ ?>
        <table style="color: black;" class="fs12 mb5">
        <td width="60" class="bold acenter fs11" style="vertical-align:middle"><i><?=user_helper::get_months(date('n',$k))?> <?=(date('Y',$k)!=date('Y'))?date('Y',$k):''?></i></td>
       <td style="vertical-align:middle">
           <i style="color:gray"><?=t('Взнос не сделан')?></i>
       </td>
       <? if(session::has_credential('admin') || $has_access){ ?>
       <td width="80">
       </td>
       <? } ?>
    </table>
<? } ?>

</td>
</tr>
