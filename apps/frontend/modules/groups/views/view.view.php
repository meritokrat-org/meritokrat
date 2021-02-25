<?php
/**
 * @var array $group
 * @var array $users
 */
?>
<?php load::model('user/user_auth'); ?>
<?php load::model('groups/members'); ?>
<?php load::model('groups/applicants'); ?>
<?php load::model('groups/news'); ?>
<?php if ($group['category'] == 2) { ?>
    <div class="mb5"><a href="/groups/index?category=2"><?= t('Назад к списку рабочих групп') ?> &rArr;</a></div>
<?php } ?>
<div class="profile d-flex pt-3">
    <div style="width: 230px">
        <div style="text-align:center; margin-bottom: 5px;">
            <?= group_helper::photo($group['id'], 'p', false, []) ?>
        </div>

        <div style="width: 227px; margin: auto;">
            <?php /*$rate_offset = ceil( $group['rate'] ) + 2 ?>
			<div class="rate"><div style="background-position: <?=$rate_offset?>px 0px"><?=t('Рейтинг')?>: <?=number_format($group['rate'], 2)?></div></div>
*/ ?>
            <div class="ml5 profile_menu">
                <?php if ($group['privacy'] == groups_peer::PRIVACY_PUBLIC && user_auth_peer::get_rights(session::get_user_id(), 10)) { ?>
                    <a id="menu_join" href="javascript:;" style="<?= $is_member ? 'display:none;' : '' ?>"
                       onclick="groupsController.join(<?= $group['id'] ?>);">
                        <?= tag_helper::image('icons/check.png', ['class' => 'vcenter mr5']) ?>
                        <?= ($has_invite) ? t('Принять приглашение') : t('Вступить') ?>
                    </a>
                <?php } ?>
            </div>
            <div class="share left ml5" style="margin-left:40px">
                <?php if (session::is_authenticated() && user_auth_peer::get_rights(session::get_user_id(), 10)) { ?>
                    <?php if ($group['privacy'] != groups_peer::PRIVACY_PRIVATE or $group['private'] == 1 or groups_peer::instance()->is_moderator($group['id'], session::get_user_id())) { ?>
                        <a href="javascript:;" onclick="Application.inviteItem('group', 2, <?= $group['id'] ?>)"
                           class="share mb5 ml15"><span class="fs18"><?= t('Пригласить') ?></span></a>
                    <?php } ?>
                <?php } ?>
            </div>

            <?php if (!$privacy_closed) { ?>
                <div class="clear"></div>
                <?php if ($group['user_id'] != 31) { ?>
                    <div class="column_head_small mt15"><?= t('Координаторы') ?></div>
                    <div class="fs11 p10 mb5 box_content">
                        <div style="height: <?= (user_auth_peer::get_rights($group['user_id'], 20)) ? '120' : '70'; ?>px;">
                            <div class="left">
                                <?= user_helper::photo($group['user_id'], 's', ['class' => 'border1']) ?>
                            </div>
                            <div class="left ml15">
                                <?php $username = user_helper::full_name($group['user_id']);
                                if ($group['user_id'] == 31) $username = str_replace("Оргкомітету", "<br/>Оргкомітету", $username); ?>
                                <?= $username ?>
                                <br/>
                                <?= t('Руководитель') ?><br/>
                                <?php $udata = user_auth_peer::instance()->get_item($group['user_id']) ?>
                                <?php if ($udata['status'] > 0) { ?><span
                                        class="cgray"><?= user_auth_peer::get_status($udata['status']) ?></span> <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($moderators) { ?>
                    <?php foreach ($moderators as $id) { ?>
                        <div class="fs11 p10 mb5 box_content">
                            <div style="height: <?= (user_auth_peer::get_rights($id, 20)) ? '120' : '70'; ?>px;">
                                <div class="left">
                                    <?= user_helper::photo($id, 's', ['class' => 'border1']) ?>
                                </div>
                                <div class="left ml15">
                                    <?= user_helper::full_name($id) ?><br/>
                                    <?= t('Модератор') ?><br/>
                                    <?php $udata = user_auth_peer::instance()->get_item($id) ?>
                                    <?php if ($udata['status'] > 0) { ?> <span
                                            class="cgray"><?= user_auth_peer::get_status($udata['status']) ?> </span><?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>

                <br/>
                <div class="column_head_small">
					<span class="left"><a href="/groups/news?id=<?= $group['id'] ?>"
                                          class="fs11 right"><?= t('Новости') ?></a></span>
                    <!--a href="/groups/news?id=<?= $group['id'] ?>" class="fs11 right"><?= t('Все') ?></a-->
                    <div class="clear"></div>
                </div>
                <?php load::view_helper('image') ?>
                <?php if ($news) { ?>
                    <?php foreach ($news as $id) { ?>
                        <?php $new = groups_news_peer::instance()->get_item($id) ?>
                        <div class="fs11 mb5 pb5 clear" style="background: #F7F7F7;">
                            <div style="width: 60px;" class="left mr10">
                                <?php
                                if (!$new['photo']) {
                                    echo group_helper::photo($group['id'], 's', true, ['class' => 'border1']);
                                } else
                                    echo image_helper::photo($new['photo'], 's', 'groupnews', ['class' => 'border1']);
                                ?>
                            </div>
                            <div class="fs11 ml5 white bold"></div>
                            <div class="fs11 p5">
                                <div class="mb5 quiet"><?= date_helper::human($new['created_ts'], ', ') ?></div>
                                <a href="groups/newsread?id=<?= $new['id'] ?>" class="fs12 bold"
                                   style="color:black"><?= stripslashes(nl2br(htmlspecialchars($new['title']))) ?></a>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>

                    <div class="fs11 pb5" style="background: #F7F7F7;">
                        <div class="fs11 ml5 white bold"></div>
                        <div class="fs11 p5">
                            <?= t('Новостей еще нет') ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if (groups_peer::instance()->is_moderator($group['id'], session::get_user_id())) { ?>
                    <div class="box_content mt5 fs11"><a href="/groups/add_news?id=<?= $group['id'] ?>"
                                                         class="ml10 fs11"><?= t('Добавить новость') ?> &rarr;</a></div>
                <?php } ?>
            <?php } ?>
            <div class="profile_menu">
                <?php if (session::is_authenticated()) { ?>
                    <?php if ($group['user_id'] != session::get_user_id()) { ?>
                        <a id="menu_leave" href="javascript:;" style="<?= !$is_member ? 'display:none;' : '' ?>"
                           onclick="groupsController.leave(<?= $group['id'] ?>);"><?= tag_helper::image('icons/delete.png', ['class' => 'vcenter mr5']) ?><?= t('Покинуть') ?></a>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="flex-grow-1">
        <h1 class="mb5 fs28" style=" line-height: 1; height: 60px; overflow: hidden; color: rgb(102, 0, 0);">
            <?= stripslashes(htmlspecialchars($group['title'])) ?>
        </h1>
        <?php if (session::has_credential('admin')) { ?>
            <div style="margin-top: -28px;" class="left"><span
                        style="font-size: 12px;"><?= $group['active'] == 1 ? t('Одобрено') : t('Не одобрено') ?></span>
            </div>
        <?php } ?>
        <?php if ($privacy_closed) { ?>
            <?php if (session::is_authenticated() && $group['category'] != 2) { ?>
                <?php load::model('bookmarks/bookmarks'); ?>
                <?php $bkm = bookmarks_peer::instance()->is_bookmarked(session::get_user_id(), 3, $group['id']); ?>

                <a class="right bookmark mb10 ml5 b3" style="<?= ($bkm) ? 'display:none' : '' ?>" href="#add_bookmark"
                   onclick="Application.bookmarkItem('3','<?= $group['id'] ?>');return false;"><b></b><span><?= t('В закладки') ?></span></a>
                <a class="right unbkmrk mb10 ml5 b3" style="<?= ($bkm) ? '' : 'display:none' ?>" href="#del_bookmark"
                   onclick="Application.unbookmarkItem('3','<?= $group['id'] ?>');return false;"><b></b><span><?= t('Удалить из закладок') ?></span></a>

            <?php } ?>
        <?php } ?>
        <div class="left fs11 quiet bold">
            <?= t('Сообщество') ?>
            <?php if (groups_peer::instance()->is_moderator($group['id'], session::get_user_id()) || session::has_credential('designer') || session::has_credential('admin')) { ?>
                <a href="/groups/edit?id=<?= $group['id'] ?>" class="ml10 fs11"><?= t('Редактировать') ?></a>
            <?php } ?>

            <?php if (session::has_credential('admin')) { ?>
                <a href="/admin/groups?key=<?= $group['id'] ?>" class="ml10 fs11"><?= t('Администрирование') ?></a>
                <?= $group['active'] != 1 ? '<a href="/groups/approve_group?group_id=' . $group['id'] . '" class="ml10 fs11">' . t('Одобрить') . '</a>' : '' ?>
                <a onclick="return confirm('Видалити цю спільноту?');"
                   href="/groups/delete_group?group_id=<?= $group['id'] ?>" class="ml10 fs11"><?= t('Удалить') ?></a>
            <?php } ?>
            <?php if (session::has_credential('admin') || groups_peer::instance()->is_moderator($group['id'], session::get_user_id())) { ?>
                <a href="/messages/compose?group=<?= $group['id'] ?>" class="ml10 fs11"><?= t('Рассылки') ?></a>
            <?php } ?>
        </div>
        <div class="clear"></div>
        <?php if (!$privacy_closed) { ?>

            <!--if programma-->
            <?php if ($group['category'] == 2) { ?>

                <!-- POSITIONS -->
                <div class="tab_pane mt10">
                    <div class="ml10 mt5" style="text-transform:uppercase"><?= t('Позиция') ?></div>
                </div>
                <div id="pane_position" class="content_pane">

                    <?php if (groups_peer::instance()->is_moderator($group['id'], session::get_user_id()) || ($is_member && !$group['private'])) { ?>
                        <div class="box_content p5 mb10 fs11">
                            <a href="/groups/post_edit?group_id=<?= $group['id'] ?>&add=1"><?= t('Добавить обсуждение') ?>
                                &rarr;</a>
                        </div>
                    <?php } ?>

                    <?php if (!$positions) { ?>
                        <div class="m5 acenter fs12"><?= t('Обсуждений еще нет') ?></div>
                    <?php } else { ?>
                        <?php foreach ($positions as $id) { ?>
                            <?php $post = blogs_posts_peer::instance()->get_item($id) ?>
                            <div class="mb10 box_content p10 mr10" id="comment<?= $id ?>">
                                <div class="mb5 bold fs12">
                                    <a href="/groups/post?group_id=<?= $group['id'] ?>&id=<?= $id ?>"><?= stripslashes(htmlspecialchars($post['title'])) ?></a>
                                </div>
                                <div class="fs11 pb5">
                                    <div class="left quiet">
                                        <?= user_helper::full_name($post['user_id'], true, ['class' => 'mr10'], false) ?>
                                        <?= t('Комментариев') ?>:
                                        <b class="mr5"><?= blogs_comments_peer::instance()->get_count_by_post($id) ?></b>
                                        <?= t('Просмотров') ?>: <b><?= $post['views'] ?></b>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <div class="box_content p5 mb10 fs11"><a
                                href="/groups/posts?group_id=<?= $group['id'] ?>"><?= t('Все обсуждения') ?> &rarr;</a>
                    </div>
                </div>

                <!-- IDEALS -->
                <div class="tab_pane mt10">
                    <div class="ml10 mt5" style="text-transform:uppercase"><?= t('Идеи для Идеальной Страны') ?></div>
                </div>
                <div id="pane_ideal" class="content_pane">

                    <?php if (groups_peer::instance()->is_moderator($group['id'], session::get_user_id()) || ($is_member && !$group['private'])) { ?>
                        <div class="box_content p5 mb10 fs11">
                            <a href="/groups/post_edit?group_id=<?= $group['id'] ?>&add=1"><?= t('Добавить обсуждение') ?>
                                &rarr;</a>
                        </div>
                    <?php } ?>

                    <?php if (!$ideals) { ?>
                        <div class="m5 acenter fs12"><?= t('Обсуждений еще нет') ?></div>
                    <?php } else { ?>
                        <?php foreach ($ideals as $id) { ?>
                            <?php $post = blogs_posts_peer::instance()->get_item($id) ?>
                            <div class="mb10 box_content p10 mr10" id="comment<?= $id ?>">
                                <div class="mb5 bold fs12">
                                    <a href="/groups/post?group_id=<?= $group['id'] ?>&id=<?= $id ?>"><?= stripslashes(htmlspecialchars($post['title'])) ?></a>
                                </div>
                                <div class="fs11 pb5">
                                    <div class="left quiet">
                                        <?= user_helper::full_name($post['user_id'], true, ['class' => 'mr10'], false) ?>
                                        <?= t('Комментариев') ?>:
                                        <b class="mr5"><?= blogs_comments_peer::instance()->get_count_by_post($id) ?></b>
                                        <?= t('Просмотров') ?>: <b><?= $post['views'] ?></b>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <div class="box_content p5 mb10 fs11"><a
                                href="/groups/posts?group_id=<?= $group['id'] ?>"><?= t('Все обсуждения') ?> &rarr;</a>
                    </div>
                </div>

                <!-- POSTS -->
                <div class="tab_pane">
                    <div class="ml10 mt5" style="text-transform:uppercase"><?= t('Обсуждения') ?></div>
                </div>
                <div id="pane_posts" class="content_pane">

                    <?php if (groups_peer::instance()->is_moderator($group['id'], session::get_user_id()) || ($is_member && !$group['private'])) { ?>
                        <div class="box_content p5 mb10 fs11">
                            <a href="/groups/post_edit?group_id=<?= $group['id'] ?>&add=1"><?= t('Добавить обсуждение') ?>
                                &rarr;</a>
                        </div>
                    <?php } ?>

                    <?php if (!$posts) { ?>
                        <div class="m5 acenter fs12"><?= t('Обсуждений еще нет') ?></div>
                    <?php } else { ?>
                        <?php foreach ($posts as $id) { ?>
                            <?php $post = blogs_posts_peer::instance()->get_item($id) ?>
                            <div class="mb10 box_content p10 mr10" id="comment<?= $id ?>">
                                <div class="mb5 bold fs12">
                                    <a href="/groups/post?group_id=<?= $group['id'] ?>&id=<?= $id ?>"><?= stripslashes(htmlspecialchars($post['title'])) ?></a>
                                </div>
                                <div class="fs11 pb5">
                                    <div class="left quiet">
                                        <?= user_helper::full_name($post['user_id'], true, ['class' => 'mr10'], false) ?>
                                        <?= t('Комментариев') ?>:
                                        <b class="mr5"><?= blogs_comments_peer::instance()->get_count_by_post($id) ?></b>
                                        <?= t('Просмотров') ?>: <b><?= $post['views'] ?></b>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <div class="box_content p5 mb10 fs11"><a
                                href="/groups/posts?group_id=<?= $group['id'] ?>"><?= t('Все обсуждения') ?> &rarr;</a>
                    </div>
                </div>

                <div class="tab_pane">
                    <div class="ml10 mt5" style="text-transform:uppercase"><?= t('Библиотека') ?></div>
                </div>
                <div id="pane_files" class="content_pane">
                    <div class="box_content p5 fs11">
                        <?php if (groups_peer::instance()->is_moderator($group['id'], session::get_user_id()) || ($is_member && !$group['private'])) { ?>
                            <a href="/groups/file?id=<?= $group['id'] ?>&add=1"><?= t('Добавить материал') ?> &rarr;</a>
                        <?php } ?>
                        <div class="right"><a id="lstfiles" href="javascript:;"><?= t('Последние') ?></a></div>
                    </div>
                    <?php include 'partials/files.php' ?>
                    <div class="box_content p5 mb10 fs11"><a
                                href="/groups/file?id=<?= $group['id'] ?>"><?= t('Все материалы') ?> &rarr;</a></div>
                </div>

            <?php } else { ?>

                <table class="fs12 mt10" style="margin-bottom:0px;">
                    <?php if ($group['type'] != 0) { ?>
                        <tr>
                        <td width="35%;" class="bold aright"><?= t('Сфера') ?></td>
                        <td><?= groups_peer::get_type($group['type']) ?></td></tr><?php } ?>
                    <?php if ($group['level'] != 0) { ?>
                    <tr>
                        <td width="35%;" class="bold aright"><?= t('Уровень') ?></td>
                        <td><?= groups_peer::get_level($group['level']) ?><?php } ?>

                            <?php /* <!--tr><td width="35%;" class="bold aright"><?=t('Территория')?></td><td><?=groups_peer::get_teritory($group['teritory'])?></td></tr-->
				if ( $group['url'] ) { ?>
					<tr><td class="bold aright"><?=t('Web сайт')?></td><td><a rel="nofollow" target="_blank" href="http://<?=$group['url']?>"><?=htmlspecialchars($group['url'])?></a></td></tr>
				<? } */ ?>

                    <tr>
                        <td width="35%;" class="bold aright"><?= t('Краткое описание') ?></td>
                        <td><?= stripslashes(htmlspecialchars($group['aims'])) ?></td>
                    </tr>
                    <!--tr><td width="35%;" class="bold aright"><?= t('Территория') ?></td><td><?= groups_peer::get_teritory($group['teritory']) ?></td></tr-->
                    <?php if (count($councilMembers)) { ?>
                        <tr>
                            <td class="bold aright"><?= t('Количество участников') ?></td>
                            <td>
                                <a href="/groups/members?id=<?= $group['id'] ?>"><?= count($councilMembers) ?></a> <?php load::model('bookmarks/bookmarks'); ?>
                                <?php $bkm = bookmarks_peer::instance()->is_bookmarked(session::get_user_id(), 3, $group['id']); ?>

                                <a class="right bookmark mb10 ml5 b3" style="<?= ($bkm) ? 'display:none' : '' ?>"
                                   href="#add_bookmark"
                                   onclick="Application.bookmarkItem('3','<?= $group['id'] ?>');return false;"><b></b><span><?= t('В закладки') ?></span></a>
                                <a class="right unbkmrk mb10 ml5 b3" style="<?= ($bkm) ? '' : 'display:none' ?>"
                                   href="#del_bookmark"
                                   onclick="Application.unbookmarkItem('3','<?= $group['id'] ?>');return false;"><b></b><span><?= t('Удалить из закладок') ?></span></a>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
                <div class="tab_pane">
                    <a rel="description" href="javascript:;"><?= t('Описание') ?></a>
                    <a rel="events"
                       href="javascript:;"><?= t('События') ?><?= ($eventcount = count($events)) > 0 ? '&nbsp;' . $eventcount : '' ?></a>
                    <?php /* if ( count($talks)>0 ) {  ?><a rel="talk" href="javascript:;" class="selected"><?=t('Мысли')?></a><? //} */ ?>
                    <a rel="posts" href="javascript:;"
                       class="selected"><?= t('Мысли') ?><?= $posts_count > 0 ? '&nbsp;' . $posts_count : '' ?></a>
                    <?php /* if ( count($proposals)>0 or session::has_credential('admin') ) { ?><a rel="proposal" href="javascript:;"<?=count($proposals) ? '' : ' style="font-weight:normal"'?>><?=t('Предложения')?></a><? } ?>
                            <? if ( count($positions)>0 or session::has_credential('admin')) { ?><a rel="position" href="javascript:;"<?=count($positions) ? '' : ' style="font-weight:normal"'?>><?=t('Позиция МПУ')?></a><? } */ ?>
                    <?php /* if ( count($files)>0 or groups_peer::instance()->is_moderator($group['id'], session::get_user_id())) { */ ?>
                    <a rel="files" id="flshowall"
                       href="javascript:;"<?= ($filecount = db::get_scalar("SELECT count(*) FROM groups_files WHERE group_id=" . $group['id'])) > 0 ? '' : ' style="font-weight:normal"' ?>><?= t('Библиотека') ?><?= ($filecount > 0) ? '&nbsp;' . $filecount : '' ?></a><?php // } ?>
                    <?php /* if ( count($links)>0 or groups_peer::instance()->is_moderator($group['id'], session::get_user_id())) { ?><a rel="links" href="javascript:;"<?=count($links) ? '' : ' style="font-weight:normal"'?>><?=t('Ссылки')?></a><? } */ ?>
                    <!--a rel="aims" href="javascript:;"><?= t('Цели') ?></a-->
                    <?php if ($photos or groups_peer::instance()->is_moderator($group['id'], session::get_user_id())) { ?>
                        <a rel="photo"
                           href="javascript:;"<?= ($photocount = count($photos)) > 0 ? '' : ' style="font-weight:normal"' ?>><?= t('Фото') ?><?= ($photocount > 0) ? '&nbsp;' . $photocount : '' ?></a><?php } ?>
                    <div class="clear"></div>
                </div>

                <div id="pane_description" class="content_pane hidden">
                    <?php if ($group['description']) { ?>
                        <div class="m5 fs12"><?= nl2br(htmlspecialchars($group['description'])) ?></div>
                    <?php } else { ?>
                        <div class="m5 acenter fs12"><?= t('Описания еще нет') ?></div>
                    <?php } ?>
                </div>

                <div id="pane_events" class="content_pane hidden">
                    <?php if (groups_peer::instance()->is_moderator($group['id'], session::get_user_id())) { ?>
                        <div class="box_content p5 mb10 fs11"><a
                                    href="/events/create?type=1&content_id=<?= $group['id'] ?>"><?= t('Добавить событие') ?>
                                &rarr;</a>
                        </div>
                    <?php } ?>
                    <?php if ($events) { ?>
                        <div class="mb10 box_content p10 mr10">
                            <?php foreach ($events as $event_id) { ?>
                                <?php $event = events_peer::instance()->get_item($event_id); ?>
                                <div class="mb5 bold fs12">
                                    <a class="acenter mb10 ml10" href="/event<?= $event_id ?>"><?= $event['name'] ?></a><br/>
                                </div>
                                <div class="quiet">
                                    <div class="fs11 pb5 ml10">
                                        <?php if (date('d-m-Y', $event['start']) == date('d-m-Y', $event['end']))
                                            $kolu = date_helper::get_format_date($event['start'], false) . ', ' . t('с') . ' ' . date('H:i', $event['start']) . ' до ' . date('H:i', $event['end']);
                                        else $kolu = t('с') . ' ' . date_helper::get_format_date($event['start']) . ' ' . date('H:i', $event['start']) . ' <br/>до ' .
                                                date_helper::get_format_date($event['end']) . ' ' . date('H:i', $event['end']); ?><?= $kolu ?>
                                        <br/>
                                        <?= t('Организатор') . ": " ?>
                                        <?php
                                        switch ($event['type']) {
                                            case 3:
                                                ?>
                                                <a href="/profile-31"
                                                   style="color:black"><?= t("Секретариат МПУ") ?></a>
                                                <?php
                                                break;
                                            default:
                                                ?>
                                                <?= user_helper::full_name($event['user_id'], true, ['style' => 'color:black'], false); ?>
                                            <?php
                                        }
                                        ?>
                                        <br/>
                                        <?= t('Событие посещают') ?>:
                                        <b><?= $event['users1sum'] + $event['users3sum'] + $event['users1count'] + $event['users3count'] ?> <?= t('участников') ?></b>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } else { ?>
                        <div class="m5 acenter fs12">
                            <?= t('Событий еще нет') ?>
                        </div>
                    <?php } ?>
                </div>

                <div id="pane_aims" class="content_pane hidden">
                    <?php if ($group['aims']) { ?>
                        <div class="m5 fs12"><?= nl2br(htmlspecialchars($group['aims'])) ?></div>
                    <?php } else { ?>
                        <div class="m5 acenter fs12"><?= t('Цели еще не определены') ?></div>
                    <?php } ?>
                </div>

                <div id="pane_photo" class="content_pane hidden">
                    <?php if ($photos) { ?>
                        <div class="fs12 mt10 p10 mb15 gallery" style="text-align:center">
                            <?php foreach ($photos as $photo_id) { ?>
                                <a href="/photo?type=3&oid=<?= $group['id'] ?>" rel="prettyPhoto[gallery]">
                                    <?= photo_helper::photo($photo_id, 'h', []) ?>
                                </a>
                            <?php } ?>
                            <br>
                            <a class="right fs12" href="/photo?type=3&oid=<?= $group['id'] ?>"><?= t('Все фото') ?>
                                &rArr</a><br>
                        </div>
                    <?php } else { ?>
                        <div class="m5 acenter fs12">
                            <?= t('Фотографий еще нет') ?>
                            <?php if (groups_peer::instance()->is_moderator($group['id'], session::get_user_id()) || ($is_member && !$group['private'])) { ?>
                                <br/>
                                <a href="/photo/add?type=3&oid=<?= $group['id'] ?>"><?= t('Добавить фото') ?></a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <?php /*
			<div id="pane_links" class="content_pane hidden">
                            <div class="box_content p5 mb10 fs11"><a href="/groups/link?id=<?=$group['id']?>"><?=t('Ссылки сообщества')?> &rarr;</a></div>
				<? if ( $links ) { ?>
					<div class="m5 fs12">
						<? foreach ( $links as $link_id ) {
                                                        $link=groups_links_peer::instance()->get_item($link_id);?>
                                                        <div class="left mr15 bold"><a href="<?=$link['url']?>"><?=$link['title']?></a><!--br/><span class="fs10"><?=$link['title']?></span--></div>
                                                        <div class="left mr15 fs11" style="width:200px;"><?=user_helper::full_name($link['user_id'], true)?></div>
                                                        <div class="clear mb5"></div>
						<? } ?>
					</div>
				<? } else { ?>
					<div class="m5 acenter fs12">
						<?=t('')?>
						<? if ( groups_peer::instance()->is_moderator($group['id'], session::get_user_id()) ) { ?>
							<br />
							<a href="/groups/link?id=<?=$group['id']?>&add=1"><?=t('Добавить')?></a>
						<? } ?>
					</div>
				<? } ?>
                            <div class="box_content p5 mb10 fs11"><a href="/groups/link?id=<?=$group['id']?>"><?=t('Все ссылки')?> &rarr;</a></div>
			</div>
                        */ ?>
                <div id="pane_files" class="content_pane hidden">
                    <div class="box_content p5 fs11">
                        <?php if (groups_peer::instance()->is_moderator($group['id'], session::get_user_id()) || ($is_member && !$group['private'])) { ?>
                            <a href="/groups/file?id=<?= $group['id'] ?>&add=1"><?= t('Добавить материал') ?></a>
                        <?php } ?>
                        <div class="right"><a id="lstfiles" href="javascript:;"><?= t('Последние') ?></a></div>
                    </div>
                    <?php include 'partials/files.php' ?>
                    <div class="box_content p5 mb10 fs11"><a
                                href="/groups/file?id=<?= $group['id'] ?>"><?= t('Все материалы') ?> &rarr;</a></div>
                </div>

                <div id="pane_talk" class="content_pane hide">
                    <?php if (groups_peer::instance()->is_moderator($group['id'], session::get_user_id()) || ($is_member && !$group['private'])) { ?>
                        <div class="box_content p5 mb10 fs11">
                            <a href="/groups/talk?id=<?= $group['id'] ?>&add=1"><?= t('Добавить мысль') ?> &rarr;</a>
                        </div>
                    <?php } ?>
                    <?php if (!$talks) { ?>
                        <div class="m5 acenter fs12"><?= t('Мыслей еще нет') ?></div>
                    <?php } else { ?>
                        <?php foreach ($talks as $id) { ?>
                            <?php $topic = groups_topics_peer::instance()->get_item($id) ?>
                            <div class="mb10 box_content p10 mr10" id="comment<?= $id ?>">
                                <div class="mb5 bold fs12">
                                    <a href="/groups/talk_topic?id=<?= $id ?>"><?= stripslashes(htmlspecialchars($topic['topic'])) ?></a>
                                </div>
                                <div class="fs11 pb5">
                                    <div class="left quiet">
                                        <?= t('Всего сообщений') ?>:
                                        <b><?= $topic['messages_count'] ?></b>,
                                        <?= t('Последнее') ?>:
                                        <a href="/groups/talk_topic?id=<?= $id ?>&last=1"><?= date_helper::human($topic['updated_ts'], ', ') ?>
                                            &rarr;</a>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <div class="box_content p5 mb10 fs11"><a
                                href="/groups/talk?id=<?= $group['id'] ?>"><?= t('Все мысли') ?> &rarr;</a></div>
                </div>

                <!-- BLOGPOSTS -->

                <div id="pane_posts" class="content_pane">

                    <?php if (groups_peer::instance()->is_moderator($group['id'], session::get_user_id()) || ($is_member && !$group['private'])) { ?>
                        <div class="box_content p5 mb10 fs11">
                            <a href="/groups/post_edit?group_id=<?= $group['id'] ?>&add=1"><?= t('Добавить мысль') ?>
                                &rarr;</a>
                        </div>
                    <?php } ?>

                    <?php if (!$posts) { ?>
                        <div class="m5 acenter fs12"><?= t('Мыслей еще нет') ?></div>
                    <?php } else { ?>
                        <?php foreach ($posts as $id) { ?>
                            <?php $post = blogs_posts_peer::instance()->get_item($id) ?>
                            <div class="mb10 box_content p10 mr10" id="comment<?= $id ?>">
                                <div class="mb5 bold fs12">
                                    <a href="/groups/post?group_id=<?= $group['id'] ?>&id=<?= $id ?>"><?= stripslashes(htmlspecialchars($post['title'])) ?></a>
                                </div>
                                <div class="fs11 pb5">
                                    <div class="left quiet">
                                        <?= user_helper::full_name($post['user_id'], true, ['class' => 'mr10'], false) ?>
                                        <?= t('Комментариев') ?>:
                                        <b class="mr5"><?= blogs_comments_peer::instance()->get_count_by_post($id) ?></b>
                                        <?= t('Просмотров') ?>: <b><?= $post['views'] ?></b>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <div class="box_content p5 mb10 fs11"><a
                                href="/groups/posts?group_id=<?= $group['id'] ?>"><?= t('Все мысли') ?> &rarr;</a></div>
                </div>

            <?php } ?>

        <?php } else if (session::is_authenticated()) { ?>
            <div class="screen_message acenter bold">
                <?= t('Это закрытое сообщество, для доступа к содержимому Вам необходимо вступить в нее') ?>
                <?php if (!groups_applicants_peer::instance()->is_applicant($group['id'], session::get_user_id())) { ?>
                    <input id="menu_apply" type="button" class="button" value="<?= t('Вступить') ?>"
                           rel="<?= $group['id'] ?>"/> <?php // <!--onclick="groupsController.apply(<?=$group['id'] ?>
                    <div id="text_apply" class="hidden quiet normal"><?= t('Заявка на вступление отправлена') ?></div>
                <?php } else { ?>
                    <div class="quiet normal"><?= t('Заявка на вступление отправлена') ?></div>
                <?php } ?>
            </div>
        <?php } else if (!session::is_authenticated()) { ?>
            <div class="screen_message acenter bold">
                <?= t('Это закрытое сообщество. Для просмотра Вам необходимо <a href="/sign/up">зарегистрироваться</a> в сети и отправить заявку на вступление в это сообщество') ?>
                .
            </div>
        <?php } ?>

        <?= \App\Component\Member\Widget::create()
                ->setTitle(sprintf('%s - %d', t('Участники'), count($users)))
                ->addAction(sprintf('/groups/members?id=%d', $group['id']), sprintf('%s &rarr;', t('Все')))
                ->setMembers(array_map(function ($id) {
                    $data = user_auth_peer::instance()->get_item($id);

                    return \App\Component\Member\Card::create()
                            ->setId($id)
                            ->setFirstName($data['first_name'])
                            ->setLastName($data['last_name']);
                }, $users))
                ->render() ?>

    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        $('#menu_apply').click(function () {
            var groupId = $(this).attr('rel');
            $('#menu_leave').remove();
            $.post(
                    '/groups/join',
                    {id: groupId},
                    function () {
                        $('#menu_join').hide();
                        $('#menu_leave').fadeIn(150);
                    },
                    'json',
            );
            $('#menu_apply').hide();
            $('#text_apply').fadeIn(150);
        });
    });
</script>