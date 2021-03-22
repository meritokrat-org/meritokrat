<?php
if (request::get_int('region')) {
    $url['region'] = sprintf('&egion=%s', request::get_int('region'));
}
if (request::get_int('status')) {
    $url['status'] = sprintf('&status=%s', request::get_int('status'));
}
?>

<style>
    li b {
        color: #660000;
    }

    #sort_buffer {
        min-height: 30px;
        list-style: none;
    }
</style>

<?php if (session::is_authenticated()) { ?>
    <?php
    $hasAdminCredential = static function () {
        return session::has_credential('admin');
    };
    $cardListGroup      = [
            [
                    'btn'     => [
                            'href' => '?filter[ppo][function]=1',
                            'text' => sprintf('* %s', t('Голова ПО')),
                    ],
                    'badge'   => static function () {
                        return db::get_scalar(
                                'select count(*) from ppo_members pm right join ppo p on p.id = pm.group_id where pm.function = 1'
                        );
                    },
                    'checkup' => $hasAdminCredential,
            ],
    ];
    ?>

    <div class="card mt-2">
        <h5 class="card-header text-uppercase text-white bg-primary"><?= t('По статусу') ?></h5>
        <ul class="list-group list-group-flush m-0">
            <?= implode(
                    PHP_EOL,
                    array_filter(
                            array_map(
                                    static function ($item) {
                                        if (array_key_exists('checkup', $item) && !$item['checkup']) {
                                            return null;
                                        }

                                        return <<<HTML
<li class="list-group-item d-flex justify-content-between align-items-center">
    <a class="btn btn-sm text-primary" href="{$item['btn']['href']}">{$item['btn']['text']}</a>
    <span class="badge bg-secondary">{$item['badge']()}</span>
</li>
HTML;
                                    },
                                    $cardListGroup
                            ),
                            static function ($item) {
                                return null !== $item;
                            }
                    )
            ) ?>
        </ul>
    </div>

    <div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('statuces')">
        <div class="left"><?= t('По статусу') ?></div>
        <div class="right mt5 icoupicon <?= ($cur_status > 0 || request::get_int('meritokrat') || request::get_int(
                        'expert'
                ) || request::get('offline')) ? '' : 'hide' ?>" style="cursor: pointer;" id="statuces_on"></div>
        <div class="right mt5 icodownt <?= ($cur_status > 0 || request::get_int('meritokrat') || request::get_int(
                        'expert'
                ) || request::get('offline')) ? 'hide' : '' ?>" style="cursor: pointer;" id="statuces_off"></div>
    </div>
    <div class="p10 box_content" id="statuces">
        <ul class="mb5">
            <?php if (session::has_credential('admin')) { ?>
                <li>
                    <a href="?filter[ppo][function]=1" style="<?= 1 == request::get_int(
                            'suslik'
                    ) ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;">*<?= t(
                                'Голова ПО'
                        ) ?></a>
                    <div class="right fs11 bold">
                        <?= db::get_scalar(
                                'select count(*) from ppo_members pm right join ppo p on p.id = pm.group_id where pm.function = 1'
                        ) ?>
                    </div>
                </li>
            <?php } ?>
            <!--li><a href="/profile-5" style="margin: 1px;"><?= t('Глава Оргкомитета') ?></a></li-->
            <?php /* foreach ( user_auth_peer::get_typess() as $type => $title ) { ?>
			<li><a href="/people/index?type=<?=$type?>" style="<?= $type==$cur_type ? 'color: #772f23; font-weight: bold; text-decoration: none;' : ''?>; margin: 1px;"><?=$title?></a></li>
		<? } */ ?>
            <?php
            $statuces = user_auth_peer::get_statucess();
            krsort($statuces);
            foreach ($statuces as $status => $title) {
                if (1 === $status) {
                    $cnt = db::get_scalar(
                            'SELECT count(*) FROM user_auth a, user_data u WHERE a.status=:status AND (a.ban=0 OR a.ban IS NULL) AND a.del=0 AND a.id=u.user_id AND a.active=TRUE',
                            ['status' => $status]
                    );
                } else {
                    if (10 === $status) {
                        $cnt = db::get_scalar(
                                'SELECT count(*) FROM user_auth a, user_data u WHERE a.status=:status AND a.del=0 AND a.id=u.user_id AND a.active=TRUE',
                                ['status' => $status]
                        );
                    } else {
                        $cnt = db::get_scalar(
                                'SELECT count(*) FROM user_auth a, user_data u WHERE (a.status=:status OR a.ban=:ban) AND a.del=0 AND a.id=u.user_id AND a.active=TRUE',
                                ['status' => $status, 'ban' => $status]
                        );
                    }
                }
                ?><?php $adminStar = '';
                if ($status <= user_auth_peer::POTENTIAL_SUPPORTER) {
                    if (!session::has_credential('admin')) {
                        continue;
                    }
                    $adminStar = '*';
                } ?>
                <li>
                    <a href="/people/index?<?= (-10 === $status) ? 'meritokrat=1' : 'status='.$status ?><?= $url['region'] ?>" style="<?= (($status === $cur_status) || (10 === $status && request::get_int(
                                            'meritokrat'
                                    ))) ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;"><?= $adminStar ?><?= $title ?></a>
                    <div class="right fs11 bold"><?= $cnt ?></div>
                </li>
            <?php } ?>
            <!--li><a href="/people/index?expert=1" style="<?= 1 == request::get_int(
                    'expert'
            ) ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;"><?= t(
                    'Эксперты'
            ) ?></a></li-->
            <?php if (session::has_credential('admin')) { ?>

                <!--<li><a href="/people/index?status=10" style="<?= 10 == request::get_int(
                        'status'
                ) ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;">*<?= t(
                        'Лише мерітократи'
                ) ?></a><div class="right fs11 bold"><?= db::get_scalar(
                        "SELECT count(*) FROM user_auth WHERE (status=10 OR ban=10) AND del=0"
                ); ?></div></li>-->
                <li><a href="/people/index?suslik=1" style="<?= 1 == request::get_int(
                            'suslik'
                    ) ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;">*<?= t(
                                'Скрытый профиль'
                        ) ?></a>
                    <div class="right fs11 bold"><?= count(
                                user_auth_peer::instance()->get_suslik_people()
                        ); ?></div>
                </li>
                <li><a href="/people/index?famous=1" style="<?= 1 == request::get_int(
                            'famous'
                    ) ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;">*<?= t(
                                'Известные люди'
                        ) ?></a>
                    <div class="right fs11 bold"><?= count(
                                user_auth_peer::instance()->get_famous_people()
                        ); ?></div>
                </li><!--li><a href="/people/index?type=0" style="<?= (isset($_GET['type']) and 0 == $_GET['type']) ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;">*<?= t(
                        'Без статуса'
                ) ?></a></li-->
                <li>
                    <a href="/people/index?identification=check" style="<?= 'check' == $_GET['identification'] ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;">*<?= t(
                                'Не идентифицированные'
                        ) ?></a>
                    <div class="right fs11 bold"><?= count(
                                user_auth_peer::instance()->get_by_identification()
                        ); ?></div>
                </li>

                <li>
                    <a href="/people/index?activate=1" style="<?= 1 == $_GET['activate'] ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;">*<?= t(
                                'Останні активовані'
                        ) ?></a>
                    <div class="right fs11 bold"><?= db::get_scalar(
                                "SELECT count(*) FROM user_auth WHERE activated_ts IS NOT NULL"
                        ); ?></div>
                </li>
                <li>
                    <a href="/people/index?offline=all" style="<?= $_GET['offline'] ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;">*<?= t(
                                'Офф-лайн учасники'
                        ) ?></a>
                    <div class="right fs11 bold"><?= count(
                                db::get_cols("SELECT id FROM user_auth WHERE offline > 0 AND del=0")
                        ); ?></div>
                </li>
            <?php } ?>
            <?php if (session::has_credential('admin')) { ?>
            <li>
                <a href="/people/index?del=1" style="<?= $_GET['del'] ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;">*<?= t(
                            'Удаленные'
                    ) ?></a>
                <div class="right fs11 bold"><?= count(
                            db::get_cols("SELECT id FROM user_auth WHERE del != 0 ORDER BY del_ts DESC")
                    ); ?></div>
                <?php } ?>
            </li>

            <!--<li>-->
            <!--    <div class="right fs11 bold">-->
            <? //=db::get_scalar('select count(*) from user_auth where status = 5')?><!--</div>-->
            <!--    <a href="/people/index?activate=1&status=5">*--><? //=t('Н Прихильник')?><!--</a>-->
            <!--</li>-->
            <!---->
            <!--<li>-->
            <!--    <div class="right fs11 bold">-->
            <? //=db::get_scalar('select count(*) from user_auth where status = 15')?><!--</div>-->
            <!--    <a href="/people/index?activate=1&status=15">*--><? //=t('Н Кандидат у члени партії')?><!--</a>-->
            <!--</li>-->
            <li>
                <a href="/people/index?fn=1">
                    <?= t('Експерт н.р.') ?></a>
                <div class="right fs11 bold"><?= db::get_scalar(
                            'select count(id) from user_auth where functions like \'[%"1"%]\''
                    ) ?></div>
            </li>
            <li>
                <a href="/people/index?fn=3"><?= t('Експерт р.р.') ?></a>
                <div class="right fs11 bold"><?= db::get_scalar(
                            'select count(id) from user_auth where functions like \'[%"3"%]\''
                    ) ?></div>
            </li>
            <li>
                <a href="/people/index?fn=5"><?= t('Експерт м.р.') ?></a>
                <div class="right fs11 bold"><?= db::get_scalar(
                            'select count(id) from user_auth where functions like \'[%"5"%]\''
                    ) ?></div>
            </li>
        </ul>
        <?php /* <div class="ml15 bold"><a href="/help/index?statuces"><?=t('Статусы и как их менять')?></a></div><? */ ?>
    </div>
