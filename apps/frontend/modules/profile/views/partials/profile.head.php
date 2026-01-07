<?php
/**
 * @var array $user
 * @var array $user_data
 * @var bool $access
 */

?>

<div class="row" style="min-height: 5rem">
    <div class="col-7">

        <h1 class="mb-1 fs28" style="color: black; line-height: 1;">
            <?= stripslashes(htmlspecialchars($user_data['first_name'])) ?><!--
            -->&nbsp;<?= stripslashes(htmlspecialchars($user_data['last_name'])) ?>
        </h1>

        <div>
            <?php $collection = [] ?>

            <?php if (user_auth_peer::getStatus($user['status_functionary'], user_auth_peer::STATUS_TYPE_FUNCTIONARY)) {
                $collection[] = user_auth_peer::getStatus($user['status_functionary'], user_auth_peer::STATUS_TYPE_FUNCTIONARY);
            } ?>

            <?php if (user_auth_peer::getStatus($user['status_politician'], user_auth_peer::STATUS_TYPE_POLITICIAN)) {
                $collection[] = user_auth_peer::getStatus($user['status_politician'], user_auth_peer::STATUS_TYPE_POLITICIAN);
            } ?>

            <?php if (user_auth_peer::getStatus($user['status'])) {
                $collection[] = user_auth_peer::getStatus($user['status']);
            } ?>

            <?= implode('<span style="padding: 0 1px">/</span>', $collection) ?>
        </div>

        <?php $row = db::get_row('select p.id, p.title, pm.function from ppo_members pm join ppo p on p.id = pm.group_id where pm.user_id = :userId limit 1;', ['userId' => $user['id']]) ?>
        <?php if ($row) { ?>
            <div>
                <a href="<?= sprintf('/ppo%s/1', $row['id']) ?>"><?= $row['title'] ?></a><?= $row['function'] > 0 ? ', ' . t(ppo_members_peer::FUNCTIONS[$row['function']]) : '' ?>
            </div>
        <?php } ?>

    </div>
    <div class="col-5 text-end">

        <div class="fs11 cgray">
            <?= user_sessions_peer::instance()->last_visit($user_data['user_id']) ?>
        </div>

        <?php if ($user['active']) { ?>
            <div class="fs11 cgray text-nowrap"><?= t('В сети c') ?> <?= date_helper::get_format_date($user['activated_ts']) ?> <?= t(
                        'г.'
                ) ?></div>
        <?php } ?>

        <div class="cgray fs11">
            <?php $date_point = date('Y-m-d', mktime(0, 0, 0, date("m") - 3, date("d"), date("Y"))) . " 00:00:00"; ?>
            <?php $visits_log = db::get_rows(
                    "SELECT * FROM user_visits_log WHERE user_id = :user_id AND time > :time ORDER BY time DESC",
                    ["user_id" => $user['id'], "time" => $date_point]
            ) ?>
            <?php $value = 0; ?>
            <?php foreach ($visits_log as $visit_log) { ?>
                <?php $value += (strtotime($visit_log["time_out"]) - strtotime($visit_log["time"])); ?>
            <?php } ?>
            <?php $tokens = explode(".", number_format($value / 3600, 2)); ?>
            <?php $minuts = (int) ($tokens[1] * 60 / 100); ?>
            <a href="/results/person?id=<?= $user['id'] ?>" target="_blank">* За ост. 3 міс: <?= $tokens[0] ?>
                г. <?= $minuts ?> хв.</a>
        </div>

        <div class="fs11 m-0 d-none" style="color: gray">
            <?php if (
                    !$user['offline']
                    && (session::has_credential('admin') || (((count($is_regional_coordinator) > 0 && in_array(
                                                    $user_data['region_id'],
                                                    $is_regional_coordinator,
                                                    true
                                            )) || (count($is_raion_coordinator) > 0 && in_array(
                                                    $user_data['city_id'],
                                                    $is_raion_coordinator,
                                                    true
                                            )))))
            ) { ?>
                <span class="cgray fs11 mr5">*
            <?= sprintf(
                    '%d год., %d хв.',
                    (int) (db_key::i()->get('all_time_' . $user['id']) / 3600),
                    (int) ((db_key::i()->get('all_time_' . $user['id']) % 3600) / 60)
            ) ?>
            , <br/>ост:
            <?php $last_visit = user_sessions_peer::instance()->get_item($user['id']); ?>
                    <?= (int) (($last_visit['visit_ts'] - $last_visit['start']) / 60) . " хв." ?>
            </span><br>
            <?php } ?>
        </div>

        <div class="btn-group" role="group">
            <?php if (session::get_user_id() !== $user['id'] && (session::has_credential(
                                    'superadmin'
                            ) || session::has_credential('programmer'))) { ?>
                <a href="/profile/login?id=<?= $user_data['user_id'] ?>" class="btn btn-sm btn-link text-secondary">
                    <span class="material-icons" style="font-size: 1rem">login</span>
                </a>
            <?php } else if (session::get_user_id() === $user['id'] && session::get('admin_id') && session::get(
                            'admin_id'
                    ) !== session::get_user_id()) { ?>
                <a class="btn btn-sm btn-link text-secondary" href="/profile/logout">
                    <span class="material-icons" style="font-size: 1rem">reply</span>
                </a>
            <?php } ?>
            <?php if ($access || session::get_user_id() === $user['id']) { ?>
                <a href="/profile/edit<?= ($access) ? '?id=' . $user_data['user_id'] : '' ?>"
                   class="btn btn-sm btn-link text-secondary">
                    <span class="material-icons" style="font-size: 1rem">edit</span>
                </a>
            <?php } ?>
            <?php if (session::has_credential('admin')) { ?>
                <a href="/admin/users?key=<?= $user_data['user_id'] ?>" class="btn btn-sm btn-link text-secondary">
                    <span class="material-icons" style="font-size: 1rem">more</span>
                </a>
                <?php if (!$user['active']) { ?>
                    <a id="resend<?= $user_data['user_id'] ?>" href="javascript:"
                       onclick="profileController.reSend(<?= $user_data['user_id'] ?>)"
                       class="btn btn-sm btn-link text-secondary">
                        <span class="material-icons" style="font-size: 1rem">forward_to_inbox</span>
                    </a>
                    <span class="fs11" id="status<?= $user_data['user_id'] ?>"></span>
                <?php } ?>
            <?php } ?>
            <?php if (session::has_credential('superadmin') || session::has_credential('programmer')) { ?>
                <a href="javascript:void(0);" class="btn btn-sm btn-link text-danger"
                   onclick="profileController.deleteProfile(<?= $user_data['user_id'] ?>, '1', true)">
                    <span class="material-icons" style="font-size: 1rem">delete_forever</span>
                </a>
            <?php } ?>
        </div>

    </div>
