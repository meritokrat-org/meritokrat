<?php if (context::get_controller()->get_module() === "shop") { ?>
    <div id="cart" style="background-color: #EEEEEF">
        <h1 class="column_head p-0 m-0" style="line-height: initial">
            <a href="/shop" class="btn btn-sm"><?= t('Корзина') ?></a>
            <a id="edit" class="btn btn-sm text-white" style="cursor: pointer;" onclick="return false;">
                <span class="material-icons">edit</span>
            </a>
        </h1>
        <table style="word-wrap: normal; width: 100%; margin-bottom: 0;">
            <tr>
                <td style="width: 50%; font-weight: bold; text-align: right">
                    <?= t("Товаров") ?> :
                </td>
                <td id="col" style="text-align: left">

                </td>
            </tr>
            <tr>
                <td style="font-weight: bold; text-align: right">
                    <?= t("Сумма") ?> :
                </td>
                <td id="sum" style="text-align: left">

                </td>
            </tr>
        </table>
        <hr style="margin-bottom: 10px;">
        <div style="width: 100%; text-align: center;">
            <input data-id="<?= $item["id"] ?>" onclick="window.location = '/shop/buy'"
                   style="background: none repeat scroll 0% 0% #800000; color: #ffffff; font-weight: bold; margin: 0 auto 10px auto;"
                   type="button" value="<?= t("Оформить заказ") ?>">
        </div>
    </div>

    <script>
        $(document).ready(function () {
            setCart();

            $('#edit').click(function () {
                var MyWin = window.open('http://' + window.location.host + '/shop/buy/edit', 'MyWin', 'menubar=yes, width=880px, height=650px');
            });

            $('.pay_submit').click(function () {
                var itemBox = $('div[data-id=' + $(this).attr('data-id') + ']');

                var size = itemBox.find(':checked');
                var num = parseInt($(itemBox.find('[type=number]')).val());
                var price = parseInt($(itemBox.find('.price')).html());

                var err = itemBox.find('.error');
                var cart = [];
                var item = {};

                if (($(size).length == 0) && ($(itemBox.find('[type=radio]')).length > 0)) {
                    $(err).show();
                    return false;
                } else
                    $(err).hide();

                item.id = $(this).attr('data-id');
                item.price = price;
                item.num = num;

                if ($(itemBox.find('[type=radio]')).length > 0)
                    item.size = $(size).val();
                else
                    item.size = null;

                if ($.cookie('cart') != null) {
                    var cookie = JSON.parse($.cookie('cart'));

                    $(cookie).each(function (i, e) {
                        if (e.id == item.id && e.size == item.size)
                            item.num = item.num + e.num;
                        else
                            cart.push(e);
                    });
                }

                cart.push(item);

                $.cookie('cart', JSON.stringify(cart), {path: '/'});

                $(size).attr('checked', false);
                $(num).val(1);

                setCart();
            });
        });

        function setCart() {
            var col = 0;
            var sum = 0;

            if ($.cookie('cart'))
                JSON.parse($.cookie('cart')).forEach(function (e) {
                    col += e.num;
                    sum += e.num * e.price;
                });

            if (col > 0) {
                $('#col').html(col);
                $('#sum_col').html(col);
            } else
                $('#col').html('0');

            if (sum > 0) {
                $('#sum').html(sum);
                $('#sum_price').html(sum);
            } else
                $('#sum').html('0');
        }
    </script>
<?php } ?>

<?php if (session::is_authenticated()) { ?>
    <div class="ml10 mt10 hide">
        <a href="/profile/desktop?id=<?= session::get_user_id() ?>">
            <img src="/static/images/ban_2012_<?= (session::get("language") != "ru" ? "ua" : "ru") ?>.png"/>
        </a>
    </div>
<?php } ?>

