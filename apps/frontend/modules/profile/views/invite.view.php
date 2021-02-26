<?php
load::model('ppo/ppo');
?>
<div class="left ml10 mt10" style="width: 95%;">
    <?php if (request::get('success')) { ?>
        <div style="width: 500px;" class="screen_message">
            <?= t('Спасибо за инициативу! Приглашение было отправлено на указанную почту.<br/><br/>С уважением<br/>Администрация сайта ') ?>
            <a href="https://meritokrat.org/">meritokrat.org</a>
        </div>
    <?php } else if ($recomendation) { ?>
        <div style="width: 500px;" class="screen_message">
            <?= t('Спасибо за инициативу! Вашу рекомендация передана на рассмотрение.') ?>
            <a href="https://meritokrat.org/">meritokrat.org</a>
        </div>
    <?php } else { ?>
        <h1 class="column_head"><?= !user_auth_peer::instance()->is_inviter(session::get_user_id()) ? t('Рекомендовать нового участника') : t('Пригласить пользователя') ?></h1>

        <div class="box_content fs12">
            <form method="post"
                  action="<?= user_auth_peer::instance()->is_inviter(session::get_user_id()) ? '/admin/users_create' : '/profile/invite' ?>">
                <input type="hidden" name="is_invite" value="1">

                <div class="row m-0 p-3" style="align-items: flex-end">
                    <div class="col-6 p-0">
                        <fieldset class="border-0 p-0">
                            <div>
                                <!--<label for="email" class="form-label">Email</label>-->
                                <input class="form-control form-control-sm" id="email" name="email"
                                       placeholder="<?= t('Email') ?>"
                                       type="email">
                            </div>

                            <div class="mt-2">
                                <!--<label for="firstName" class="form-label">--><?php //= t('Имя') ?><!--</label>-->
                                <input class="form-control form-control-sm" id="firstName" name="first_name"
                                       placeholder="<?= t('Имя') ?>"
                                       value="<?= request::get('first_name') ?>"
                                       type="text">
                            </div>

                            <div class="mt-2">
                                <!--<label for="lastName" class="form-label">--><?php //= t('Фамилия') ?><!--</label>-->
                                <input class="form-control form-control-sm" id="lastName" name="last_name"
                                       placeholder="<?= t('Фамилия') ?>"
                                       type="text"
                                       value="<?= request::get('last_name') ?>">
                            </div>

                            <div class="mt-3">
                                <div class="row m-0">
                                    <label class="col-form-label col-2 p-0 text-end fw-bold"><?= t('Пол') ?></label>
                                    <div class="col">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender"
                                                   id="genderUndefined"
                                                   value=""
                                                   checked>
                                            <label class="form-check-label" for="genderUndefined">-</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender" id="genderMale"
                                                   value="m">
                                            <label class="form-check-label" for="genderMale"><?= t('Мужской') ?></label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender" id="genderFemale"
                                                   value="f">
                                            <label class="form-check-label"
                                                   for="genderFemale"><?= t('Женский') ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </fieldset>

                        <fieldset class="border-0 p-0">
                            <legend><?= t('Место проживания') ?></legend>
                            <div>
                                <label class="form-label" for="country"><?= t('Страна') ?></label>
                                <?php load::model('geo') ?>
                                <?php $сountries = geo_peer::instance()->get_countries(); ?>
                                <input name="country_id" type="hidden" value="<?= request::get_int('country') ?>"/>
                                <?= tag_helper::select('country', $сountries, ['class' => 'form-select form-select-sm', 'use_values' => false, 'value' => request::get_int('country'), 'id' => 'country', 'rel' => t('Выберите страну')]) ?>
                            </div>
                            <div class="mt-2">
                                <label class="form-label" for="region"><?= t('Регион') ?></label>
                                <input name="region_id" type="hidden" value="<?= request::get_int('region') ?>"/>
                                <?php if (1 === request::get_int('country')) {
                                    $regions = geo_peer::instance()->get_regions(request::get_int('country'));
                                } else if (request::get_int('country') > 1) {
                                    $regions['9999'] = 'закордон';
                                } else {
                                    $regions = [];
                                }
                                ?>
                                <?= tag_helper::select('region', $regions, ['class' => 'form-select form-select-sm', 'use_values' => false, 'value' => request::get_int('region'), 'id' => 'region', 'rel' => t('Выберите регион'),]) ?>
                                <input name="region_txt" type='text' class="form-control form-control-sm"
                                       style="display: none" id="region_txt">
                            </div>
                            <div class="mt-2">
                                <label class="form-label" for="city"><?= t('Город/Район') ?></label>
                                <input name="city_id" type="hidden" value="<?= request::get_int('city') ?>"/>
                                <?php
                                if (request::get_int('region') > 0 && request::get_int('region') !== 9999) {
                                    $cities = geo_peer::instance()->get_cities(request::get_int('region'));
                                } else if (request::get_int('country') > 1) {
                                    $cities['9999'] = 'закордон';
                                } else {
                                    $cities = [];
                                }
                                ?>
                                <?= tag_helper::select('city', $cities, ['class' => 'form-select form-select-sm', 'use_values' => false, 'value' => request::get_int('city'), 'id' => 'city', 'rel' => t('Выберите город/район')]); ?>
                                <input class="form-control form-control-sm" name="city_txt" type='text'
                                       style="display: none"
                                       id="city_txt">
                            </div>

                        </fieldset>
                        <fieldset class="border-0 p-0">
                            <legend><?= t('Партийная организация') ?></legend>
                            <div>
                                <div class="combobox">
                                    <label for="ppo_region" class="form-label">Регіональна</label>
                                    <select class="form-select form-select-sm"
                                            id="ppo_region" name="ppo[region]">
                                        <option value="0">&mdash;</option>
                                        <?php foreach (ppo_peer::instance()->findByLevelAndRegion() as $row) { ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['title'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="combobox mt-2">
                                    <label for="ppo_city" class="form-label">Місцева</label>
                                    <select class="form-select form-select-sm"
                                            id="ppo_city" name="ppo[city]"></select>
                                </div>
                                <div class="combobox mt-2">
                                    <label for="ppo_primary" class="form-label">Первинна</label>
                                    <select class="form-select form-select-sm"
                                            id="ppo_primary" name="ppo[primary]"></select>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', () => {
                                        const ppo = {
                                            region: ui.Combobox(document.querySelector('select#ppo_region')),
                                            city: ui.Combobox(document.querySelector('select#ppo_city')),
                                            primary: ui.Combobox(document.querySelector('select#ppo_primary')),
                                        };

                                        const state = new Proxy({}, {
                                            get(target, p) {
                                                return p in target ? target[p] : '0';
                                            },
                                        });

                                        ppo.city.dependOn(ppo.region, (value, resolve, reject) => {
                                            return 0 === value
                                                    ? reject()
                                                    : api.ppo
                                                            .getChildren(value)
                                                            .then(data => data.length > 0
                                                                    ? resolve({data, value: state.city})
                                                                    : reject());
                                        });

                                        ppo.primary.dependOn(ppo.city, (value, resolve, reject) => {
                                            return 0 === value
                                                    ? reject()
                                                    : api.ppo
                                                            .getChildren(value)
                                                            .then(data => data.length > 0
                                                                    ? resolve({data, value: state.primary})
                                                                    : reject());
                                        });

                                        ppo.region.setValue(state.region);
                                    });
                                </script>
                                <div class="mt-2">
                                    <label for="status" class="form-label">Статус</label>
                                    <select class="form-select form-select-sm"
                                            id="status" name="status">
                                        <!--<option disabled hidden selected>-->
                                        <? //= t(' - выберите статус - ') ?><!--</option>-->
                                        <option value="0">&mdash;</option>
                                        <?php foreach (user_auth_peer::get_statuses(user_auth_peer::STATUS_TYPE_MAIN, !session::has_credential('admin')) as $statusId => $status) { ?>
                                            <option value="<?= $statusId ?>"><?= $status ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        <div class="mt-3">
                            <div class="row m-0">
                                <label class="col-form-label col-2 p-0 text-end fw-bold"><?= t('Язык') ?></label>
                                <div class="col">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="language" id="languageUa"
                                               checked>
                                        <label class="form-check-label" for="languageUa"><?= t('Украинский') ?></label>
                                    </div>
                                    <div class="form-check form-check-inline m-0">
                                        <input class="form-check-input" type="radio" name="language" id="languageRu">
                                        <label class="form-check-label" for="languageRu"><?= t('Русский') ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0 mt-2 text-black-50">
                            <div class="col offset-2">
                                <div class="form-check m-0">
                                    <input class="form-check-input" type="checkbox" name="send_email" id="sendEmail"
                                           checked="checked">
                                    <label class="form-check-label"
                                           for="sendEmail"><?= t('Отправить приглашение на электронную почту') ?></label>
                                </div>
                            </div>
                        </div>

                        <?php if (session::has_credential('admin')) { ?>
                            <div class="row m-0 mt-2 text-black-50">
                                <div class="col offset-2">
                                    <div class="form-check m-0">
                                        <input class="form-check-input" type="checkbox" name="suslik" id="suslik">
                                        <label class="form-check-label"
                                               for="suslik">* <?= t('Cкрытый профиль') ?></label>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>


                        <div class="row m-0 mt-2">
                            <div class="col offset-2">
                                <input type="submit" name="submit"
                                       class="btn btn-sm btn-primary text-uppercase"
                                       style="font-size: .75rem; padding: .25rem 1rem"
                                       value="<?= t('Отправить') ?>"/>
                            </div>
                        </div>

                    </div>

                    <div class="col-6 pr-0 " style="font-size: 13px; line-height: 1.2">
                        <p><b>Симпатик</b> - подобаються ідеї партії, але я ще не вирішив(ла), чи буде за неї голосувати</p>
                        <p><b>Прихильник</b> - буде голосувати за партію та її представників</p>
                        <p><b>Активний прихильник</b>  - буде голосувати за партію та її представників, а також буде рекомендувати голосувати своїх знайомих</p>
                        <p><b>Активіст</b> - готовий(ва) голосувати за партію та її представників, а також буде активно допомагати у діяльності пратії та її виборчих кампаніях</p>
                        <p><b>Кандидат в члени партії</b> - готовий(ва) активно займатись політичною діяльністю разом з партією «Успішна Україна»</p>
                    </div>

                </div>
            </form>
        </div>
    <?php } ?>
</div>