<?php } ?>

<?php if (session::has_credential('admin')) { ?>
    <div class="column_head mt10" style="cursor: pointer; display: none" onclick="Application.ShowHide('functions')">
        <div class="left">*<?= t('По функции') ?></div>
        <div class="right mt5 icoupicon <?= request::get_int(
                'function'
        ) > 0 ? '' : 'hide' ?>" style="cursor: pointer;" id="functions_on"></div>
        <div class="right mt5 icodownt <?= !request::get_int(
                'function'
        ) ? '' : 'hide' ?>" style="cursor: pointer;" id="functions_off"></div>
    </div>
    <div class="p10 box_content <?= request::get_int('function') > 0 ? '' : 'hide' ?>" id="functions">
        <ul class="mb5 ">
            <?php
            $functions[1]   = t('Член Политсовета');
            $functions[23]  = t('Член Президии Политического Совета');
            $functions[2]   = t('Член ЦКРК');
            $functions[113] = t('Глава РПО');
            $functions[112] = t('Глава МПО');
            $functions[111] = t('Глава ППО');
            $functions[123] = t('Секретарь РПО');
            $functions[122] = t('Секретарь МПО');
            $functions[121] = t('Секретарь ППО');
            $functions[5]   = t('Координатор развития региона');
            $functions[6]   = t('Координатор развития района');
            $functions[22]  = t('Работник Секретариата');
            $functions[24]  = t('Член Редколлегии');
            $functions[14]  = t('Представитель в ВУЗах');
            $functions[18]  = t('Логистический координатор');
            //$functions=user_auth_peer::get_functions();

            foreach ($functions as $function_id => $function_title) { ?>
                <li><a href="/people?function=<?= $function_id ?>" style="<?= $function_id == request::get_int(
                            'function'
                    ) ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>margin: 1px;"><?= $function_title ?></a>
                </li>
            <?php } ?>

            <li>
                <a href="#"><?= t('Експерт н.р.') ?></a></li>
            <li><a href="#"><?= t('Експерт р.р.') ?></a></li>
            <li><a href="#"><?= t('Експерт м.р.') ?></a>
            </li>
        </ul>
    </div>
