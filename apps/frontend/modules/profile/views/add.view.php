<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker-uk.js"></script>
<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.mouse.js"></script>
<script type="text/javascript">jQuery.noConflict();</script>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $.datepicker.setDefaults($.extend(
            $.datepicker.regional["uk"])
        );
        $('#birthday').datepicker({
            changeMonth: true,
            changeYear: true,
            autoSize: true,
            showOptions: {direction: 'left'},
            dateFormat: 'yy-mm-dd',
            shortYearCutoff: 90,
            yearRange: '1940:2010',
            firstDay: true,
            minDate: new Date(1940, 1 - 1, 1)
        });
    });
</script>
<style type="text/css">
    .ui-datepicker {
        margin-left: 160px;
    }
</style>
<div class="left ml10 mt10" style="width: 95%;">

    <h1 class="column_head"><?= t('Добавить участника') ?></h1>

    <div class="box_content acenter p10 fs12">

        <form method="post" action="/profile/add">
            <table>
                <tr>
                    <td colspan="2" class="p10 acenter">
                        <div class="left bold" style="color: red; margin-right:45px; margin-left:45px;">УВАГА! <span
                                class="bold" style="color: black;"> Перед тим як додати нового учасника, будь ласка, повідомте людину про Ваш намір, а також надайте інформацію про мерітократію та діяльність нашої мерітократичної команди.</span>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="aright"><?= t('Имя') ?></td>
                    <td class="aleft"><input type="text" class="text" name="first_name" value="<?= $first_name ?>"></td>
                </tr>
                <tr>
                    <td class="aright"><?= t('Фамилия') ?></td>
                    <td class="aleft"><input type="text" class="text" name="last_name" value="<?= $last_name ?>"></td>
                </tr>
                <tr>
                    <td class="aright"><?= t('Пол') ?></td>
                    <td class="aleft">
                        <input type="radio" name="gender"
                               value="m" <?= ($gender == 'm' OR !$gender) ? 'checked="checked"' : '' ?>> М
                        <input type="radio" name="gender" value="f" <?= ($gender == 'f') ? 'checked="checked"' : '' ?>>
                        Ж
                    </td>
                </tr>


                <tr>
                    <td class="aright"><?= t('Дата рождения') ?> (<?= t('необязательно') ?>)</td>
                    <? $ages = range(15, 107) ?>
                    <td class="aleft">
                        <input name="birthday" rel="<?= t('Заполните дату рождения корректно') ?>" class="text"
                               type="text" id="birthday" style="width:153px;"
                               value="<?= request::get_string('birthday') ?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="aright"><?= t('Страна') ?></td>
                    <td class="aleft">
                        <? if (1 != 1) { ?>
                            <input name="country_id" type="hidden" value="<?= request::get_int('country') ?>"/>
                            <? load::model('geo') ?>
                            <? $сountries = geo_peer::instance()->get_countries();
                            ksort($сountries); ?>
                            <?= tag_helper::select('country', $сountries, array('use_values' => false, 'value' => request::get_int('country'), 'id' => 'country', 'rel' => t('Выберите страну'))) ?>
                        <? } ?>
                        <input name="country_id" type="hidden" value="<?= request::get_int('country') ?>"/>
                        <? load::model('geo') ?>
                        <? $сountries = geo_peer::instance()->get_countries();
                        //	                        var_dump($сountries); ?>
                        <?= tag_helper::select('country', $сountries, array('use_values' => false, 'value' => request::get_int('country'), 'id' => 'country', 'rel' => t('Выберите страну'), /*'onchange'=>"getRegions(this,'info','region','/ua/ajax/action/regions/')"*/)) ?>
                        <!--input name="country" class="text" type="text" value="<?= $country['name_' . translate::get_lang()] ?>" /-->
                    </td>
                </tr>
                <tr id="region_row">
                    <td class="aright"><?= t('Регион') ?></td>
                    <td class="aleft">
                        <? if (1 != 1) { ?>
                            <input name="region_id" type="hidden" value="<?= request::get_int('region') ?>"/>
                            <? if (request::get_int('country') == 1) $regions = geo_peer::instance()->get_regions(request::get_int('country'));
                            elseif (request::get_int('country') > 1) $regions['9999'] = 'закордон';
                            else  $regions[''] = '&mdash;'; ?>
                            <?= tag_helper::select('region', $regions, array('use_values' => false, 'value' => request::get_int('region'), 'id' => 'region', 'rel' => t('Выберите регион'),)); ?>
                        <? } ?>
                        <input name="region_id" type="hidden" value="<?= request::get_int('region') ?>"/>
                        <?
                        if (request::get_int('country') == 1)
                            $regions = geo_peer::instance()->get_regions(request::get_int('country'));
                        elseif (request::get_int('country') > 1)
                            $regions['9999'] = 'закордон';
                        else
                            $regions = array();
                        ?>
                        <?= tag_helper::select('region', $regions, array('use_values' => false, 'value' => request::get_int('region'), 'id' => 'region', 'rel' => t('Выберите регион'),)); ?>
                        <!--input name="region" class="text" type="text" value="<?= $region['name_' . translate::get_lang()] ?>" /-->
                        <input name="region_txt" type='text' style="display: none" id="region_txt">
                    </td>
                </tr>
                <tr>
                    <td class="aright"><?= t('Город/Район') ?></td>
                    <td class="aleft">
                        <? if (1 != 1) { ?>
                            <input name="city_id" type="hidden" value="<?= request::get_int('city') ?>"/>
                            <? if (request::get_int('region') > 0 and request::get_int('region') != 10000 and request::get_int('region') != 9999) $cities = geo_peer::instance()->get_cities(request::get_int('region'));
                            elseif (request::get_int('country') > 1) $cities['10000'] = 'закордон';
                            else $cities[''] = '&mdash;'; ?>
                            <?= tag_helper::select('city', $cities, array('use_values' => false, 'value' => request::get_int('city'), 'id' => 'city', 'rel' => t('Выберите город/район'))); ?>
                            <input name="city_id" type="hidden" value="<?= request::get_int('city') ?>"/>
                        <? } ?>
                        <?
                        if (request::get_int('region') > 0 and request::get_int('region') != 9999)
                            $cities = geo_peer::instance()->get_cities(request::get_int('region'));
                        elseif (request::get_int('country') > 1)
                            $cities['9999'] = 'закордон';
                        else $cities = array();
                        ?>
                        <?= tag_helper::select('city', $cities, array('use_values' => false, 'value' => request::get_int('city'), 'id' => 'city', 'rel' => t('Выберите город/район'))); ?>
                        <? // $city = geo_peer::instance()->get_city(request::get_int('city']) ?>
                        <!--input name="city" class="text" type="text" value="<?= $city['name_' . translate::get_lang()] ?>" /-->
                        <input name="city_txt" type='text' style="display: none" id="city_txt">
                    </td>
                </tr>
                <tr>
                    <td class="aright"><?= t('Сфера деятельности') ?></td>
                    <? $sferas = user_auth_peer::instance()->get_segments(); ?>
                    <? $sferas[''] = "&mdash;";
                    ksort($sferas);
                    ?>
                    <td class="aleft"><?= tag_helper::select('segment', $sferas, array('use_values' => false, 'value' => request::get_int('segment'))) ?></td>
                </tr>


                <tr>
                    <td class="aright bold"></td>
                    <td class="aleft"><?= t('все поля обязательны для заполнения') ?></td>
                </tr>
            </table>

            <input type="submit" name="submit" class="button" value="<?= t('Отправить') ?>"/>
        </form>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#party_region').change(function () {
            var region_id = $(this).val();
            var region_attr_id = $(this).attr('id');
            if (region_id == '0') {
                $('#party_city').html('');
                $('#party_city').attr('disabled', true);
                return (false);
            }
            $('#party_city').attr('disabled', true);
            $('#party_city').html('<option>завантаження...</option>');

            var url = '/profile/get_select';
            $.post(url, {"region": region_id},
                function (result) {
                    if (result.type == 'error') {
                        alert('error');
                        return (false);
                    }
                    else {
                        var options = '<option value="">- оберіть місто/район -</option>';
                        $(result.cities).each(function () {
                            options += '<option value="' + $(this).attr('id') + '">' + $(this).attr('title') + '</option>';
                        });
                        $('#party_city').html(options);
                        $('#party_city').attr('disabled', false);
                    }
                },
                "json"
            );
        });
    });
</script>