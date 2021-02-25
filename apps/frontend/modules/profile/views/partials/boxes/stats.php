<div id="divastats" class="hide">
    <!--	<div class="pt5 fs11 tab_pane_gray">-->
    <!--		<a rel="invited" href="javascript:;" class="cgray ml10 selected">-->
    <? //= t('Приглашенные') ?><!--</a>-->
    <!---->
    <!--		<div class="clear"></div>-->
    <!--	</div>-->

    <div id="pane_invited" class="content_pane">

        <div class="pt5 fs11 tab_pane_gray">
            <a rel="all" href="javascript:;" class="cgray ml10"><?= t('Все') ?> <?=db::get_scalar('select count(*) from user_auth where invited_by = :inviter', ['inviter' => $user['id']])?></a>
            <a rel="active" href="javascript:;" class="cgray ml10"><?= t('Активные') ?> <?=db::get_scalar('select count(*) from user_auth where invited_by = :inviter and active = :active', ['inviter' => $user['id'], 'active' => 1])?></a>
            <a rel="not_active" href="javascript:;" class="cgray ml10 selected"><?= t('Не активные') ?> <?=db::get_scalar('select count(*) from user_auth where invited_by = :inviter and active = :active', ['inviter' => $user['id'], 'active' => 0])?></a>

            <? if (session::has_credential('admin')) { ?>
                <a rel="hidden" href="javascript:;" class="cgray ml10">* <?= t('Скрытые') ?></a>
            <? } ?>

            <div class="clear"></div>
        </div>

        <style>
            .stat {
                width: 100%;
            }

            .stat td {
                padding: 0 !important;
            }

            .id {
                width: 45px;
                text-align: center;
            }

            .name {
                width: 250px;
                text-align: center;
            }

            .invites {
                width: 80px;
                text-align: center;
            }

            .actions {
                width: 55px;
                text-align: center;
            }
        </style>

        <div id="pane_all" class="content_pane">
            <table class="stat">
                <tr>
                    <td class=><?= t('Имя') ?></td>
                    <td class="status"><?= t('Статус') ?></td>
                    <td class="invites"><?= t('Приглашений') ?></td>
                    <td class="actions"><?= t('Действие') ?></td>
                </tr>
                <? foreach ($invited as $user) {
                    $user['data'] = user_data_peer::instance()->get_item($user['id']);
                    ?>
                    <tr data-id="<?= $user['id'] ?>">

                        <td>
                            <a href="/profile-<?= $user['id'] ?>"><?= $user['data']['first_name'] ?> <?= $user['data']['last_name'] ?></a><br/>
                        </td>
                        <td>
                            <span style="color:#888"><?= date('d.m.Y', $user['last_invite']) ?></span>
                        </td>
                        <td style="text-align: center" id="result<?= $user['id'] ?>">
                            <?= (db_key::i()->exists($user['id'].'_invited_again')) ? db_key::i()->get(
                                    $user['id'].'_invited_again'
                            ) : '1'; ?>
                        </td>
                        <td>
                            <table style="margin: 0px;" class="aleft">
                                <tr>
                                    <? if (!$user['active']) { ?>
                                        <td style="padding: 0px 5px 0 0 ;">
                                            <img class="pointer" onClick="reSend('<?= $user['id'] ?>');"
                                                 alt="<?= t('Повторное приглашение') ?>"
                                                 style="width: 15px; border: 1px solid black; height: 12px;"
                                                 src="/static/images/icons/4.4.jpg">
                                        </td>
                                    <? } ?>
                                    <? if (session::has_credential('admin')) { ?>
                                        <td style="padding: 0px 5px 0 0 ;">
                                            <a href="/profile/edit?id=<?= $user['id'] ?>">
                                                <img class="pointer" alt="<?= t('Редактировать') ?>"
                                                     src="/static/images/icons/2.2.png">
                                            </a>
                                        </td>
                                    <? } ?>
                                    <td style="padding: 0px;">
                                        <img class="pointer"
                                             onClick="profileController.hideProfile(<?= $user['id'] ?>,'0');"
                                             alt="<?= t('Удалить') ?>" src="/static/images/icons/3.3.png">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                <? } ?>
            </table>
        </div>

        <div id="pane_active" class="hide content_pane">
            <table class="stat">
                <tr>
                    
                    <td class=""><?= t('Имя') ?></td>
                    <td class="status"><?= t('Статус') ?></td>
                    <td class="invites"><?= t('Приглашений') ?></td>
                    <td class="actions"><?= t('Действие') ?></td>
                </tr>
                <? foreach ($invited as $user) {
                    if ($user['active']) {
                        $user['data'] = user_data_peer::instance()->get_item($user['id']);
                        ?>
                        <tr data-id="<?= $user['id'] ?>">
                            <td>
                                <a href="/profile-<?= $user['id'] ?>"><?= $user['data']['first_name'] ?> <?= $user['data']['last_name'] ?></a><br/>
                            </td>
                            <td>
                                <span style="color:#888"><?= date('d.m.Y', $user['last_invite']) ?></span>
                            </td>
                            <td style="text-align: center" id="result<?= $user['id'] ?>">
                                <?= (db_key::i()->exists($user['id'].'_invited_again')) ? db_key::i()->get(
                                        $user['id'].'_invited_again'
                                ) : '1'; ?>
                            </td>
                            <td>
                                <table style="margin: 0px;" class="aleft">
                                    <tr>
                                        <? if (session::has_credential('admin')) { ?>
                                            <td style="padding: 0px 5px 0 0 ;">
                                                <a href="/profile/edit?id=<?= $v['user_id'] ?>">
                                                    <img class="pointer" alt="<?= t('Редактировать') ?>"
                                                         src="/static/images/icons/2.2.png">
                                                </a>
                                            </td>
                                        <? } ?>
                                        <td style="padding: 0px;">
                                            <img class="pointer"
                                                 onClick="profileController.deleteProfile(<?= $v['user_id'] ?>,'0');"
                                                 alt="<?= t('Удалить') ?>" src="/static/images/icons/3.3.png">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    <? } ?>
                <? } ?>
            </table>
        </div>

        <div id="pane_not_active" class="hide content_pane">
            <table class="stat">
                <tr>
                    
                    <td class=""><?= t('Имя') ?></td>
                    <td class="status"><?= t('Статус') ?></td>
                    <td class="invites"><?= t('Приглашений') ?></td>
                    <td class="actions"><?= t('Действие') ?></td>
                </tr>
                <? foreach ($invited as $user) {
                    if (!$user['active']) {
                        $user['data'] = user_data_peer::instance()->get_item($user['id']);
                        ?>
                        <tr data-id="<?= $user['id'] ?>">

                            <td>
                                <a href="/profile-<?= $user['id'] ?>"><?= $user['data']['first_name'] ?> <?= $user['data']['last_name'] ?></a><br/>
                            </td>
                            <td>
                                <span style="color:#888"><?= date('d.m.Y', $user['last_invite']) ?></span>
                            </td>
                            <td style="text-align: center" id="result<?= $user['id'] ?>">
                                <?= (db_key::i()->exists($user['id'].'_invited_again')) ? db_key::i()->get(
                                        $user['id'].'_invited_again'
                                ) : '1'; ?>
                            </td>
                            <td>
                                <table style="margin: 0px;" class="aleft">
                                    <tr>
                                        <td style="padding: 0px 5px 0 0 ;">
                                            <img class="pointer" onClick="reSend('<?= $user['user_id'] ?>');"
                                                 alt="<?= t('Повторное приглашение') ?>"
                                                 style="width: 15px; border: 1px solid black; height: 12px;"
                                                 src="/static/images/icons/4.4.jpg">
                                        </td>
                                        <? if (session::has_credential('admin')) { ?>
                                            <td style="padding: 0px 5px 0 0 ;">
                                                <a href="/profile/edit?id=<?= $user['user_id'] ?>">
                                                    <img class="pointer" alt="<?= t('Редактировать') ?>"
                                                         src="/static/images/icons/2.2.png">
                                                </a>
                                            </td>
                                        <? } ?>
                                        <td style="padding: 0px;">
                                            <img class="pointer"
                                                 onClick="profileController.deleteProfile(<?= $user['user_id'] ?>,'0');"
                                                 alt="<?= t('Удалить') ?>" src="/static/images/icons/3.3.png">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    <? } ?>
                <? } ?>
            </table>
        </div>

        <div id="pane_hidden" class="hide content_pane">
            <table class="stat">
                <tr>
                    
                    <td class=""><?= t('Имя') ?></td>
                    <td class="status"><?= t('Статус') ?></td>
                    <td class="invites"><?= t('Приглашений') ?></td>
                    <td class="actions"><?= t('Действие') ?></td>
                </tr>
                <? foreach ($hiden_inviter as $user) {
                    if (!$user['active']) {
                        $user['data'] = user_data_peer::instance()->get_item($user['id']);
                        ?>
                        <tr data-id="<?= $user['id'] ?>">
                            <td>
                                <a href="/profile-<?= $user['id'] ?>"><?= $user['data']['first_name'] ?> <?= $user['data']['last_name'] ?></a><br/>
                            </td>
                            <td>
                                <span style="color:#888"><?= date('d.m.Y', $user['last_invite']) ?></span>
                            </td>
                            <td style="text-align: center" id="result<?= $user['id'] ?>">
                                <?= (db_key::i()->exists($user['id'].'_invited_again')) ? db_key::i()->get(
                                        $user['id'].'_invited_again'
                                ) : '1'; ?>
                            </td>
                            <td>
                                <table style="margin: 0px;" class="aleft">
                                    <tr>
                                        <td style="padding: 0px 5px 0 0 ;">
                                            <img class="pointer" onClick="reSend('<?= $user['user_id'] ?>');"
                                                 alt="<?= t('Повторное приглашение') ?>"
                                                 style="width: 15px; border: 1px solid black; height: 12px;"
                                                 src="/static/images/icons/4.4.jpg">
                                        </td>
                                        <? if (session::has_credential('admin')) { ?>
                                            <td style="padding: 0px 5px 0 0 ;">
                                                <a href="/profile/edit?id=<?= $user['user_id'] ?>">
                                                    <img class="pointer" alt="<?= t('Редактировать') ?>"
                                                         src="/static/images/icons/2.2.png">
                                                </a>
                                            </td>
                                        <? } ?>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    <? } ?>
                <? } ?>
            </table>
        </div>

    </div>
</div>

<script>
    function reSend(id) {
        $.ajax({
            type: 'post',
            url: '/profile/user_inv_ajax',
            data: {
                id: id,
                act: 'resend',
            },
            success: function (resp) {
                data = eval('(' + resp + ')');
                context.obj_id = data.message;
                if (data.success != 'ok') {
                    context.obj_id = data.message;
                    Popup.show();
                    Popup.setHtml(data.html);
                    Popup.position();
                } else {
                    $('#result' + id).html('<b>' + data.c + '</b>');
                    var date = new Date(parseInt(data.last_invite) * 1000);
                    $('#lastinv' + id).html('<b>' + addZero(date.getDate()) + '.' + addZero(date.getUTCMonth()) + '.' + date.getFullYear() + ' ' + addZero(date.getHours()) + ':' + addZero(date.getMinutes()) + '</b>');
                }
            },
        });
    }

    function addZero(i) {
        return i < 10 ? '0' + i : '' + i;
    }
</script>