<?php } ?>




<?php if (session::has_credential('admin')) { ?>
    <div class="column_head mt10" style="cursor: pointer" onclick="Application.ShowHide('regions')">
        <div class="left">*<?= t('По региону') ?></div>
        <div class="right mt5 icoupicon <?= (request::get_int('region') || $cur_status > 0 || request::get_int(
                        'meritokrat'
                ) || request::get_int('expert') || request::get(
                        'offline'
                )) ? '' : 'hide' ?>" id="regions_on" style="cursor: pointer;"></div>
        <div class="right mt5 icodownt <?= !(request::get_int('region') || $cur_status > 0 || request::get_int(
                        'meritokrat'
                ) || request::get_int('expert') || request::get(
                        'offline'
                )) ? '' : 'hide' ?>" id="regions_off" style="cursor: pointer;"></div>
    </div>
    <div class="p10 box_content <?= (request::get_int('region') || $cur_status > 0 || request::get_int(
                    'meritokrat'
            ) || request::get_int('expert') || request::get('offline')) ? '' : 'hide' ?>" id="regions">
        <?php $all_regions = geo_peer::instance()->get_regions(1);
        foreach ($all_regions as $region_id => $title) {

            $bind['region_id'] = $region_id;
            if (request::get_int('status') && 10 == request::get_int('status')) {
                $count_users = db::get_scalar(
                        'SELECT count(user_id) FROM user_data WHERE region_id=:region_id AND user_id IN (SELECT id FROM user_auth WHERE status>='.request::get_int(
                                'status'
                        ).')',
                        $bind
                );
            } else {
                if (request::get_int('status')) {
                    $count_users = db::get_scalar(
                            'SELECT count(user_id) FROM user_data WHERE region_id=:region_id AND user_id IN (SELECT id FROM user_auth WHERE status='.request::get_int(
                                    'status'
                            ).')',
                            $bind
                    );
                } else {
                    $bind['active'] = 1;
                    $count_users    = db::get_scalar(
                            'SELECT count(user_id) FROM user_data WHERE region_id=:region_id AND user_id IN (SELECT id FROM user_auth WHERE active=:active '.$sqladd.')',
                            $bind
                    );
                }
            }
            $az_regions[]                                 = [
                    'id'    => $region_id,
                    'title' => $title,
                    'count' => $count_users,
            ];
            $rate_regions[$count_users.($region_id % 10)] = [
                    'id'    => $region_id,
                    'title' => $title,
                    'count' => $count_users,
            ];
            ksort($rate_regions);
        }
        ?>
        <div class="fs11 left ml10">
            <a href="javascript:;" id="az" class="areg hide">Назва &#9660;</a><a href="javascript:;" id="za" class="areg">Назва &#9650;</a>
        </div>
        <div class="fs11 right mr15">
            <a href="javascript:;" id="rate" class="areg hide">Рейтинг &#9650;</a><a href="javascript:;" id="unrate" class="areg">Рейтинг &#9660;</a>
        </div>
        <br>

        <ul class="mb5 dreg" id="ul_az">
            <?php foreach ($az_regions as $region) { ?>
                <li>
                    <a href="/people?region=<?= $region['id'].$url['status'] ?>" <?= (request::get_int(
                                    'region'
                            ) == $region['id']) ? 'class="bold"' : '' ?>
                            style="margin: 1px;"><?= $region['title'] ?></a>
                    <div class="cbrown right fs11 bold"><?= $region['count'] ?></div>
                </li>
            <?php } ?>
        </ul>

        <ul class="mb5 dreg hide" id="ul_za">
            <?php rsort($az_regions);
            foreach ($az_regions as $region) { ?>
                <li>
                    <a href="/people?region=<?= $region['id'].$url['status'] ?>" <?= (request::get_int(
                                    'region'
                            ) == $region['id']) ? 'class="bold"' : '' ?>
                            style="margin: 1px;"><?= $region['title'] ?></a>
                    <div class="cbrown right fs11 bold"><?= $region['count'] ?></div>
                </li>
            <?php } ?>
        </ul>

        <ul class="mb5 dreg hide" id="ul_unrate">
            <?php foreach ($rate_regions as $region_count => $region) { ?>
                <li>
                    <a href="/people?region=<?= $region['id'].$url['status'] ?>" <?= (request::get_int(
                                    'region'
                            ) == $region['id']) ? 'class="bold"' : '' ?>
                            style="margin: 1px;"><?= $region['title'] ?></a>
                    <div class="cbrown right fs11 bold"><?= $region['count'] ?></div>
                </li>
            <?php } ?>
        </ul>

        <ul class="mb5 dreg hide" id="ul_rate">
            <?php
            foreach (array_reverse($rate_regions) as $region_count => $region) { ?>
                <li>
                    <a href="/people?region=<?= $region['id'].$url['status'] ?>" <?= (request::get_int(
                                    'region'
                            ) == $region['id']) ? 'class="bold"' : '' ?>
                            style="margin: 1px;"><?= $region['title'] ?></a>
                    <div class="cbrown right fs11 bold"><?= $region['count'] ?></div>
                </li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>

<?php if (session::has_credential('admin') || count($lists) > 0) { ?>
    <div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('list')">
        <div class="left">*<?= t('Списки участников') ?></div>
        <div class="right mt5 icoupicon <?= request::get_int(
                'list'
        ) > 0 ? '' : 'hide' ?>" style="cursor: pointer;" id="list_on"></div>
        <div class="right mt5 icodownt <?= !request::get_int(
                'list'
        ) ? '' : 'hide' ?>" style="cursor: pointer;" id="list_off"></div>
    </div>
    <div class="p10 box_content <?= request::get_int('list') > 0 ? '' : 'hide' ?>" id="list">
        <ul class="mb5 ">
            <?php foreach ($lists as $l) { ?><?php $item = lists_peer::instance()->get_item($l) ?>
                <li><a href="/people?list=<?= $l ?>" style="<?= $l == request::get_int(
                            'list'
                    ) ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>margin: 1px;"><?= $item['title'] ?></a>
                </li>
            <?php } ?>
        </ul>
        <?php if (session::has_credential('admin') || count($own_lists) > 0 || count($edit_lists) > 0) { ?>
            <a href="/lists" class="fs12 ml10"><?= t('Редактировать') ?> &rarr;</a>
        <?php } ?>
    </div>
