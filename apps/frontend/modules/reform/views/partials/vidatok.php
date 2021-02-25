<div id="vdata<?=$v?>">
<? $finance = ppo_finance_peer::instance()->get_item($v) ?>
<? $finance_log = ppo_finance_log_peer::instance()->get_by_finance($v) ?>
    <span class="vid" style="display:none"><?=$v?></span>
    <span class="vdate_day" style="display:none"><?=date('j',$finance['date'])?></span>
    <span class="vdate_month" style="display:none"><?=date('n',$finance['date'])?></span>
    <span class="vdate_year" style="display:none"><?=date('Y',$finance['date'])?></span>
    <span class="vsumm" style="display:none"><?=$finance['summ']?></span>
    <span class="vtext" style="display:none"><?=stripslashes($finance['text'])?></span>
    <table class="fs12">
        <tr>
            <td class="aright" width="90"><?=date('d.m.Y',$finance['date'])?></td>
            <td style="color:black"><b><?=$finance['summ']?> грн.</b> - <?=stripslashes($finance['text'])?></td>
            <td width="50">
                <a href="javascript:;" class="vedit dib icoedt" rel="<?=$v?>"></a>
                <a href="javascript:;" class="vdelete dib icodel" rel="<?=$v?>"></a>
            </td>
        </tr>
        <tr>
            <td class="aright"></td>
            <td style="color:black">
                <? $flog = ppo_finance_log_peer::instance()->get_item($finance_log[0]) ?>
                <? unset($finance_log[0]);array_reverse($finance_log); ?>
                <?=t('Добавлено')?>: <?=user_helper::full_name($flog['user_id'], true, array(), false).' ('.date('d.m.Y',$flog['date']).')'?>
            </td>
            <td></td>
        </tr>
        <? if(count($finance_log)>0){ ?>
        <tr>
            <td class="aright"></td>
            <td style="color:black">
                <?=t('Отредактировано')?>:              
                <? foreach($finance_log as $f){ ?>
                    <? $flog = ppo_finance_log_peer::instance()->get_item($f) ?>
                    <? $flogusers[] = user_helper::full_name($flog['user_id'], true, array(), false).' ('.date('d.m.Y',$flog['date']).')' ?>
                <? } ?>
                <?=implode(', ',$flogusers)?>
            </td>
            <td></td>
        </tr>
        <? } ?>
    </table>
</div>
           