<? if (request::get_string("tab") == "map") { ?>
<!--    <script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyCvfdOuQ3tJI1SNGZ1Nzg7fjeXECzTdOes&sensor=false"></script>-->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCvfdOuQ3tJI1SNGZ1Nzg7fjeXECzTdOes"
            type="text/javascript"></script>
    <script type="text/javascript" src="/static/javascript/map.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
        // function initMap() {
            mapWrapper = new MapWrapper('<?=(!$user_data['locationlat']) ? '50.4599800'
                : $user_data['locationlat']?>', '<?=(!$user_data['locationlng']) ? '30.4810890'
                : $user_data['locationlng']?>',<?=(!$user_data['MapZoom']) ? '9' : $user_data['MapZoom']?>);
            mapWrapper.init();
            <?=(!$user_data['locationlat']) ? 'mapWrapper.setMapToCity();' : 'mapWrapper.addMarker();'?>
        });

        function sbm() {
            mapWrapper.SaveToDB();
            return false;
        }
    </script>
    <form id="map_form" class="mt10 form hidden">
        <div style="border: 1px solid red;
    padding: 10px;
    text-align: center;">
            <?= t('Отметьте свое положение на карте перемещая маркер и нажмите "Сохранить"') ?>
        </div>
        <table width="100%" class="fs12">
            <tr>
                <td colspan="2">
                    <div class="map" id="Map" style="height: 750px; width: 750px;">

                    </div>
                    <? if (session::has_credential('admin')) { ?>
                        <input type="hidden" name="id" value="<?= $user_data['user_id'] ?>" />
                    <? } ?>
                    <input type="hidden" name="type" value="map" />
                    <input type="hidden" id="LocationLat" name="LocationLat" value="<?= $user_data['locationlat'] ?>" />
                    <input type="hidden" id="LocationLng" name="LocationLng" value="<?= $user_data['locationlng'] ?>" />
                    <input type="hidden" id="MapZoom" name="MapZoom" value="<?= $user_data['MapZoom'] ?>" />
                    <div class="info" id="Info">
                        <div class="input_city">
                            <div class="title">
                                <?= t('Адрес') ?>:
                            </div>
                            <div class="control">
                                <input id="address" class="address" name="address" type="text"
                                       value="<? if (!$user_data['locationlat']
                                           && $user_data['country_id'] == 1) { ?><? $country = geo_peer::instance()
                                           ->get_country($user_data['country_id']) ?><?= $country['name_'
                                       .translate::get_lang()] ?><? if ($user_data['region_id']) { ?><? $region = geo_peer::instance()
                                           ->get_region($user_data['region_id']) ?>, <?= $region['name_'
                                       .translate::get_lang()] ?><? } ?><? if ($user_data['city_id']) { ?><? $city = geo_peer::instance()
                                           ->get_city($user_data['city_id']) ?>, <?= $city['name_'
                                       .translate::get_lang()] ?><? } ?><? if ($user_data['street']) { ?>, <?= stripslashes(htmlspecialchars($user_data['street'])) ?> <?= stripslashes(htmlspecialchars($user_data['house'])) ?><? } ?><? } else { ?><? $country = geo_peer::instance()
                                           ->get_country($user_data['country_id']) ?><?= $country['name_'.translate::get_lang()] ?><? } ?>" />
                                <input id="submit" class="submit" name="submit" type="button" value="<?= t('Поиск') ?>" />

                            </div>
                        </div>
                        <div class="info-put-marker">
                            <?= t('Переместите маркер') ?>
                        </div>
                    </div>
                    </div>


                </td>
            </tr>
            <tr>
                <td width="170" class="aleft"><?= t('Показывать меня на карте') ?></td>
                <td class="aleft"><input value="1" <?= $user_data['onmap'] ? 'checked' : '' ?> type="checkbox" name="onmap" /></td>
            </tr>
            <tr>
                <td class="aright"></td>
                <td>
                    <input id="mapbutton" type="submit" name="submit" class="button" value=" <?= t('Сохранить') ?> " />
                    <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?= t('Отмена') ?> ">
                    <?= tag_helper::wait_panel('map_wait') ?>
                    <div class="success hidden mr10 mt10"><?= t('Изменения сохранены') ?></div>
                </td>
            </tr>
        </table>
    </form>
<? } ?>