</div>

<div class="d-none">
    <div style="font-size: 12px;" class="mr5">
        <?= ($user['offline'] || $user['del']) ? t('Оффлайн') . ' ' : '' ?>
        <?php if ($user['del']) { ?>
            <?= ($user['del'] === $user['id']) ? '(' . t('Самоудаление') . ')' : '(' . t('Удален') . ')' ?> <a
                    href="/profile/resume?id=<?= $user['id'] ?>">відновити</a><br/>
        <?php } ?>
        <?php /* старые статусы
        <?=$user['id']==5 ? t('Глава (Лидер) Партии') : user_auth_peer::get_type($user['type']).user_auth_peer::get_hidden_type($user['hidden_type'],$user['type']); ?></div>
        <? if ( session::get_user_id() == $user['id'] ) { ?>
                <? if (in_array($user['type'],array(4,5,7,0))) { ?>
                        <div class="left">
                            <a class="cgray fs11 ml5" href="/profile/edit" style=""><?=t('изменить статус')?></a>
                        </div>
                        <div class="left ml5"><?//=tag_helper::image('/icons/atention.gif', array('alt'=>"Atention",'style'=>'margin-top:1px;'))?></div>
                <? } ?>
        <? } ?>
        */ ?>
        <?php
        //        $private_status = $user['status'];
        //        if ($user['ban']) {
        //            $private_status = $user['ban'];
        //        }
        ?>
        <div>
            <?php $userStatus = [t('Глава (Лидер) Партии')] ?>
            <?php if ($user['id'] !== 5) { ?>
                <?php $userStatus = [user_auth_peer::get_status($user['ban'] ?: $user['status'])] ?>
                <?php if ($user['function'] > 0 && ($userFunction = user_auth_peer::get_user_function(
                                $user['function']
                        )) !== null) { ?>
                    <?php $userStatus[] = $userFunction ?>
                <?php } ?>
            <?php } ?>
            <?= implode(', ', $userStatus) ?>
        </div>
    </div>

    <?php if ($user['expert']) {
        $expert = (strlen($user["expert"]) > 2) ? unserialize($user["expert"]) : [];
        if (count($expert) > 0) {
            $exp = [];
            foreach ($expert as $id) {
                $exp[] = user_auth_peer::get_expert_type($id);
            } ?>

            <p class="fs12" style="margin:30px 0 0 0"><?= t('Эксперт в') ?> :
                <?= implode(", ", $exp) ?>
            </p>
        <?php }
    } ?>

    <?php if ($user['ban'] && (session::get_user_id() == $user['id'] || session::has_credential('admin'))) { ?>,
        <p class="fs11 cgray"
           style="margin:0"><?= t('Права в сети ограничены') ?> <?= date(
                    "H:i d-m-Y",
                    ban_peer::instance()->get_end_time($user['id'])
            ) ?>
            <?php if (session::get_user_id() == $user['id']) { ?> <br/> <a
                    href="/messages/compose?user_id=10599"><?= t('Пожаловаться') ?></a><?php } ?>
        </p>
    <?php } ?>