<?php if (session::get_user_id() > 0) {
    $user_data = user_data_peer::instance()->get_item(session::get_user_id()) ?>
    <?php $user = user_auth_peer::instance()->get_item(session::get_user_id()) ?>

    <?php if (session::has_credential("admin")) { ?>
        <style>
            li b {
                color: black;
            }

            #sort_buffer {
                min-height: 30px;
                list-style: none;
            }

            #sort_box {
                position: relative;
                width: 230px;
                box-shadow: 0 0 15px rgba(0, 0, 0, .5);
                left: 10px;
            }

            #sort_box ul {
                margin: 0;
                padding: 0 0 0 2px;
                width: 100%;
            }
        </style>

        <?php if (session::has_credential('admin')
                && context::get_controller()->get_module() === "profile"
                && context::get_controller()->get_action() === "index"
        ) { ?>
            <div id="sort_box">
                <div class="column_head" style="cursor: pointer;" onclick="Application.ShowHide('sort')">
                    <div class="left">*<?= t('Сортировка') ?></div>
                    <div
                            class="right mt5 icoupicon <?= ($cur_status > 0 || request::get_int(
                                            'meritokrat'
                                    ) || request::get_int('expert') || request::get('offline')) ? '' : 'hide' ?>"
                            style="cursor: pointer;" id="sort_on"></div>
                    <div
                            class="right mt5 icodownt <?= ($cur_status > 0 || request::get_int(
                                            'meritokrat'
                                    ) || request::get_int('expert') || request::get('offline')) ? 'hide' : '' ?>"
                            style="cursor: pointer;" id="sort_off"></div>
                </div>
                <div class="pt10 pb10 box_content hide" id="sort">
                    <ul class="connectedSortable mb5" id="sort_buffer" style="overflow: scroll; height: 350px;">
                        <?php foreach ($sortable_list as $sort_user) { ?>
                            <li style="width: 98%"
                                data-user-id='<?= $sort_user ?>'><?= user_helper::full_name(
                                        $sort_user,
                                        true,
                                        ['class' => 'bold']
                                ) ?>
                                <a style="float: right; cursor: pointer" class="del_sort"
                                   data-user-id='<?= $sort_user ?>'>X</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div style="width: 266px;" class="mt10"></div>
        <?php } ?>
    <?php } ?>

    <?php if (session::is_authenticated()) { ?>
        <div class="ml-2">
            <button class="btn btn-primary w-100 rounded-0 text-uppercase" onclick="window.location = '/profile/invite'"><?= t('Пригласить') ?></button>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                $('#invite_friend').mouseover(function () {
                    $(this).css('box-shadow', 'inset 0px 0px 5px #FFCC66');
                })
                    .mouseout(function () {
                        $(this).css('box-shadow', 'none');
                    })
                    .mousedown(function () {
                        $(this).css('box-shadow', 'inset 0px 4px 5px #444');
                    })
                    .mouseup(function () {
                        $(this).css('box-shadow', 'none');
                    });
                // .click(function () {
                //     window.location = '/profile/invite';
                // });

            });
        </script>
    <?php } ?>

    <div class="row p-2 m-0 ml-2" style="background: #EEEEEF">
        <script>
            var timerID = null;

            function stopclock() {
                if (timerID)
                    clearInterval(timerID);
                timerID = null;
            }

            function startclock() {
                stopclock();
                timerID = setInterval(showtime, 1000); // или setInterval("showtime()", 1000)
            }

            function showtime() {
                //$("#mesgs").html((new Date()).toLocaleTimeString());
            }
        </script>
        <div class="col-4 p-0">
            <?= user_helper::photo(session::get_user_id(), 'r', ['class' => 'img-fluid img-thumbnail']) ?>
            <div><?= user_auth_peer::get_status($user_data['status']) ?></div>
        </div>
        <div class="col p-0 pl-2">
            <div class="d-flex flex-column">
                <?php if (session::get('admin_id') && session::get('admin_id') !== session::get_user_id()) { ?>
                    <a style="font-size: 9px;" href="/profile/logout"><?= user_helper::full_name(
                                session::get('admin_id'),
                                false
                        ) ?>&lArr;</a>
                <?php } ?>
                <?php if (session::has_credential('admin')) { ?>
                    <a href="/admin" class="bold">* <?= t("Админка") ?></a>
                <?php } ?>
                <a href="/profile-<?= $user_data['user_id'] ?>"><?= t('Профиль') ?></a>
                <?php if (session::has_credential('admin')) { ?>
                    <a href="/help/index?pages"><?= t('Сторінки') ?></a>
                <?php } ?>

                <?php //if (in_array(session::get_user_id(), [561, 5, 2, 346, 13969], true)) { ?>
                <!--    <a href="/help/index?shtab" >--><? //= t('Штаб') ?><!--</a>-->
                <?php //} ?>

                <?php if (user_auth_peer::instance()->get_rights(session::get_user_id(), 1)) { ?>
                    <?php /* user_auth_peer::instance()->get_rights(session::get_user_id(), 10) || $user['desktop'] === 1 */ ?>
                    <!--<a href="/profile/desktop?id=<?= $user_data['user_id'] ?>" class="bold" style="font-size: 12px "><?= t(
                            'Выборы 2012'
                    ) ?></a>-->
                <?php } ?>

                <a href="/messages"><?= t(
                            'Сообщения'
                    ) ?><?= $new_messages ? " <b id=\"mesgs\">+".$new_messages."</b>" : '' ?></a>
                <a style="display: inline" href="/friends"><?= t('Друзья') ?></a>
                <?php if ($pending_friends) { ?>
                    <a class="fw-bold" style="display: inline; margin-left:0"
                       href="/friends?pending=1"><?= count($pending_friends) ?></a>
                <?php } ?>

                <a href="/search?map=1&distance=10&submit=1">
                    <?= t('Кто рядом') ?>
                </a>

                <a href="/events/mine"><?= t('События') ?></a>

                <?php load::model('comments/comments') ?>

                <a href="/comments"><?= t('Комментарии') ?> <?= ($num = comments_peer::instance()->get_new_count(
                    )) ? '<b>+'.$num.'</b>' : '' ?></a>

                <a href="/bookmarks"><?= t('Закладки') ?></a>
                <?php if (session::has_credential('admin')) { ?>
                    <a href="/profile/user_invites?active=0">*<?= t('Управление приглашениями') ?></a>
                <?php } ?>
                <?php $num = db::get_scalar(
                        "SELECT count(DISTINCT(obj_id)) FROM invites WHERE to_id=:to_id AND status = 0",
                        ['to_id' => session::get_user_id()]
                ) ?>
                <?php if (session::has_credential('admin') || $num) { ?>
                    <a href="/invites"><?= t('Приглашения') ?> <b><?= $num ? '+'.$num : '' ?></b></a>
                <?php } ?>

                <?php
                if (session::has_credential('admin')) {
                    $count_applicants = db::get_scalar('SELECT count(*) FROM groups_applicants', []);
                } else {
                    load::model('groups/groups');
                    load::model('groups/applicants');
                    $count_applicants      = 0;
                    $creator               = db::get_cols(
                            'SELECT id FROM groups WHERE user_id='.session::get_user_id(),
                            [],
                            null,
                            true
                    );
                    $groups_artem_negodyai = db::get_cols('SELECT DISTINCT(group_id) FROM groups_applicants');
                    foreach ($groups_artem_negodyai as $id) {
                        if (groups_peer::instance()->is_moderator($id, session::get_user_id()) || in_array(
                                        $id,
                                        $creator
                                )) {
                            $count_applicants = $count_applicants + count(
                                            groups_applicants_peer::instance()->get_by_group($id)
                                    );
                        }
                    }
                }
                if ($count_applicants > 0) {
                    ?>
                    <a href="/groups?app=1"><?= t(
                                'Заявок'
                        ) ?></a>
                    <?php
                }
                ?>
                <?php if (session::has_credentials(['admin'])) {
                    $count_phts = db::get_scalar("SELECT count(*) FROM user_data WHERE new_photo_salt!=''", []);
                    if ($count_phts > 0) {
                        ?>
                        <a href="/profile/approve_photos"><?= t(
                                    'Прем. фото'
                            ) ?><?= $count_phts ? '<b> +'.$count_phts.'</b>' : '' ?></a>
                    <?php }
                } ?>

                <?php if (session::has_credentials(['admin'])) { ?>
                    <a href="/admin/users_list"><?= t("Список пользователей") ?></a>
                    <!--                 <a href="/precincts"><?= t("Избирательные участки") ?></a> -->
                    <a href="/houses"><?= t("Список домов") ?></a>
                <?php } ?>

                <?php if (session::has_credentials(['admin'])) {
                    $count_nms = count(user_data_peer::instance()->get_new_names());
                    if ($count_nms > 0) {
                        ?>
                        <a href="/admin/approve_name"><?= t(
                                    'Прем. имен'
                            ) ?><?= $count_nms ? '<b> +'.$count_nms.'</b>' : '' ?></a>
                    <?php }
                } ?>

                <?php if (session::has_credential('redcollegiant')) {
                    $count_posts = db::get_scalar(
                            "SELECT count(*) FROM blogs_posts WHERE type=1 AND created_ts>1312200000 AND id NOT IN (SELECT post_id FROM blogs_views WHERE user_id=".session::get_user_id(
                            ).")",
                            []
                    );
                    if ($count_posts > 0) {
                        ?>
                        <a href="/blogs/not_viewed"><?= t('Непросмотренные') ?> +<?= $count_posts ?></a>
                    <?php }
                }
                if (session::get_user_id()) {
                    $menuset = db_key::i()->get('menuset_'.session::get_user_id());
                }
                ?>

                <div id="menuholder" class="d-flex flex-column <?= ($menuset) ? '' : 'hide' ?>">
                    <a href="/blog-<?= $user_data['user_id'] ?>"><?= t('Публикации') ?></a>
                    <a href="/polls"><?= t('Опросы') ?></a>
                    <a href="/groups?user_id=<?= $user_data['user_id'] ?>"><?= t('Сообщества') ?></a>
                    <a href="/profile/edit?tab=settings"><?= t('Настройки') ?></a>
                </div>

                <a id="showm" href="javascript:" class="hoverover"
                   style="<?= ($menuset) ? 'display:none;' : '' ?>"><?= t('Показать все') ?>&nbsp;&nbsp;&nbsp;&nbsp;</a>
                <a id="hidem" href="javascript:" class="hoverover"
                   style="background:none;<?= ($menuset) ? '' : 'display:none;' ?>"><?= t('Свернуть') ?></a>
            </div>
        </div>
    </div>

    <?php
    /**
     *
     *
     *              НАЧАЛО РАБ КАБИНЕТА
     *
     */
    ?>

    <style type="text/css">
        .wo a {
            color: #000 !important;
            border-bottom: 1px solid #FFDF94 !important;
        }

        #mini-desktop {
            color: black !important;
        }
    </style>

    <?php if (session::get_user_id() == $user['id']) { ?>
        <div id="mini-desktop" class="column_head_small ml10 mt10 fs11 hide"
             style="height: 20px; background: #0d6efd;"><a href="javascript:" id="mini-desktop"
                                                                           style="color: white !important;"><?= t('Рабочий кабинет') ?></a>
        </div>
        <?php
        if (session::get_user_id()) {
            $minidesktopset = db_key::i()->get('minidesktopset_'.session::get_user_id());
        }
        ?>
        <div class="fs11 profile_menu ml10 wo <?= ($minidesktopset) ? '' : 'hide' ?>" id="mini-desktop-content"
             style="background: #FAF1D4;">
            <a id="ppo_link" href=""><?= t('Моя парторганизация') ?></a>
            <a id="reestr_num_link" href="/reestr"><?= t('Реестр членов') ?></a>
            <?php
            if (db::get_scalar(
                            "SELECT count(*) FROM ppo_members
     WHERE user_id=:user_id AND function IN(1,2) 
     AND group_id IN(SELECT id FROM ppo WHERE category IN(2,3) AND active=1)",
                            ["user_id" => session::get_user_id()]
                    )
                    || session::has_credential('admin')
            ) {
                ?><a id="reestr_num_link" href="/pporeestr"><?= t('Реестр ПО') ?></a><?php } ?>
            <a id="zayava_num_link" href="/zayava/list"><?= t('Заявки') ?></a>
            <a id="pay_num_link" href="/reestr/payments"><?= t('Взносы') ?></a>
            <a id="not_viewed_users_link" href="/people/not_viewed?mode=new"><?= t('Новые участники') ?></a>
            <a id="not_viewed_additional_link" href="/people/not_viewed"><?= t('Участники с доп.инфо ') ?></a>
            <a id="compose_ppo_link" href=""><?= t('Рассылка') ?></a>
            <a href="/zayava?new=1"><?= t('Подать заявление в члены за офф-лайна') ?></a>
            <?php if (session::has_credential('admin')) { ?>
                <a id="eventreport_link" href="/eventreport"><?= t('Отчеты про агитацию') ?></a>
                <a id="credentialed_link" href="/admin/credentialed"><?= t('Учасники з дод. правами') ?></a>
            <?php } ?>
        </div>
    <?php } ?>
    <div class="mt10 mb10 ml10 hide">
        <a href="/donate">
            <img src="/static/images/banner_meritokrat.png"/>
        </a>
    </div>


    <?php if (context::get_controller()->get_module() == 'profile' and request::get(
                    'action'
            ) == 'index' and request::get_string('id') > 0) { ?>
        <h1 class="column_head ml10 mt10"><?= t('Стена') ?></h1>
        <div class="aright fs11 pt5">
            <?php if (session::is_authenticated()) { ?>
                <a class="dotted" href="javascript:"
                   onclick="$('#ask_form').show(50);"><?= t('Оставить сообщение') ?></a>
            <?php } ?>
        </div>

        <?php if (session::is_authenticated()) { ?>
            <form style="background: #F7F7F7;" class="fs12 p10 mb10 ml10 hidden" action="/profile/ask" id="ask_form">
                <input type="hidden" name="profile_id" value="<?= request::get_string('id') ?>">

                <div class="mt5">
					<textarea rel="<?= t('Введите текст сообщения') ?>" name="text"
                              style="width: 98%; height: 50px;"></textarea>

                    <div class="mt5">
                        <input name="submit" type="submit" value=" <?= t('Отправить') ?> " class="button">
                        <?= tag_helper::wait_panel('ask_wait') ?>
                        <a href="javascript:" class="dotted fs11"
                           onclick="$('#ask_form').hide();"><?= t('Отмена') ?></a>
                    </div>
                </div>
            </form>

            <div class="mt10 ml10 acenter hidden success"
                 id="quesiton_success"><?= t('Спасибо, Ваше сообщение добавлено') ?></div>

        <?php } ?>

        <div id="questions">
            <?php if ($questions) { ?>
                <?php foreach ($questions as $id) {
                    include conf::get('project_root').'/apps/frontend/modules/profile/views/partials/question.php';
                } ?>
            <?php } else { ?>
                <div id="no_questions" class="acenter fs12 p5 ml10"><?= t('Сообщений нет') ?></div>
            <?php } ?>
        </div>
    <?php }
} ?>

