<? load::model('user/user_auth'); ?>
<? load::model('team/members'); ?>
<? load::model('team/applicants'); ?>
<? load::model('team/news'); ?>

<div class="profile d-flex flex-row">
    <div style="width: 230px;">
        <? if (in_array($group["id"], [14, 10, 15, 17])) { ?>
            <a style="margin: 5px 0 5px 8px; display: block" href="/team4/1">← <?= t('Назад к списку') ?></a>
        <? } else { ?>
            <a style="margin: 5px 0 5px 8px; display: block" href="/team">← <?= t('Назад к списку') ?></a>
        <? } ?>

        <div square="avatar" style="text-align:center; margin-bottom: 5px">
            <? include 'profile/avatar.php'; ?>
            <?= avatar($group) ?>
        </div>

        <div style="width: 227px; margin: auto;">

            <div class="ml5 profile_menu">
                <?
                /*db::get_scalar("SELECT count(*)
                    FROM team_members
                    WHERE group_id IN(SELECT id FROM team WHERE category=1)
                    AND user_id=".session::get_user_id())==0*/
                if ($group['ptype'] != 1 || ($group['ptype'] == 1 && $user_data['region_id'] == $group['region_id'])) {
                    if ($user['status'] == 20 && $group['category'] == 1) { ?>
                        <? if (!team_applicants_peer::instance()->is_applicant($group['id'], session::get_user_id())) { ?>
                            <a id="menu_apply" href="javascript:;" style="<?= $is_member ? 'display:none;' : '' ?>"
                               rel="<?= $group['id'] ?>">
                                <?= tag_helper::image('icons/check.png', array('class' => 'vcenter mr5')) ?>
                                <?= ($has_invite) ? t('Принять приглашение') : t('Подать заявку на вступление') ?>
                            </a>
                            <div id="text_apply"
                                 class="hidden quiet normal"><?= t('Заявка на вступление отправлена') ?></div>
                        <? } else { ?>
                            <div class="quiet normal"><?= t('Заявка на вступление отправлена') ?></div>
                        <? } ?>
                    <? }
                } ?>
            </div>

            <div square="invite" class="share left ml5" style="margin-left: 40px">
                <? if ($allow_edit && $group['category'] == 1) { ?>
                    <a href="javascript:;" onclick="Application.inviteItem('team', 5, <?= $group['id'] ?>)"
                       class="share mb5 ml15">
                        <span class="fs18"><?= t('Пригласить') ?></span>
                    </a>
                <? } ?>
            </div>

            <br/>

            <? if (!$privacy_closed) { ?>
                <div square="leads">
                    <? $group['glava_id'] = (int)team_members_peer::instance()->get_user_by_function(1, $group['id'], $group); ?>
                    <? $group['secretar_id'] = (int)team_members_peer::instance()->get_user_by_function(2, $group['id'], $group); ?>

                    <div class="column_head_small mt15"><?= t('Руководство') ?></div>

                    <? $types = $group['category'] == 4 ? [
                        t('Начальник команды'),
                        t('Руководитель оргмассового направления'),
                        t('Руководитель агитационно-рекламного направления'),
                        t('Руководитель информационно-аналитического направления'),
                        t('Руководитель административного направления'),
                        t('Главный юрисконсульт'),
                        t('Бухгалтер'),
                        t('Управляющий делами'),
                        t('Начальник службы безопасности')
                    ] : [
                        t('Лидер'),
                        t('Ответственный Секретарь')
                    ] ?>

                    <? for ($i = 1; $i <= ($group['category'] == 4 ? 9 : 2); $i++) { ?>
                        <? if (!($uid = (int)team_members_peer::instance()->get_user_by_function($i, $group['id'], $group))) {
                            continue;
                        } ?>
                        <div class="fs11 p10 mb5 box_content">
                            <div>
                                <div class="left" style="width: 50px">
                                    <?= user_helper::photo($uid, 's', array('width' => '100%'), true, 'user', '', false, false) ?>
                                </div>
                                <div class="left ml10" style="width: 147px">
                                    <?= user_helper::full_name($uid) ?>
                                    <br/>
                                    <?= $types[$i - 1] ?><br/>
                                    <? $udata = user_auth_peer::instance()->get_item($uid) ?>
                                    <? if ($udata['status'] > 0) { ?>
                                        <span class="cgray"><?= user_auth_peer::get_status($udata['status']) ?></span>
                                    <? } ?>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    <? } ?>
                </div>

                <br/>

                <div class="column_head_small">
                    <span class="left">
                        <a href="/team/news?id=<?= $group['id'] ?>" class="fs11 right"><?= t('Новости') ?></a>
                    </span>
                    <div class="clear"></div>
                </div>

                <? load::view_helper('image') ?>
                <? if ($news) { ?>
                    <? foreach ($news as $id) { ?>
                        <? $new = team_news_peer::instance()->get_item($id) ?>
                        <div class="fs11 mb5 pb5 clear" style="background: #F7F7F7;">
                            <div style="width: 60px;" class="left mr10">
                                <?= user_helper::team_photo(user_helper::team_photo_path($group['id'], 's', $group['photo_salt'])) ?>
                            </div>
                            <div class="fs11 ml5 white bold"></div>
                            <div class="fs11 p5">
                                <div class="mb5 quiet"><?= date_helper::human($new['created_ts'], ', ') ?></div>
                                <a href="team/newsread?id=<?= $new['id'] ?>" class="fs12 bold" style="color:black">
                                    <?= stripslashes(nl2br(htmlspecialchars($new['title']))) ?>
                                </a>
                            </div>
                        </div>
                    <? } ?>
                <? } else { ?>
                    <div class="fs11 pb5" style="background: #F7F7F7;">
                        <div class="fs11 ml5 white bold"></div>
                        <div class="fs11 p5">
                            <?= t('Новостей еще нет') ?>
                        </div>
                    </div>
                <? } ?>
                <? if (team_peer::instance()->is_moderator($group['id'], session::get_user_id())) { ?>
                    <div class="box_content left mt5 fs11">
                        <a href="/team/add_news?id=<?= $group['id'] ?>"
                           class="ml10 fs11"><?= t('Добавить новость') ?> &rarr;</a>
                    </div>
                <? } ?>
            <? } ?>
        </div>
    </div>
    <div class="flex-grow-1">
        <div>
            <script zz-url="/team/<?= $group["id"] ?>"
                    src="https://<?= $_SERVER['SERVER_NAME'] ?>/zz/js/lib/zz-iframe.min.js">
            </script>
        </div>

        <? if (1 != 1) { ?>
            <h1 class="mb5"
                style="font-size: 20px; line-height: 1; height: 60px; overflow: hidden; color: rgb(102, 0, 0);">
                <?= stripslashes(htmlspecialchars($group['title'])) ?> (<?= implode(' / ', $team_stat) ?>)
            </h1>

            <div style="margin-top: -35px; width: 100%" class="left">
                <!-- <span class="fs11 bold"><? if ($group['category'] == 0) $group['category'] = 1;
                $levels = team_peer::get_levels();
                echo $levels[$group['category']]; ?></span> -->
                <? if ($allow_create) { ?>
                    <a style="margin-right: 10px; float: right"
                       href="/team/create" <?= $sub_menu == '/team/create' ? 'class="bold"' : '' ?>><b><?= t('Создать') ?>
                            команду</b></a>
                <? } ?>
            </div>
            <div class="right fs11 quiet"><?= $group['active'] == 1 ? t('Одобрено') : t('Не одобрено') ?></div>
            <div class="left fs11 quiet bold">

                <a rel="common" class="tab_menu selected ml10 fs11 quiet"
                   href="javascript:;"><?= t('Основные сведения') ?></a>
                <? if (session::has_credential('admin') || (team_members_peer::instance()->allow_edit(session::get_user_id(), $group)
                        && sizeof(array_intersect(array(113, 123), $user_functions)) > 0)
                ) { ?>
                    <a rel="more" class="tab_menu ml10 fs11" href="javascript:;"><?= t('Служебная информация') ?></a>
                <? } ?>
                <? if ($allow_edit) { ?>
                    <a href="/team/edit?id=<?= $group['id'] ?>" class="ml10 fs11"><?= t('Редактировать') ?></a>
                <? } ?>
                <? if (session::has_credential('admin')) { ?>
                    <?= $group['active'] != 1 ? '<a href="/team/approve_team?team_id=' . $group['id'] . '" class="ml10 fs11">' . t('Одобрить') . '</a>' : '' ?>
                    <a onclick="return confirm('Видалити цю команду?');"
                       href="/team/delete_team?team_id=<?= $group['id'] ?>" class="ml10 fs11"><?= t('Удалить') ?></a>
                <? } ?>
                <? if ($allow_edit) { ?>
                    <a href="/messages/compose_team?team=<?= $group['id'] ?>" class="ml10 fs11"><?= t('Рассылки') ?></a>
                <? } ?>
            </div>

            <div class="clear"></div>
            <table id="common_box" class="tbox fs12 mt10" style="margin-bottom:0px;">
                <tr>
                    <td width="40%" class="bold aright">
                        <?= t('Регион') ?> / <?= t('Район') ?> / <?= t('Нас. пункт') ?>:
                    </td>
                    <td>
                        <? $region = geo_peer::instance()->get_region($group['region_id']); ?>
                        <?= $region['name_' . translate::get_lang()] ?><? $city = geo_peer::instance()->get_city($group['city_id']); ?>
                        <?= $group['category'] < 3 ? ' / ' . $city['name_' . translate::get_lang()] : '' ?><? if ($group['location']) { ?> / <?= stripslashes(htmlspecialchars($group['location'])) ?><? } ?>
                    </td>
                    <? if ($group['coords']){ ?>
                <tr>
                    <td width="40%" class="bold aright">
                        <?= t('Территория деятельности') ?>:
                    </td>
                    <td>
                        <a id="teritory" href="javascript:;"><?= t('Посмотреть на карте') ?></a>
                    </td>
                </tr><? } ?>
                <? if ($group['category'] == 1) { ?>
                    <tr>
                    <td width="40%" class="bold aright">
                        <?= t('Тип') ?>:
                    </td>
                    <? if (session::is_authenticated()) { ?>
                        <td>
                            <? $types = team_peer::get_ptypes();
                            echo $types[$group['ptype']] ?>
                            <? $bkm = bookmarks_peer::instance()->is_bookmarked(session::get_user_id(), 8, $group['id']); ?>
                            <a class="right bookmark mb10 ml5 b8" style="<?= ($bkm) ? 'display:none' : '' ?>"
                               href="#add_bookmark"
                               onclick="Application.bookmarkItem('8','<?= $group['id'] ?>');return false;"><b></b><span><?= t('В закладки') ?></span></a>
                            <a class="right unbkmrk mb10 ml5 b8" style="<?= ($bkm) ? '' : 'display:none' ?>"
                               href="#del_bookmark"
                               onclick="Application.unbookmarkItem('8','<?= $group['id'] ?>');return false;"><b></b><span><?= t('Удалить из закладок') ?></span></a>
                        </td>
                    <? } ?>
                    </tr><? } ?>
            </table>

            <? if (session::has_credential('admin') || team_members_peer::instance()->allow_edit(session::get_user_id(), $group)) { ?>
                <table id="more_box" class="tbox hidden fs12 mt10" style="margin-bottom:0px;">
                    <tr>
                        <td class="bold aright">
                            <?= t('Создал') ?>
                        </td>
                        <td>
                            <?= user_helper::full_name($group['creator_id']) ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="bold aright">
                            <?= t('Адрес') ?>
                        </td>
                        <td>
                            <?= stripslashes(htmlspecialchars($group['adres'])) ?>
                        </td>
                    </tr>
                    <? if ($group['dzbori'] || $group['uchasniki']) { ?>
                        <tr>
                            <td style="width:50%;" class="aright mr15 bold"><?= t('Учредительное собрание') ?>:</td>
                            <td></td>
                        </tr>
                    <? } ?>
                    <? if ($group['dzbori']) { ?>
                        <tr>
                            <td class="aright"><?= t('Дата') ?></td>
                            <td>
                                <?= $group['dzbori'] ? date("d-m-Y", $group['dzbori']) : '' ?>
                            </td>
                        </tr>
                    <? } ?>
                    <? $__members = explode(',', str_replace(array('{', '}'), array('', ''), $group['uchasniki']));
                    if ($__members[0] > 0) { ?>
                        <tr>
                            <td class="aright">Учасники</td>
                            <td>
                                <? $mc = 1;
                                foreach ($__members as $m): ?>
                                    <?= user_helper::full_name((int)$m, true, array(), false) ?>
                                    <?= $mc != count($__members) ? ', ' : '' ?>
                                    <? $mc++; endforeach; ?>
                            </td>
                        </tr>
                    <? } ?>
                    <? if ($group['uhvalnum'] || $group['duhval'] || $group['dovidnum']) { ?>
                        <tr>
                            <td class="aright mr15 bold">Рiшення Голови про затвердження:</td>
                            <td></td>
                        </tr>
                    <? } ?>
                    <? if ($group['uhvalnum']) { ?>
                        <tr>
                        <td class="aright">№</td>
                        <td>
                            <?= $group['uhvalnum'] ?>
                        </td>
                        </tr><? } ?>
                    <? if ($group['duhval']) { ?>
                        <tr>
                        <td class="aright"><?= t('Дата') ?></td>
                        <td>
                            <?= ($group['duhval']) ? date("d-m-Y", $group['duhval']) : '' ?>
                        </td>
                        </tr><? } ?>
                    <? if ($group['dovidnum'] || $group['doviddate'] || $group['protokolsdate'] || $group['dovidsdate']) { ?>
                        <tr>
                            <td class="aright mr15 bold">Легалiзацiя:</td>
                            <td></td>
                        </tr>
                    <? } ?>
                    <? if ($group['dovidnum']) { ?>
                        <tr>
                        <td class="aright">№ довiдки</td>
                        <td>
                            <?= $group['dovidnum'] ?>
                        </td>
                        </tr><? } ?>
                    <? if ($group['doviddate']) { ?>
                        <tr>
                        <td class="aright"><?= t('Дата выдачи') ?></td>
                        <td>
                            <?= ($group['doviddate']) ? date("d-m-Y", $group['doviddate']) : '' ?>
                        </td>
                        </tr><? } ?>
                    <? if ($group['svidcopy']) { ?>
                        <tr>
                            <td class="aright">Копія свідоцтва видана</td>
                            <td>
                            </td>
                        </tr><? } ?>
                    <? if ($group['protokolsdate'] || $group['dovidsdate']) { ?>
                        <tr>
                            <td class="aright mr15 bold">Отримання документів Секретаріатом:</td>
                            <td></td>
                        </tr><? } ?>
                    <? if ($group['protokolsdate']) { ?>
                        <tr>
                            <td class="aright">Протокол</td>
                        </tr><? } ?>
                    <? if ($group['dovidsdate']) { ?>
                        <tr>
                            <td class="aright">Довiдка / Свiдоцтво</td>
                        </tr><? } ?>
                    <? if ($group['zayava']) { ?>
                        <tr>
                            <td class="aright">Заява</td>
                        </tr><? } ?>
                    <? if ($group['vklnumber'] || $group['vkldate']) { ?>
                        <tr>
                            <td class="aright mr15 bold">Рiшення Голови про включення в структуру:</td>
                            <td></td>
                        </tr><? } ?>
                    <? if ($group['vklnumber']) { ?>
                        <tr>
                        <td class="aright">№</td>
                        <td>
                            <?= $group['vklnumber'] ?>
                        </td>
                        </tr><? } ?>
                    <? if ($group['vkldate']) { ?>
                        <tr>
                        <td class="aright"><?= t('Дата принятия') ?></td>
                        <td>
                            <?= ($group['vkldate']) ? date("d-m-Y", $group['vkldate']) : '' ?>
                        </td>
                        </tr><? } ?>
                    <? if ($group['svidvruch'] || $group['svidvig'] || $group['svidnum']) { ?>
                        <tr>
                            <td class="aright mr15 bold">Свідоцтво:</td>
                            <td></td>
                        </tr><? } ?>
                    <? if ($group['svidnum']) { ?>
                        <tr>
                        <td class="aright">№ Свідоцтва</td>
                        <td>
                            <?= $group['svidnum'] ?>
                        </td>
                        </tr><? } ?>
                    <? if ($group['svidvig']) { ?>
                        <tr>
                            <td class="aright">Виготовлення</td>
                        </tr><? } ?>
                    <? if ($group['svidvruch']) { ?>
                        <tr>
                            <td class="aright">Вручення</td>
                        </tr><? } ?>
                    <? if ($group['svidcom'] != '') { ?>
                        <tr>
                        <td class="aright">Коментар</td>
                        <td><?= $group['svidcom'] ?></td>
                        </tr><? } ?>


                    <!--        PARTY INVENTORY        -->
                    <?
                    if (!empty($inv_owners) && session::has_credential('admin')) {
                        $inventory_types = user_party_inventory_peer::instance()->get_inventory_type();
                        $inventory_types[0] = '&mdash;';
                        ksort($inventory_types);
                        foreach ($inventory_types as $inv_id => $inv_name) {
                            $current = db::get_scalar("SELECT sum(inventory_count) FROM party_inventory WHERE inventory_type=:itype AND user_id IN (" . implode(',', $inv_owners) . ")", array('itype' => $inv_id));
                            if ($current) { ?>
                                <? if (!$spike) { ?>
                                    <tr>
                                        <td class="aright mr15 bold">Партійний інвентар</td>
                                        <td></td>
                                    </tr>
                                    <? $spike = 1;
                                } ?>
                                <tr>
                                    <td class="aright mr15"><?= $inv_name ?></td>
                                    <td>
                                        <?= $current; ?>
                                    </td>
                                </tr>
                            <? } ?>
                        <? } ?>
                    <? } ?>
                    <!--        END        -->
                </table>
            <? } ?>

            <? if (count($sub_teams) > 0) { ?>
                <? include 'view/teams.php' ?>
            <? } ?>

            <? if (in_array($group["id"], [14, 10, 15, 17])) { ?>
                <div class="ml10 mt10">
                    <h1 class="column_head mt10 mb10" style="">
                        <?= t('Члены') ?> - <?= $members_cnt ?>
                        <? /*if($group['location']!=''){?><?=stripslashes(htmlspecialchars($group['location']))?> - <?}?>
                        <?=$group['category']<3?$city['name_' . translate::get_lang()].' - ':''?> <?=$region['name_' . translate::get_lang()]*/ ?>
                        <a class="right fs12" href="/team/members?id=<?= $group['id'] ?>"
                           style="text-transform:none;font-weight:normal;">
                            <?= t('Все члены') ?> &rarr;</a>
                    </h1>

                    <div class="left" style="width: 100%">
                        <? foreach ((array)$users as $k => $v) {
                            $user = user_data_peer::instance()->get_item($v);
                            ?>
                            <div class="left"
                                 style="width: 85px; margin: 0 1px 5px 0; height: 135px; text-align: center">
                                <a href="/profile-<?= $v ?>">
                                    <?= tag_helper::image("p/user/" . $user["user_id"] . $user["photo_salt"] . ".jpg", ["style" => "width: 70px"], context::get('image_server')) ?>
                                    <? if (is_null($user["user_id"])) { ?>
                                        <?= t("Неизвестный участник") ?>
                                    <? } else { ?>
                                        <?= $user["first_name"] . " " . $user["last_name"] ?>
                                    <? } ?>
                                </a>
                            </div>
                        <? } ?>
                    </div>
                </div>
                <div class="clear"></div>
            <? } ?>
        <? } ?>

        <? include 'view/services.php' ?>

    </div>
