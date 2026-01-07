<?php load::view_helper('image'); ?>
<style>
    a.sidebar-link {
        color: black;
        font-weight: normal;
    }

    a.atab.sidebar-link:hover, a.sidebar-link.asel:focus {
        color: black;
        font-weight: bold;
    }
</style>
<?php if (
        context::get_controller()->get_module() === "profile" &&
        context::get_controller()->get_action() === "index" &&
        session::has_credential('admin')
) { ?>
    <script src="/static/javascript/jquery/jquery-1.10.2.js"></script>
    <script src="/static/javascript/jquery/jquery-ui-1.11.4.js"></script>

    <style>
        .placeholder {
            height: 1.5em;
            line-height: 1.2em;
        }
    </style>

    <script>
        var j = jQuery.noConflict();
        j(document).ready(function () {
            j('#sort_profile_photo, #sort_buffer').sortable({
                connectWith: '#sort_buffer',
                placeholder: 'placeholder',
                start: function (event, ui) {
                    $('b', ui.item).show();
                    $('div', ui.item).hide().parent().addClass('sortable_li');
                },
                stop: function (event, ui) {
                    if ($(ui.item).parent().attr('id') == 'sort_buffer') {
                        $('a.del_sort', ui.item).show();
                    } else {
                        $('b', ui.item).hide();
                        $('div', ui.item).show();
                        $('a.del_sort', ui.item).hide();
                    }

                    var sort_ids = new Array;

                    $('li', '#sort_buffer').each(function (n, e) {
                        sort_ids.push($(e).attr('data-user-id'));
                    });

                    $.post('/people', {
                        sort: true,
                        list: sort_ids,
                    }, function (response) {
                        console.log(response);
                    }, 'json');
                },
                remove: function (event, ui) {

                    var id = $(ui.item).attr('data-user-id');

                    ui.item.clone().prependTo('#sort_profile_photo');

                    if ($('#sort_buffer').children('li[data-user-id=' + id + ']').length > 1) {
                        $('#sort_buffer').children('li[data-user-id=' + id + ']').not('.sortable_li').remove();
                        $('#sort_buffer').children('li[data-user-id=' + id + ']').removeClass('sortable_li').css('width', '98%');
                    }

                    $('b', '#sort_profile_photo').hide();
                    $('div', '#sort_profile_photo').show();
                    $('a.del_sort', '#sort_profile_photo').hide();
                },
            }).disableSelection();

            $('.del_sort').click(function () {
                $(this).parent().remove();

                var sort_ids = new Array;

                $('li', '#sort_buffer').each(function (n, e) {
                    sort_ids.push($(e).attr('data-user-id'));
                });

                $.post('/people', {
                    sort: true,
                    list: sort_ids,
                }, function (response) {
                    console.log(response);
                }, 'json');
            });
        });
    </script>
<?php } ?>

<div class="mb10">
    <?php if ((context::get_controller()->get_module() == "profile") && (session::has_credential('admin'))) { ?>
        <ul style="list-style: none; margin: 0; padding: 0; height: 30px;" id="sort_profile_photo">
            <li data-user-id="<?= $user_data['user_id'] ?>">
                <div class="column_head"
                     style="text-align: center; border: 1px solid #000000; cursor: move; font-size: 15px; font-weight: bold;">
                    Сортировать
                </div>
                <b style="display:none;"><?= user_helper::full_name($user_data['user_id'], true); ?></b>
                <a style="display:none; float: right; cursor: pointer" class="del_sort"
                   data-user-id='<?= $user ?>'>X</a>
            </li>
        </ul>
    <?php } ?>

    <?= user_helper::photo(
            $user_data['user_id'],
            'p',
            [
                    'class' => 'border1', 'id' => 'avatar',
                    'style' => 'background-color: white; width: 100%; border: none; margin: 0; padding: 0;',
                    /*'width'=>'150', 'height'=>'240',*/
                    'alt'   => htmlspecialchars($user_data['first_name'] . ' ' . $user_data['last_name']),
            ],
            false
    )//,'profile')     ?>
