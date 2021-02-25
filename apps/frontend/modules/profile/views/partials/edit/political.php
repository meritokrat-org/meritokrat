<?php
/**
 * @var array $user_data
 */
?>
<form id="political_form" class="mt10 form hidden position-relative">
    <?php if (session::has_credential('admin')) { ?>
        <input type="hidden" name="id" value="<?= $user_data['user_id'] ?>">
    <?php } ?>
    <input type="hidden" name="type" value="political">
    <div class="position-absolute" style="top: 50px; right: 100px">
        <input type="submit" name="submit" class="button" value=" <?= t('Сохранить') ?> ">
    </div>
    <table class="fs12 w-100">

        <tr>
            <td class="text-end"></td>
            <td class="fw-bold"><?= t('Партийная организация') ?></td>
        </tr>

        <tr>
            <td colspan="2">
                <div class="combobox row m-0">
                    <label for="ppo_region" class="col-3 col-form-label text-end">Регіональна</label>
                    <div class="col">
                        <select class="form-select form-select-sm w-50"
                                id="ppo_region" name="ppo[region]">
                            <option value="0">&mdash;</option>
                            <?php foreach (ppo_peer::instance()->findByLevelAndRegion() as $row) { ?>
                                <option value="<?= $row['id'] ?>"><?= $row['title'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="combobox row m-0 mt-1">
                    <label for="ppo_city" class="col-3 col-form-label text-end">Місцева</label>
                    <div class="col">
                        <select class="form-select form-select-sm w-50"
                                id="ppo_city" name="ppo[city]"></select>
                    </div>
                </div>
                <div class="combobox row m-0 mt-1">
                    <label for="ppo_primary" class="col-3 col-form-label text-end">Первинна</label>
                    <div class="col">
                        <select class="form-select form-select-sm w-50"
                                id="ppo_primary" name="ppo[primary]"></select>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const ppo = {
                            region: ui.Combobox(document.querySelector('select#ppo_region')),
                            city: ui.Combobox(document.querySelector('select#ppo_city')),
                            primary: ui.Combobox(document.querySelector('select#ppo_primary')),
                        };

                        const state = new Proxy(JSON.parse('<?= $user['ppo'] ?>'), {
                            get(target, p) {
                                return p in target ? target[p] : 0;
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
            </td>
        </tr>

        <tr>
            <td class="text-end"></td>
            <td class="fw-bold pt-2"><?= t('Деятельность на избирательных должностях и в избирательных органах') ?></td>
        </tr>
        <tr>
            <td colspan="2" id="wa_holder">
                <?php $wa_cnt = 0 ?>
                <?php if (count($work_action) < 1) $work_action[] = null ?>
                <?php foreach ($work_action as $wa) { ?>
                    <div class="work_action">
                        <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom:0">
                            <tr>
                                <td class="aright" width="195"><?= t('Дата начала') ?></td>
                                <td>
                                    <?php if ($wa['start']) { ?>
                                        <?php $bparts = explode('-', stripslashes(htmlspecialchars($wa['start']))) ?>
                                        <?= user_helper::datefields('astart', mktime(0, 0, 0, intval($bparts[1]), intval($bparts[2]), intval($bparts[0])), true) ?>
                                    <?php } else { ?>
                                        <?= user_helper::datefields('astart', 0, true) ?>
                                    <?php } ?>
                                    <!--input style="width:150px" name="astart[]" id="wa_start_<?= $wa_cnt ?>" class="text wa_data" type="text" value="<?= stripslashes(htmlspecialchars($wa['start'])) ?>" /-->
                                    <input type="button" name="cleanfields" class="button cleanfields"
                                           value=" <?= t('Очистить') ?> ">
                                    <input type="button" name="clear"
                                           class="button <?= ($wa_cnt == 0) ? 'hide' : '' ?> clear"
                                           value=" <?= t('Убрать') ?> ">
                                </td>
                            </tr>
                            <tr>
                                <td class="aright"><?= t('Дата завершения') ?></td>
                                <td>
                                    <?php if ($wa['end']) { ?>
                                        <?php $bparts = explode('-', stripslashes(htmlspecialchars($wa['end']))) ?>
                                        <?= user_helper::datefields('aend', mktime(0, 0, 0, intval($bparts[1]), intval($bparts[2]), intval($bparts[0])), true) ?>
                                    <?php } else { ?>
                                        <?= user_helper::datefields('aend', 0, true) ?>
                                    <?php } ?>
                                    <!--input style="width:150px" name="aend[]" id="wa_end_<?= $wa_cnt ?>" class="text wa_data" type="text" value="<?= stripslashes(htmlspecialchars($wa['end'])) ?>" /-->
                                </td>
                            </tr>
                            <tr>
                                <td class="aright"><?= t('Должность') ?></td>
                                <td>
                                    <?= tag_helper::select('apost[]', user_helper::get_political_post(), ['use_values' => false, 'class' => 'aposter', 'value' => $wa['post']]) ?>
                                </td>
                            </tr>
                            <tr class="apost <?= (!$wa['post'] or in_array($wa['post'], [3, 4])) ? 'hide' : '' ?>">
                                <td class="aright"><?= t('Регион') ?></td>
                                <td>
                                    <?php $regions = geo_peer::instance()->get_regions(1); ?>
                                    <?php $regions[0] = '&mdash;';
                                    ksort($regions); ?>
                                    <?= tag_helper::select('aregion[]', $regions, ['use_values' => false, 'class' => 'region', 'value' => $wa['region']]); ?>
                                    <!--input name="aregion[]" class="text" type="text" value="<?= stripslashes(htmlspecialchars($wa['region'])) ?>" /-->
                                </td>
                            </tr>
                            <tr class="apost <?= (!$wa['post'] or in_array($wa['post'], [3, 4])) ? 'hide' : '' ?>">
                                <td class="aright"><?= t('Город/район') ?></td>
                                <td>
                                    <?php
                                    if ($wa['region'] > 0 and $wa['region'] != 10000 and $wa['region'] != 9999) {
                                        $cities    = geo_peer::instance()->get_cities($wa['region']);
                                        $cities[0] = '&mdash;';
                                        ksort($cities);
                                    } else {
                                        $cities = ['&mdash;'];
                                    }
                                    ?>
                                    <?= tag_helper::select('acity[]', $cities, ['use_values' => false, 'class' => 'city', 'value' => $wa['city']]); ?>
                                    <!--input name="acity[]" class="text" type="text" value="<?= stripslashes(htmlspecialchars($wa['city'])) ?>" /-->
                                </td>
                            </tr>
                            <tr class="apost <?= (!$wa['post'] or in_array($wa['post'], [3, 4])) ? 'hide' : '' ?>">
                                <td class="aright"><?= t('Населенный пункт') ?> *</td>
                                <td>
                                    <input name="alocation[]" class="text" type="text"
                                           value="<?= stripslashes(htmlspecialchars($wa['location'])) ?>"/>
                                </td>
                            </tr>
                            <tr class="apost <?= (!$wa['post'] or in_array($wa['post'], [3, 4])) ? 'hide' : '' ?>">
                                <td class="aright"><?= t('Название местного совета') ?> *</td>
                                <td>
                                    <input name="aname[]" class="text" type="text"
                                           value="<?= stripslashes(htmlspecialchars($wa['name'])) ?>"/>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php $wa_cnt++ ?>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td width="200"></td>
            <td><input type="button" name="wa_add" id="wa_add" class="button" value=" <?= t('Добавить') ?> "></td>
        </tr>

        <tr>
            <td class="aright" width="200"></td>
            <td style="padding-top:20px"><b> <?= t('История участия в политических партиях') ?></b></td>
        </tr>
        <?php $noparty = db_key::i()->get('noparty' . session::get_user_id()) ?>
        <tr>
            <td class="aright" width="200"></td>
            <td style="padding:10px 0">
                <input type="checkbox" name="noparty"
                       id="noparty" <?= ($noparty) ? 'checked="checked"' : '' ?> /> <?= t('Не был членом ни одной политической партии') ?>
            </td>
        </tr>
        <tr class="noparty <?= ($noparty) ? 'hide' : '' ?>">
            <td colspan="2" id="wpr_holder">
                <?php $wpr_cnt = 0 ?>
                <?php if (count($work_party) < 1) $work_party[] = null ?>
                <?php foreach ($work_party as $wpr) { ?>
                    <div class="work_party">
                        <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom:0">
                            <tr>
                                <td class="aright" width="195"><?= t('Название партии') ?> *</td>
                                <td>
                                    <input name="pname[]" class="text" type="text"
                                           value="<?= stripslashes(htmlspecialchars($wpr['name'])) ?>"/>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="button" name="cleanfields" class="button cleanfields"
                                           value=" <?= t('Очистить') ?> ">
                                    <input type="button" name="clear"
                                           class="button <?= ($wpr_cnt == 0) ? 'hide' : '' ?> clear"
                                           value=" <?= t('Убрать') ?> ">
                                </td>
                            </tr>
                            <tr>
                                <td class="aright"><?= t('Статус') ?></td>
                                <td>
                                    <?= tag_helper::select('pstatus[]', user_helper::get_political_rank(), ['use_values' => false, 'value' => $wpr['status']]) ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="aright"><?= t('Должность') ?></td>
                                <td>
                                    <input name="ppost[]" class="text" type="text"
                                           value="<?= stripslashes(htmlspecialchars($wpr['post'])) ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="aright"><?= t('Ваши обязанности в партии') ?></td>
                                <td>
                                    <textarea name="pacting[]"
                                              class="text"><?= stripslashes(htmlspecialchars($wpr['acting'])) ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td class="aright"><?= t('Вступление') ?></td>
                                <td>
                                    <?php if ($wpr['start']) { ?>
                                        <?php $bparts = explode('-', stripslashes(htmlspecialchars($wpr['start']))) ?>
                                        <?= user_helper::datefields('pstart', mktime(0, 0, 0, intval($bparts[1]), intval($bparts[2]), intval($bparts[0])), true) ?>
                                    <?php } else { ?>
                                        <?= user_helper::datefields('pstart', 0, true) ?>
                                    <?php } ?>
                                    <!--input style="width:150px" name="pstart[]" id="wpr_start_<?= $wpr_cnt ?>" class="text wpr_data" type="text" value="<?= stripslashes(htmlspecialchars($wpr['start'])) ?>" /-->
                                </td>
                            </tr>
                            <tr>
                                <td class="aright"><?= t('Выход') ?></td>
                                <td>
                                    <?php if ($wpr['end']) { ?>
                                        <?php $bparts = explode('-', stripslashes(htmlspecialchars($wpr['end']))) ?>
                                        <?= user_helper::datefields('pend', mktime(0, 0, 0, intval($bparts[1]), intval($bparts[2]), intval($bparts[0])), true) ?>
                                    <?php } else { ?>
                                        <?= user_helper::datefields('pend', 0, true) ?>
                                    <?php } ?>
                                    <!--input style="width:150px" name="pend[]" id="wpr_end_<?= $wpr_cnt ?>" class="text wpr_data" type="text" value="<?= stripslashes(htmlspecialchars($wpr['end'])) ?>" /-->
                                </td>
                            </tr>
                            <tr>
                                <td class="aright"><?= t('Веб-сайт') ?></td>
                                <td>
                                    <input name="psite[]" class="text" type="text"
                                           value="<?= stripslashes(htmlspecialchars($wpr['site'])) ?>"/>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php $wpr_cnt++ ?>
                <?php } ?>
            </td>
        </tr>
        <tr class="noparty <?= ($noparty) ? 'hide' : '' ?>">
            <td width="200"></td>
            <td><input type="button" name="wpr_add" id="wpr_add" class="button" value=" <?= t('Добавить') ?> "></td>
        </tr>
        <tr>
            <td class="aright"></td>
            <td style="padding-top:20px"><b> <?= t('Участие в избирательном процессе') ?></b></td>
        </tr>
        <tr>
            <td colspan="2" id="we_holder">
                <?php $we_cnt = 0 ?>
                <?php if (count($work_election) < 1) $work_election[] = null ?>
                <?php foreach ($work_election as $we) { ?>
                    <div class="work_election">
                        <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom:0">
                            <tr>
                                <td class="aright" width="195"><?= t('Год') ?></td>
                                <td>
                                    <?= tag_helper::select('eyear[]', user_helper::get_years(), ['use_values' => false, 'value' => $we['year']]) ?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="button" name="cleanfields" class="button cleanfields"
                                           value=" <?= t('Очистить') ?> ">
                                    <input type="button" name="clear"
                                           class="button <?= ($we_cnt == 0) ? 'hide' : '' ?> clear"
                                           value=" <?= t('Убрать') ?> ">
                                </td>
                            </tr>
                            <tr>
                                <td class="aright"><?= t('Выборы') ?></td>
                                <td>
                                    <?= tag_helper::select('etype[]', user_helper::get_political_vibor(), ['use_values' => false, 'value' => $we['type']]) ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="aright"><?= t('Статус') ?></td>
                                <td>
                                    <?= tag_helper::select('estatus[]', user_helper::get_political_status(), ['use_values' => false, 'value' => $we['status']]) ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="aright"><?= t('Регион') ?></td>
                                <td>
                                    <?php $regions = geo_peer::instance()->get_regions(1); ?>
                                    <?php $regions[0] = '&mdash;';
                                    ksort($regions); ?>
                                    <?= tag_helper::select('eregion[]', $regions, ['use_values' => false, 'class' => 'region', 'value' => $we['region']]); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="aright"><?= t('Город/район') ?></td>
                                <td>
                                    <?php
                                    if ($we['region'] > 0 and $we['region'] != 10000 and $we['region'] != 9999) {
                                        $cities    = geo_peer::instance()->get_cities($we['region']);
                                        $cities[0] = '&mdash;';
                                        ksort($cities);
                                    } else {
                                        $cities = ['&mdash;'];
                                    }

                                    ?>
                                    <?= tag_helper::select('ecity[]', $cities, ['use_values' => false, 'class' => 'city', 'value' => $we['city']]); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="aright"><?= t('Населенный пункт') ?> *</td>
                                <td>
                                    <input name="elocation[]" class="text" type="text"
                                           value="<?= stripslashes(htmlspecialchars($we['location'])) ?>"/>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php $we_cnt++ ?>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td width="200"></td>
            <td><input type="button" name="we_add" id="we_add" class="button" value=" <?= t('Добавить') ?> "></td>
        </tr>

        <tr>
            <td></td>
            <td style="padding-top:20px">
                <?= t('Поля, отмеченные * обязательны для заполнения') ?>
            </td>
        </tr>
        <tr>
            <td></td>
            <td style="padding-top:20px">
                <input type="submit" name="submit" class="button" value=" <?= t('Сохранить') ?> ">
                <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray"
                       value=" <?= t('Отмена') ?> ">
                <?= tag_helper::wait_panel('political_wait') ?>
                <div class="success hidden mr10 mt10"><?= t('Изменения сохранены') ?></div>
                <div class="error hidden mr10 mt10"><?= t('Вы заполнили не все поля. Часть информации может быть не сохранена') ?></div>
            </td>
        </tr>

    </table>
</form>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#noparty').click(function () {
            if ($(this).is(':checked')) {
                $('.noparty').hide();
                inputclean($('.noparty'));
                $.post('/profile/setdata', {'key': 'noparty', 'data': 1});
            } else {
                $('.noparty').show();
                $.post('/profile/setdata', {'key': 'noparty', 'data': 0});
            }
        });

        $('.cleanfields').unbind('click').click(function () {
            inputclean($(this).parent().parent().parent());
        });

        $('#wpr_add').click(function () {
            var $place = $('div.work_party:first').clone();
            $('#wpr_holder').append($place);
            _wdo();
            inputclean($place);
            $place.find('.cleanfields').click(function () {
                inputclean($place);
            });
            $place.find('.clear').removeClass('hide');
        });

        $('#we_add').click(function () {
            var $place = $('div.work_election:first').clone();
            $('#we_holder').append($place);
            _wdo();
            inputclean($place);
            $place.find('.cleanfields').click(function () {
                inputclean($place);
            });
            $place.find('.clear').removeClass('hide');
        });

        $('#wa_add').click(function () {
            var $place = $('div.work_action:first').clone();
            $('#wa_holder').append($place);
            _wa();
            inputclean($place);
            $place.find('.clear').removeClass('hide');
            $place.find('.apost').addClass('hide');
            $place.find('.cleanfields').click(function () {
                inputclean($place);
            });
        });

        _wa();
        _wdo();
    });

    function inputclean(obj) {
        obj.find('input').not('.clear').not('.cleanfields').val('');
        obj.find('textarea').val('');
        obj.find('select').find('option').each(function () {
            this.selected = false;
        }).find(':first').attr('selected', true);
        obj.find('select.city').html('<option value="0">&mdash;</option>');
    }

    function _wdo() {
        $('div.work_party, div.work_election').each(function () {
            var $this = $(this);
            $this.find('.clear').click(function () {
                $this.remove();
            });
            $this.find('.region').unbind('change');
            _regionSelectors($this);
        });
    }

    function _wa() {
        $('div.work_action').each(function () {
            var $this = $(this);
            $this.find('.clear').click(function () {
                $this.remove();
            });
            $this.find('.aposter').change(function () {
                if ($(this).val() == 1 || $(this).val() == 2)
                    $this.find('.apost').removeClass('hide');
                else
                    $this.find('.apost').addClass('hide');
            });
            _regionSelectors($this);
        });
    }

    function _regionSelectors($this) {
        $this.find('.region').change(function () {
            var region_id = $(this).val();
            if (region_id == '0') {
                $this.find('.city').html('');
                $this.find('.city').attr('disabled', true);
                return (false);
            }
            $this.find('.city').attr('disabled', true);
            $this.find('.city').html('<option>завантаження...</option>');

            var url = 'https://<?=context::get('server')?>/profile/get_select';
            $.post(url, {'region': region_id},
                    function (result) {
                        if (result.type == 'error') {
                            alert('error');
                            return (false);
                        } else {
                            var options = '<option value="">- оберіть місто/район -</option>';
                            $(result.cities).each(function () {
                                options += '<option value="' + $(this).attr('id') + '">' + $(this).attr('title') + '</option>';
                            });
                            $this.find('.city').html(options);
                            $this.find('.city').attr('disabled', false);
                        }
                    },
                    'json',
            );
        });
    }
</script>