</div>
<?
if ($_SERVER['SERVER_NAME'] == 'meritokrat.org') {
    $key = 'ABQIAAAAeJTsA7ppykO6RHwqXVTnxhRUv1QFGme1wBmmBs0G3PPf8lp1HxSLUl3FK3V4kfgdjiurxjuNdubvAg';
} else {
    //ABQIAAAAeJTsA7ppykO6RHwqXVTnxhS237pdi7AAC2Fq3Ha5pN09SYJt4xRkBNsN6wrom0qaIxq0Haiiaurq6A
    $key = 'ABQIAAAAXi7AtY5jQ4YMZS3uNqaQVhSn51_jLMmjl25B6QxLNt9bnzD_KBRpTuhouSuZjyfhXbGmAM6vx3bLFw';
}

if ($group['map_lat'] == '' || $group['map_lon'] == '') {
    $group['map_lat'] = '50.4599800';
    $group['map_lon'] = '30.4810890';
    $ow = 0;
} else $ow = 1;
if ($group['map_zoom'] == 0) $group['map_zoom'] = '8';
?>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?= $key ?>"
        type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        $("#menu_apply").click(function () {
            var groupId = $(this).attr('rel');
            $.post(
                '/team/check_join',
                {id: groupId},
                function (results) {
                    if (results.check == 0) {
                        $('#menu_leave').remove();
                        $.post(
                            '/team/join',
                            {id: groupId},
                            function () {
                                $('#menu_join').hide();
                                $('#menu_leave').fadeIn(150);
                            },
                            'json'
                        );
                        $('#menu_apply').hide();
                        $('#text_apply').fadeIn(150);
                    } else {
                        Application.showInfo('why_move');
                    }
                },
                'json'
            );

        });
    });
    function teamJoin() {
        $('#menu_leave').remove();
        $.post(
            '/team/join',
            {id: $("#menu_apply").attr('rel'), text: $("#team_join_text").val()},
            function () {
                $('#menu_join').hide();
                $('#menu_leave').fadeIn(150);
            },
            'json'
        );
        $('#menu_apply').hide();
        $('#text_apply').fadeIn(150);
    }
    $('.tab_menu').click(function () {
        $('.tab_menu').removeClass('quiet');
        $(this).addClass('quiet');
        $(this).blur();
        $('.tbox').hide();
        $('#' + $(this).attr('rel') + '_box').show();
    });