</div>

<style>
    .atab {
        color: white;
        font-weight: bold
    }

    a.atab:hover, a.atab:focus, .asel {
        color: #FFCC66;
        font-weight: bold;
        text-decoration: none
    }
</style>
<div class="clear"></div>

<?php // if(in_array(session::get_user_id(), array(2, 11752))){ ?>
<?php if (
        $user['status'] == 20
        && session::get_user_id() == $user["id"]
        && !db_key::i()->exists("vipusk-id-" . session::get_user_id())
) { ?>
    <div class="mt15 fs16 bold" style="text-decoration: underline">
        <a href="/zayava/vipusk">Подати он-лайн заяву на перевипуск партквитка &rarr;</a>
    </div>
<?php } ?>
<?php // } ?>

<?php if (session::get_user_id() == request::get_int('id') || $show_program_block == 1) { ?>
    <div class="mt5 hide fs12">
        <?php if ($show_program_block == 0) { ?>
            <div class="left" style="width: 27px; height: 40px; background: url('/static/images/quote.png')"></div>
            <div class="left" style="width: 480px; margin-left: 20px;">
                <div class="mt10 mb10 cbrown bold" style="font-size: 18px;"><?= t('Программа МПУ') ?></div>
                <div style="font-style: italic"><?= $program_quote['text'] ?></div>
                <div class="aright fs11 mt10">
                    <a href="/idea38"
                       style="text-decoration: underline"><?= t('Читать программу МПУ полностью') ?> &rarr;</a>
                </div>
            </div>
            <div class="clear"></div>
        <?php } else if ($show_program_block == 1) { ?>
            <div>
                <div class="left"
                     style="width: 64px; height: 49px; background: url('/static/images/2012<?= (session::get(
                                     'language'
                             ) == "ru") ? "_ru" : "" ?>.png')"></div>
                <div class="left" style="width: 440px; margin-left: 20px;">
                    <div class="mt10 mb10 cbrown bold"
                         style="font-size: 18px;"><?= t('Твой взнос в участие МПУ в выборах') ?></div>
                    <div class="mt10">
                        <table width="100%">
                            <tr>
                                <td align="center">
                                    <div class="bold" style="font-size: 18px"><span
                                                style="font-size: 25px"><?= $count_people_will_vote ?></span> <?= t(
                                                "людей"
                                        ) ?>
                                    </div>
                                    <div class="fs11" style="color: #888; font-style: italic;">
                                        <?= t("проагитировал за МПУ") ?>
                                    </div>
                                </td>
                                <td align="center">
                                    <div class="bold" style="font-size: 18px"><span
                                                style="font-size: 25px"><?= number_format(
                                                    $payments_summa,
                                                    0,
                                                    ",",
                                                    " "
                                            ) ?></span>
                                        грн.
                                    </div>
                                    <div class="fs11" style="color: #888; font-style: italic; width: 130px">
                                        <?= t("внесено в фонд избирательной заставы") ?>
                                    </div>
                                </td>
                                <td align="center">
                                    <div class="left mr5"
                                         style="background: url('/static/images/2012_icon<?= ($task_cover_facebook != 1) ? "_g" : "" ?>.png'); width: 50px; height: 48px;"></div>
                                    <div class="left fs11" style="color: #888; font-style: italic; width: 80px">
                                        <?= t("Установил обложку в Facebook") ?>
                                    </div>
                                    <div class="clear"></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="aright fs11" style="margin-top: -15px">
                        <a href="/profile/desktop?id=<?= $show_program_user_id ?>" style="text-decoration: underline">
                            <?php if (session::get_user_id() == $show_program_user_id) { ?>
                                <?= t('Добавить информацию') ?> &rarr;
                            <?php } else { ?>
                                <?= t('Смотри больше') ?> &rarr;
                            <?php } ?>
                        </a>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        <?php } else if ($show_program_block == 2) { ?>
            <div class="left"
                 style="width: 64px; height: 49px; background: url('/static/images/2012<?= (session::get(
                                 'language'
                         ) == "ru") ? "_ru" : "" ?>.png')"></div>
            <div class="left" style="width: 440px; margin-left: 20px;">
                <div class="mt10 mb10 cbrown bold" style="font-size: 18px;"><?= t('Событие недели') ?></div>
                <div style="font-style: italic">
                    <div class="left mr10">
                        <?= t('Установите М-обложку в своем профиле в Facebook') ?>
                        <div class="aright fs11 mt10">
                            <a href="/help/index?fb-M-cover"
                               style="text-decoration: underline"><?= t('Как это сделать') ?> &rarr;</a>
                        </div>
                    </div>
                    <div class="left"><img src="/static/images/2012_icon.png"/></div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="clear"></div>
        <?php } ?>
    </div>
