<? if (1 != 1) { ?>
    <div>
        <div>
            <div class="ml10 mt10">
                <script zz-url="/team"
                        src="https://<?= $_SERVER['SERVER_NAME'] ?>:8887/js/lib/zz-iframe.min.js">
                </script>
            </div>
        </div>
    </div>
<? } ?>

<? $sub_menu = '/team'; ?>
<? include 'partials/sub_menu.php';
load::view_helper('group');
load::model('groups/groups'); ?>

<div class="left" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10" style="width: 62%;">

    <h1 class="column_head" style="font-size: 11px">
        <span class="right">
            <a href="/team" class="<?= request::get_int('category') == 'all' ? 'white' : '' ?>">
                <?= t('Все') ?> (<?= (int)db::get_scalar("SELECT count(*) FROM team WHERE active=1") ?>)
            </a>
        </span>
        <?= t('Список') ?>
    </h1>

    <? if (request::get_string('req')) { ?>
        <div class="mt5 fs11 mb10"><a href="/team" class="cgray">&larr; <?= t('Вернуться к общему списку') ?></a></div>
    <? } ?>

    <? if (request::get_int("list", 0) > 0) { ?>
        <? $groups = db::get_rows("SELECT team_members.group_id FROM team_members LEFT JOIN lists2users ON team_members.user_id = lists2users.user_id WHERE lists2users.list_id = :list_id GROUP BY team_members.group_id;", ["list_id" => request::get_int("list")]);
        if (count($groups) > 0) {
            foreach ($groups as $id) {
                include 'partials/lists.php';
            }
        } else { ?>
            <div class="mb10 p10 box_content">
                <div class="acenter">
                    Пока нет
                </div>
                <div class="clear"></div>
            </div>
            <?
        }
    } else { ?>
        <? if ($hot) {
            foreach ($hot as $id) {
                include 'partials/group.php';
            }
        } else {
            ?>
            <div class="mb10 p10 box_content">
                <div class="acenter">
                    <?= t('Пока нет') ?>
                </div>
                <div class="clear"></div>
            </div>
        <? }
    }
    ?>
    <div class="bottom_line_d mb10"></div>
    <div class="right pager"><?= pager_helper::get_full($pager) ?></div>
</div>
<style type="text/css">
    .column_head a.white {
        color: #fff !important;
    }
</style>