</script>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $("#teritory").click(function () {
            if ($('#popup_box').length)$('#popup_box').show();
            else
                teamController.showTerritory(1, <?=request::get_int('id')?>);
            return false;
        });
        $("#glava").click(function () {
            Application.showUsers('glava');
            return false;
        });
        $("#secretar").click(function () {
            Application.showUsers('secretar');
            return false;
        });
        $('#category').change(function () {
            if ($(this).val() == 2) {
                $("#sfera-tr").show();
                $("#level-tr").hide();
                $("#hidden_1").removeAttr('checked');
            }
            else if ($(this).val() == 3) {
                $("#sfera-tr").hide();
                $("#level-tr").show();
                $("#hidden_1").removeAttr('checked');
            }
            else {
                $("#sfera-tr").hide();
                $("#level-tr").hide();
                $("#hidden_1").removeAttr('checked');
                if ($(this).val() == 4) {
                    $("#hidden_1").attr('checked', 'checked');
                    $("#privacy_2").attr('checked', 'checked');
                    $("#privacy_1").removeAttr('checked');
                }
            }
        });
    });
</script>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        <?if(request::get('tab')){?>
        $('.form').hide();
        $('#<?=request::get('tab')?>_form').show();
        <?}?>
    });
    $(".info").click(function () {
        if (!$("#applicant_info_" + this.id).is(":visible")) {
            $("#applicant_info_" + this.id).slideDown(100);

        }
        else {
            $("#applicant_info_" + this.id).slideUp(100);
        }
    });
    $('#city').change(function () {
        if ($(this).val() >= 700 || $('#region').val() == 13)$('#nspu').hide();
        else $('#nspu').show();
    });
    $('.teamcategory').change(function () {
        if ($(this).val() == 3)$('#scity').hide();
        else $('#scity').show();
        if ($(this).val() == 1)$('.ptype').show();
        else $('.ptype').hide();
    });

    function initialize2() {
        if (GBrowserIsCompatible()) {
            map = new GMap2(document.getElementById("Map"));

            map.setCenter(new GLatLng('<?=$group['map_lat']?>', '<?=$group['map_lon']?>'), <?=$group['map_zoom']?>);

            map.addControl(new GLargeMapControl());
            map.addControl(new GMapTypeControl());
            <?
            $cord_array = explode('; ', $group['coords']);
            $cord_array = array_unique(array_diff($cord_array, array('')));
            if(count($cord_array) > 0) {?>
            <?
            echo "var polyline = new GPolyline([";
            foreach($cord_array as $k=>$c) {
            $coordinates = explode(", ", $c);
            ?>
            <?
            echo "new GLatLng(" . $coordinates[1] . "," . $coordinates[0] . "),\n";
            }
            $firstcoordinates = explode(", ", $cord_array[0]);
            echo "new GLatLng(" . $firstcoordinates[1] . "," . $firstcoordinates[0] . ")\n";
            echo "],\"#ff0000\", 5, 1);\n";
            echo "map.addOverlay(polyline);\n\n";
            }
            ?>
            map.clearOverlays();
            GEvent.addListener(map, 'click', mapClick);
        }
    }
    function showDetail(id) {
        Popup.show();
        Popup.setHtml($(id).html());
        Popup.position()
    }
    function deleteItem(id, type) {
        $.ajax({
            type: 'post',
            url: '/team/edit',
            data: {
                submit: 1,
                delete_id: id,
                id: '<?=$group['id']?>',
                type: 'delete_inventory'
            },
            success: function (data) {
                resp = eval("(" + data + ")");
                if (resp.success == 1) {
                    $('#row' + id).remove();
                    $('#delete_' + type + '_link').html(resp.count);
                    Popup.setHtml($('#popup_' + type).html());
                }
                else alert(resp.error)
            }
        });
    }
</script>