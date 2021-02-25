<?php $sub_menu = '/ppo/create'; ?>
<?php include 'partials/sub_menu.php' ?>

<?php if (!$allow_create) { ?>
    <div class="screen_message acenter"><?= t('У вас недостаточно прав') ?></div>
<?php } else if ($succes or request::get('success')) { ?>
    <div style="width: 500px;" class="screen_message"><?= t(
                'Спасибо за инициативу!<br/>
<br/>
Ваша заявка на создание партийной организации отправлена на рассмотрение Секретариата МПУ. 
Мы проинформируем Вас как только заявка будет одобрена.'
        ) ?></div>
<?php } else { ?>
    <script src="/static/javascript/map.js"></script>
    <form class="form_bg mt10 add_form" name="add_form" id="add_form">
        <input id="address" class="address" name="address" type="hidden"
               value="<?php if ($user_data['country_id']) { ?><?php $country = geo_peer::instance()->get_country(
                       $user_data['country_id']
               ) ?><?= $country['name_' . translate::get_lang()] ?><?php if ($user_data['region_id']) { ?><?php $region = geo_peer::instance()->get_region(
                       $user_data['region_id']
               ) ?>, <?= $region['name_' . translate::get_lang()] ?><?php } ?><?php if ($user_data['city_id']) { ?><?php $city = geo_peer::instance()->get_city(
                       $user_data['city_id']
               ) ?>, <?= $city['name_' . translate::get_lang()] ?><?php } ?><?php if ($user_data['street']) { ?>, <?= stripslashes(
                       htmlspecialchars($user_data['street'])
               ) ?> <?= stripslashes(htmlspecialchars($user_data['house'])) ?><?php } ?><?php } ?>"/>
        <input type="hidden" id="country" value="1">
        <h1 class="column_head"><?= t('Новая партийная организация') ?></h1>
        <table width="100%" class="fs12">
            <tr>
                <td width="22%" class="aright"><?= t('Уровень') ?></td>
                <td>
                    <select name="category" class="ppocategory">
                        <?php if (3 === $user_level) { ?>
                            <option value="3"><?= t('Региональная') ?></option>
                        <?php } ?>
                        <?php if ($user_level > 1) { ?>
                            <option value="2"><?= t('Местная') ?></option>
                            <option value="20" disabled><?= t('Окружная') ?></option>
                            <option value="1"><?= t('Первичная') ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr class="ptype <?= $user_level > 1 ? 'hidden' : '' ?>">
                <td width="22%" class="aright"><?= t('Тип') ?></td>
                <td>
                    <select name="ptype">
                        <?php
                        foreach (ppo_peer::get_ptypes() as $type => $title) { ?>
                            <option value="<?= $type ?>"><?= $title ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="18%" class="aright"><?= t('Название') ?></td>
                <td><input rel="<?= t('Введите название организации') ?>" style="width: 350px;"
                           value="<?= request::get('title') ?>" name="title" type="text" class="text"/></td>
            </tr>
            <tr id="region_row">
                <td class="aright"><?= t('Регион') ?></td>
                <td>
                    <?php $regions = geo_peer::instance()->get_regions($user_data['country_id']); ?>
                    <?php $regions['9999'] = 'закордон'; ?>
                    <?= tag_helper::select(
                            'region',
                            $regions,
                            ['use_values' => false, 'value' => $user_data['region_id'], 'id' => 'region']
                    ) ?>
                </td>
            </tr>
            <tr id="scity" class="<?= session::has_credential('admin') ? 'hidden' : '' ?>">
                <td class="aright"><?= t('Город/Район') ?></td>
                <td>
                    <?php if ($user_data['region_id'] > 0 && 10000 !== $user_data['region_id']) {
                        $cities = geo_peer::instance()->get_cities($user_data['region_id']);
                    } else if ($user_data['country_id'] > 1) {
                        $cities['10000'] = 'закордон';
                    } else {
                        $cities[''] = '&mdash;';
                    } ?>
                    <?= tag_helper::select(
                            'city',
                            $cities,
                            ['use_values' => false, 'value' => $user_data['city_id'], 'id' => 'city']
                    ); ?>
                </td>
            </tr>
            <tr id="nspu"
                class="<?= ($user_data['city_id'] >= 700 || 13 === $user_data['region_id']) ? 'hidden' : '' ?>">
                <td class="aright"><?= t('Населенный пункт') ?>
                </td>
                <td><input name="location"
                           class="text" type="text"
                           value=""/></td>
            </tr>
            <tr>
                <td class="aright"><?= t('Адрес') ?></td>
                <td><input name="adres" rel="<?= t('Адрес') ?>" class="text adres" type="text"/></td>
            </tr>
            <tr id="terd" class="<?= $user_level > 1 ? 'hidden' : '' ?>">
                <td class="aright"><?= t('Территория деятельности') ?></td>
                <td><a id="teritory" href="javascript:;"><?= t('Отметить на карте') ?></a></td>
            </tr>
            <tr>
                <td class="aright"><?= t('Дата учредительного собрания') ?></td>
                <td>
                    <input name="dzbori" id="dzbori" rel="<?= t('Дата учредительного собрания') ?>"
                           value="<?= request::get_string('dzbori') ?>" class="text" type="text"/>
                </td>
            </tr>
            <tr>
                <td class="aright"><?= t('Глава') ?></td>
                <td><input type="text" class="text" rel="<?= t('Глава') ?>" disabled="disabled" name="glava"/>
                    <input type="hidden" name="glavaid"/>
                    <a class="one_add_function" rel="glava" href="javascript:;"><?= t('Выбрать') ?></a></td>
            </tr>
            <tr>
                <td class="aright"><?= t('Ответственный Секретарь') ?>
                </td>

                <td><input type="text" class="text" rel="<?= t('Ответственный Секретарь') ?>" disabled="disabled"
                           name="secretar"/>
                    <input type="hidden" name="secretarid"/>
                    <a class="one_add_function" rel="secretar" href="javascript:;"><?= t('Выбрать') ?></a></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" name="submit" class="button" value=" <?= t('Отправить') ?> ">
                    <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray"
                           value=" <?= t('Отмена') ?> ">
                    <?= tag_helper::wait_panel() ?>
                    <div class="success hidden mr10 mt10"><?= t('Партийная организация создана, ожидайте...') ?></div>
                </td>
            </tr>
        </table>
        <input type="hidden" id="latbox" name="map_lat"/>
        <input type="hidden" id="lonbox" name="map_lon"/>
        <input type="hidden" id="zoom" name="map_zoom"/>
        <input type="hidden" id="coords" name="coords"/>
    </form>
<?php } ?>
<script src="/static/javascript/jquery/jquery.ui.datepicker.js"></script>
<script src="/static/javascript/jquery/jquery.ui.datepicker-uk.js"></script>
<script>
    jQuery(document).ready(function ($) {
        var settings = {
            changeMonth: true,
            changeYear: true,
            autoSize: true,
            showOptions: {direction: 'left'},
            dateFormat: 'dd-mm-yy',
            yearRange: '2010:2025',
            firstDay: true,
        };
        $('#dzbori').datepicker(settings);
        $('#dmu').datepicker(settings);
        if ($('input[@name=\'mu\']:checked').val()) $('#dmu').show();
    });
</script>
<script>
    jQuery(document).ready(function ($) {
        <?php /*$.datepicker.setDefaults($.extend(
      $.datepicker.regional["uk"])
    );*/ ?>
        $('#teritory').click(function () {
            ppoController.showTerritory();
            return false;
        });
        $('.one_add_function').click(function () {
            Application.showUsers($(this).attr('rel'));
            $(this).html('<?= t('Изменить') ?>');
            return false;
        });
        $('#glava').click(function () {
            Application.showUsers('glava');
            return false;
        });
        $('#secretar').click(function () {
            Application.showUsers('secretar');
            return false;
        });
        $('#category').change(function () {
            if ($(this).val() == 2) {
                $('#sfera-tr').show();
                $('#level-tr').hide();
                $('#hidden_1').removeAttr('checked');
            } else if ($(this).val() == 3) {
                $('#sfera-tr').hide();
                $('#level-tr').show();
                $('#hidden_1').removeAttr('checked');
            } else {
                $('#sfera-tr').hide();
                $('#level-tr').hide();
                $('#hidden_1').removeAttr('checked');
                if ($(this).val() == 4) {
                    $('#hidden_1').attr('checked', 'checked');
                    $('#privacy_2').attr('checked', 'checked');
                    $('#privacy_1').removeAttr('checked');
                }
            }
        });
    });
    $('#city').change(function () {
        if ($(this).val() >= 700 || $('#region').val() == 13) $('#nspu').hide();
        else $('#nspu').show();
    });
    $('.ppocategory').change(function () {
        if ($(this).val() == 3) $('#scity').hide();
        else $('#scity').show();
        if ($(this).val() == 1) {
            $('.ptype').show();
            $('#terd').show();
        } else {
            $('.ptype').hide();
            $('#terd').hide();
        }
    });
</script>

<?php
if ('meritokrat.org' === $_SERVER['SERVER_NAME']) {
    $key = 'AIzaSyDH9gzmncFxqj3TpA0qwZFTGJzdJtPCJgk';
} else {
    $key = 'ABQIAAAAXi7AtY5jQ4YMZS3uNqaQVhSn51_jLMmjl25B6QxLNt9bnzD_KBRpTuhouSuZjyfhXbGmAM6vx3bLFw';
}
?>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= $key ?>"
        type="text/javascript"></script>
<script>
    function initialize2() {
        // if (GBrowserIsCompatible()) {
        map = new google.maps.Map(document.getElementById('Map'));

        var gzm = document.getElementById('zoom').value;
        if (gzm == '') gzm = 13;
        var address = $('#address').attr('value');
        geocoder = new google.maps.Geocoder();
        geocoder.geocode({'address': address}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                var str = '';
                str += results[0].geometry.location;

                str = str.replace('(', '');
                str = str.replace(')', '');
                var arr = str.split(',');
                map.setCenter(new google.maps.LatLng(arr[0], arr[1]), 13);
            }
        });

        map.controls.push(google.maps.MapTypeControlStyle);
        // map.addControl(new GMapTypeControl());
        // map.clearOverlays();
        // GEvent.addListener(map, 'click', mapClick);
        // featureTable_ = document.getElementById("featuretbody");
        // select("hand_b");
    }

    // }
</script>