<?php if (request::get('type') === "populars") { ?>
    <?php $sub_menu = '/blogs?type=populars'; ?>
<?php } else { ?>
    <?php $sub_menu = '/blogs'; ?>
<?php } ?>

<?php if (session::is_authenticated()) { ?>
    <?php include 'partials/sub_menu.php' ?>
<?php } ?>

<?php if (session::is_authenticated()) { ?>
    <div class="left mr10" style="width: 35%;"><?php include 'partials/left.php' ?></div>
<?php } ?>

<div class="left" style="width: 62%;">
    <?php if ($tag) { ?>
        <h1 class="column_head">
            <a href="/blogs">
                <?= t(
                    array_values(
                        array_filter(
                            ['1' => 'Блоги', 'populars' => 'Популярные публикации', '' => 'Публикации'],
                            static function ($value, $key) {
                                return $key === request::get_string('type');
                            },
                            ARRAY_FILTER_USE_BOTH
                        )
                    )[0]
                ) ?>
            </a>
            &rarr; <?= stripslashes(htmlspecialchars($tag)) ?></h1>
        <a class="right icorss mt10" href="/blogs/rss?tag=<?= urlencode($tag) ?>" title="rss"></a>
    <?php } else { ?>
        <h1 class="column_head">
            <?= t(
                array_values(
                    array_filter(
                        ['1' => 'Блоги участников', 'populars' => 'Популярные публикации', '' => 'Публикации'],
                        static function ($value) {
                            return $value === request::get_string('type');
                        },
                        ARRAY_FILTER_USE_KEY
                    )
                )[0]
            ) ?>
        </h1>
        <a class="right icorss mt10" href="/blogs/rss" title="rss"></a>
        <?php
    } ?>

    <?php
    if (count($list) > 0) { ?>
        <?php
        foreach ($list as $id) { ?>
            <?php
            if (!$post_data = blogs_posts_peer::instance()->get_item($id)) {
                continue;
            } ?>
            <?php
            include 'partials/post.php'; ?>
            <?php
        } ?>
        <?php
    } else { ?>
        <div class="fs12 p10" style="text-align:center;color:grey;">
            <?= t('Не найдено ни одной публикации') ?>.
            <br/>
            <?= t('Попробуйте воспользоваться') ?>
            <a href="javascript:;" onclick="$('#fastsrch').hide();$('#advancedsrch').show();$('a.advance').hide();">
                <?= t('расширенным поиском') ?>
            </a>
        </div>
        <?php
    } ?>

    <div class="right pager"><?= pager_helper::get_short($pager) ?></div>

</div>

<?php if (!session::is_authenticated()) { ?>
    <div class="left ml10" style="width: 35%;"><?php include 'partials/left.php' ?></div>
<?php } ?>
