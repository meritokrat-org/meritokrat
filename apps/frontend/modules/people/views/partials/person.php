<?php load::model('ppo/members') ?>
<?php $user_data = user_data_peer::instance()->get_item($id); ?>
<?php $user_auth = user_auth_peer::instance()->get_item($id); ?>
<?php $last_visit = user_sessions_peer::instance()->last_visit($id); ?>

<?php if ($locationlat) { ?>
    <?php $distance = user_data_peer::instance()->get_distance($locationlat, $locationlng, $id); ?>
<?php } ?>
<div class="box_content p10 mb10"
     style="<?php if (!$user_auth["active"] or $user_auth["suslik = 1"]) { ?>background: #FFF8D3;<?php } ?>">
    <div class="minimize" style="display: none">
        <?= user_helper::full_name($id, true, ['class' => 'bold']) ?><a style="float: right; cursor: pointer"
                                                                        class="del_sort" data-user-id='<?= $id ?>'>X</a>
    </div>

    <div class="maximize d-flex flex-row">
        <div style="width: 80px;">
            <?= user_helper::photo($id, 't', ['class' => 'border1 left']) ?>
        </div>

        <div style="width: 224px;">
            <div>
                <?php if ($last_visit) { ?>
                    <!--<img class="left" style="padding-top: 2px; padding-right: 4px" alt="online" src="/static/images/common/user_online.png" />-->
                <?php } ?>
                <?= user_helper::full_name($id, true, ['class' => 'text-dark bold']) ?>
            </div>

            <div class="fs11">
                <?php $collection = [] ?>

                <?php if (user_auth_peer::getStatus(
                        $user_auth['status_functionary'],
                        user_auth_peer::STATUS_TYPE_FUNCTIONARY
                )) {
                    $collection[] = user_auth_peer::getStatus(
                            $user_auth['status_functionary'],
                            user_auth_peer::STATUS_TYPE_FUNCTIONARY
                    );
                } ?>

                <?php if (user_auth_peer::getStatus(
                        $user_auth['status_politician'],
                        user_auth_peer::STATUS_TYPE_POLITICIAN
                )) {
                    $collection[] = user_auth_peer::getStatus(
                            $user_auth['status_politician'],
                            user_auth_peer::STATUS_TYPE_POLITICIAN
                    );
                } ?>

                <?php if (user_auth_peer::getStatus($user_auth['status'])) {
                    $collection[] = user_auth_peer::getStatus($user_auth['status']);
                } ?>

                <?= implode('<span style="padding: 0 1px">/</span>', $collection) ?>
            </div>

            <?php $row = db::get_row(
                    'select p.id, p.title, pm.function from ppo_members pm join ppo p on p.id = pm.group_id where pm.user_id = :userId limit 1;',
                    ['userId' => $user_auth['id']]
            ) ?>
            <?php if ($row) { ?>
                <div class="fs11">
                    <a href="<?= sprintf(
                            '/ppo%s/1',
                            $row['id']
                    ) ?>"><?= $row['title'] ?></a><?= $row['function'] > 0 ? ', '.t(
                                    ppo_members_peer::FUNCTIONS[$row['function']]
                            ) : '' ?>
                </div>
            <?php } ?>

            <?php load::model('geo') ?>
            <?php if ($user_data['country_id']) { ?>
                <?php $country = geo_peer::instance()->get_country($user_data['country_id']) ?>
                <?php $geoParts = [
                        [
                                'href'  => sprintf('/search?submit=1&country=%s', $user_data['country_id']),
                                'text'  => $country['name_'.translate::get_lang()],
                                'style' => 'padding-right: 1px',
                        ],
                ] ?>
                <?php if ($user_data['region_id']) {
                    $region     = geo_peer::instance()->get_region($user_data['region_id']);
                    $geoParts[] = [
                            'href'  => sprintf(
                                    '/search?submit=1&country=%s&region=%s',
                                    $user_data['country_id'],
                                    $user_data['region_id']
                            ),
                            'text'  => $region['name_'.translate::get_lang()],
                            'style' => 'padding: 0 1px',
                    ];
                } ?>
                <?php if ($user_data['city_id']) {
                    $city       = geo_peer::instance()->get_city($user_data['city_id']);
                    $geoParts[] = [
                            'href'  => sprintf(
                                    '/search?submit=1&country=%s&region=%s&city=%s',
                                    $user_data['country_id'],
                                    $user_data['region_id'],
                                    $user_data['city_id']
                            ),
                            'text'  => $city['name_'.translate::get_lang()],
                            'style' => 'padding-left: 1px',
                    ];
                } ?>
                <div class="fs11">
                    <?= implode(
                            '/',
                            array_map(
                                    static function ($part) {
                                        return <<<HTML
<a href="{$part['href']}" style="{$part['style']}">{$part['text']}</a>
HTML;
                                    },
                                    $geoParts
                            )
                    ) ?>
                </div>
            <?php } ?>

            <div class="fs11">
                <?php if (request::get_int('function') > 0) { ?>
                    <?php load::model('user/user_desktop'); ?>
                    <?php $user_desktop = user_desktop_peer::instance()->get_item($id); ?>
                    <?php if ($user_desktop['user_id'] === 5) { ?>
                        <?php $echo_functions[$id][] = t('Глава (Лидер) Партии'); ?>
                    <?php } ?>
                    <?php foreach (user_auth_peer::get_functions() as $function_id => $function_title) { ?>
                        <?php if (in_array(
                                $function_id,
                                explode(',', str_replace(['{', '}'], ['', ''], $user_desktop['functions']))
                        )) { ?>
                            <?php $echo_functions[$id][] = $function_title; ?>
                        <?php } ?>
                    <?php } ?>
                    <?= implode(', ', $echo_functions[$id]) ?>
                <?php } else { ?>
                    <?php load::model('user/user_work') ?>
                    <?php $user_work = user_work_peer::instance()->get_item($id) ?>
                    <?php if ($user_work['work_name']) { ?>
                        <?= stripslashes(htmlspecialchars($user_work['work_name'])) ?>,
                    <?php } ?>
                    <?php if ($user_work['position']) { ?>
                        <?= stripslashes(htmlspecialchars($user_work['position'])) ?>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>

        <div class="fs11" style="width: 140px; text-align: right;">
            <?php if (session::has_credential('admin')) { ?>
                <div>
                    <?php if (strpos($last_visit, "img") === false) { ?>
                        <?= $last_visit ?>
                    <?php } ?>
                </div>
            <?php } ?>
            <?php if (session::has_credential('admin')) { ?>
                <?php $last_visit = user_sessions_peer::instance()->get_item($id); ?>
                <div>* останнє: <?= (int) (($last_visit['visit_ts'] - $last_visit['start']) / 60)." хв." ?></div>
                <div>*
                    усього: <?= (int) (db_key::i()->get('all_time_'.$id) / 3600)." год., ".(int) ((db_key::i()->get(
                                            'all_time_'.$id
                                    ) % 3600) / 60)." хв." ?></div>
                <div>* відвідувань: <?= (int) db_key::i()->get('all_visits_'.$id) ?></div>
            <?php } ?>
            <div class="bord pt5 m0"><b><?= ($distance) ? user_helper::convert_distance($distance) : '' ?></b></div>
            <?php if (request::get_int('list') && (session::has_credential('admin') || (in_array(
                                            session::get_user_id(),
                                            $own_lists
                                    ) || in_array(session::get_user_id(), $edit_lists)))) { ?>
                <div>
                    <a href="javascript:;" class="delfromlist" rel="<?= $id ?>"><?= t('Удалить из списка') ?></a>
                </div>
            <?php } ?>
            <?php if (request::get('bookmark')) { ?>
                <?php if (request::get('bookmark') == 6) { ?>
                    <?php $bkm = bookmarks_peer::instance()->is_bookmarked(
                            session::get_user_id(),
                            6,
                            $user_data['user_id']
                    ); ?>
                    <a class="right fs10"
                       style="<?= ($bkm) ? 'display:none;' : 'display:block;' ?>width:70px;text-align:center;margin-right:4px;"
                       href="#add_bookmark"
                       onclick="Application.bookmarkThisItem(this,'6','<?= $user_data['user_id'] ?>');return false;"><b></b><span><?= t(
                                    'Добавить в любимые авторы'
                            ) ?></span></a>
                    <a class="right fs10"
                       style="<?= ($bkm) ? 'display:block;' : 'display:none;' ?>width:70px;text-align:center;margin-right:4px;"
                       href="#del_bookmark"
                       onclick="Application.unbookmarkThisItem(this,'6','<?= $user_data['user_id'] ?>');return false;"><b></b><span><?= t(
                                    'Удалить из любимых авторов'
                            ) ?></span></a>
                <?php } else { ?>
                    <?php $bkm = bookmarks_peer::instance()->is_bookmarked(
                            session::get_user_id(),
                            2,
                            $user_data['user_id']
                    ); ?>
                    <a class="bookmark mb10 ml5 right" style="<?= ($bkm) ? 'display:none;' : 'display:block;' ?>"
                       href="#add_bookmark"
                       onclick="Application.bookmarkThisItem(this,'2','<?= $user_data['user_id'] ?>');return false;"><b></b><span><?= t(
                                    'В закладки'
                            ) ?></span></a>
                    <a class="unbkmrk mb10 ml5 right" style="<?= ($bkm) ? 'display:block;' : 'display:none;' ?>"
                       href="#del_bookmark"
                       onclick="Application.unbookmarkThisItem(this,'2','<?= $user_data['user_id'] ?>');return false;"><b></b><span><?= t(
                                    'Удалить из закладок'
                            ) ?></span></a>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>
