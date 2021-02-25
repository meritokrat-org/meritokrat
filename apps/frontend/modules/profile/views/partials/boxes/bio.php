<div class="content_pane hide" id="pane_bio">

    <?
    include 'work.php';
    include 'education.php';
    ?>

    <table width="100%" class="fs12">

        <? if (session::has_credential('admin')) { ?>

            <!-- DIV POLITICAL -->
            <? if (is_array($work_action[0])) { ?>
                <tr>
                    <td width="35%;" class="bold aright">
                        <?= t('Деятельность на избирательных должностях и в избирательных органах') ?>
                    </td>
                    <td style="color:#333333">
                        <? foreach ($work_action as $wa) { ?>
                            <p class="mb10">
                                <?= user_helper::get_political_post($wa['post']) . ', ' ?>
                                <?= $wa['name'] . ', ' ?>
                                <? if ($wa['region']) { ?>
                                    <? $region = geo_peer::instance()->get_region($wa['region']) ?> <a
                                        href="/search?submit=1&region=<?= $wa['region'] ?>"><?= $region['name_' . translate::get_lang()] ?></a>
                                <? } ?>
                                <? if ($wa['city']) { ?>
                                    <? $city = geo_peer::instance()->get_city($wa['city']) ?> / <a
                                        href="/search?submit=1&region=<?= $wa['region'] ?>&city=<?= $wa['city'] ?>"><?= $city['name_' . translate::get_lang()] ?></a>
                                <? } ?>
                                <? if ($wa['location']) { ?> / <?= stripslashes(htmlspecialchars($wa['location'])) ?><? } ?>
                                <?= ', ' . user_data_peer::instance()->get_formated_data($wa['start']) . ' - ' . user_data_peer::instance()->get_formated_data($wa['end']) ?>
                            </p>
                        <? } ?>
                    </td>
                </tr>
            <? } ?>
            <? if (is_array($work_party[0])) { ?>
                <tr>
                    <td width="35%;" class="bold aright">
                        <?= t('История участия в политических партиях') ?>
                    </td>
                    <td style="color:#333333">
                        <? foreach ($work_party as $wp) { ?>
                            <p class="mb10">
                                <?= $wp['name'] . ', ' ?>
                                <?= user_helper::get_political_rank($wp['status']) . ', ' ?>
                                <?= $wp['name'] . ', ' ?>
                                <?= $wp['post'] . ', ' ?>
                                <?= $wp['acting'] . ', ' ?>
                                <?= $wp['start'] . ' - ' . $wp['end'] . ', ' ?>
                                <?= $wp['site'] ?>
                            </p>
                        <? } ?>
                    </td>
                </tr>
            <? } ?>
            <? if (is_array($work_election[0])) { ?>
                <tr>
                    <td width="35%;" class="bold aright">
                        <?= t('Участие в избирательном процессе') ?>
                    </td>
                    <td style="color:#333333">
                        <? foreach ($work_election as $we) { ?>
                            <p class="mb10">
                                <?= user_helper::get_political_vibor($we['type']) . ', ' ?>
                                <?= user_helper::get_political_status($we['status']) . ', ' ?>
                                <? if ($we['region']) { ?>
                                    <? $region = geo_peer::instance()->get_region($we['region']) ?> <a
                                        href="/search?submit=1&region=<?= $we['region'] ?>"><?= $region['name_' . translate::get_lang()] ?></a>
                                <? } ?>
                                <? if ($we['city']) { ?>
                                    <? $city = geo_peer::instance()->get_city($we['city']) ?> / <a
                                        href="/search?submit=1&region=<?= $we['region'] ?>&city=<?= $we['city'] ?>"><?= $city['name_' . translate::get_lang()] ?></a>
                                <? } ?>
                                <? if ($we['location']) { ?> / <?= stripslashes(htmlspecialchars($we['location'])) ?><? } ?>
                                <?= ', ' . $we['year'] . ' ' . t('год') ?>
                            </p>
                        <? } ?>
                    </td>
                </tr>
            <? } ?>
        <? } ?>

        <!-- POLITICAL ACTIVITY END -->
        <? if ($user_bio['birth_family']) { ?>
            <tr>
                <td class="bold aright" width="35%;"><?= t('Дата и место рождения, семья') ?>:</td>
                <td style="color:#333333"><?= nl2br(stripslashes(htmlspecialchars($user_bio['birth_family']))) ?></td>
            </tr>
        <? }
        if ($user_bio['major_education']) { ?>
            <tr>
                <td class="bold aright" width="35%;"><?= t('Основное образование') ?>:</td>
                <td style="color:#333333"><?= nl2br(stripslashes(htmlspecialchars($user_bio['major_education']))) ?></td>
            </tr>
        <? }
        if ($user_bio['work']) { ?>
            <tr>
                <td class="bold aright" width="35%;"><?= t('Трудовая деятельность') ?>:</td>
                <td style="color:#333333"><?= nl2br(stripslashes(htmlspecialchars($user_bio['work']))) ?></td>
            </tr>
        <? }
        if ($user_bio['society']) { ?>
            <tr>
                <td class="bold aright" width="35%;"><?= t('Общественная деятельность') ?>:</td>
                <td style="color:#333333"><?= nl2br(stripslashes(htmlspecialchars($user_bio['society']))) ?></td>
            </tr>
        <? }
        if ($user_bio['politika']) { ?>
            <tr>
                <td class="bold aright" width="35%;"><?= t('Политическая деятельность') ?>:</td>
                <td style="color:#333333"><?= nl2br(stripslashes(htmlspecialchars($user_bio['politika']))) ?></td>
            </tr>
        <? }
        if ($user_bio['science']) { ?>
            <tr>
                <td class="bold aright" width="35%;"><?= t('Научная деятельность') ?>:</td>
                <td style="color:#333333"><?= nl2br(stripslashes(htmlspecialchars($user_bio['science']))) ?></td>
            </tr>
        <? }
        if ($user_bio['additional_education']) { ?>
            <tr>
                <td class="bold aright" width="35%;"><?= t('Семинары, тренинги, другое доп. образование') ?>:</td>
                <td style="color:#333333"><?= nl2br(stripslashes(htmlspecialchars($user_bio['additional_education']))) ?></td>
            </tr>
        <? }
        if ($user_bio['progress']) { ?>
            <tr>
                <td class="bold aright" width="35%;"><?= t('Важные успехи и достижения') ?>:</td>
                <td style="color:#333333"><?= nl2br(stripslashes(htmlspecialchars($user_bio['progress']))) ?></td>
            </tr>
        <? }
        if ($user_bio['other']) { ?>
            <tr>
                <td class="bold aright" width="35%;"><?= t('Другая информация') ?>:</td>
                <td style="color:#333333"><?= nl2br(stripslashes(htmlspecialchars($user_bio['other']))) ?></td>
            </tr>
        <? } ?>
    </table>


</div>