<?php } ?>

<?php if (session::has_credential('admin')) { ?>
    <div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('function')">
        <div class="left">*<?= t('По целевой группе') ?></div>
        <div class="right mt5 icoupicon <?= request::get_int(
                'target'
        ) > 0 ? '' : 'hide' ?>" style="cursor: pointer;" id="function_on"></div>
        <div class="right mt5 icodownt <?= !request::get_int(
                'target'
        ) ? '' : 'hide' ?>" style="cursor: pointer;" id="function_off"></div>
    </div>
    <div class="p10 box_content <?= request::get_int('target') > 0 ? '' : 'hide' ?>" id="function">
        <ul class="mb5 ">
            <?php
            $targets = user_helper::get_targets();
            foreach ($targets as $k => $t) {
                $rait_targets[db::get_scalar(
                        'SELECT count(*) 
                                FROM user_data WHERE target && :target',
                        ['target' => '{'.$k.'}']
                )] = ["function_id" => $k, "function_title" => $t];
            }
            krsort($rait_targets);
            foreach ($rait_targets as $count => $data) { ?>
                <li>
                    <a class="fs12" href="/people?target=<?= $data['function_id'] ?>" style="<?= $data['function_id'] == request::get_int(
                            'target'
                    ) ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>margin: 1px;"><?= $data['function_title'] ?></a>
                    <div class="cbrown right fs11 bold"><?= $count ?></div>
                </li>
            <?php }
            unset($rait_targets); ?>
        </ul>
    </div>
