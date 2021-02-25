<?php
$function1 = (int) ppo_members_peer::instance()->get_user_by_function(1, $group['id']);
$function5 = (int) ppo_members_peer::instance()->get_user_by_function(ppo_members_peer::ACTING_HEAD, $group['id']);
$function2 = (int) ppo_members_peer::instance()->get_user_by_function(2, $group['id']);
$function6 = (int) ppo_members_peer::instance()->get_user_by_function(ppo_members_peer::ACTING_MANAGER, $group['id']);
$commissionMembers = ppo_members_peer::instance()->get_users_by_function(3, $group['id']);
$councilMembers = ppo_members_peer::instance()->get_users_by_function(4, $group['id']);
?>
<form id="leadership_form" action="/ppo/edit?type=leadership&submit=1&id=<?= $group['id'] ?>" class="form mt10 hidden">
    <table width="100%" class="fs12">
        <tr>
            <td style="width: 40%" class="aright mr15 bold"><?= t('ТЕКУЩЕЕ РУКОВОДСТВО') ?></td>
            <td></td>
        </tr>
        <tr>
            <td class="aright mr15 bold"><?= t('Глава') ?></td>
            <td><span id="function1div"><?= user_helper::full_name($function1, true, [], false) ?>
                </span><input type="hidden" id="function1id" name="function1id"/>
                - <a class="one_add_function" rel="function1" href="javascript:"><?= t('Изменить') ?></a></td>
        </tr>
        <tr>
            <td class="aright mr15 bold"><?= t('и.о.Главы') ?></td>
            <td><span id="function5div"><?= user_helper::full_name($function5, true, [], false) ?>
                </span><input type="hidden" id="function5id" name="function5id"/>
                - <a class="one_add_function" rel="function5" href="javascript:"><?= t('Изменить') ?></a></td>
        </tr>
        <tr>
            <td class="aright mr15 bold"><?= t('Руководитель Секретариата') ?></td>
            <input type="hidden" id="function2id" name="function2id"/>
            <td><span id="function2div"><?= user_helper::full_name($function2, true, [], false) ?>
            </span> - <a class="one_add_function" rel="function2" href="javascript:"><?= t('Изменить') ?></a></td>
        </tr>
        <tr>
            <td class="aright mr15 bold"><?= t('и.о.Руководителя Секретариата') ?></td>
            <input type="hidden" id="function6id" name="function6id"/>
            <td><span id="function6div"><?= user_helper::full_name($function6, true, [], false) ?>
            </span> - <a class="one_add_function" rel="function6" href="javascript:"><?= t('Изменить') ?></a></td>
        </tr>
        <tr>
            <td class="aright mr15 bold"><?= t('Члены Совета') ?></td>
            <td>
                <a class="more_add_function" data-insert-before="managers-4" data-field-name="managers[4]"
                   href="javascript:"><?= t('Добавить') ?></a>
            </td>
        </tr>
        <?php foreach ($councilMembers as $m) { ?>
            <tr id="member<?= $m ?>">
                <td></td>
                <td>
                    <input type="hidden" value="<?= $m ?>" name="managers[4][]">
                    <?= user_helper::full_name($m, true, [], false).' - ' ?>
                    <a class="one_delete_function" rel="<?= $m ?>" href="javascript:"><?= t('Удалить') ?></a>
                </td>
            </tr>
        <?php } ?>
        <tr class="managers-4"></tr>

        <tr>
            <td class="aright mr15 bold"><?= t('Члены КРК') ?></td>
            <td>
                <a class="more_add_function" data-insert-before="managers-3" data-field-name="managers[3]"
                   href="javascript:"><?= t('Добавить') ?></a>
            </td>
        </tr>
        <?php foreach ($commissionMembers as $m) { ?>
            <tr id="member<?= $m ?>">
                <td></td>
                <td>
                    <input name="managers[3][]" type="hidden" value="<?= $m ?>">
                    <?= user_helper::full_name($m, true, [], false) ?>
                    - <a class="one_delete_function" rel="<?= $m ?>" href="javascript:"><?= t('Удалить') ?></a>
                </td>
            </tr>
        <?php } ?>
        <tr class="managers-3"></tr>

        <tr>
            <td></td>
            <td>
                <input type="submit" name="submit" class="button" value=" <?= t('Сохранить') ?> ">
                <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray"
                       value=" <?= t('Отмена') ?> ">
                <?= tag_helper::wait_panel() ?>
                <div class="success hidden mr10 mt10"><?= t('Изменения сохранены...') ?></div>
            </td>
        </tr>
        <tr>
            <td style="width: 40%" class="aright mr15 bold"><?= t('ИСТОРИЯ ИЗМЕНЕНИЙ РУКОВОДСТВА') ?></td>
            <td></td>
        </tr>
        <?php load::model('ppo/members_history');
        $councilMembers = ppo_members_history_peer::instance()->get_history($group['id'], 1);
        if ($councilMembers) {
            ?>
            <tr>
                <td class="aright mr15 bold"><?= t('Глава') ?></td>
                <td></td>
            </tr>
            <?php
            foreach ($councilMembers as $m) {
                ?>
                <tr>
                    <td></td>
                    <td>
                        <?= user_helper::full_name($m['user_id'], true, [], false) ?> <?= date(
                                "d.m.Y",
                                $m['date_start']
                        ) ?>
                        - <?= $m['date_end'] ? date("d.m.Y", $m['date_end']) : t('текущий момент') ?>
                    </td>
                </tr>
            <?php }
        }
        $councilMembers = ppo_members_history_peer::instance()->get_history($group['id'], 2);
        if ($councilMembers) {
            ?>
            <tr>
                <td class="aright mr15 bold"><?= t('Ответственный Секретарь') ?></td>
                <td></td>
            </tr>
            <?php
            foreach ($councilMembers as $m) {
                ?>
                <tr>
                    <td></td>
                    <td>
                        <?= user_helper::full_name($m['user_id'], true, [], false) ?> <?= date(
                                "d.m.Y",
                                $m['date_start']
                        ) ?>
                        - <?= $m['date_end'] ? date('d.m.Y', $m['date_end']) : t('текущий момент') ?>
                    </td>
                </tr>
            <?php }
        } ?>
        <?php $councilMembers = ppo_members_history_peer::instance()->get_history($group['id'], 3);
        if ($councilMembers) {
            ?>
            <tr>
                <td class="aright mr15 bold"><?= t('Ревизор') ?></td>
                <td></td>
            </tr>
            <?php
            foreach ($councilMembers as $m) {
                ?>
                <tr>
                    <td></td>
                    <td>
                        <?= user_helper::full_name($m['user_id'], true, [], false) ?> <?= date(
                                'd.m.Y',
                                $m['date_start']
                        ) ?>
                        - <?= $m['date_end'] ? date('d.m.Y', $m['date_end']) : t('текущий момент') ?>
                    </td>
                </tr>
            <?php }
        } ?>
        <?php $councilMembers = ppo_members_history_peer::instance()->get_history($group['id'], 4);
        if ($councilMembers) {
            ?>
            <tr>
                <td class="aright mr15 bold"><?= t('Члены Совета') ?></td>
                <td></td>
            </tr>
            <?php
            foreach ($councilMembers as $m) {
                ?>
                <tr>
                    <td></td>
                    <td>
                        <?= user_helper::full_name($m['user_id'], true, [], false) ?> <?= date(
                                "d.m.Y",
                                $m['date_start']
                        ) ?>
                        - <?= $m['date_end'] ? date("d.m.Y", $m['date_end']) : t('текущий момент') ?>
                    </td>
                </tr>
            <?php }
        } ?>
    </table>
</form>
<script>
    $('.one_add_function').click(function () {
        Application.showUsers($(this).attr('rel'), <?= $group['id'] ?>, 0);
        return false;
    });

    $('.more_add_function').click(function () {
        const {fieldName, insertBefore} = this.dataset;
        Application.showUsersPopup({
            id: insertBefore,
            ppo_id: parseInt('<?= $group['id'] ?>'),
            multi: 1,
            field_name: fieldName,
        });
        return false;
    });

    $('.one_delete_function').click(function () {
        $('#member' + $(this).attr('rel')).remove();
        return false;
    });
</script>