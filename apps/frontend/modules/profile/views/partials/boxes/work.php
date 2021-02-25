<? if (count($user_works) > 0) { ?>
    <table width="100%" class="fs12">
        <tr>
            <td class="bold" colspan="2">
                <div class="ml15 fs16"><?= t('Работа') ?></div>
            </td>
        </tr>

        <? foreach ($user_works as $work) { ?>
            <tr>
                <td class="bold aright" width="35%;">
                    <? if (intval($work['date_finish']) != 1) { ?>
                        <?= t('Прошлое') ?> <?= t('место работы') ?>
                    <? } else { ?>
                        <?= t('Текущее') ?> <?= t('место работы') ?>
                    <? } ?>
                </td>
                <td style="color:#333333">
                    <? if ($work['name']) { ?>
                        <strong><?= stripslashes(htmlspecialchars($work['name'])) ?></strong>
                    <? } ?>

                    <? if ($work['city_id']) { ?>
                        , <?= geo_peer::instance()->get_city($work['city_id'])['name_ua'] ?>,
                    <? } ?>
                    <? if ($work['region_id']) { ?>
                        <?= geo_peer::instance()->get_region($work['region_id'])['name_ua'] ?>,
                    <? } ?>
                    <? if ($work['country_id']) { ?>
                        <?= geo_peer::instance()->get_country($work['country_id'])['name_ua'] ?>
                    <? } ?>

                    <br/>

                    <? if ($work['post']) { ?>
                        <?= stripslashes(htmlspecialchars($work['post'])) ?>,
                    <? } ?>
                    <? if ($work['date_start']) { ?>
                        <?= t('с') ?> <?= stripslashes(htmlspecialchars($work['date_start'])) ?>
                    <? } ?>

                    <? if (intval($work['date_finish']) != 1) { ?>
                        <?= t('по') ?> <?= stripslashes(htmlspecialchars($work['date_finish'])) ?>
                    <? } ?>
                </td>
            </tr>
        <? } ?>
    </table>
<? } ?>