<?php } ?>


<div class="mt10 bg-secondary " style="-moz-border-radius: 3px 3px 3px 3px;
    color: white;
    font-size: 11px;
    font-weight: bold;
    height: 27px;
    margin-bottom: 0;
    padding: 6px 2px 0;">
    <?php if (session::get_user_id() === $user['id'] || session::has_credential('admin')) { ?>
        <a
                href="javascript:"
                class="atab <?= (session::get_user_id() === $user['id'] || (!request::get('atab') || request::get(
                                        'atab'
                                ) == 'afeed')) ? 'asel' : '' ?>"
                id="afeed"><?= session::has_credential('admin') ? '*' : '' ?><?= t('Лента активности') ?></a>
    <?php } ?>
    &nbsp;<a href="javascript:" class="atab <?= (session::get_user_id() == $user['id']) ? '' : 'asel' ?>"
             id="amain"><?= t('Про меня') ?></a>
    &nbsp;<a href="javascript:" class="atab" id="ablog"><?= t('Мой блог') ?></a>
    &nbsp;<a href="javascript:" class="atab" id="acontent"><?= t('Мои материалы') ?></a>
    <?php if (rating_helper::has_access($user['id']) && $user['status'] >= 20) { ?>&nbsp;<a href="javascript:"
                                                                                            class="atab <?= (request::get(
                                                                                                            'atab'
                                                                                                    ) == 'rating') ? ' asel' : ''; ?>"
                                                                                            id="arating"><?= t(
            'Мой рейтинг'
    ) ?></a><?php } ?>
    <?php if ($show_info) { ?>
        &nbsp;<a href="javascript:" class="atab" id="ainfo">*<?= t('Админ') ?></a>
        &nbsp;<a href="<?= sprintf('/admin/users?key=%d', $user['id']) ?>" style="color:  white">*<?= t(
                    'Админ 2'
            ) ?></a>
    <?php } ?>

    <?php if (session::has_credential('admin') || session::get_user_id() == $user['id']) { ?>
        &nbsp;<a href="javascript:" class="atab hide" id="astats">*<?= t('Статистика') ?></a>
    <?php } ?>
