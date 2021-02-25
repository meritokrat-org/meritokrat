<style>
    ul {
        margin-bottom: 0px;
    }
</style>
<div id="divarating" class="<?=(request::get('atab')!='rating') ? ' hidden' : ''?>">
    <table>
        <tr>
            <th>
                <?=t('Критерий')?>
            </th>
            <th>
                <?=t('Количество')?>
            </th>
            <th>
                <?=t('Баллы')?>
            </th>
        </tr>
        <? 
        if($rating_types)
        foreach ($rating_types as $k=>$v) {
        ?>
        <tr>
            <td style="color:black;" class="aleft fs12">
                <?=$v['name_'.  getenv('LANGUAGE')]?>
            </td>
            <td class="acenter fs12" style="color:black;">
                <?
                if($ratingData[$v['alias']]) 
                /////////////////////1st kostil`
                    if($v['alias']=='speach') {
                        $speach_count = db::get_scalar("SELECT COUNT(*) FROM user_desktop_event WHERE user_id=:uid AND (part=2 OR part=0)",array('uid'=>$user['id']));
                        echo ($speach_count);
                    }
                    else 
                        echo $ratingData[$v['alias']] ;
                else 
                    echo ' - ';
                ?>
                <?//=($ratingData[$v['alias']]) ? $ratingData[$v['alias']] : ' - '?>
            </td>
            <td class="acenter fs12" style="color:black;">
                <?=($rating_by_user[$user['id']][$v['alias']]) ? number_format($rating_by_user[$user['id']][$v['alias']], 0, '.', ' ') : ' - '?>
            </td>
        </tr>
        <? } ?>
        <?if($ap_data && $ap_sum) {?>
        <tr>
            <td class="aleft fs12" style="color:black;" colspan="3">
                <?=t('Дополнительные баллы по решению Президии Политсовета МПУ')?>
            </td>
        </tr>
        <?
        
            foreach ($ap_data as $k => $v) {
        ?>
            <tr>
                <td class="aleft fs12" style="color:black;">
                    <div class="ml15"><ul><li><?=$v['reason']?></li></ul></div>
                </td>
                <td>&nbsp;</td>
                <td class="acenter fs12" style="color:black;"><?=$v['points']?></td>
            </tr>
        <? 
            }
        ?>
            <tr>
                <td>
                    <div class="aright fs12" style="color:black;"><?=t('Вобщем дополнительных баллов')?></div>
                </td>
                <td>
                    &nbsp;
                </td>
                <td class="acenter fs12" style="color:black;"><?=$ap_sum?></td>
            </tr>
        <tr>
        <? } ?>
            <th class="aleft fs12" style="color:black;">
                <?=t('Общий рейтинг')?>
            </th>
            <td>&nbsp;</td>        
            <td class="acenter fs12" style="color:black;">
                <b><?=($rating_by_user[$user['id']]['full_rating']) ? number_format($rating_by_user[$user['id']]['full_rating'], 0, '.', ' ') : ' - '?></b>
            </td>
        </tr>
    </table>
</div>