</div>

<div class="clear"></div>
<?php if (session::has_credential('admin')) { ?>
    <div class="cgray fs10 ml5">*Профіль
        створений <?= date_helper::get_format_date($user['created_ts']) ?> <?= t('г.') ?></div> <?php } ?>
<div class="clear"></div>
<div>
    <?php /* $rate_offset = ceil( $user_data['rate']/10 ) + 2 ?>
	<!--div class="rate"><div style="background-position: <?=$rate_offset?>px 0px"><?=t('Опыт')?>: <?=number_format($user_data['rate'], 2)?></div></div-->
	<? */
    if (session::is_authenticated()) {
        load::view_helper('date');
        load::view_helper('status');
        ?>


        <?php if ($user['id'] !== session::get_user_id() &&
                (
                        (
                                status_helper::get_status(session::get_user_id()) < status_helper::MERITOCRAT &&
                                !db_key::i()->exists(
                                        session::get_user_id() . "_ask_" . status_helper::MERITOCRAT . "_recommendation_" . $user['id']
                                ) &&
                                (
                                        status_helper::can_M_recommend($user['id']) ||
                                        status_helper::can_PM_recommend($user_data)
                                )
                        ) ||
                        (
                                status_helper::get_status(session::get_user_id()) < status_helper::MPU_MEMBER &&
                                !db_key::i()->exists(
                                        session::get_user_id() . "_ask_" . status_helper::MPU_MEMBER . "_recommendation_" . $user['id']
                                ) &&
                                status_helper::can_PM_recommend($user_data)
                        )
                )
//                        )
        ) {
            ?>
            <a class="dotted ml5" href="javascript:;"
               onclick="askRecommendation('<?= $user['id'] ?>','<?= $user['status'] ?>');return false;"><b><?= T(
                            'Получить рекомендацию'
                    ) ?></b></a>
        <?php } ?>
        <div class="profile_menu">
            <?php
            if (
                    session::get_user_id() !== $user['id'] &&
                    $user['status'] < 20 &&
                    (
                            session::has_credential('admin') ||
                            status_helper::can_ex_recommend(
                                    user_data_peer::instance()->get_item(session::get_user_id()),
                                    user_data_peer::instance()->get_item($user['id'])
                            ) ||
                            (
                                    db_key::i()->exists(
                                            $user['id'] . "_ask_" . status_helper::MPU_MEMBER . "_recommendation_" . session::get_user_id()
                                    ) &&
                                    !db::get_scalar(
                                            'SELECT count(*) AS count FROM user_recommend WHERE recommending_user_id=' . session::get_user_id() . ' AND user_id=' . $user['id'] . ' AND status=' . status_helper::MPU_MEMBER
                                    )
                            ) ||
                            (
                                    status_helper::get_status(session::get_user_id()) >= status_helper::MPU_MEMBER &&
                                    status_helper::offlineOwn(session::get_user_id(), $user['id'])
                            )
                    )
            ) {
                ?>
                <a style="font-size:13px;" href="#" class="mt10" onclick="Application.showInfo('recommend_to_member');">
                    <b><?= t('Рекомендовать') . ' ' . t('в члены партии') ?></b>
                </a>
            <?php } ?>
            <?php
            if (
                    session::get_user_id() != $user['id'] &&
                    $user['status'] < 10 &&
                    (
                            session::has_credential('admin') ||
                            status_helper::can_ex_recommend(
                                    user_data_peer::instance()->get_item(session::get_user_id()),
                                    user_data_peer::instance()->get_item($user['id'])
                            ) ||
                            (
                                    db_key::i()->exists(
                                            $user['id'] . "_ask_" . status_helper::MERITOCRAT . "_recommendation_" . session::get_user_id()
                                    ) &&
                                    !db::get_scalar(
                                            'SELECT count(*) AS count FROM user_recommend WHERE recommending_user_id=' . session::get_user_id() . ' AND user_id=' . $user['id'] . ' AND status=' . status_helper::MERITOCRAT
                                    )
                            ) ||
                            (
                                    status_helper::get_status(session::get_user_id()) >= status_helper::MERITOCRAT &&
                                    status_helper::offlineOwn(session::get_user_id(), $user['id'])
                            )
                    )
            ) {
                ?>
                <a style="font-size:13px;" href="#" class="mt10"
                   onclick="Application.showInfo('recommend_to_meritokrat');">
                    <b><?= t('Рекомендовать') . ' ' . t('в меритократы') ?></b>
                </a>
            <?php } ?>

            <?php load::model('user/user_recommend'); ?>
            <?php if ($recommendations = user_recommend_peer::instance()->get_list(
                    ['user_id' => $user['id'], 'status' => 10]
            )) { ?>
                <span class="fs11 bold ml5"><?= t('Рекомендовали в "Меритократы"') ?>:</span><br/>
                <?php foreach ($recommendations as $r_id) {
                    $recommendation = user_recommend_peer::instance()->get_item($r_id) ?>
                    <?php if ($recommendation['recommending_user_id'] == session::get_user_id()) { ?>
                        <a onclick="if(!confirm('<?= t('Вы точно хотите удалить рекоммендацию?') ?>'))return false;"
                           href="/profile/delete_recomend&id=<?= $r_id ?>" class="p0"
                           title="<?= t('Удалить рекоммендацию') ?>" style="display: inline">x</a>
                    <?php } ?>
                    <?= user_helper::full_name(
                        $recommendation['recommending_user_id'],
                        true,
                        ['style' => 'display:inline;'],
                        false
                ) ?>
                    <span
                            class="fs11 cgray">(<?= date(
                                "d.m.Y H:i",
                                $recommendation['ts']
                        )//date_helper::get_format_date($recommendation['ts'],true)     ?>
                        )</span><br>

                <?php } ?>
                <br>
            <?php } ?>
            <?php if (session::has_credential('admin')) { ?>
                <?php load::model('user/user_recommend_log'); ?>
                <?php if ($reclog = user_recommend_log_peer::instance()->get_list(['user_id' => $user['id']])) { ?>
                    <span class="fs11 bold ml5"><?= t('Удаленные рекомендации') ?>:</span><br/>
                    <?php foreach ($reclog as $log_id) { ?>
                        <?php $log = user_recommend_log_peer::instance()->get_item($log_id) ?>
                        <?php $log_data = unserialize($log['data']) ?>
                        <?= user_helper::full_name(
                                $log_data['recommending_user_id'],
                                true,
                                ['style' => 'display:inline;'],
                                false
                        ) ?>
                        <br/>
                        <span
                                class="fs11 cgray ml5">(<?= t('предоставлена') . ': ' . date(
                                    "d.m.Y H:i",
                                    $log_data['ts']
                            ) ?>
                            )</span><br>
                        <span class="fs11 cgray ml5">(<?= t('удалена') . ': ' . date("d.m.Y H:i", $log['ts']) ?>)</span>
                        <br>
                        <span class="fs11 cblack ml5">
																	<?php if ($log_data["status"] == 10) { ?>
                                                                        <?= t('Рекомендовали в "Меритократы"') ?>
                                                                    <?php } else if ($log_data["status"] == 20) { ?>
                                                                        <?= t('Рекомендовали в члены Игоря Шевченка') ?>
                                                                    <?php } ?>
																</span><br/><br/>
                    <?php } ?>
                    <br>
                <?php } ?>
            <?php } ?>
            <?php if ($recommendations = user_recommend_peer::instance()->get_list(
                    ['user_id' => $user['id'], 'status' => 20]
            )) { ?>
                <span class="fs11 bold ml5"><?= t('Рекомендовали в члены команды Игоря Шевченка') ?>:</span><br/>
                <?php foreach ($recommendations as $r_id) {
                    $recommendation = user_recommend_peer::instance()->get_item($r_id) ?>
                    <?php if ($recommendation['recommending_user_id'] == session::get_user_id()) { ?>
                        <a onclick="if(!confirm('<?= t('Вы точно хотите удалить рекоммендацию?') ?>'))return false;"
                           href="/profile/delete_recomend&id=<?= $r_id ?>" class="p0"
                           title="<?= t('Удалить рекоммендацию') ?>" style="display: inline">x</a>
                    <?php } ?>
                    <?= user_helper::full_name(
                        $recommendation['recommending_user_id'],
                        true,
                        ['style' => 'display:inline;'],
                        false
                ) ?>
                    <span
                            class="fs11 cgray">(<?= date(
                                "d.m.Y H:i",
                                $recommendation['ts']
                        )//date_helper::get_format_date($recommendation['ts'],true)     ?>
                        )</span><br>

                <?php } ?>
                <br>
            <?php } ?>

            <?php if (session::has_credential('admin')) {
                load::model('user/membership');
                load::action_helper('membership', false);

                $mem   = user_membership_peer::instance()->get_user($user['id']);
                $uauth = user_auth_peer::instance()->get_item($user['id']);
                if ($mem['remove_type'] && $mem['removedate'] && $uauth["status"] != 20) {
                    ?>
                    <div class="fs12 mb10 bold acenter">
                        <?= membership_helper::get_party_off_types($mem['remove_type']) . ', ' . date(
                                'd.m.Y',
                                $mem['removedate']
                        ) ?>
                    </div>
                <?php }
            } ?>

            <?php #} ?>



            <?php //} ?>
            <?php
            load::model('ppo/ppo');
            $user_ppo = ppo_peer::instance()->get_user_ppo($user['id']);
            if ($user_ppo) {
                if (session::get_user_id() == $user['id']) { ?>
                    <a class="fs12"
                       href="/ppo<?= $user_ppo['id'] ?>/<?= $user_ppo['number'] ?>"><b><?= t(
                                    'Моя партийная организация'
                            ) ?></b></a>
                <?php } else { ?>
                    <a class="fs12"
                       href="/ppo<?= $user_ppo['id'] ?>/<?= $user_ppo['number'] ?>"><b><?= $user_ppo['title'] ?></b></a>
                <?php }
            } ?>

            <?php include __DIR__ . '/profile.nav/invited_by_me.php' ?>

            <?php if (session::has_credential('admin') && $user['id'] === session::get_user_id()) { ?>
                <a class="fs12" href="/zayava">* <?= ($zayava) ? t('Мое заявление') : t('Вступить в команду Игоря Шевченка') ?></a>
            <?php } ?>

            <?php if (session::has_credential('admin') && $zayava) { ?>
                <a class="fs12" href="/zayava?print=1&zayava=<?= $zayava ?>"><b><?= t('Заявление') ?></b></a>
            <?php } ?>

            <!--            --><?php // if (session::get_user_id() == $user['id']) { ?>
            <!--                <a href="/search?map=1&distance=10&submit=1">-->
            <!--                    <b>--><?php //= t('Кто рядом') ?><!--</b>-->
            <!--                </a>-->
            <!--            --><?php // } ?>


            <?php if (session::get_user_id() == $user['id'] || session::has_credential('admin')) { ?>
                <a href="/profile/edit?id=<?= $user['id'] ?>"><?= t('Редактировать профиль') ?></a>
                <a href="/profile/edit?id=<?= $user['id'] ?>&tab=photo"><?= t('Сменить фото') ?></a>
            <?php } ?>
            <?php if (request::get_string('action') == 'desktop') { ?>
                <a href="/profile-<?= $user['id'] ?>"><?= t('Вернуться в профиль') ?></a>
            <?php } ?>
            <?php if (session::get_user_id() != $user['id']) { ?>
                <?php if (!$user['offline'] && !$user['del']) { ?>
                    <a href="/messages/compose?user_id=<?= $user['id'] ?>"><?= t('Написать') ?></a>
                    <?php if ($history_messages = messages_peer::instance()->get_history_by_user(
                            session::get_user_id(),
                            $user['id']
                    )) { ?>
                        <a href="/messages/view?sender_id=<?= $user['id'] ?>"><?= t('История переписки') ?>
                            (<?= count($history_messages) ?>)</a>
                    <?php } ?>
                    <?php if (!friends_pending_peer::instance()->is_pending(
                                    $user['id'],
                                    session::get_user_id()
                            ) && !friends_peer::instance()->is_friend(session::get_user_id(), $user['id'])) { ?>
                        <a href="javascript:;" id="menu_add_friends"
                           onclick="Application.addToFriends(<?= $user['id'] ?>);"><?= t('Добавить в друзья') ?></a>
                    <?php } ?>

                    <?php load::model('bookmarks/bookmarks'); ?>
                    <?php $bkm = bookmarks_peer::instance()->is_bookmarked(session::get_user_id(), 6, $user['id']); ?>

                    <a href="#add_bookmark" class="b6" style="<?= ($bkm) ? 'display:none' : 'display:block' ?>"
                       onclick="Application.bookmarkItem('6','<?= $user['id'] ?>');return false;"><?= t(
                                'Добавить в любимые авторы'
                        ) ?></a>
                    <a href="#del_bookmark" class="b6" style="<?= ($bkm) ? 'display:block' : 'display:none' ?>"
                       onclick="Application.unbookmarkItem('6','<?= $user['id'] ?>');return false;"><?= t(
                                'Удалить из любимых авторов'
                        ) ?></a>
                <?php } ?>
                <?php $bkm = bookmarks_peer::instance()->is_bookmarked(session::get_user_id(), 2, $user['id']); ?>

                <a href="#add_bookmark" class="b2" style="<?= ($bkm) ? 'display:none' : 'display:block' ?>"
                   onclick="Application.bookmarkItem('2','<?= $user['id'] ?>');return false;"><?= t('В закладки') ?></a>
                <a href="#del_bookmark" class="b2" style="<?= ($bkm) ? 'display:block' : 'display:none' ?>"
                   onclick="Application.unbookmarkItem('2','<?= $user['id'] ?>');return false;"><?= t(
                            'Удалить из закладок'
                    ) ?></a>

                <?php if (!$user['offline']) { ?>
                    <?php if ($history_messages = messages_peer::instance()->get_history_by_user(
                            session::get_user_id(),
                            $user['id']
                    )) { ?>
                        <!--<a href="/messages/view?sender_id=<?= $user['id'] ?>"><?= t(
                                'История переписки'
                        ) ?> (<?= count($history_messages) ?>)</a>-->
                    <?php } ?>

                    <?php /* if ( !user_blacklist_peer::is_banned(session::get_user_id(), $user['id']) ) { ?>
                                            <a href="javascript:;" id="menu_blacklist" onclick="Application.addToBlacklist(<?=$user['id']?>);"class="bad"><?=t('В черный список')?></a>
                                    <? } */ ?>
                <?php } ?>

                <?php if (session::has_credential('admin')) { ?>
                    <a href="javascript:;" onclick="Application.groupInvite()">*<?= t('Пригласить в группу') ?></a>
                    <a href="javascript:;"
                       onclick="Application.showLists('<?= $user['id'] ?>')">*<?= t('Добавить в список') ?></a>
                <?php } ?>

            <?php } else { ?>
                <a href="/profile/invite"><?= t('Пригласить пользователя') ?></a>
                <?php if (session::has_credential('admin')) { ?>
                <a href="/profile/branding">* <?= t('Брендировать фото') ?></a>
                <?php } ?>

                <?php if ($is_ppo_leader) { ?>
                    <a href="/messages/compose_ppo?ppo=<?= $is_ppo_leader ?>" class="fs12">
                        <?= t('Сделать рассылку по ПО') ?>
                    </a>
                <?php } ?>

                <?php if (session::has_credential('admin')) { ?>
                    <a class="fs12" href="/profile/add">* <?= t('Создать офф-лайн профиль') ?></a>
                <?php } ?>

            <?php } ?>
        </div>
    <?php } ?>

    <?php
    /**
     *
     *
     *              НАЧАЛО МИНИ-РАБ СТОЛА
     *
     */
    ?>

    <?php // if(in_array(11752, array(session::get_user_id()))){ ?>
    <?php include 'mini_desktop.php' ?>
    <?php // } else { ?>
    <?php // include 'mini_desktop.backup.php'; ?>
    <?php // } ?>

    <?php
    /**
     *
     *
     *          УРА! ЭТОТ КОШМАР ЗАКОНЧИЛСЯ
     *
     */
    ?>

    <?php if (!$user['offline'] && !$user['del']) { ?>

        <?php if ($friends) { ?>
            <div style="background:#F6f6f6" class="fs12 bold mb10 mt15">
                &nbsp;&nbsp;<?= t('Друзья пользователя') ?> <?= count($friends) ?></div>
            <?php $num = 0; ?>
            <?php foreach ($friends as $id) { ?>
                <?php $num++; ?>
                <?php if ($num > 3) {
                    break;
                } ?>
                <?php if ($friend = user_data_peer::instance()->get_item($id)) { ?>
                    <div class="box_content p5 fs11">
                        <div
                                class="left"><?= user_helper::photo(
                                    $id,
                                    'sm',
                                    ['class' => 'border1 left'],
                                    true
                            ) ?></div>
                        <div style="margin-left: 60px;">
                            <?= user_helper::full_name($id) ?><br/>
                            <?php $fr = user_auth_peer::instance()->get_item($id) ?>
                            <span class="fs11 quiet"><?= user_auth_peer::get_status($fr['status']) ?></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                <?php }
            } ?>
            <div class="ml5 mt5 fs11" style="background: #F7F7F7">
                <a style="color:gray;"
                   href="/friends?user=<?= $user_data['user_id'] ?>"><?= t('Все друзья пользователя') ?>
                    (<?= count($friends) ?>) &rarr;</a>
            </div>
            <?php if (count($shared_friends) > 0) { ?>
                <div class="ml5 fs11" style="background: #F7F7F7">
                    <a style="color:gray;"
                       href="/friends/shared?user=<?= $user_data['user_id'] ?>"><?= t('Общие друзья') ?>
                        (<?= count($shared_friends) ?>) &rarr;</a>
                </div>
            <?php } ?>
            <?php // } ?>
        <?php } ?>

        <?php if ($user_groups) { ?>
            <?php $num = 0; ?>
            <div style="background:#F6f6f6" class="fs12 bold mb10 mt15">
                &nbsp;&nbsp;<?= t('Сообщества пользователя') ?> <?= count($user_groups) ?></div>
            <?php foreach ($user_groups as $id) { ?>
                <?php $num++; ?>
                <?php if ($num > 4) {
                    break;
                } ?>
                <?php if ($group = groups_peer::instance()->get_item($id)) { ?>
                    <div class="box_content p5 fs11">
                        <div
                                class="left"><?= group_helper::photo(
                                    $group['id'],
                                    's',
                                    true,
                                    ['class' => 'border1']
                            ) ?></div>
                        <div style="margin-left: 60px;">
                            <a href="/group<?= $group['id'] ?>"><?= stripslashes(
                                        htmlspecialchars($group['title'])
                                ) ?></a>

                            <div class="mt5 quiet fs11"><?= groups_peer::get_type($group['type']) ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                <?php }
            } ?>

            <div class="ml5 mt5 fs11" style="background: #F7F7F7">
                <a style="color:gray;"
                   href="/groups/index?user_id=<?= $user_data['user_id'] ?>"><?= t('Все сообщества пользователя') ?>
                    (<?= count($user_groups) ?>) &rarr;</a>
            </div>
        <?php } ?>

    <?php } ?>

</div>

<script type="text/javascript">
    jQuery(document).ready(function () {
        if ($('#minidesktop-content').html() == '') {
            $('#withoutinfo').show();
        }
        ;
    });

</script>