</div>

<div class="clear"></div>
<!-- DIV MAIN -->

<?php if (session::get_user_id() == $user['id'] || session::has_credential('admin')) { ?>
    <div id="divafeed"
         class="<?= (session::get_user_id() == $user['id']) ? '' : 'hide' ?><?= (!request::get('atab') || request::get(
                         'atab'
                 ) == 'afeed') ? '' : ' hidden' ?>">
        <?php if (!$feed_list) { ?>
            <div class="acenter screen_message fs11 quiet"><?= t('Обновлений еще нет') ?></div>
        <?php } else { ?>
            <?php foreach ($feed_list as $id) {
                include conf::get(
                                'project_root'
                        ) . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR . '/frontend/modules/feed/views/partials/item.php';
            } ?>
            <div class="bottom_line_d mr10 mb10"></div>
        <?php } ?>
    </div>
<?php } ?>
<?php if (rating_helper::has_access($user['id']) && $user['status'] >= 20) { ?>
    <?php include 'boxes/rating.php' ?>
<?php } ?>


<div id="divamain"
     class="<?= (request::get('atab') == 'target' || session::get_user_id() == $user['id'] || session::get_user_id() == 4) ? 'hide' : '' ?>">
    <div class="pt5 fs11 tab_pane_gray">

        <a rel="about" href="javascript:" class="selected"><?= t('Основное') ?></a>
        <?php if ($user_data['interests'] or $user_data['books'] or $user_data['films'] or $user_data['sites'] or $user_data['music'] or $user_data['leisure']) { ?>
            <a rel="interests" href="javascript:"><?= t('Интересы') ?></a>
        <?php } ?>
        <?php if (($user_bio && @count(array_unique(@array_values($user_bio))) > 2) || ($user_work && @count(
                                array_unique(@array_values($user_work))
                        ) > 2) || ($user_education && @count(array_unique(@array_values($user_education))) > 2)) {
            ?>
            <a rel="bio" href="javascript:"><?= t('Биография') ?></a>
        <?php } ?>
        <div class="clear"></div>
    </div>

    <?php if (user_auth_peer::get_rights(session::get_user_id(), $user_data['contact_access'])) {
        include 'boxes/user_info.php';
        include 'boxes/interests.php';
        include 'boxes/bio.php';
    } else {
        echo '<div class="screen_message acenter bold">' . t('Пользователь делится данными своего профиля только с') . ' ';
        switch ($user_data['contact_access']) {
            case 1:
                echo t('зарегистрированными пользователями');
                break;
            case 10:
                echo t('Меритократами');
                break;
            case 20:
                echo t('членами МПУ');
                break;
        }
        echo '</div>';
    }
    ?>
</div>

<?php if ($show_info) {
    //ADMIN INFO
    include 'boxes/admin_info.php';
} ?>

<?php
if (session::has_credential("admin") || session::get_user_id() == $user['id']) {
    include 'boxes/stats.php';
}
?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        $('.atab').click(function () {
            $('.atab').removeClass('asel');
            $(this).addClass('asel');
            var id = this.id;
            $('.atab').each(function () {
                if (this.id != id)
                    $('#div' + this.id).hide();
            });
            $('#div' + id).show();
        });
    });
</script>

<script src="<?= context::get('static_server') ?>module_feed.js?1" type="text/javascript"></script>
