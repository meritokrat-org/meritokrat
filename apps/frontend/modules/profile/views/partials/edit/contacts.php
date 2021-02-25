<form id="contacts_form" class="form mt10 hidden">
    <?php if (session::has_credential('admin') || $access) { ?>
        <input type="hidden" name="id" value="<?= $user_data['user_id'] ?>">
    <?php } ?>
    <input type="hidden" name="type" value="contacts">
    <table width="100%" class="fs12">

        <tr>
            <td></td>
            <td class="aleft"><b><?= t('Контакты') ?></b></td>
        </tr>
        <tr>
            <td class="aright"><?= t('Email') ?></td>
            <td>
                <input name="email" rel="<?= t('') ?>" class="text" type="text"
                       value="<?= stripslashes(htmlspecialchars($user_data['email'])) ?>"/>
            </td>
        </tr>
        <tr>
            <td class="aright"><?= t('Мобильный телефон') ?></td>
            <td>
                <input name="mobile" rel="<?= t('') ?>" class="text" type="text"
                       value="<?= stripslashes(htmlspecialchars($user_data['mobile'])) ?>"/>
            </td>
        </tr>
        <tr>
            <td class="aright"><?= t('Рабочий телефон') ?></td>
            <td>
                <input name="work_phone" rel="<?= t('') ?>" class="text" type="text"
                       value="<?= stripslashes(htmlspecialchars($user_data['work_phone'])) ?>"/>
            </td>
        </tr>
        <tr>
            <td class="aright"><?= t('Домашний телефон') ?></td>
            <td>
                <input name="home_phone" rel="<?= t('') ?>" class="text" type="text"
                       value="<?= stripslashes(htmlspecialchars($user_data['home_phone'])) ?>"/>
            </td>
        </tr>
        <tr>
            <td class="aright"><?= t('Другой телефон') ?></td>
            <td>
                <input name="phone" rel="<?= t('') ?>" class="text" type="text"
                       value="<?= stripslashes(htmlspecialchars($user_data['phone'])) ?>"/>
            </td>
        </tr>
        <tr>
            <td class="aright"><?= t('Сайт') ?></td>
            <td>
                <input name="site" rel="<?= t('') ?>" class="text" type="text"
                       value="<?= stripslashes(htmlspecialchars($user_data['site'])) ?>"/>
            </td>
        </tr>

        <tr>
            <td class="aright"><?= t('Skype') ?></td>
            <td>
                <input name="skype" rel="<?= t('') ?>" class="text" type="text"
                       value="<?= stripslashes(htmlspecialchars($user_data['skype'])) ?>"/>
            </td>
        </tr>
        <!--tr>
                        <td class="aright"><?= t('Другое') ?></td-->
        <?php $user_contacts = unserialize($user_data['contacts']); ?>
        <?php foreach (user_data_peer::get_contact_types() as $type => $type_title) { ?>
            <tr>
                <td class="aright"><?= $type_title ?></td>
                <td><input type="text" name="contacts[<?= $type ?>]" class="text"
                           value="<?= stripslashes(htmlspecialchars($user_contacts[$type])) ?>"/></td>
            </tr>
        <?php } ?>
        <!--/td-->
        <tr>
            <td></td>
            <td>
                <input type="submit" name="submit" class="button" value=" <?= t('Сохранить') ?> ">
                <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray"
                       value=" <?= t('Отмена') ?> ">
                <?= tag_helper::wait_panel('contacts_wait') ?>
                <div class="success hidden mr10 mt10"><?= t('Изменения сохранены') ?></div>
            </td>
        </tr>
    </table>
</form>