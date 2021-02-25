<style>
    #referals_table td {
        border: 1px solid #e0e0e0;
        text-align: center;
        vertical-align: middle;
    }
</style>
<div class="left mt10" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10 mt10" style="width: 62%;">
    <h1 class="column_head">Джерела приєднання</h1>
    <table id="referals_table">
        <tr>
            <th>Реферал</th>
            <th>Кількість користувачів</th>
            <th>Процентне відношення</th>
        </tr>
    <?if($results) {
        foreach ($results as $type_id=>$user_ids) {
            if($type_id!=6) {?>
                <tr style="background: #f0f0f0;">
                    <td><?=user_shevchenko_data_peer::get_referals($type_id);?></td>
                    <td><?=count($results[$type_id])?></td>
                    <td><?=number_format((count($results[$type_id])/$all_users)*100,1)?></td>
                </tr>
            <? } ?>
            <?if($type_id==3) {
                $social_count = count($results[$type_id]);
                ?>
                <tr>
                    <td>Facebook</td>
                    <td><?=$facebook?></td>
                    <td><?=number_format(($facebook/$social_count)*100,1)?></td>
                </tr>  
                <tr>
                    <td>Vkontakte</td>
                    <td><?=$vk?></td>
                    <td><?=number_format(($vk/$social_count)*100,1)?></td>
                </tr> 
                <tr>
                    <td>Інша мережа</td>
                    <td><?=$other?></td>
                    <td><?=number_format(($other/$social_count)*100,1)?></td>
                </tr> 
            <? } ?>
        <? } ?>
    <? } ?>
        <tr style="background: #f0f0f0;">
            <td>Інше джерело</td>
            <td><?=$other?></td>
            <td><?=number_format(($other/$all_users)*100,1)?></td>
        </tr>
    </table>
</div>
