<?php if (request::get_int('pending')) { ?>
    <h1 class="column_head mt10 mr10">
        <?php if (session::get_user_id() === $user_id) { ?>
            <?= t('Мои друзья') ?> &rarr; <?= t('Запросы на дружбу') ?>
        <?php } else { ?>
            <?= strip_tags(user_helper::full_name($id), "<a>") ?> &rarr; <?= t('Друзья') ?>
        <?php } ?>
    </h1>
    <?php foreach ($pending_friends as $id) {
        include 'partials/friend_pending.php';
    } ?>
<?php } elseif (request::get('action') === 'news') { ?>
    <h1 class="column_head mt10 mr10"><a href="/friends/"><?= t('Мои друзья') ?></a> &rarr; <?= t('Новости') ?></h1>
    <?php if (intval($friends_news[0]) > 0) {
        foreach ($friends_news as $item_id) {
            include 'partials/friend_news.php';
        } ?>
        <div class="right pager mr10"><?= pager_helper::get_full($pager, request::get_int('page')) ?></div>
    <?php } else { ?>
        <br/>&nbsp;<?= t('Новостей еще нет') ?>
    <?php }
} else { ?>
    <?php if (session::get_user_id() == $user_id) { ?>
        <table class="column_head mt10 mr10">
            <tr>
                <td class="p0 column_head" style="background: none;"><a href="/friends"><?= t('Мои друзья') ?></a>
                    &nbsp;
                    <?= db::get_scalar(
                        'SELECT count(id) FROM friends WHERE user_id=:user_id',
                        array('user_id' => session::get_user_id())
                    ) ?>
                </td>
                <td class="p0 column_head" style="background: none;">
                    <a href="/friends/news"><?= t('Новости') ?></a>
                </td>
                <td class="p0 column_head aright" style="background: none;">
                    <a href="/friends?online=1" style="text-transform:none;">Онлайн</a>
                    <?= db::get_scalar(
                        'SELECT count(user_id) FROM user_sessions WHERE visit_ts>=:visit_ts AND user_id IN (SELECT friend_id FROM friends WHERE user_id=:user_id )',
                        array('visit_ts' => time() - 600, 'user_id' => session::get_user_id())
                    ) ?>
                    <?php if (count($pending_friends) > 0) { ?>
                        &nbsp;
                        <a href="/friends?pending=1" style="text-transform:none;">
                            <?= t('Запросы на дружбу') ?>
                        </a>
                        <?= count($pending_friends) ?>
                    <?php } ?>
                </td>
            </tr>
        </table>
    <?php } else { ?>
        <h1 class="column_head mt10 mr10">
            <?= strip_tags(user_helper::full_name($user_id), "<a>") ?> &rarr; <a
                    href="/friends?user=<?= $user_id ?>"><?= t('Друзья') ?></a>
            &nbsp;
            <?= db::get_scalar(
                'SELECT count(id) FROM friends WHERE user_id=:user_id',
                array('user_id' => $user_id)
            ) ?>
            <div class="right">
                <a href="/friends?user=<?= $user_id ?>&online=1" style="text-transform:none;">Онлайн</a>
                <?= db::get_scalar(
                    'SELECT count(user_id) FROM user_sessions WHERE visit_ts>=:visit_ts AND user_id IN (SELECT friend_id FROM friends WHERE user_id=:user_id )',
                    array('visit_ts' => time() - 600, 'user_id' => $user_id)
                ) ?>
            </div>
        </h1>
    <?php } ?>
    <div class="m-0" style="display: grid; grid-template-columns: repeat(3, 1fr)">
        <?php foreach ($friends as $id) { ?>
            <?php include 'partials/friend.php' ?>
        <?php } ?>
    </div>

    <div class="pager mr10"><?= pager_helper::get_full($pager, request::get_int('page')) ?></div>
<?php } ?>