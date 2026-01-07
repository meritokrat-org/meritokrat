<form id="common_form" class="form mt10 hidden">
    <?php if (session::has_credential('admin') || $access) { ?>
        <input type="hidden" name="id" value="<?= $user_data['user_id'] ?>"/>
    <?php } ?>
    <input type="hidden" name="type" value="common">
    <table class="fs12" style="width: 100%">
        <tr>
            <td class="aright"><b><?= t('Имя') ?></b></td>
            <td>
                <input name="first_name" rel="<?= t('Введите полное имя') ?>" class="text" type="text"
                       value="<?= stripslashes(htmlspecialchars($user_data['first_name'])) ?>"/>
            </td>
        </tr>
        <tr>
            <td class="aright"><b><?= t('Фамилия') ?></b></td>
            <td>
                <input name="last_name" rel="<?= t('Введите фамилию') ?>" class="text" type="text"
                       value="<?= stripslashes(htmlspecialchars($user_data['last_name'])) ?>"/>
            </td>
        </tr>
        <tr>
            <td class="aright"><?= t('Отчество') ?></td>
            <td>
                <input name="father_name" rel="<?= t('Введите отчество') ?>" class="text" type="text"
                       value="<?= stripslashes(htmlspecialchars($user_data['father_name'])) ?>"/>
            </td>
        </tr>
        <?php // старые статусы
        /* if (in_array($user['type'],array(4,5,7,0))) { ?>
        <tr>
                <td class="aright"><?=t('Статус')?></td>
                <td>
                    <? $all_types=user_auth_peer::get_types();
                        foreach(array(4,5,7) as $type) $types[$type]=$all_types[$type];
                        if ($user['type']==0) $user['type']=7;
                    ?>
                        <?=tag_helper::select('utype', $types, array('value' => $user['type']))?>
                </td>
        </tr>
        <? } */ ?>
        <tr>
            <td class="aright"><?= t('Дата рождения') ?></td>
            <?php $ages = range(15, 107) ?>
            <td>
                <?php $bparts = explode('-', stripslashes(htmlspecialchars($user_data['birthday']))) ?>
                <?= user_helper::datefields(
                    'birthday',
                    mktime(0, 0, 0, intval($bparts[1]), intval($bparts[2]), intval($bparts[0])),
                    false,
                    [],
                    true,
                    1950
                ) ?>
                <!--input name="birthday" rel="<?= t(
                    'Заполните дату рождения корректно'
                ) ?>" class="text" type="text" id="birthday" style="width:153px;" value="<?= stripslashes(
                    htmlspecialchars($user_data['birthday'])
                ) ?>" /-->
                <?php //=tag_helper::select('age', $ages, array('use_values' => true, 'value' => $user_data['age']))?>
            </td>
        </tr>
        <tr>
            <td class="aright"><?= t('Пол') ?></td>
            <td>
                <label>
                    <input type="radio" name="gender" value="m" <?= $user_data['gender'] == 'm' ? 'checked' : '' ?> />
                    <?= t('Мужской') ?>
                </label>
                <label>
                    <input type="radio" name="gender" value="f" <?= $user_data['gender'] == 'f' ? 'checked' : '' ?> />
                    <?= t('Женский') ?>
                </label>
            </td>
        </tr>
    </table>


    <!--    <table class="fs12" style="width: 100%">-->
    <!--        <tr>-->
    <!--            <td class="aright">-->
    <!--                <label for="locality_country">--><?php //= t('Страна') ?><!--</label>-->
    <!--            </td>-->
    <!--            <td class="aleft">-->
    <!--                <select-->
    <!--                        id="locality_country"-->
    <!--                        name="locality[country]"-->
    <!--                        style="width: 131px"-->
    <!--                        onload="new Locality(this)"-->
    <!--                ></select>-->
    <!--                --><?php // tag_helper::select('country', [], [
    //                    'style' => 'width: 131px;',
    //                    'data-value' => $user_data['country_id'],
    //                    'data-tag' => 'locality',
    //                ]) ?>
    <!--            </td>-->
    <!--        </tr>-->
    <!--        <tr>-->
    <!--            <td class="aright">--><?php //= t('Регион') ?><!--</td>-->
    <!--            <td class="aleft">-->
    <!--                <select-->
    <!--                        id="locality_region"-->
    <!--                        name="locality[region]"-->
    <!--                        style="width: 131px"-->
    <!--                        onload="new Locality(this)"-->
    <!--                ></select>-->
    <!--                --><?php //= tag_helper::select('region', [], [
    //                    'style' => 'width: 130px;',
    //                    'data-value' => $user_data['region_id'],
    //                    'data-tag' => 'locality',
    ////                    'data-depends-on' => 'country',
    //                ]) ?>
    <!--            </td>-->
    <!--        </tr>-->
    <!--        <tr>-->
    <!--            <td class="aright">--><?php //= sprintf('%s / %s', t('Город'), t('Район')) ?><!--</td>-->
    <!--            <td class="aleft">-->
    <!--                --><?php //= tag_helper::select('district', [], [
    //                    'style' => 'width: 130px;',
    //                    'data-value' => $user_data['district_id'],
    //                    'data-tag' => 'locality',
    //                ]) ?>
    <!--            </td>-->
    <!--        </tr>-->
    <!--        <tr>-->
    <!--            <td class="aright">--><?php //= t('Громада') ?><!--</td>-->
    <!--            <td class="aleft">-->
    <!--                --><?php //= tag_helper::select('community', [], [
    //                    'style' => 'width: 130px;',
    //                    'data-tag' => 'locality',
    //                ]) ?>
    <!--            </td>-->
    <!--        </tr>-->
    <!--        <tr>-->
    <!--            <td class="aright">--><?php //= t('Населений пункт') ?><!--</td>-->
    <!--            <td class="aleft">-->
    <!--                --><?php //= tag_helper::select('city', [], [
    //                    'style' => 'width: 130px;',
    //                    'data-tag' => 'locality',
    //                ]) ?>
    <!--            </td>-->
    <!--        </tr>-->
    <!--    </table>-->

    <div class="container my-2 py-3" style="border-top: 1px solid #387ceb; border-bottom: 1px solid #387ceb;"
         data-container="locality"
         onload="new Locality(this, this.querySelector(':scope> div select[name=\'locality[country]\']'))">

        <input type="hidden" name="locality[id]"
               value="<?= user_locality_peer::instance()->get_item($user_data['user_id']) ?>"/>

        <div class="row">
            <label class="col-3 col-form-label col-form-label-sm text-end fs-6"><?= t('Страна') ?></label>
            <div class="col-3">
                <select name="locality[country]"
                        class="form-select form-select-sm"
                        aria-label="<?= t('Страна') ?>"
                        onchange="this.locality.region(this)">
                <option disabled selected hidden><?= t('Выберите страну') ?></option>
                </select>
            </div>
        </div>
        <div class="row">
            <label class="col-3 col-form-label col-form-label-sm text-end fs-6"><?= t('Регион') ?></label>
            <div class="col-3">
                <select name="locality[region]"
                        class="form-select form-select-sm"
                        aria-label="<?= t('Регион') ?>"
                        onchange="this.locality.district(this)">
                    <option disabled selected hidden><?= t('Выберите регион') ?></option>
                </select>
            </div>
        </div>
        <div class="row">
            <label class="col-3 col-form-label col-form-label-sm text-end fs-6"><?= t('Район') ?></label>
            <div class="col-3">
                <select name="locality[district]"
                        class="form-select form-select-sm"
                        aria-label="<?= t('Район') ?>"
                        onchange="this.locality.community(this)">
                    <option disabled selected hidden><?= t('Выберите город или район') ?></option>
                </select>
            </div>
        </div>
        <div class="row">
            <label class="col-3 col-form-label col-form-label-sm text-end fs-6"><?= t('Громада') ?></label>
            <div class="col-3">
                <select name="locality[community]"
                        class="form-select form-select-sm"
                        aria-label="<?= t('Громада') ?>"
                        onchange="this.locality.city(this)">
                    <option disabled selected hidden><?= t('Выберите громаду') ?></option>
                </select>
            </div>
        </div>
        <div class="row">
            <label class="col-3 col-form-label col-form-label-sm text-end fs-6"><?= t('Населений пункт') ?></label>
            <div class="col-3">
                <select name="locality[city]"
                        class="form-select form-select-sm"
                        aria-label="<?= t('Населений пункт') ?>"
                        onchange="this.locality.area(this)">
                    <option disabled selected hidden><?= t('Выберите населений пункт') ?></option>
                </select>
            </div>
        </div>
        <div class="row">
            <label class="col-3 col-form-label col-form-label-sm text-end fs-6"><?= t('Район') ?></label>
            <div class="col-3">
                <select name="locality[area]"
                        class="form-select form-select-sm"
                        aria-label="<?= t('Район') ?>">
                    <option disabled selected hidden><?= t('Выберите район в городе') ?></option>
                </select>
            </div>
        </div>
    </div>

    <table class="fs12" style="width: 100%">
        <tr>
            <td class="aright"><?= t('Место проживания') ?><br>
                <span class="cgray fs11"><?= t('населенный пункт') ?></span>
            </td>
            <td><input name="location" rel="<?= t('город, поселок, село') ?>" class="text" type="text"
                       value="<?= stripslashes(htmlspecialchars($user_data['location'])) ?>"/></td>
        </tr>
        <tr>
            <td class="aright"><?= t('Улица') ?></td>
            <td><input name="street"
                       class="text adrstreet" type="text"
                       value="<?= stripslashes(htmlspecialchars($user_data['street'])) ?>"/></td>
        </tr>
        <tr>
            <td class="aright"><?= t('№ дома') ?></td>
            <td><input name="house"
                       class="text adrhouse" type="text"
                       value="<?= stripslashes(htmlspecialchars($user_data['house'])) ?>"/></td>
        </tr>
        <tr class="shadr <?= ($user_data['street'] || $user_data['house']) ? '' : 'hidden' ?>">
            <td class="aright"><?= t('Показывать в профиле') ?></td>
            <td class="shadr">
                <input class="showadrop" value="1" <?= db_key::i()->exists("showadrop_".$uid) ? 'checked' : '' ?>
                       type="checkbox" name="showadrop"/>
            </td>
        </tr>
        <?php if ($user['status'] >= 5) { ?>
            <tr id="region_row">
                <td class="right"><b><?= t('Территория деятельности') ?></b></td>
                <td></td>
            </tr>
            <tr id="region_row">
                <td class="aright"><b><?= t('Регион') ?></b></td>
                <td>

                    <?php $regions = geo_peer::instance()->get_regions(1); ?>

                    <?php
                    $regions[0] = "&mdash;";
                    ksort($regions);
                    ?>
                    <?= tag_helper::select(
                        'party_region',
                        $regions,
                        [
                            'use_values' => false,
                            'value' => $user_data['party_region_id'],
                            'id' => 'party_region',
                            'rel' => t('Выберите регион'),
                        ]
                    ); ?>
                </td>
            </tr>
            <tr>
                <td class="aright"><b><?= t('Город/Район') ?></b></td>
                <td>
                    <?php if ($user_data['party_region_id'] > 0 and $user_data['party_region_id'] != 10000 and $user_data['party_region_id'] != 9999) {
                        $cities = geo_peer::instance()->get_cities($user_data['party_region_id']);
                    } else {
                        if ($user_data['party_country_id'] > 1) {
                            $cities['10000'] = 'закордон';
                        } else {
                            $cities[''] = '&mdash;';
                        }
                    } ?>

                    <?= tag_helper::select(
                        'party_city',
                        $cities,
                        [
                            'use_values' => false,
                            'value' => $user_data['party_city_id'],
                            'id' => 'party_city',
                            'rel' => t('Выберите город/район'),
                        ]
                    ); ?>
                </td>
            </tr>
            <tr>
                <td class="aright"><?= t('Населенный пункт') ?></td>
                <td><input name="party_location" rel="<?= t('город, поселок, село') ?>" class="text" type="text"
                           value="<?= stripslashes(htmlspecialchars($user_data['party_location'])) ?>"/></td>
            </tr>
        <?php } ?>
        <tr>
            <td class="aright"><?= t('Сфера деятельности') ?></td>
            <?php $sferas = user_auth_peer::instance()->get_segments(); ?>
            <?php $sferas[''] = "&mdash;";
            ksort($sferas);
            ?>
            <td><?= tag_helper::select(
                    'segment',
                    $sferas,
                    ['use_values' => false, 'value' => $user_data['segment']]
                ) ?></td>
        </tr>
        <tr>
            <td class="aright"><?= t('Дополнительная сфера') ?></td>
            <td><?= tag_helper::select(
                    'additional_segment',
                    $sferas,
                    ['use_values' => false, 'value' => $user_data['additional_segment']]
                ) ?></td>
        </tr>
        <tr>
            <td class="aright"><?= t('Дополнительная информация') ?></td>
            <td><textarea style="height: 75px; width: 400px;"
                          name="additional_info"><?= stripslashes(
                        htmlspecialchars($user_data['additional_info'])
                    ) ?></textarea>
            </td>
        </tr>
        <?php // include 'target.php'; ?>
        <?php if (session::has_credential('admin')) { ?>
            <tr>
                <td class="aright">*<?= t('О себе') ?></td>
                <td><textarea style="height: 75px; width: 400px;"
                              name="about"><?= stripslashes(htmlspecialchars($user_data['about'])) ?></textarea></td>
            </tr>
            <tr>
                <td class="aright">*<?= t('Язык') ?></td>
                <td>
                    <input <?= $user_data['language'] == 'ua' ? 'checked' : '' ?> id="lang_ua" type="radio"
                                                                                  name="language" value="ua"/>
                    <label for="lang_ua"><?= tag_helper::image('/icons/ua.png', ['alt' => "ua"]) ?></label>

                    <input <?= $user_data['language'] == 'ru' ? 'checked' : '' ?> id="lang_ru" type="radio"
                                                                                  name="language" value="ru"/>
                    <label for="lang_ru"><?= tag_helper::image('/icons/ru.png', ['alt' => "ru"]) ?></label>
                    <?php /*
                                <input <?=$user_data['language']=='en' ? 'checked' : ''?> id="lang_en" type="radio" name="language" value="en"/>
                                <label for="lang_en"><?=tag_helper::image('/icons/en.png', array('alt'=>"en"))?></label>
                                */ ?>
                </td>
            </tr>
        <?php } ?>
        <?php if (session::has_credential('admin') && 0) { ?>
            <tr>
                <td class="aright">* <?= t('Скрыть профиль') ?></td>
                <td><input <?= $user['suslik'] == 1 ? 'checked' : '' ?> type="checkbox" id="suslik" name="suslik"
                                                                        value="1"/></td>
            </tr>
        <?php } ?>
        <?php
        if (session::has_credential('admin') && 0) {
            $share_users = explode(',', str_replace(['{', '}'], ['', ''], $user_data['share_users']));
            ?>
            <tr id="shareuserstr">
                <td class="aright">* <?= t('Показывать некоторым') ?></td>
                <td><a href="javascript:;" class="shareprofile"><?= t('Выбрать') ?></a>
                    <table class="m0">
                        <tr id="uchasniki"></tr>
                        <?php if ( ! empty($share_users[0])) {
                            foreach ($share_users as $su) {
                                ?>
                                <tr id="member<?= $su ?>">
                                    <td>
                                        <input type="hidden" name="members[]" value="<?= $su ?>"/>
                                        <?= user_helper::full_name($su, true, [], false) ?> -
                                        <a href="javascript:;" rel="<?= $su ?>" class="one_delete_function">Видалити</a>
                                    </td>
                                </tr>
                            <?php }
                        } ?>
                    </table>
                </td>
            </tr>
        <?php } ?>
        <!--tr>
                            <td class="aright"><?= t('Показывать не зарегистрированным') ?></td>
                                <td><input value="1" <?= db_key::i()->exists($rkey) ? 'checked' : '' ?> type="checkbox" name="public"/></td>
		</tr-->
        <tr>
            <td class="aright"></td>
            <td>
                <input type="submit" name="submit" class="button" value=" <?= t('Сохранить') ?> ">
                <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray"
                       value=" <?= t('Отмена') ?> ">
                <?= tag_helper::wait_panel('common_wait') ?>
                <div class="success hidden mr10 mt10"><?= t('Изменения сохранены') ?></div>
            </td>
        </tr>
        <tr>
            <td class="aright"><b><?= t('Важно') ?>:</b></td>
            <td>
                <?= t('жирным выделенны поля профиля обязательные для заполнения') ?>
            </td>
        </tr>
    </table>
</form>


<script type="text/javascript">
    jQuery('.adrstreet,.adrhouse').keypress(function () {
        $('.shadr').removeClass('hidden');
        $('.showadrop').attr('checked', 'checked');
    });

</script>