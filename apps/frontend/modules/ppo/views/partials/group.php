<?php $group = ppo_peer::instance()->get_item($id);
$ppoMembers    = ppo_peer::instance()->getPpoMembersRecursive($group);
;
#$members_cnt = count(ppo_members_peer::instance()->get_members($group['id'],false,$group));
$members_cnt = $cntarr[$id];
if ($group['category'] == 2) {
    $sqladd = "OR group_id IN(SELECT id FROM ppo WHERE city_id=".(int) $group['city_id']." AND category=1)";
} else {
    if ($group['category'] == 3) {
        $sqladd = "OR group_id IN(SELECT id FROM ppo WHERE region_id=".(int) $group['region_id']." AND category=2)";
    }
}
$post_cnt = db::get_scalar(
        'SELECT count(id) FROM blogs_posts WHERE (ppo_id = :ppo_id '
        //.str_replace("group_id","ppo_id",$sqladd)
        .')',
        ['ppo_id' => $group['id']]
);
$com_cnt  = db::get_scalar(
        'SELECT count(id) FROM blogs_comments WHERE post_id in (SELECT id FROM blogs_posts WHERE (ppo_id = :ppo_id '
//.str_replace("group_id","ppo_id",$sqladd)
        .'))',
        ['ppo_id' => $group['id']]
);
?>

<div class="mb10 p10 box_content">
    <div class="right fs11">
        <?php if (session::has_credential('admin') || ppo_members_peer::instance()->allow_edit(
                        session::get_user_id(),
                        $group
                )) { ?>
            <a class="bold right" href="/ppo/edit?id=<?= $group['id'] ?>"><?= t('Редактировать') ?></a>
        <?php } ?>
        <?php if (session::has_credential('admin')) { ?>
            <br/><span
                    class="fs11">   <?php if (!$group['active'] && (request::get_int(
                                        'status'
                                ) == 1 || session::has_credential('admin'))) { ?>
                    <a class="ml10 fs11" href="/ppo/approve_ppo?ppo_id=<?= $group['id'] ?>">Схвалити</a>
                    <a class="ml10 fs11" href="/ppo/delete_ppo?ppo_id=<?= $group['id'] ?>"
                       onclick="return confirm('Видалити цю партiйну органiзацiю?');">Відмовити</a>
                <?php } ?>
                                <a onclick="return confirm('Видалити цю партiйну органiзацiю?');"
                                   href="/ppo/delete_ppo?ppo_id=<?= $group['id'] ?>"
                                   class="ml10 fs11"><?= t('Удалить') ?></a><br/></span>
        <?php } ?>
    </div>
    <div class="left">
        <?php if ($group['photo_salt']) { ?>
            <?= user_helper::ppo_photo(user_helper::ppo_photo_path($group['id'], 't', $group['photo_salt'])) ?>
        <?php } else { ?>
            <?= group_helper::photo(0, 't', false) ?>
        <?php } ?>
    </div>
    <div style="margin-left: 85px;" class="ml10">
        <?php if (request::get('bookmark')) { ?>
            <?php $bkm = bookmarks_peer::instance()->is_bookmarked(session::get_user_id(), 8, $group['id']); ?>
            <a class="bookmark mb10 ml5 right" style="<?= ($bkm) ? 'display:none;' : 'display:block;' ?>"
               href="#add_bookmark"
               onclick="Application.bookmarkThisItem(this,'8','<?= $group['id'] ?>');return false;"><b></b><span><?= t(
                            'В закладки'
                    ) ?></span></a>
            <a class="unbkmrk mb10 ml5 right" style="<?= ($bkm) ? 'display:block;' : 'display:none;' ?>"
               href="#del_bookmark"
               onclick="Application.unbookmarkThisItem(this,'8','<?= $group['id'] ?>');return false;"><b></b><span><?= t(
                            'Удалить из закладок'
                    ) ?></span></a>
        <?php } ?>
        <a href="/ppo<?= $group['id'] ?>/<?= $group['number'] ?>"><?= stripslashes(
                    htmlspecialchars($group['title'])
            ) ?></a>
        &nbsp;&nbsp;&nbsp;&nbsp;

        <?php $head = db::get_rows(
                'select user_id, "function" from ppo_members where group_id = :ppoId and "function" in (1, 5) order by "function"',
                ['ppoId' => $group['id']]
        ) ?>
        <div class="mt5 quiet fs11">
            <?php if (count($head) > 0) { ?>
                <?= t(ppo_members_peer::FUNCTIONS[$head[0]['function']]) ?>: <?= user_helper::full_name(
                        $head[0]['user_id'],
                        true,
                        [],
                        false
                ) ?>
            <?php } else { ?>
                <?= t('Голова') ?>: <?= t('Отсутсвует') ?>
            <?php } ?>
        </div>

        <div class="mt5 quiet fs11"><?= t('Участников') ?>
            <b><a href="/ppo/members?id=<?= $group['id'] ?>"><?= count($ppoMembers) ?></a></b>
            &nbsp;
            <?= t('Обсуждений') ?> <b><a href="/ppo/posts?group_id=<?= $group['id'] ?>"><?= $post_cnt ?></a></b> &nbsp;
            <?= t('Комментариев') ?> <b><a href="/ppo/posts?group_id=<?= $group['id'] ?>"><?= $com_cnt ?></a></b>
            <?php
            if ($group['privacy'] === ppo_peer::PRIVACY_PRIVATE) {
                load::model('ppo/applicants');
                $applicants = ppo_applicants_peer::instance()->get_by_group($group['id']);
                if (session::has_credential('admin') || ppo_peer::instance()->is_moderator(
                                $group['id'],
                                session::get_user_id()
                        )) {
                    ?><?= t('Заявок') ?>    <b><a href="/ppo/edit?id=<?= $group['id'] ?>"><span id="new_applicants"
                                                                                                class="green fs10"><?= $applicants ? '+'.count(
                                            $applicants
                                    ) : '' ?></span></a>
                    </b><?php }
            } ?>
        </div>
    </div>
    <div class="clear"></div>
</div>