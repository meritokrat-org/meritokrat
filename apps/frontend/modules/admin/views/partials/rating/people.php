<table>
    <tr>
        <th>
            <a href="/admin/rating?type=people&direct=<?= (request::get('direct') == 'ASC') ? 'DESC' : 'ASC' ?>"><?= t(
                        'Рейтинг'
                ) ?></a>
        </th>
        <th>
            <?= t('Член партии') ?>
        </th>
        <th class="aright">
            <div style="margin-right: 25px;">
                <a href="/admin/rating?type=people&direct=<?= (request::get(
                                'direct'
                        ) == 'ASC') ? 'DESC' : 'ASC' ?>"><?= t('Баллы') ?></a>
            </div>
        </th>
    </tr>
    <?
    $page = request::get_int('page');
    $direct = request::get('direct', 'DESC');

    if ($direct != 'ASC') {
        $i = ($page) ? 50 * ($page - 1) : 0;
    } else {
        $i = ($page) ? ($councilMembers - 50 * ($page - 1)) : $councilMembers;
    }


    foreach ($list as $id) {
        if ($direct != 'ASC') {
            $i++;
        } else {
            $i--;
        }
        $rating = rating_helper::get_rating_by_id($id);
        ?>
        <tr id="user_<?= $id ?>_name">
            <td class="fs12 acenter" style="width: 5%;" style="color:black;">
                <b><?= $i; ?></b>
            </td>
            <td class="fs12" style="color:black;">
                <?= user_helper::full_name($id, true, [], false); ?>
            </td>
            <td class="aright fs12" style="color:black;">
                <div style="margin-right: 25px;">
                    <a href="javascript:;" onClick="showDetail('<?= $id ?>')">
                        <b><?= ($by_users[$id]['full_rating']) ? number_format(
                                    $by_users[$id]['full_rating'],
                                    0,
                                    '.',
                                    ' '
                            ) : '0' ?></b>
                    </a>
                </div>
            </td>
        </tr>
        <? foreach ($by_users[$id] as $field => $points) { ?>
            <tr class="hidden form_bg detail_<?= $id ?>_info">
                <td class="fs12 border1" style="color:black;" colspan="2">
                    <?= ($alias2names[$field]) ?>
                </td>
                <td class="aright fs12 border1" style="color:black; width: 25%;">
                    <div style="margin-right: 25px;">
                        <a href='/profile/desktop?id=<?= $id ?>&tab=<?= (in_array(
                                $field,
                                ['guest', 'supporters', 'meritokrats', 'mpu_members']
                        )) ? 'people&'.$field.'=1' : $field ?>'><b><?= ($points) ? number_format(
                                        $points,
                                        0,
                                        '.',
                                        ' '
                                ) : ' - ' ?></b></a>
                    </div>
                </td>
            </tr>
        <? } ?>
    <? } ?>
</table>
<div class="clear"></div>
<div class="right pager"><?= pager_helper::get_full($pager) ?></div>
<script>
    function showDetail(id) {
        //_this = $('#detail_'+id+'_head');
        _this2 = $('.detail_' + id + '_info');
        _name = $('#user_' + id + '_name');
        if (_this2.hasClass('hidden')) {
            _this2.removeClass('hidden');
            _name.find('td').css('background', '#cccccc');
        } else {
            _this2.addClass('hidden');
            _name.find('td').css('background', '#fff');
        }
    }
</script>
<style>
    .detail_table,
    .detail_table td,
    .detail_table th {
        border: 1px solid #ccc;
        border-collapse: collapse;
    }
</style>