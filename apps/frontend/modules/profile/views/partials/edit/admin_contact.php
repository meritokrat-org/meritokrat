
<form id="admin_contact_form" class="form mt10 hidden">
<? if ( session::has_credential('admin') ) { ?>
    <input type="hidden" name="id" value="<?=$user_data['user_id']?>">
<? } ?>
<input type="hidden" name="type" value="admin_contact"/>

<table width="100%" class="fs12">

    <!-- приоритетные -->
    <tr>
        <td class="aright"></td>
        <td><?=t('Приоритетные')?></td>
    </tr>

    <tr>
        <td class="aright"><?=t('Телефон')?></td>
        <td><input type="text" class="text" name="phone" value="<?=$user_novasys['phone'] ? stripslashes(htmlspecialchars($user_novasys['phone'])) : ($user_data['mobile'] ? $user_data['mobile'] : stripslashes(htmlspecialchars($user_info['phone'])))?>"/></td>
    </tr>

    <tr>
        <td class="aright"><?=t('Электронная почта')?></td>
        <td><input type="text" class="text" name="email0" value="<?=$user_novasys['email0'] ? stripslashes(htmlspecialchars($user_novasys['email0'])) : stripslashes(htmlspecialchars($user_info['email']))?>"/></td>
    </tr>

    <tr>
        <td class="aright"><?=t('Веб-сайт')?></td>
        <td><input type="text" class="text" name="site" value="<?=$user_novasys['site'] ? stripslashes(htmlspecialchars($user_novasys['site'])) : stripslashes(htmlspecialchars($user_info['site']))?>"/></td>
    </tr>

    <!-- личные -->
    <tr>
        <td class="aright"></td>
        <td><?=t('Личные')?></td>
    </tr>

    <tr>
        <td class="aright"><?=t('Мобильный телефон')?></td>
        <td><input type="text" class="text" name="mphone1" value="<?=stripslashes(htmlspecialchars($user_novasys['mphone1']))?>"/></td>
    </tr>

    <tr>
        <td class="aright"><?=t('Альтернативный моб.тел')?></td>
        <td><input type="text" class="text" name="mphone1a" value="<?=stripslashes(htmlspecialchars($user_novasys['mphone1a']))?>"/></td>
    </tr>

    <tr>
        <td class="aright"><?=t('Факс')?></td>
        <td><input type="text" class="text" name="fax1" value="<?=stripslashes(htmlspecialchars($user_novasys['fax1']))?>"/></td>
    </tr>

    <tr>
        <td class="aright"><?=t('Электронная почта')?></td>
        <td><input type="text" class="text" name="email1" value="<?=stripslashes(htmlspecialchars($user_novasys['email1']))?>"/></td>
    </tr>

    <tr>
        <td class="aright"><?=t('Альтернативная эл.почта')?></td>
        <td><input type="text" class="text" name="email1a" value="<?=stripslashes(htmlspecialchars($user_novasys['email1a']))?>"/></td>
    </tr>

    <tr>
        <td class="aright"><?=t('Веб-сайт')?></td>
        <td><input type="text" class="text" name="site1" value="<?=stripslashes(htmlspecialchars($user_novasys['site1']))?>"/></td>
    </tr>

    <tr>
        <td class="aright">Skype</td>
        <td><input type="text" class="text" name="skype1" value="<?=stripslashes(htmlspecialchars($user_novasys['skype1']))?>"/></td>
    </tr>

    <!-- рабочие -->
    <tr>
        <td class="aright"></td>
        <td><?=t('Рабочие')?></td>
    </tr>

    <tr>
        <td class="aright"><?=t('Рабочий телефон')?></td>
        <td><input type="text" class="text" name="phone2" value="<?=stripslashes(htmlspecialchars($user_novasys['phone2']))?>"/></td>
    </tr>

    <tr>
        <td class="aright"><?=t('Рабочий факс')?></td>
        <td><input type="text" class="text" name="fax2" value="<?=stripslashes(htmlspecialchars($user_novasys['fax2']))?>"/></td>
    </tr>

    <tr>
        <td class="aright"><?=t('Рабочая эл.почта')?></td>
        <td><input type="text" class="text" name="email2" value="<?=stripslashes(htmlspecialchars($user_novasys['email2']))?>"/></td>
    </tr>

    <tr>
        <td class="aright"><?=t('Веб-сайт организации')?></td>
        <td><input type="text" class="text" name="site2" value="<?=stripslashes(htmlspecialchars($user_novasys['site2']))?>"/></td>
    </tr>

    <!-- контакты помощника -->
    <tr>
        <td class="aright"></td>
        <td><?=t('Контакты помощника')?></td>
    </tr>

    <tr>
        <td class="aright"><?=t('Имя')?></td>
        <td><input type="text" class="text" name="name3" value="<?=stripslashes(htmlspecialchars($user_novasys['name3']))?>"/></td>
    </tr>

    <tr>
        <td class="aright"><?=t('Фамилия')?></td>
        <td><input type="text" class="text" name="lname3" value="<?=stripslashes(htmlspecialchars($user_novasys['lname3']))?>"/></td>
    </tr>

    <tr>
        <td class="aright"><?=t('Отчество')?></td>
        <td><input type="text" class="text" name="mname3" value="<?=stripslashes(htmlspecialchars($user_novasys['mname3']))?>"/></td>
    </tr>

    <tr>
        <td class="aright"><?=t('Рабочий телефон')?></td>
        <td><input type="text" class="text" name="phone3" value="<?=stripslashes(htmlspecialchars($user_novasys['phone3']))?>"/></td>
    </tr>

    <tr>
        <td class="aright"><?=t('Домашний телефон')?></td>
        <td><input type="text" class="text" name="hphone3" value="<?=stripslashes(htmlspecialchars($user_novasys['hphone3']))?>"/></td>
    </tr>

    <tr>
        <td class="aright"><?=t('Мобильный телефон')?></td>
        <td><input type="text" class="text" name="mphone3" value="<?=stripslashes(htmlspecialchars($user_novasys['mphone3']))?>"/></td>
    </tr>

    <tr>
        <td class="aright"><?=t('Электронная почта')?></td>
        <td><input type="text" class="text" name="email3" value="<?=stripslashes(htmlspecialchars($user_novasys['email3']))?>"/></td>
    </tr>

    <tr>
        <td class="aright">Skype</td>
        <td><input type="text" class="text" name="skype3" value="<?=stripslashes(htmlspecialchars($user_novasys['skype3']))?>"/></td>
    </tr>

    <tr>
        <td></td>
        <td>
                <input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
                <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                <?=tag_helper::wait_panel('admin_contact_wait') ?>
                <div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
        </td>
    </tr>
</table>
</form>