<?php } ?>

<?php if (session::has_credential('admin')) { ?>
    <div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('adminfunction')">
        <div class="left">*<?= t('По целевой группе') ?></div>
        <div class="right mt5 icoupicon <?= request::get_int(
                'admintarget'
        ) > 0 ? '' : 'hide' ?>" style="cursor: pointer;" id="adminfunction_on"></div>
        <div class="right mt5 icodownt <?= !request::get_int(
                'admintarget'
        ) ? '' : 'hide' ?>" style="cursor: pointer;" id="adminfunction_off"></div>
    </div>
    <div class="p10 box_content <?= request::get_int('admintarget') > 0 ? '' : 'hide' ?>" id="adminfunction">
        <ul class="mb5 ">
            <?php
            $targets = user_helper::get_targets();
            foreach ($targets as $k => $t) {
                $rait_targets[db::get_scalar(
                        'SELECT count(*) 
                                FROM user_data WHERE admin_target && :admintarget',
                        ['admintarget' => '{'.$k.'}']
                )] = ["function_id" => $k, "function_title" => $t];
            }
            krsort($rait_targets);
            foreach ($rait_targets as $count => $data) { ?>
                <li>
                    <a class="fs12" href="/people?admintarget=<?= $data['function_id'] ?>" style="<?= $data['function_id'] == request::get_int(
                            'admintarget'
                    ) ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>margin: 1px;"><?= $data['function_title'] ?></a>
                    <div class="cbrown right fs11 bold"><?= $count ?></div>
                </li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>

