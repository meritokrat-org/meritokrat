<?php

/**
 * @var array $users
 */

load::model('user/user_auth');
load::model('ppo/members');
load::model('ppo/applicants');
load::model('ppo/news');

?>

<div class="profile d-flex flex-row">
    <div style="width: 230px;">
        <a style="margin: 5px 0 5px 8px; display: block" href="/ppo">← <?= t('Назад к списку') ?></a>

        <div style="text-align:center; margin-bottom: 5px;">
            <?php if ($group['photo_salt']) { ?>
                <?= user_helper::ppo_photo(user_helper::ppo_photo_path($group['id'], 'p', $group['photo_salt'])) ?>
            <?php } else {
                load::view_helper('group');
                load::model('groups/groups'); ?>
                <?= group_helper::photo(0, 'p', false) ?>
                <?php
            } ?>
        </div>

        <div style="width: 227px; margin: auto;">
            <div class="ml5 profile_menu">
                <?php
                /*db::get_scalar("SELECT count(*)
        FROM ppo_members
        WHERE group_id IN(SELECT id FROM ppo WHERE category=1)
        AND user_id=".session::get_user_id())==0*/

                if ($group['ptype'] != 1 || ($group['ptype'] == 1 && $user_data['region_id'] == $group['region_id'])) {
                    if ($user['status'] == 20 && $group['category'] == 1) { ?>
                        <?php if (!ppo_applicants_peer::instance()->is_applicant(
                            $group['id'],
                            session::get_user_id()
                        )) { ?>
                            <a id="menu_apply" href="javascript:" style="<?= $is_member ? 'display:none;' : '' ?>"
                               rel="<?= $group['id'] ?>">
                                <?= tag_helper::image('icons/check.png', ['class' => 'vcenter mr5']) ?>
                                <?= ($has_invite) ? t('Принять приглашение') : t('Подать заявку на вступление') ?>
                            </a>
                            <div id="text_apply"
                                 class="hidden quiet normal"><?= t('Заявка на вступление отправлена') ?></div>
                        <?php } else { ?>
                            <div class="quiet normal"><?= t('Заявка на вступление отправлена') ?></div>
                        <?php } ?>
                    <?php }
                } ?>
            </div>
            <div class="share left ml5" style="margin-left:40px">
                <?php if ($allow_edit && $group['category'] == 1) { ?>
                    <a href="javascript:" onclick="Application.inviteItem('ppo', 4, <?= $group['id'] ?>)"
                       class="share mb5 ml15"><span class="fs18"><?= t('Пригласить') ?></span></a>
                <?php } ?>
            </div>
            <br/>
            <?php if (!$privacy_closed) {
                $group['glava_id']    = (int)ppo_members_peer::instance()->get_user_by_function(
                    1,
                    $group['id'],
                    $group
                );
                $group['secretar_id'] = (int)ppo_members_peer::instance()->get_user_by_function(
                    2,
                    $group['id'],
                    $group
                ); ?>
                <div class="column_head_small mt15"><?= t('Руководство') ?></div>
                <?php $personCard = static function ($userId, $function) {
                $functionName = t(ppo_members_peer::FUNCTIONS[$function]);
                $userFullName = user_helper::full_name($userId, true, [], false);
                $userPhoto    = user_helper::photo(
                    $userId,
                    's',
                    ['class' => 'border1'],
                    true,
                    'user',
                    '',
                    false,
                    false
                );

                return <<<HTML
<div class="fs11 box_content p-2">
    <div class="d-flex">
        <div class="flex-shrink-1">{$userPhoto}</div>
        <div class="px-2">
            <div>{$userFullName}</div>
            <div>{$functionName}</div>
        </div>
    </div>
</div>
HTML;
            } ?>
                <div class="mt5 quiet fs11">
                    <?php $head = db::get_rows(
                        'select user_id, "function" from ppo_members where group_id = :ppoId and "function" in (1, 5) order by "function"',
                        ['ppoId' => $group['id']]
                    ); ?>
                    <?php $secretary = db::get_rows(
                        'select user_id, "function" from ppo_members where group_id = :ppoId and "function" in (2, 6) order by "function"',
                        ['ppoId' => $group['id']]
                    ); ?>
                    <?php $managers = db::get_rows(
                        'select * from ppo_members pm where pm.group_id = :ppo and pm."function" in (3, 4) order by pm."function" desc',
                        ['ppo' => $group['id']]
                    ) ?>
                    <?php if (count($head) > 0) { ?>
                        <?= $personCard($head[0]['user_id'], $head[0]['function']) ?>
                    <?php } ?>
                    <?php if (count($secretary) > 0) { ?>
                        <?= $personCard($secretary[0]['user_id'], $secretary[0]['function']) ?>
                    <?php } ?>
                </div>
                <?php $managerPosition = [3 => 'Член КРК', 4 => 'Член Совета'] ?>
                <?php foreach ($managers as $manager) { ?>
                    <?= $personCard($manager['user_id'], $manager['function']) ?>
                <?php } ?>
                <br/>
                <div class="column_head_small">
					<span class="left"><a href="/ppo/news?id=<?= $group['id'] ?>"
                                          class="fs11 right"><?= t('Новости') ?></a></span>

                    <div class="clear"></div>
                </div>
                <?php load::view_helper('image') ?>
                <?php if ($news) { ?>
                    <?php foreach ($news as $id) { ?>
                        <?php $new = ppo_news_peer::instance()->get_item($id) ?>
                        <div class="fs11 mb5 pb5 clear" style="background: #F7F7F7;">
                            <div style="width: 60px;" class="left mr10">
                                <?= user_helper::ppo_photo(
                                    user_helper::ppo_photo_path($group['id'], 's', $group['photo_salt'])
                                ) ?>
                            </div>
                            <div class="fs11 ml5 white bold"></div>
                            <div class="fs11 p5">
                                <div class="mb5 quiet"><?= date_helper::human($new['created_ts'], ', ') ?></div>
                                <a href="ppo/newsread?id=<?= $new['id'] ?>" class="fs12 bold"
                                   style="color:black"><?= stripslashes(
                                        nl2br(htmlspecialchars($new['title']))
                                    ) ?></a>
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
                <?php if (ppo_peer::instance()->is_moderator($group['id'], session::get_user_id())) { ?>
                    <div class="box_content left mt5 fs11"><a href="/ppo/add_news?id=<?= $group['id'] ?>"
                                                              class="ml10 fs11"><?= t('Добавить новость') ?>
                            &rarr;</a>
                    </div>
                <?php } ?> <br/>
            <?php } ?>
        </div>
    </div>

    <div class="flex-grow-1" style="max-width: 515px; padding-top: 10px">

        <div class="d-flex w-100">
            <h1 class="flex-grow-1 m-0" style="font-size: 20px; color: black;">
                <?= stripslashes(htmlspecialchars($group['title'])) ?>
            </h1>
            <div class="pr5">
                <span class="badge bg-primary" style="font-size: 14pt">
                <?= db::get_scalar(
                    'select count(*)
from ppo_members p
         right join user_auth ua on ua.id = p.user_id
where p.group_id = :ppoId
  and ua.status not in (-1, 1, 3);',
                    ['ppoId' => $group['id']]
                ) ?>
                </span>
            </div>
            <div class="flex-shrink-1 px-2 fs11 quiet text-end d-none">
                <?= call_user_func(
                    function ($isActive = false) {
                        $attributes = [
                            'class' => ['form-check-input'],
                            'type'  => 'checkbox',
                        ];

                        if (true === $isActive) {
                            $attributes['checked'] = 'checked';
                            $attributes['class'][] = 'bg-success';
                        }

                        $attributes    = implode(
                            ' ',
                            array_map(
                                function ($val, $attr) {
                                    switch ($attr) {
                                        case 'class':
                                            $val = implode(' ', $val);
                                            break;
                                    }

                                    return sprintf('%s="%s"', $attr, $val);
                                },
                                array_values($attributes),
                                array_keys($attributes)
                            )
                        );
                        $ppoStateLabel = $isActive ? t('Одобрено') : t('Не одобрено');

                        return <<<HTML
<div data-ppo-state="label">{$ppoStateLabel}</div>
<div>
    <div class="form-check form-switch">
        <label class="form-check-label">
            <input onchange="ppoStateController.handleChange(this)" {$attributes}/>
        </label>
    </div>
</div>
HTML;
                    },
                    1 === $group['active']
                ) ?>
                <script>
                    const ppoStateController = (() => {
                        const ppoStateLabel = document.querySelector('div[data-ppo-state="label"]');
                        const labelMap = new Map([ [ '1', '<?=t('Одобрено')?>' ], [ '0', '<?=t('Не одобрено')?>' ] ]);

                        return {
                            submitState,
                            handleChange,
                        };

                        function submitState(state, cb) {
                            fetch(`/api/ppo/set_state?id=<?=$group['id']?>&state=${state}`)
                                .then(r => r.json())
                                .then(response => cb({ state, response }));
                        }

                        function handleChange(target) {
                            target.classList.toggle('bg-success');
                            target.value = target.classList.contains('bg-success') ? 1 : 0;

                            this.submitState(target.value, ({ state }) => {
                                ppoStateLabel.textContent = labelMap.get(state.toString());
                            });
                        }
                    })();
                </script>
            </div>
        </div>

        <div class="mt-1 fs11 bold">
            <?php $levels = ppo_peer::get_levels() ?>
            <?php $group['category'] = $group['category'] ?: 1 ?>
            <?= $levels[$group['category']] ?> <?= mb_strtolower(t('Партийная организация')) ?>
        </div>

        <div class="mt-3 fs11 quiet fw-bold">
            <a rel="common" class="tab_menu selected fs11 quiet"
               href="javascript:"><?= t('Основные сведения') ?></a>
            <?php if (session::has_credential('admin')
                || (ppo_members_peer::instance()->allow_edit(session::get_user_id(), $group)
                    && sizeof(array_intersect([113, 123], $user_functions)) > 0)
            ) { ?>
                <a rel="more" class="tab_menu ml10 fs11" href="javascript:"><?= t('Служебная информация') ?></a>
            <?php } ?>
            <?php if ($allow_edit) { ?>
                <a href="/ppo/edit?id=<?= $group['id'] ?>" class="ml10 fs11"><?= t('Редактировать') ?></a>
            <?php } ?>
            <?php if (session::has_credential('admin')) { ?>
                <?= $group['active'] != 1 ? '<a href="/ppo/approve_ppo?ppo_id='.$group['id'].'" class="ml10 fs11">'.t(
                        'Одобрить'
                    ).'</a>' : '' ?>
                <a onclick="return confirm('Видалити цю партiйну органiзацiю?');"
                   href="/ppo/delete_ppo?ppo_id=<?= $group['id'] ?>" class="ml10 fs11"><?= t('Удалить') ?></a>
            <?php } ?>
            <?php if ($allow_edit) { ?>
                <a href="/messages/compose_ppo?ppo=<?= $group['id'] ?>" class="ml10 fs11"><?= t(
                        'Рассылки'
                    ) ?></a>
            <?php } ?>
        </div>

        <table id="common_box" class="tbox fs12 mt10" style="margin-bottom: 0">
            <tr>
                <td width="40%" class="bold aright">
                    <?= t('Регион') ?> / <?= t('Район') ?> / <?= t('Нас. пункт') ?>:
                </td>
                <td>
                    <?php $region = geo_peer::instance()->get_region($group['region_id']); ?>
                    <?= $region['name_'.translate::get_lang()] ?><?php $city = geo_peer::instance()->get_city(
                        $group['city_id']
                    ); ?>
                    <?= $group['category'] < 3 ? ' / '.$city['name_'.translate::get_lang(
                        )] : '' ?><?php if ($group['location']) { ?> / <?= stripslashes(
                        htmlspecialchars($group['location'])
                    ) ?><?php } ?>
                </td>
                <?php if ($group['coords']){ ?>
            <tr>
                <td width="40%" class="bold aright">
                    <?= t('Территория деятельности') ?>:
                </td>
                <td>
                    <a id="teritory" href="javascript:"><?= t('Посмотреть на карте') ?></a>
                </td>
            </tr><?php } ?>
            <?php if ($group['category'] == 1) { ?>
                <tr>
                <td width="40%" class="bold aright">
                    <?= t('Тип') ?>:
                </td>
                <?php if (session::is_authenticated()) { ?>
                    <td>
                        <?php $types = ppo_peer::get_ptypes();
                        echo $types[$group['ptype']] ?>
                        <?php $bkm = bookmarks_peer::instance()->is_bookmarked(
                            session::get_user_id(),
                            8,
                            $group['id']
                        ); ?>
                        <a class="right bookmark mb10 ml5 b8" style="<?= ($bkm) ? 'display:none' : '' ?>"
                           href="#add_bookmark"
                           onclick="Application.bookmarkItem('8','<?= $group['id'] ?>');return false;"><b></b><span><?= t(
                                    'В закладки'
                                ) ?></span></a>
                        <a class="right unbkmrk mb10 ml5 b8" style="<?= ($bkm) ? '' : 'display:none' ?>"
                           href="#del_bookmark"
                           onclick="Application.unbookmarkItem('8','<?= $group['id'] ?>');return false;"><b></b><span><?= t(
                                    'Удалить из закладок'
                                ) ?></span></a>
                    </td>
                <?php } ?>
                </tr><?php } ?>
        </table>
        <?php if (session::has_credential('admin') || ppo_members_peer::instance()->allow_edit(
                session::get_user_id(),
                $group
            )) { ?>
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
                <?php if ($group['dzbori'] || $group['uchasniki']) { ?>
                    <tr>
                    <td style="width:50%;" class="aright mr15 bold"><?= t('Учредительное собрание') ?>:</td>
                    <td></td>
                    </tr><?php } ?>
                <?php if ($group['dzbori']) { ?>
                    <tr>
                    <td class="aright"><?= t('Дата') ?></td>
                    <td>
                        <?= $group['dzbori'] ? date("d-m-Y", $group['dzbori']) : '' ?>
                    </td>
                    </tr><?php }
                $members = explode(',', str_replace(['{', '}'], ['', ''], $group['uchasniki']));
                if ($members[0] > 0) { ?>
                    <tr>
                        <td class="aright">Учасники</td>
                        <td>
                            <?php $mc = 1;
                            foreach ($members as $m): ?>
                                <?= user_helper::full_name((int)$m, true, [], false) ?>
                                <?= $mc != count($members) ? ', ' : '' ?>
                                <?php $mc++; endforeach; ?>
                        </td>
                    </tr> <?php } ?>
                <?php if ($group['uhvalnum'] || $group['duhval'] || $group['dovidnum']) { ?>
                    <tr>
                        <td class="aright mr15 bold">Рiшення Голови про затвердження:</td>
                        <td></td>
                    </tr>
                <?php } ?>
                <?php if ($group['uhvalnum']) { ?>
                    <tr>
                    <td class="aright">№</td>
                    <td>
                        <?= $group['uhvalnum'] ?>
                    </td>
                    </tr><?php } ?>
                <?php if ($group['duhval']) { ?>
                    <tr>
                    <td class="aright"><?= t('Дата') ?></td>
                    <td>
                        <?= ($group['duhval']) ? date("d-m-Y", $group['duhval']) : '' ?>
                    </td>
                    </tr><?php } ?>
                <?php if ($group['dovidnum'] || $group['doviddate'] || $group['protokolsdate'] || $group['dovidsdate']) { ?>
                    <tr>
                        <td class="aright mr15 bold">Легалiзацiя:</td>
                        <td></td>
                    </tr>
                <?php } ?>
                <?php if ($group['dovidnum']) { ?>
                    <tr>
                    <td class="aright">№ довiдки</td>
                    <td>
                        <?= $group['dovidnum'] ?>
                    </td>
                    </tr><?php } ?>
                <?php if ($group['doviddate']) { ?>
                    <tr>
                    <td class="aright"><?= t('Дата выдачи') ?></td>
                    <td>
                        <?= ($group['doviddate']) ? date("d-m-Y", $group['doviddate']) : '' ?>
                    </td>
                    </tr><?php } ?>
                <?php if ($group['svidcopy']) { ?>
                    <tr>
                        <td class="aright">Копія свідоцтва видана</td>
                        <td>
                        </td>
                    </tr><?php } ?>
                <?php if ($group['protokolsdate'] || $group['dovidsdate']) { ?>
                    <tr>
                        <td class="aright mr15 bold">Отримання документів Секретаріатом:</td>
                        <td></td>
                    </tr><?php } ?>
                <?php if ($group['protokolsdate']) { ?>
                    <tr>
                        <td class="aright">Протокол</td>
                    </tr><?php } ?>
                <?php if ($group['dovidsdate']) { ?>
                    <tr>
                        <td class="aright">Довiдка / Свiдоцтво</td>
                    </tr><?php } ?>
                <?php if ($group['zayava']) { ?>
                    <tr>
                        <td class="aright">Заява</td>
                    </tr><?php } ?>
                <?php if ($group['vklnumber'] || $group['vkldate']) { ?>
                    <tr>
                        <td class="aright mr15 bold">Рiшення Голови про включення в структуру:</td>
                        <td></td>
                    </tr><?php } ?>
                <?php if ($group['vklnumber']) { ?>
                    <tr>
                    <td class="aright">№</td>
                    <td>
                        <?= $group['vklnumber'] ?>
                    </td>
                    </tr><?php } ?>
                <?php if ($group['vkldate']) { ?>
                    <tr>
                    <td class="aright"><?= t('Дата принятия') ?></td>
                    <td>
                        <?= ($group['vkldate']) ? date("d-m-Y", $group['vkldate']) : '' ?>
                    </td>
                    </tr><?php } ?>
                <?php if ($group['svidvruch'] || $group['svidvig'] || $group['svidnum']) { ?>
                    <tr>
                        <td class="aright mr15 bold">Свідоцтво:</td>
                        <td></td>
                    </tr><?php } ?>
                <?php if ($group['svidnum']) { ?>
                    <tr>
                    <td class="aright">№ Свідоцтва</td>
                    <td>
                        <?= $group['svidnum'] ?>
                    </td>
                    </tr><?php } ?>
                <?php if ($group['svidvig']) { ?>
                    <tr>
                        <td class="aright">Виготовлення</td>
                    </tr><?php } ?>
                <?php if ($group['svidvruch']) { ?>
                    <tr>
                        <td class="aright">Вручення</td>
                    </tr><?php } ?>
                <?php if ($group['svidcom'] != '') { ?>
                    <tr>
                    <td class="aright">Коментар</td>
                    <td><?= $group['svidcom'] ?></td>
                    </tr><?php } ?>


                <!--        PARTY INVENTORY        -->
                <?php
                if (!empty($inv_owners) && session::has_credential('admin')) {
                    $inventory_types    = user_party_inventory_peer::instance()->get_inventory_type();
                    $inventory_types[0] = '&mdash;';
                    ksort($inventory_types);
                    foreach ($inventory_types as $inv_id => $inv_name) {
                        $current = db::get_scalar(
                            "SELECT sum(inventory_count) FROM party_inventory WHERE inventory_type=:itype AND user_id IN (".implode(
                                ',',
                                $inv_owners
                            ).")",
                            ['itype' => $inv_id]
                        );
                        if ($current) { ?>
                            <?php if (!$spike) { ?>
                                <tr>
                                    <td class="aright mr15 bold">Партійний інвентар</td>
                                    <td></td>
                                </tr>
                                <?php $spike = 1;
                            } ?>
                            <tr>
                                <td class="aright mr15"><?= $inv_name ?></td>
                                <td>
                                    <?= $current; ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
                <!--        END        -->
            </table>
        <?php } ?>

        <?php if ($children_mpo) { ?>
            <div class="mb-2">
                <div class="row align-middle m-0 mb-2 py-1 rounded-top bg-primary" style="font-size: 11px">
                    <div class="col m-0 white fw-bold pl5"><?= t('Подчиненные местные организации') ?>
                        - <?= count($children_mpo) ?></div>
                </div>
                <?php foreach ((array)$children_mpo as $pgroup) { ?>
                    <div class="pl5">
                        <a href="/ppo<?= $pgroup['id'] ?>/<?= $pgroup['number'] ?>">
                            <?= $pgroup['title'] ?>
                            <span>(<?= db::get_scalar(
                                    'select count(*) from ppo_members where group_id = :ppoId',
                                    ['ppoId' => $pgroup['id']]
                                ) ?>)</span>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>

        <?php if ($children_ppo) { ?>
            <div class="ml10 mt10">
                <h1 class="column_head mt10 mb10">
                    <?= t('Подчиненные первичные 1организации') ?> <?= count($children_ppo) ?>
                </h1>

                <div class="pcontent_pane">
                    <?php foreach ((array)$children_ppo as $pgroup) { ?>
                        <div class="pl5">
                            <a href="/ppo<?= $pgroup['id'] ?>/<?= $pgroup['number'] ?>">
                                <?= $pgroup['title'] ?>
                                <span>(<?= db::get_scalar(
                                        'select count(*) from ppo_members where group_id = :ppoId',
                                        ['ppoId' => $pgroup['id']]
                                    ) ?>)</span>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="clear"></div>
        <?php } ?>

        <?= call_user_func(require __DIR__.'/view/members.php', $group) ?>

        <div class="ml10">
            <div class="tab_pane" style="background: #0d6efd !important">
                <a rel="events" href="javascript:"><?= t('События') ?></a>
                <a rel="posts" href="javascript:" class="selected"><?= t('Обсуждения') ?></a>
                <a rel="files" href="javascript:"><?= t('Библиотека') ?></a>
                <a rel="foto" href="javascript:"><?= t('Фото') ?></a>
                <a rel="report" href="javascript:"><?= t('Отчеты') ?></a>
                <?php if ($group['category'] == 3) { ?>
                    <a rel="finance" href="javascript:"><?= t('Финансы') ?></a>
                <?php } ?>
                <div class="clear"></div>
            </div>
            <div id="pane_posts" class="content_pane">
                <?php if (session::is_authenticated()) { ?>
                    <div class="box_content p5 mb10 fs11"><a
                                href="/ppo/post_edit?group_id=<?= $group['id'] ?>&add=1"><?= t(
                                'Добавить тему'
                            ) ?>
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
                                <a href="/ppo/post?group_id=<?= $group['id'] ?>&id=<?= $id ?>"><?= stripslashes(
                                        htmlspecialchars($post['title'])
                                    ) ?></a>
                            </div>
                            <div class="fs11 pb5">
                                <div class="left quiet">
                                    <?= user_helper::full_name(
                                        $post['user_id'],
                                        true,
                                        ['class' => 'mr10'],
                                        false
                                    ) ?>
                                    <?= t('Комментариев') ?>:
                                    <b class="mr5"><?= blogs_comments_peer::instance()->get_count_by_post(
                                            $id
                                        ) ?></b>
                                    <?= t('Просмотров') ?>: <b><?= $post['views'] ?></b>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
                <div class="box_content p5 mb10 fs11"><a
                            href="/ppo/posts?group_id=<?= $group['id'] ?>"><?= t('Все мысли') ?> &rarr;</a>
                </div>
            </div>
            <div id="pane_events" class="content_pane hidden">
                <?php if (session::has_credential('admin') or
                    ppo_peer::instance()->is_moderator($group['id'], session::get_user_id()) or
                    user_desktop_peer::instance()->is_regional_coordinator(session::get_user_id()) or
                    user_desktop_peer::instance()->is_raion_coordinator(session::get_user_id()) or
                    ppo_members_peer::instance()->is_member($group["id"], session::get_user_id())
                ) { ?>
                    <div class="box_content p5 mb10 fs11"><a
                                href="/events/create?type=4&content_id=<?= $group['id'] ?>"><?= t(
                                'Добавить событие'
                            ) ?>
                            &rarr;</a>
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
                                    <?php if (date('d-m-Y', $event['start']) == date('d-m-Y', $event['end'])) {
                                        $kolu = date_helper::get_format_date($event['start'], false).', '.t(
                                                'с'
                                            ).' '.date('H:i', $event['start']).' до '.date(
                                                'H:i',
                                                $event['end']
                                            );
                                    } else {
                                        $kolu = t('с').' '.date_helper::get_format_date(
                                                $event['start']
                                            ).' '.date(
                                                'H:i',
                                                $event['start']
                                            ).' <br/>до '.
                                            date_helper::get_format_date($event['end']).' '.date(
                                                'H:i',
                                                $event['end']
                                            );
                                    } ?><?= $kolu ?>
                                    <br/>
                                    <?= t('Организатор').": " ?>
                                    <?php
                                    switch ($event['type']) {
                                        case 3:
                                            ?>
                                            <a href="/profile-31" style="color:black"><?= t(
                                                    "Секретариат МПУ"
                                                ) ?></a>
                                            <?php
                                            break;
                                        default:
                                            ?>
                                            <?= user_helper::full_name(
                                            $event['user_id'],
                                            true,
                                            ['style' => 'color:black'],
                                            false
                                        ); ?>
                                        <?php
                                    }
                                    ?>
                                    <br/>
                                    <?= t('Событие посещают') ?>:
                                    <b><?= $event['users1sum'] + $event['users3sum'] + $event['users1count'] + $event['users3count'] ?> <?= t(
                                            'участников'
                                        ) ?></b>
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
                        class="box_content p5 mb10 fs11"><?php if (groups_members_peer::instance()->is_member(
                            $group['id'],
                            session::get_user_id()
                        ) || session::has_credential('admin')) { ?>
                        <a href="/ppo/file?id=<?= $group['id'] ?>&add=1"><?= t('Добавить материал') ?>&rarr;</a>
                    <?php } ?>
                </div>
                <?php if ($files) { ?>
                    <div class="mt5 m5 fs12 mb5 left">
                        <?php foreach ($files as $file_id) {
                            $file = ppo_files_peer::instance()->get_item($file_id);
                            if (isset($file['files'])) {
                                $arr = unserialize($file['files']);
                            }
                            ?>
                            <div class="box_content mt5 mb10"
                                 style="border-bottom: 1px solid #f7f7f7;width:520px;">
                                <div class="left">
                                    <div class="ml5"><a
                                                href="<?= (isset($file['files'])) ? context::get(
                                                        'file_server'
                                                    ).$file['id'].'/'.$arr[0]['salt']."/".$arr[0]['name'] : $file['url'] ?>"><?= stripslashes(
                                                htmlspecialchars($file['title'])
                                            ) ?></a>
                                    </div>
                                    <div class="left ml5 fs12"><?= $file['author'] ?></div>
                                </div>
                                <?php if (isset($file['files'])) {
                                    foreach ($arr as $f) {
                                        $ext = end(explode('.', $f['name']));
                                        ?>
                                        <div class="left ml5 <?//=$file['author'] ? 'mt15' : ''?>">
                                            <a href="<?= context::get(
                                                'file_server'
                                            ).$file['id'].'/'.$f['salt']."/".$f['name'] ?>">
                                                <img
                                                        src="/static/images/files/<?= ppo_files_peer::instance(
                                                        )->get_icon($ext) ?>">
                                            </a></div>
                                    <?php }
                                } else { ?>
                                    <div class="left ml5 <?php //=$file['author'] ? 'mt15' : ''?>"><img
                                                src="/static/images/files/IE.png"></div> <?php } ?>
                                <?php if ($file['lang'] == 'ua' or $file['lang'] == 'en') { ?>
                                    <div class="left ml5"
                                         style="margin-top:  1<?php //=$file['author'] ? '17' : '2'?>px;"><?= tag_helper::image(
                                    'icons/'.$file['lang'].'.png',
                                    ['']
                                ) ?></div><?php } ?>
                                <div class="right aright mr5"
                                     style="border-bottom: 1px solid #d7d7d7; color:#565656;"> <?= $file['size'] ? $file['size'] : '' //$file['exts'] ? ppo_files_peer::formatBytes(filesize($file['url'])) : ''                                                                                       ?>
                                    <?= tag_helper::image(
                                        'icons/1.png',
                                        ['alt' => "Інформація", 'id' => $file['id'], 'class' => "info ml5 "]
                                    ) ?>
                                    <?php if (ppo_peer::instance()->is_moderator(
                                            $group['id'],
                                            session::get_user_id()
                                        ) || $file['user_id'] == session::get_user_id()) { ?>
                                        <a href="/ppo/file_edit?id=<?= $file['id'] ?>"><img class="ml5"
                                                                                            alt="Редагування"
                                                                                            src="/static/images/icons/2.png"></a>
                                        <a onclick="return confirm('Ви впевнені?');"
                                           href="/ppo/file_delete?id=<?= $file['id'] ?>"><img class="ml5"
                                                                                              alt="видалення"
                                                                                              src="/static/images/icons/3.png"></a>
                                    <?php } ?>
                                </div>
                                <div class="clear"></div>
                                <div id="file_describe_<?= $id ?>"
                                     class="ml10 fs11 hidden"><?= stripslashes(
                                        htmlspecialchars($file['describe'])
                                    ) ?></div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="clear mb5"></div>
                <?php } else { ?>
                    <div class="m5 acenter fs12">
                        <?= t('') ?>
                        <?php if (ppo_peer::instance()->is_moderator(
                                $group['id'],
                                session::get_user_id()
                            ) || session::has_credential('admin')) { ?>
                            <a href="/ppo/file?id=<?= $group['id'] ?>&add=1"><?= t('Добавить материал') ?>
                                &rarr;</a>
                            <br/>
                        <?php } ?>
                    </div>
                <?php } ?>
                <div class="box_content p5 mb10 fs11"><a
                            href="/ppo/file?id=<?= $group['id'] ?>"><?= t('Все материалы') ?> &rarr;</a></div>
            </div>
            <div id="pane_foto" class="content_pane hidden">
                <?php if (ppo_peer::instance()->is_moderator(
                        $group['id'],
                        session::get_user_id()
                    ) || session::has_credential('admin')) { ?>
                    <div class="box_content p5 mb10 fs11">
                        <a href="/photo/add?type=2&oid=<?= $group['id'] ?>"><?= t('Добавить фото') ?> &rarr;</a>
                    </div>
                <?php } ?>
                <?php if ($photos) { ?>
                    <div class="mt10 p10 mb15 fs12 gallery" style="text-align:center">
                        <?php foreach ($photos as $photo_id) { ?>
                            <a href="/photo?type=2&oid=<?= $group['id'] ?>" class="ml10"
                               rel="prettyPhoto[gallery]">
                                <?= photo_helper::photo($photo_id, 'h', []) ?>
                            </a>
                        <?php } ?>
                        <br/>
                        <a class="right fs12"
                           href="/photo?type=2&oid=<?= $group['id'] ?>"><?= t('Все фото') ?> &rarr;</a><br>
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
                                    <?php if (date('d-m-Y', $report['start']) == date(
                                            'd-m-Y',
                                            $report['end']
                                        )) {
                                        $kolu = date_helper::get_format_date($report['start'], false).', '.t(
                                                'с'
                                            ).' '.date('H:i', $report['start']).' до '.date(
                                                'H:i',
                                                $report['end']
                                            );
                                    } else {
                                        $kolu = t('с').' '.date_helper::get_format_date(
                                                $report['start']
                                            ).' '.date(
                                                'H:i',
                                                $report['start']
                                            ).' <br/>до '.
                                            date_helper::get_format_date($report['end']).' '.date(
                                                'H:i',
                                                $report['end']
                                            );
                                    } ?><?= $kolu ?>
                                    <br/>
                                    <?= t('Организатор').": " ?>
                                    <?= user_helper::full_name(
                                        $report['user_id'],
                                        true,
                                        ['style' => 'color:black'],
                                        false
                                    ); ?>
                                    <?php if (session::has_credential('admin') || $is_leader) { ?>
                                        <?php $statuses = [
                                            [t('Новый'), 'green'],
                                            [t('На утверждении'), 'blue'],
                                            [t('На доработке'), 'red'],
                                            [t('Утвержден'), 'black'],
                                            [t('Мероприятие не состоялось'), 'red'],
                                        ] ?>
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
                            <a href="/ppo/edit&id=<?= $group['id'] ?>&tab=vidatki"><?= t('Редактировать') ?>
                                &rarr;</a>
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
                    <?php if ($finances && (session::has_credential(
                                'admin'
                            ) || ($user['status'] == 20 && $user_data['region_id'] == $group['region_id']))) { ?>
                        <?php foreach ($finances as $finance_id) { ?>
                            <div class="mb10 box_content p10 mr10">
                                <?php $finance = ppo_finance_peer::instance()->get_item($finance_id); ?>
                                <div class="quiet">
                                    <div class="fs11">
                                        <?= date('d.m.Y', $finance['date']) ?> - <b><?= $finance['summ'] ?>
                                            грн.</b>
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
                        <?= t('Остаток (фонд)') ?>: <?= intval($ftotal) + intval($ffondtotal) - intval(
                            $fsumm
                        ) ?> грн.
                    </div>
                </div>
            <?php } ?>

        </div>
        <div class="clear"></div>
    </div>
</div>

<?php

if ($_SERVER['SERVER_NAME'] === 'meritokrat.org') {
    $key = 'ABQIAAAAeJTsA7ppykO6RHwqXVTnxhRUv1QFGme1wBmmBs0G3PPf8lp1HxSLUl3FK3V4kfgdjiurxjuNdubvAg';
} else {
    //ABQIAAAAeJTsA7ppykO6RHwqXVTnxhS237pdi7AAC2Fq3Ha5pN09SYJt4xRkBNsN6wrom0qaIxq0Haiiaurq6A
    $key = 'ABQIAAAAXi7AtY5jQ4YMZS3uNqaQVhSn51_jLMmjl25B6QxLNt9bnzD_KBRpTuhouSuZjyfhXbGmAM6vx3bLFw';
}

if ($group['map_lat'] == '' || $group['map_lon'] == '') {
    $group['map_lat'] = '50.4599800';
    $group['map_lon'] = '30.4810890';
    $ow               = 0;
} else {
    $ow = 1;
}
if ($group['map_zoom'] == 0) {
    $group['map_zoom'] = '8';
}
?>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?= $key ?>"
        type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        $('#menu_apply').click(function () {
            var groupId = $(this).attr('rel');
            $.post(
                '/ppo/check_join',
                { id: groupId },
                function (results) {
                    if (results.check == 0) {
                        $('#menu_leave').remove();
                        $.post(
                            '/ppo/join',
                            { id: groupId },
                            function () {
                                $('#menu_join').hide();
                                $('#menu_leave').fadeIn(150);
                            },
                            'json',
                        );
                        $('#menu_apply').hide();
                        $('#text_apply').fadeIn(150);
                    } else {
                        Application.showInfo('why_move');
                    }
                },
                'json',
            );

        });
    });

    function ppoJoin() {
        $('#menu_leave').remove();
        $.post(
            '/ppo/join',
            { id: $('#menu_apply').attr('rel'), text: $('#ppo_join_text').val() },
            function () {
                $('#menu_join').hide();
                $('#menu_leave').fadeIn(150);
            },
            'json',
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
        $('#teritory').click(function () {
            if ($('#popup_box').length) $('#popup_box').show();
            else
                ppoController.showTerritory(1, <?=request::get_int('id')?>);
            return false;
        });
        $('#glava').click(function () {
            Application.showUsers('glava');
            return false;
        });
        $('#secretar').click(function () {
            Application.showUsers('secretar');
            return false;
        });
        $('#category').change(function () {
            if ($(this).val() == 2) {
                $('#sfera-tr').show();
                $('#level-tr').hide();
                $('#hidden_1').removeAttr('checked');
            } else if ($(this).val() == 3) {
                $('#sfera-tr').hide();
                $('#level-tr').show();
                $('#hidden_1').removeAttr('checked');
            } else {
                $('#sfera-tr').hide();
                $('#level-tr').hide();
                $('#hidden_1').removeAttr('checked');
                if ($(this).val() == 4) {
                    $('#hidden_1').attr('checked', 'checked');
                    $('#privacy_2').attr('checked', 'checked');
                    $('#privacy_1').removeAttr('checked');
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
    $('.info').click(function () {
        if (!$('#applicant_info_' + this.id).is(':visible')) {
            $('#applicant_info_' + this.id).slideDown(100);

        } else {
            $('#applicant_info_' + this.id).slideUp(100);
        }
    });
    $('#city').change(function () {
        if ($(this).val() >= 700 || $('#region').val() == 13) $('#nspu').hide();
        else $('#nspu').show();
    });
    $('.ppocategory').change(function () {
        if ($(this).val() == 3) $('#scity').hide();
        else $('#scity').show();
        if ($(this).val() == 1) $('.ptype').show();
        else $('.ptype').hide();
    });

    function initialize2() {
        if (GBrowserIsCompatible()) {
            map = new GMap2(document.getElementById('Map'));

            map.setCenter(new GLatLng('<?=$group['map_lat']?>', '<?=$group['map_lon']?>'), <?=$group['map_zoom']?>);

            map.addControl(new GLargeMapControl());
            map.addControl(new GMapTypeControl());
            <?php
            $cord_array = explode('; ', $group['coords']);
            $cord_array = array_unique(array_diff($cord_array, ['']));
            if(count($cord_array) > 0) {?>
            <?php
            echo "var polyline = new GPolyline([";
            foreach($cord_array as $k=>$c) {
            $coordinates = explode(", ", $c);
            ?>
            <?php
            echo "new GLatLng(".$coordinates[1].",".$coordinates[0]."),\n";
            }
            $firstcoordinates = explode(", ", $cord_array[0]);
            echo "new GLatLng(".$firstcoordinates[1].",".$firstcoordinates[0].")\n";
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
        Popup.position();
    }

    function deleteItem(id, type) {
        $.ajax({
            type: 'post',
            url: '/ppo/edit',
            data: {
                submit: 1,
                delete_id: id,
                id: '<?=$group['id']?>',
                type: 'delete_inventory',
            },
            success: function (data) {
                resp = eval('(' + data + ')');
                if (resp.success == 1) {
                    $('#row' + id).remove();
                    $('#delete_' + type + '_link').html(resp.count);
                    Popup.setHtml($('#popup_' + type).html());
                } else alert(resp.error);
            },
        });
    }
</script>