<?php if (session::has_credential('admin')) { ?>
<!--    <a href="javascript:" id="hide_link" onClick="right_pane()" style="margin-left: 10px">--><?//= t('Свернуть') ?><!--</a>-->
<?php } ?>
<div id="toggle_block" class="d-none">


    <?php if (session::is_authenticated()) { ?>

    <?php if (session::is_authenticated() && ($user = user_auth_peer::instance()->get_item(
            session::get_user_id()
    )) != false && $user["status"] == 20){ ?>
        <div class="ml10 mt10 hide">
            <a href="/help/index?looking_for_advice"><img alt="looking_for_advice"
                                                          src="/static/images/common/looking_for_advice.gif"/></a>
        </div>
    <?php } ?>


        <div class="ml10 mt10">
            <a href="/blogs/programs?theme=39"><img alt="singapur" src="/static/images/singapur.png"/></a>
        </div>
        <div class="ml10 mt10">
            <a href="/blogs/programs?theme=40"><img alt="gruziya" src="/static/images/gruziya.png"/></a>
        </div>

    <?php if (session::is_authenticated() && ($user = user_auth_peer::instance()->get_item(
            session::get_user_id()
    )) != false && $user["status"] < 10){ ?>
        <div class="ml10 mt10">
            <a href="/blogpost2012"><img alt="became_member" src="/static/images/common/became_member.jpg"/></a>
        </div>
    <?php } ?>

        <div class="ml10 mt10 hide" style="width:230px;margin-bottom: 18px;">
            <!--background: url('/static/images/common/ppo_<?= session::get(
                    'language'
            ) == 'ru' ? 'ru' : 'ua' ?>_center.jpg') repeat-y-->
            <!--        <img src="/static/images/common/ppo_<?= session::get('language') == 'ru' ? 'ru' : 'ua' ?>_top.jpg"/>
        <div class="acenter mr5" style="color:#999;font-size: 15px;"><?= t('По состоянию на') ?> &nbsp;<?= date(
                    'd.m.y'
            ) ?></div>
        <div class="acenter cbrown bold" style="padding-top:5px; font-size: 17px; text-decoration: none;"><?= t(
                    'Членов МПУ'
            ) ?><span style="font-size: 30px;"> <a href="http://<?= context::get(
                    'host'
            ); ?>/people/index?status=20"><?= db::get_scalar(
                    "SELECT count(*) FROM user_auth WHERE status=20 OR ban=20"
            ) ?></a></span></div>
        <div class="acenter cbrown bold" style="padding-top:10px; font-size: 17px; text-decoration: none;"><?= t(
                    'Первичных организаций'
            ) ?><br>
            <span id="ppo_all" class="ppo_link pointer" style="display: inline; font-size: 30px;"><?= intval(
                    db_key::i()->get('ppo_all')
            ) ?></span> <?php //=t('из')?> -->
            <!--            <span style="display: inline; font-size: 30px;">126</span>-->
            <!--        </div>
        <div class="acenter cbrown bold" style="padding-top:10px; font-size: 17px; text-decoration: none;"><?= t(
                    'Местных организаций'
            ) ?><br>
            <span id="mpo_all" class="ppo_link pointer" style="display: inline; font-size: 30px;"><?= intval(
                    db_key::i()->get('mpo_all')
            ) ?></span> <?php //=t('из')?> -->
            <!--            <span style="display: inline; font-size: 30px;">42</span>-->
            <!--        </div>
        <div class="acenter cbrown bold" style="padding-top:10px; font-size: 17px; text-decoration: none;"><?= t(
                    'Региональных организаций'
            ) ?><br>
            <span id="rpo_all" class="ppo_link pointer" style="display: inline; font-size: 30px;"><?= intval(
                    db_key::i()->get('rpo_all')
            ) ?></span> <?php //=t('из')?> -->
            <!--            <span style="display: inline; font-size: 30px;">14</span>-->
            <!--        </div>
        <img style="margin-bottom: -5px;" src="/static/images/common/ppo_<?= session::get(
                    'language'
            ) == 'ru' ? 'ru' : 'ua' ?>_bottom.jpg"/>-->
            <!--<a href="/blogpost3126"><img src="/static/images/banner3.gif" /></a>-->
        </div>


    <?php if (session::has_credential('admin')) { ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('.ppo_link').click(function () {
                    _rem();
                    $this = $(this);
                    $form = $('<input id="ppo_val" type="text" style="width:20px" value="' + $this.text() + '"/>');
                    $form.insertAfter($this);
                    $('#ppo_val').focus();
                    $this.hide();
                    $(document).unbind('keypress').keypress(function (event) {
                        if (event.keyCode == '13') {
                            event.preventDefault();
                            $this.text($('#ppo_val').val()).show();
                            $.post('/admin/setredisval', {'key': $this.attr('id'), 'val': $('#ppo_val').val()});
                            _rem();
                        }
                    });
                });
            });

            function _rem() {
                $('#ppo_val').remove();
                $('.ppo_link').show();
            }
        </script>
    <?php } ?>

        <!--<div class="ml10 mt10">
            <a href="http://shevchenko.ua/ua/team/vacancies/pa/"><img src="/static/images/common/vacancies.gif"></a>
        </div>-->
    <?php /*
<div class="ml10 mt10">
    <a href="/blogpost76"><img src="/static/images/common/broshures.gif"></a>
</div>
<div onclick="document.location.href='/signatures/'" class="ml10 mt10" style="cursor:pointer;height:320px;width:230px;background: url('/static/images/common/right_signatures_<?=session::get('language')=='ru' ? 'ru' : 'ua'?>.jpg')">
        <div class="acenter  bold mr<?=session::get('language')=='ru' ? '5' : '5'?>" style="color:#999;padding-top: 57px; font-size: 15px;"><?=t('По состоянию на')?> &nbsp;<?=date('d.m.y')?></div>
        <div class="acenter " style="padding-top:22px;font-size:18px;color:#999;"><?=t('Собрано подписей')?></div>     
        <div class="acenter cbrown" style="padding-top:0px;font-size:34px;"><?=db::get_scalar('SELECT sum("fact") FROM "public"."user_desktop_signatures_fact"')?> <span style="font-size:18px;"><?=t('из')?></span> 11000</div>
        <div class="acenter " style="padding-top:18px;font-size:18px;color:#999;"><?=t('Покрыто районов')?></div>  
        <div class="acenter cbrown" style="padding-top:2px;font-size:34px;"><?=count(db::get_cols('SELECT DISTINCT on ("city_id") city_id FROM "public"."user_desktop_signatures_fact" WHERE city_id<700  AND city_id>0'))?> <span style="font-size:18px;"><?=t('из')?></span> 340</div>
        <div class="acenter cbrown bold" style="padding-top:10px;font-size:14px;"><a href="/signatures"><?=t('Детальная информация')?> &rarr;</a></div>  
</div>
<div class="ml10 mt10">
    <a href="/blogpost686"><img src="/static/images/common/notate_right.gif"></a>
</div>
         * 
         */ ?>
        <div class="ml10 mt10">
            <a href="/shop" target="_blank">
                <img width="229" alt="m-store" src="/static/images/store.png"></a>
        </div>
        <!--<div class="ml10 mt10">
            <a href="/blogpost1862"><img src="/static/images/common/sho_dali.gif"></a>
        </div>-->
    <?php } else { ?>
        <script type="text/javascript">

            $(document).ready(function () {

                $('#reqistration').mouseover(function () {
                    $(this).css('box-shadow', 'inset 0px 0px 5px #FFCC66');
                })
                        .mouseout(function () {
                            $(this).css('box-shadow', 'none');
                        })
                        .mousedown(function () {
                            $(this).css('box-shadow', 'inset 0px 4px 5px #444');
                        })
                        .mouseup(function () {
                            $(this).css('box-shadow', 'none');
                        })
                        .click(function () {
                            window.location = '/sign/up';
                        });

            });

        </script>
        <!--<input type="button" value="<?= mb_strtoupper(
                t('Зарегистрироваться')
        ) ?>" class="button ml10 bold" style="width: 230px;" onClick="window.location='/sign/up'">-->
    <?php } ?>
    <!--div class="ml10 mt10">
        <a href="http://yurysty.org"><img src="/static/images/common/banner_yurysty.jpg"></a>
    </div-->
    <!--div class="ml10 mt10">
        <a href="/profile/desktop"><img src="/static/images/common/desktop_small.gif"></a>
    </div>

    <div class="ml10 mt10">
        <a href="https://meritokratia.info"><img src="/static/images/common/meritokratia.png"></a>
    </div>
    <div class="ml10 mt10 mb10 acenter">
                   <a href="/blogpost26"><img src="/static/images/common/avto.jpg" alt="avtonumbers" ></a>
    </div-->
    <div class="ml10 mt10 mb10" style="background-color:#fff8d3;padding:13px;">
        <?php $block_title = db_key::i()->get('banners_block_title') ?>
        <?php if ($block_title) { ?>
            <p style="color: black;text-decoration:underline;margin-bottom:5px;font-size:90%;font-weight:bold;"><?= $block_title ?></p>
        <?php } ?>
        <?php
        load::view_helper('banner');
        load::model('banners/items');
        $items = banners_items_peer::instance()->get_list([], [], ['position ASC']);
        foreach ($items as $i) {
            $item = banners_items_peer::instance()->get_item($i);
            ?>
            <table style="margin-bottom:5px;">
                <tr>
                    <td width="78" style="padding-right:5px;padding-left:0;">
                        <a href="<?= $item['link'] ?>" target="_blank">
                            <?= banner_helper::photo($item['photo'], 'b', ['class' => 'left']) ?>
                        </a>
                    </td>
                    <td style="vertical-align:middle;padding-left:5px;padding-right:0;">
                        <p style="color:grey;margin:0 0 10px 0;font-size:90%;"><?= stripslashes($item['author']) ?></p>

                        <p style="color:black;margin:0;font-size:90%;font-weight:bold;">
                            <a href="<?= $item['link'] ?>" target="_blank"><?= stripslashes($item['title']) ?></a>
                        </p>
                    </td>
                </tr>
            </table>
            <?php
        }
        ?>
        <?php if (session::has_credential('admin')) { ?>
            <p style="margin:0;"><a href="/banners" style="color:grey;margin:0;font-size:90%;">Редагувати</a></p>
        <?php } ?>

    </div>

    <?php /*if ( session::is_authenticated() ) { ?>
<? load::model('friends/friends'); ?>
<? if($birthdays = friends_peer::instance()->get_by_date(session::get_user_id())){ ?>
<? foreach($birthdays as $b){ ?>
    <? //$user = user_data_peer::instance()->get_item($b); ?>
    <div class="ml10 mt10 mb15" style="background-color:#fff8d3;padding:13px;">
        <p style="font-size:90%;color: black;text-decoration:underline;margin-bottom:5px;font-weight:bold;">
            <?=t('ДНИ РОЖДЕНИЯ')?>
        </p>
        <table style="margin:0;padding:0;"><tr>
            <td width="54" style="padding-left:0;">
                <a href="/profile-<?=$b?>">
                    <?=user_helper::photo($b, 'sm', array('class' => 'left'))?>
                </a>
            </td><td class="fs12" style="line-height:1.2;padding-left:0;">
                <?=strip_tags(user_helper::full_name($b,true),'<a>')?>
                <br />
                <span style="color:grey">
                <? $udata = user_auth_peer::instance()->get_item($b) ?>
                <?// if ( $udata['type']>0 and  $udata['type']<15 ) { ?> 
		<?=user_auth_peer::get_status($udata['status'])?>
		<? //} ?>
                </span>
                <br/><br/>
                <?=user_helper::birthday($b)?>
            </td>
        </tr></table>
        <a class="fs11" href="/birthdays"><?=t('Ближайшие дни рождения')?> &rarr;</a>
    </div>
<? break;}}} */ ?>
</div>
<script type="text/javascript">
    function right_pane() {
        if ($('#hide_link').html() == '<?=t('Свернуть')?>') $('#hide_link').html('<?=t('Показать')?>');
        else $('#hide_link').html('<?=t('Свернуть')?>');
        $('#toggle_block').slideToggle(300);
    }

    jQuery(document).ready(function ($) {
        $('#showm').unbind('click').click(function () {
            $.post('/profile/menuset', {'show': 1});
            $('#menuholder').slideToggle();
            $('#hidem').show();
            $(this).hide();
        });
        $('#hidem').unbind('click').click(function () {
            $.post('/profile/menuset', {'show': 0});
            $('#menuholder').slideToggle();
            $('#showm').show();
            $(this).hide();
        });

        var minidesktopset = <?=($minidesktopset) ? '1' : '0'?>;

        $('#mini-desktop').unbind('click').click(function () {
            minidesktopset = minidesktopset ? 0 : 1;
            $.post('/profile/minidesktopset', {'show': minidesktopset});
            $('#mini-desktop-content').slideToggle();
        });

        var has_credential = '<?=session::has_credential('admin');?>';

        $.post('/profile',
                {'act': 'getMiniDesktopData'},
                function (data) {
                    if (has_credential != 1 && !data.rk_access)
                        return;
                    if (data.leader_ppo_id) {
                        $('#ppo_link').attr('href', '/ppo' + data.leader_ppo_id + '/' + (data.leader_ppo_number ? data.leader_ppo_number : ''));
                        $('#compose_ppo_link').attr('href', '/messages/compose_ppo?ppo=' + data.leader_ppo_id);
                    } else {
                        $('#ppo_link').hide();
                        $('#compose_ppo_link').hide();
                    }
                    if (data.reestr_num > 0)
                        $('#reestr_num_link').append($('<b />').html(' +' + data.reestr_num));
                    if (data.zayava_num > 0)
                        $('#zayava_num_link').append($('<b />').html(' +' + data.zayava_num));
                    if (data.pay_num > 0)
                        $('#pay_num_link').append($('<b />').html(' +' + data.pay_num));
                    if (data.not_viewed_users > 0)
                        $('#not_viewed_users_link').append($('<b />').html(' +' + data.not_viewed_users));
                    if (data.not_viewed_additional > 0)
                        $('#not_viewed_additional_link').append($('<b />').html(' +' + data.not_viewed_additional));
                    $('#mini-desktop').show();
                },
                'json',
        );

    });
</script>
