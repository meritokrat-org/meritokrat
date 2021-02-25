<table width="100%" style="color: black;" class="fs12">

    <tr style="background: transparent url(/static/images/common/desktop_line.png);">
        <td class="bold cbrown" width="35%" rel="info"><?=t('Санкции')?></td>
        <td class="fs11 aright">
        </td>
    </tr>
    <tr><td colspan="2" class="pt5">
<?
$types=ban_peer::get_types();
if(is_array($bans))foreach($bans as $b){?>
<div><?=date("d-m-Y",$b['start_time'])?> - обмежений у правах <?=$types[$b['days']]?>, <?= user_helper::full_name($b['admin_id'], true, array(), false)?></div>
<? }else echo "не застосовувалися"; ?></td>
</tr>
</table>