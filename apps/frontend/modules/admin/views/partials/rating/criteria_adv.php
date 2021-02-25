<?php if ('invites' !== $field) { ?>
    <table>
        <th style="width: 65%;"><?= t('Член партии') ?></th>
        <th><a href='/admin/rating&type=criteria_adv&field=<?= $field ?>&direct=<?= 'ASC' === request::get(
                    'direct'
            ) ? 'DESC' : 'ASC' ?>'><?= t('Баллы') ?></a></th>
        <tr>
            <td colspan="2">
                <?php
                $i = 50 * request::get_int('page');
                foreach ($list as $id => $item) {
                    $i++; ?>
                    <div style="font-size: 9pt;">
                        <div style=" float: left; width: 385px;">
                            <b style="float: left;margin-right: 5px;"><?= $i ?>.&nbsp;<?= user_helper::full_name(
                                        $item,
                                        true,
                                        [],
                                        false
                                ); ?></b>
                        </div>
                        <b><a href="/profile/desktop?id=<?= $item ?>&tab=<?= (in_array(
                                    $field,
                                    ['guest', 'supporters', 'meritokrats', 'mpu_members']
                            )) ? 'people&'.$field.'=1' : $field ?>"><?= ($by_users[$item][$field]) ? number_format(
                                        $by_users[$item][$field],
                                        0,
                                        '.',
                                        ' '
                                ) : '0' ?></a></b>
                    </div>
                    <div class="clear gray_line"></div>
                <?php } ?>
            </td>
        </tr>
    </table>
    <div class="clear"></div>
    <div class="right pager"><?= pager_helper::get_full($pager) ?></div>
<?php } else { ?>
    <table class="stat">

        <?php foreach ($list as $user) { ?>
            <tr data-id="<?= $user['id'] ?>">
                <td class="p-0 align-middle" style="width: 25px">
                    <img src="https://image.meritokrat.org/<?= user_helper::photo_path($user['id'], 'm') ?>"
                         class="img-fluid w-100"/>
                </td>
                <td class="align-middle">
                    <a href="/profile-<?= $user['id'] ?>"><?= $user['first_name'] ?> <?= $user['last_name'] ?></a>
                </td>
                <td class="align-middle" style="text-align: center; font-weight: bold">
                    <b data-inviter-id="<?= $user['id'] ?>" class="invited"
                       style="cursor: pointer"><?= $user['invited'] ?></b>
                </td>
            </tr>
            <tr data-inviter-id="<?= $user['id'] ?>" class="hide"></tr>
        <?php } ?>
    </table>
<?php } ?>

<?php if (session::has_credential('admin')) { ?>
    <script>
        $(document).ready(function () {
            $('.invited').click(function () {
                var id = $(this).attr('data-inviter-id');

                $.post('/admin/rating_ajax?act=get_invited_list', {
                    inviter: id<?= null !== $status ? sprintf(', status: %s', $status) : '' ?>,
                }, function (res) {
                    $('tr[data-inviter-id]').hide();
                    $('tr[data-inviter-id=' + id + ']').html(res).show();
                });
            });
        });
    </script>
<?php } ?>