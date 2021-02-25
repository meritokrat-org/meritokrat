<div class="mt10">

    <div class="tab_pane" style="font-size: 13px;">
        <a rel="members" href="javascript:;" class="<?= $group['category'] == 1
            ? 'selected'
            : 'hide' ?>"><?= t('Участники') ?></a>
        <a rel="events" href="javascript:;"<?= $group['category'] != 1
            ? ' class="selected"'
            : '' ?>><?= t('События') ?></a>
        <a rel="posts" href="javascript:;"><?= t('Обсуждения') ?></a>
        <a rel="files" href="javascript:;"><?= t('Библиотека') ?></a>
        <a rel="foto" href="javascript:;"><?= t('Фото') ?></a>
        <a rel="report" href="javascript:;"><?= t('Отчеты') ?></a>
        <?php if ($group['category'] == 3) { ?>
            <a rel="finance" href="javascript:;"><?= t('Финансы') ?></a>
        <?php } ?>
        <div class="clear"></div>
    </div>

    <div square="members" id="pane_members" class="content_pane hidden">
        <?php if ($group['category'] === 1) { ?>
            <div class="fs11" style="background-color: #fafafa; padding: 7px 12px">
                <a href="/team/members?id=<?= $group['id'] ?>"><?= t('всі учасники') ?> &rarr;</a>
            </div>
            <div class="pcontent_pane team_content">
                <?php foreach ($councilMembers as $uid) { ?>
                    <div class="pane_item team_item" style="width: 33%; float: left; padding: 5px">
                        <div>
                            <div class="left" style="width: 40px">
                                <?= user_helper::photo($uid, 's', array('width' => '100%'), true, 'user', '', false, false) ?>
                            </div>
                            <div class="left ml10 fs11" style="width: 101px">
                                <?= user_helper::full_name($uid) ?>
                                <?php $udata = user_auth_peer::instance()->get_item($uid) ?>
                                <?php if ($udata['status'] > 0) { ?>
                                    <div class="cgray"><?= user_auth_peer::get_status($udata['status']) ?></div>
                                <?php } ?>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                <?php } ?>
                <div class="clear"></div>
            </div>
        <?php } ?>
    </div>

    <div id="pane_posts" class="content_pane hidden">
        <?php if (session::is_authenticated()) { ?>
            <div class="box_content p5 mb10 fs11"><a
                    href="/team/post_edit?team_id=<?= $group['id'] ?>&add=1"><?= t('Добавить тему') ?> &rarr;</a>
            </div>
        <?php } ?>
        <?php if (!$posts) { ?>
            <div class="m5 acenter fs12"><?= t('Мыслей еще нет') ?></div>
        <?php } else { ?>
            <?php foreach ($posts as $id) { ?>
                <?php $post = blogs_posts_peer::instance()->get_item($id) ?>
                <div class="mb10 box_content p10 mr10" id="comment<?= $id ?>">
                    <div class="mb5 bold fs12">
                        <a href="/team/post?team_id=<?= $group['id'] ?>&id=<?= $id ?>"><?= stripslashes(htmlspecialchars($post['title'])) ?></a>
                    </div>
                    <div class="fs11 pb5">
                        <div class="left quiet">
                            <?= user_helper::full_name($post['user_id'], true, array('class' => 'mr10'), false) ?>
                            <?= t('Комментариев') ?>:
                            <b class="mr5"><?= blogs_comments_peer::instance()->get_count_by_post($id) ?></b>
                            <?= t('Просмотров') ?>: <b><?= $post['views'] ?></b>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
        <div class="box_content p5 mb10 fs11">
            <a href="/team/posts?team_id=<?= $group['id'] ?>"><?= t('Все мысли') ?> &rarr;</a>
        </div>
    </div>

    <div id="pane_events" class="content_pane hidden">
        <?php if (session::has_credential('admin') OR
            team_peer::instance()->is_moderator($group['id'], session::get_user_id()) OR
            user_desktop_peer::instance()->is_regional_coordinator(session::get_user_id()) OR
            user_desktop_peer::instance()->is_raion_coordinator(session::get_user_id()) OR
            team_members_peer::instance()->is_member($group["id"], session::get_user_id())
        ) { ?>
            <div class="box_content p5 mb10 fs11"><a
                    href="/events/create?type=4&content_id=<?= $group['id'] ?>"><?= t('Добавить событие') ?> &rarr;</a>
            </div>
        <?php } ?>
        <?php if ($events) { ?>
            <div class="mb10 box_content p10 mr10">
                <?php foreach ($events as $event_id) { ?>
                    <?php $event = events_peer::instance()->get_item($event_id); ?>
                    <div class="mb5 bold fs12">
                        <a class="acenter mb10 ml10"
                           href="/event<?= $event_id ?>"><?= $event['name'] ?></a><br/>
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
                                    <a href="/profile-31" style="color:black"><?= t("Секретариат МПУ") ?></a>
                                    <?php
                                    break;
                                default:
                                    ?>
                                    <?= user_helper::full_name($event['user_id'], true, array('style' => 'color:black'), false); ?>
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

    <div id="pane_files" class="content_pane hidden">
        <div
            class="box_content p5 mb10 fs11"><?php if (groups_members_peer::instance()->is_member($group['id'], session::get_user_id()) || session::has_credential('admin')) { ?>
                <a href="/team/file?id=<?= $group['id'] ?>&add=1"><?= t('Добавить материал') ?> &rarr;</a>
            <?php } ?>
        </div>
        <?php if ($files) { ?>
            <div class="mt5 m5 fs12 mb5 left">
                <?php foreach ($files as $file_id) {
                    $file = team_files_peer::instance()->get_item($file_id);
                    if (isset($file['files']))
                        $arr = unserialize($file['files']);
                    ?>
                    <div class="box_content mt5 mb10" style="border-bottom: 1px solid #f7f7f7;width:520px;">
                        <div class="left">
                            <div class="ml5"><a
                                    href="<?= (isset($file['files'])) ? context::get('file_server') . $file['id'] . '/' . $arr[0]['salt'] . "/" . $arr[0]['name'] : $file['url'] ?>"><?= stripslashes(htmlspecialchars($file['title'])) ?></a>
                            </div>
                            <div class="left ml5 fs12"><?= $file['author'] ?></div>
                        </div>
                        <?php if (isset($file['files'])) {
                            foreach ($arr as $f) {
                                $ext = end(explode('.', $f['name']));
                                ?>
                                <div class="left ml5 <?//=$file['author'] ? 'mt15' : ''?>">
                                    <a href="<?= context::get('file_server') . $file['id'] . '/' . $f['salt'] . "/" . $f['name'] ?>">
                                        <img
                                            src="/static/images/files/<?= team_files_peer::instance()->get_icon($ext) ?>">
                                    </a></div>
                            <?php }
                        } else { ?>
                            <div class="left ml5 <?php //=$file['author'] ? 'mt15' : ''?>"><img
                                    src="/static/images/files/IE.png"></div> <?php } ?>
                        <?php if ($file['lang'] == 'ua' or $file['lang'] == 'en') { ?>
                            <div class="left ml5"
                                 style="margin-top:  1<?php //=$file['author'] ? '17' : '2'?>px;"><?= tag_helper::image('icons/' .$file['lang'] . '.png', array('')) ?></div><?php } ?>
                        <div class="right aright mr5"
                             style="border-bottom: 1px solid #d7d7d7; color:#565656;"> <?= $file['size'] ? $file['size'] : '' //$file['exts'] ? team_files_peer::formatBytes(filesize($file['url'])) : ''                ?>
                            <?= tag_helper::image('icons/1.png', array('alt' => "Інформація", 'id' => $file['id'], 'class' => "info ml5 ")) ?>
                            <?php if (team_peer::instance()->is_moderator($group['id'], session::get_user_id()) || $file['user_id'] == session::get_user_id()) { ?>
                                <a href="/team/file_edit?id=<?= $file['id'] ?>"><img class="ml5"
                                                                                     alt="Редагування"
                                                                                     src="/static/images/icons/2.png"></a>
                                <a onclick="return confirm('Ви впевнені?');"
                                   href="/team/file_delete?id=<?= $file['id'] ?>"><img class="ml5"
                                                                                       alt="видалення"
                                                                                       src="/static/images/icons/3.png"></a>
                            <?php } ?>
                        </div>
                        <div class="clear"></div>
                        <div id="file_describe_<?= $id ?>"
                             class="ml10 fs11 hidden"><?= stripslashes(htmlspecialchars($file['describe'])) ?></div>
                    </div>
                <?php } ?>
            </div>
            <div class="clear mb5"></div>
        <?php } else { ?>
            <div class="m5 acenter fs12">
                <?= t('') ?>
                <?php if (team_peer::instance()->is_moderator($group['id'], session::get_user_id()) || session::has_credential('admin')) { ?>
                    <a href="/team/file?id=<?= $group['id'] ?>&add=1"><?= t('Добавить материал') ?> &rarr;</a>
                    <br/>
                <?php } ?>
            </div>
        <?php } ?>
        <div class="box_content p5 mb10 fs11"><a
                href="/team/file?id=<?= $group['id'] ?>"><?= t('Все материалы') ?> &rarr;</a></div>
    </div>

    <div id="pane_foto" class="content_pane hidden">
        <?php if (team_peer::instance()->is_moderator($group['id'], session::get_user_id()) || session::has_credential('admin')) { ?>
            <div class="box_content p5 mb10 fs11">
                <a href="/photo/add?type=4&oid=<?= $group['id'] ?>"><?= t('Добавить фото') ?> &rarr;</a>
            </div>
        <?php } ?>
        <?php if ($photos) { ?>
            <div class="mt10 p10 mb15 fs12 gallery" style="text-align:center">
                <?php foreach ($photos as $photo_id) { ?>
                    <a href="/photo?type=4&oid=<?= $group['id'] ?>" class="ml10" rel="prettyPhoto[gallery]">
                        <?= photo_helper::photo($photo_id, 'h', array()) ?>
                    </a>
                <?php } ?>
                <br/>
                <a class="right fs12"
                   href="/photo?type=4&oid=<?= $group['id'] ?>"><?= t('Все фото') ?> &rarr;</a><br>
            </div>
        <?php } else { ?>
            <div class="m5 acenter fs12">
                <?= t('Фотографий еще нет') ?>
            </div>
        <?php } ?>
    </div>

    <div id="pane_report" class="content_pane hidden">
        <div class="box_content p5 mb10 fs11">
            <a href="/eventreport/show&po_id=<?= $group['id'] ?>"><?= t('Все отчеты') ?> &rarr;</a>
        </div>
        <?php if ($reports) { ?>
            <div class="mb10 box_content p10 mr10">
                <?php foreach ($reports as $report_id) { ?>
                    <?php $report = eventreport_peer::instance()->get_item($report_id); ?>
                    <div class="mb5 bold fs12">
                        <a class="acenter mb10 ml10"
                           href="/eventreport/view&id=<?= $report_id ?>"><?= $report['name'] ?></a><br/>
                    </div>
                    <div class="quiet">
                        <div class="fs11 pb5 ml10">
                            <?php if (date('d-m-Y', $report['start']) == date('d-m-Y', $report['end']))
                                $kolu = date_helper::get_format_date($report['start'], false) . ', ' . t('с') . ' ' . date('H:i', $report['start']) . ' до ' . date('H:i', $report['end']);
                            else $kolu = t('с') . ' ' . date_helper::get_format_date($report['start']) . ' ' . date('H:i', $report['start']) . ' <br/>до ' .
                                date_helper::get_format_date($report['end']) . ' ' . date('H:i', $report['end']); ?><?= $kolu ?>
                            <br/>
                            <?= t('Организатор') . ": " ?>
                            <?= user_helper::full_name($report['user_id'], true, array('style' => 'color:black'), false); ?>
                            <?php if (session::has_credential('admin') || $is_leader) { ?>
                                <?php $statuses = array(
                                    array(t('Новый'), 'green'),
                                    array(t('На утверждении'), 'blue'),
                                    array(t('На доработке'), 'red'),
                                    array(t('Утвержден'), 'black'),
                                    array(t('Мероприятие не состоялось'), 'red')
                                ) ?>
                                <br/>Статус: <span
                                    style="color:<?= $statuses[$report['status']][1] ?>"><?= $statuses[$report['status']][0] ?></span>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="m5 acenter fs12 p10">
                <?= t('Отчетов еще нет') ?>
            </div>
        <?php } ?>
    </div>

    <?php if ($group['category'] == 3) { ?>
        <div id="pane_finance" class="content_pane hidden">
            <?php if (session::has_credential('admin')) { ?>
                <div class="box_content p5 mb10 fs11">
                    <a href="/team/edit&id=<?= $group['id'] ?>&tab=vidatki"><?= t('Редактировать') ?> &rarr;</a>
                </div>
            <?php } ?>
            <div class="box_content p5 mb10 fs12">
                <?= t('Всего собрано вступительных взносов') ?>:
                <?= intval($finvtotal) ?> грн.
            </div>
            <div class="box_content p5 mb10 fs12">
                <?= t('Всего собрано членских взносов') ?>
                <?= intval($ftotal) ?> грн.
            </div>
            <div class="box_content p5 mb10 fs12">
                <?= t('Всего собрано целевых взносов') ?>
                <?= intval($ffondtotal) ?> грн.
            </div>
            <div class="box_content p5 mb10 fs12">
                <?= t('Всего (фонд)') ?>:
                <?= intval($ftotal) + intval($ffondtotal) ?> грн.
            </div>
            <?php if ($finances && (session::has_credential('admin') || ($user['status'] == 20 && $user_data['region_id'] == $group['region_id']))) { ?>
                <?php foreach ($finances as $finance_id) { ?>
                    <div class="mb10 box_content p10 mr10">
                        <?php $finance = team_finance_peer::instance()->get_item($finance_id); ?>
                        <div class="quiet">
                            <div class="fs11">
                                <?= date('d.m.Y', $finance['date']) ?> - <b><?= $finance['summ'] ?> грн.</b>
                                - <?= stripslashes($finance['text']) ?>
                                <?php $fsumm += $finance['summ'] ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
            <div class="box_content p5 mb10 fs12">
                <?= t('Всего затрат') ?>: <?= intval($fsumm) ?> грн.
            </div>
            <div class="box_content p5 mb10 fs12">
                <?= t('Остаток (фонд)') ?>: <?= intval($ftotal) + intval($ffondtotal) - intval($fsumm) ?> грн.
            </div>
        </div>
    <?php } ?>

</div>

<script type="text/javascript">
    $(document).ready(function () {

        (function () {

            var wrapper = $('div.tab_pane').parent(),
                rel = $('div.tab_pane > a.selected').attr('rel');

            $('> div.content_pane', wrapper).hide();
            $('> div#pane_' + rel + '.content_pane', wrapper).show();

        })();

    });
</script>