<?php if (session::has_credential('admin')) { ?>
    <script type="text/javascript">
        $(document).ready(function () {
            var sortedListHead = $('#sort_head'),
                    sortedListBody = $(sortedListHead).next();
            $(sortedListHead).click(function () {
                setTimeout(function () {
                    $.cookie(
                            'sorted_list:' + $(this).attr('data-user-id') + ':visible',
                            sortedListBody.is(':visible'),
                    );
                }, 1000);
            });
            sortedListBody.css('display',
                    $.cookie('sorted_list:' + $(sortedListHead).attr('data-user-id') + ':visible') !== true
                            ? 'none'
                            : 'block',
            );
        });
    </script>

    <div class="mt10">
        <div class="column_head" style="cursor: pointer;" onclick="Application.ShowHide('sort')" id="sort_head" data-user-id="<?= session::get_user_id(
        ) ?>">
            <div class="left">*<?= t('Сортировка') ?></div>
            <div class="right mt5 icoupicon <?= "close" == $_COOKIE[session::get_user_id(
            )."_sort_list"] ? "hide" : "" ?>" style="cursor: pointer;" id="sort_on"></div>
            <div class="right mt5 icodownt <?= "open" == $_COOKIE[session::get_user_id(
            )."_sort_list"] ? "hide" : "" ?>" style="cursor: pointer;" id="sort_off"></div>
        </div>
        <div class="p10 box_content" id="sort" style="max-height: 565px; overflow:scroll">
            <ul class="mb5 sort" id="sort_buffer">
                <?php foreach ($sortable_list as $user) { ?>
                    <li data-user-id='<?= $user ?>'><?= user_helper::full_name($user, true, ['class' => 'bold']) ?>
                        <a style="float: right; cursor: pointer" class="del_sort" data-user-id='<?= $user ?>'>X</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
<?php } ?>

<?php if (session::has_credential('admin')) { ?>
    <a href="?chronology=old_to_recent">
        <div class="column_head mt10" style="cursor: pointer">
            <div class="left">*<?= t('По посещениям') ?></div>
        </div>
    </a><!--	<div class="p10 box_content hide" id="chronology">--><!--		<ul class="mb5">--><!--			<li><a href="?chronology=old_to_recent">От недавних</a></li>--><!--			<li><a href="?chronology=recent_to_old">От давних</a></li>--><!--			<li><a href="?chronology=none">Не сортировать</a></li>--><!--		</ul>-->
    <?php /* <div class="ml15 bold"><a href="/help/index?statuces"><?=t('Статусы и как их менять')?></a></div><? */ ?>
    <!--	</div>-->
<?php } ?>

<script type="text/javascript">
    $(document).ready(function () {
        $('.areg').click(function () {
            $('.areg').removeClass('bold');
            var id = this.id;
            $(this).hide();
            if (id == 'az') {
                $('#za').show();
                $('#za').addClass('bold');
            } else if (id == 'za') {
                $('#az').show();
                $('#az').addClass('bold');
            } else if (id == 'unrate') {
                $('#rate').show();
                $('#rate').addClass('bold');
            } else if (id == 'rate') {
                $('#unrate').show();
                $('#unrate').addClass('bold');
            }

            $('.dreg').hide();
            $('#ul_' + id).show();
        });
    });
</script>
<?php if (session::has_credential('admin')) { ?>
    <h1 style="cursor: pointer;" class="column_head mt10">
        <a style="display: block;" href="/search?map=1&distance=10&submit=1">* <?= t('Кто рядом') ?></a>
    </h1>
<?php } ?>