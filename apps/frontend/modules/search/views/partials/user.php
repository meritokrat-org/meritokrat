<div class="minimize" style="display: none;">
    <?= user_helper::full_name($id, true, ['class' => 'bold']) ?><a
            style="display: none; float: right; cursor: pointer" class="del_sort" data-user-id='<?= $id ?>'>X</a>
</div>

<div class="maximize">
    <?php
    $user_desktop   = user_desktop_peer::instance()->get_functions($id);
    $user_functions = explode(',', str_replace(['{', '}'], ['', ''], $user_desktop['functions']));
    $user_auth_data = user_auth_peer::instance()->get_item($id);
    ?>

    <div style="width: 70px; float: left">
        <?= call_user_func(
                static function ($userId) {
                    return user_helper::photo(
                            $userId,
                            't',
                            ['class' => 'border1 mb5 mr10', 'align' => 'left', 'style' => 'width:60px;'],
                            true
                    );
                },
                $id
        ) ?>
        <div style="clear: both"></div>
    </div>

    <?php if (session::has_credential('admin') ||
            in_array(request::get('region'), (array) $is_regional_coordinator, true) ||
            in_array(request::get('city'), (array) $is_raion_coordinator, true)) { ?>
        <?=call_user_func(
                static function ($fullName, $isActive) {
                    if($isActive) {
                        $fullName = sprintf('<b>%s</b>', $fullName);
                    }

                    return $fullName;
        }, user_helper::full_name($id), true === $user_auth_data['active']
        )?>
        <div><?= user_helper::geo($id, true) ?></div>
        <?php if (request::get_int('map') && $map_data[$id] > 0) {
            echo '<p class="plm bord pt5 m0"><b>';
            echo user_helper::convert_distance($map_data[$id]);
            echo '</b></p>';
        } ?>

        <?php $functions = user_auth_peer::get_functions() ?>
        <?php load::model('ppo/ppo'); ?>
        <?php $flag = true; ?>
        <?php foreach ($functions as $fid => $ft) { ?>
            <?php if (in_array($fid, [111, 112, 113], true)) { ?>
                <?php $level = $fid - 110; ?>
                <?php if (!$ppo = ppo_peer::instance()->get_user_ppo($user_desktop['user_id'], $level)) { ?>
                    <?php // if($ppo['title'] != ''){ ?>
                    <?php $flag = false; ?>
                <?php } ?>
            <?php } ?>
        <?php } ?>

        <?php foreach ($functions as $function_id => $function_title) { ?>
            <?php if (in_array($function_id, $user_functions, true) && !request::get_int('map')) {
                $c1 = 0;
                $c2 = 0;
                $c3 = 0;
                $c4 = 0; ?>

                <?php // if(session::get_user_id() == 11752){ ?>
                <?php if (in_array($function_id, [111, 112, 113])) { ?>
                    <?php $level = $function_id - 110; ?>
                    <?php load::model('ppo/ppo'); ?>
                    <?php $ppo = ppo_peer::instance()->get_user_ppo($user_desktop['user_id'], $level); ?>
                    <p class="plm"><?= $function_title ?> - <a href="/ppo<?= $ppo['id'] ?>/"><?= $ppo['title'] ?></a>
                    </p>
                <?php } ?>
                <?php // } ?>

                <?php if (in_array($function_id, [5, 9]) && $flag) {
                    $rgs = user_desktop_peer::instance()->is_regional_coordinator($user_desktop['user_id']);
                    $c1  = 0;
                    if (is_array($rgs)) {
                        foreach ($rgs as $rs) {
                            ?>
                            <? if ($f_region = geo_peer::instance()->get_region($rs)) {
                                ?>
                                <?= ($c1 == 0) ? '<p class="plm">'.t($function_title).'</p>' : '' ?>
                                <p class="plm">
                                    <?= $f_region['name_'.translate::get_lang()] ?>
                                </p>
                                <? $c1++;
                            }
                        }
                    }
                }
                if (in_array($function_id, [6, 10]) && $flag) {
                    $rns = user_desktop_peer::instance()->is_raion_coordinator($user_desktop['user_id']);
                    $c2  = 0;
                    if (is_array($rns)) {
                        foreach ($rns as $rn) {
                            if ($f_raion = geo_peer::instance()->get_city($rn)) {
                                ?>
                                <?= ($c2 == 0) ? '<p class="plm">'.t($function_title).'</p>' : '' ?>
                                <p class="plm">
                                    <?= $f_raion['region_name_'.translate::get_lang()] ?> /
                                    <?= $f_raion['name_'.translate::get_lang()] ?>
                                </p>
                                <?php $c2++;
                            }
                        }
                    }
                } ?>

                <?php if ($function_id == 18) {
                    $rgs = user_desktop_peer::instance()->is_logistic_coordinator($user_desktop['user_id']);
                    if (is_array($rgs)) {
                        foreach ($rgs as $rn) {
                            if ($f_region = geo_peer::instance()->get_region($rn)) {
                                ;
                            } ?>
                            <?= ($c3 == 0) ? '<p class="plm">'.t($function_title).'</p>' : '' ?>
                            <p class="plm">
                                <?= $f_region['name_'.translate::get_lang()] ?>
                            </p>
                            <? $c3++;
                        }
                    }
                    $rns = user_desktop_peer::instance()->is_logistic_coordinator($user_desktop['user_id'], 'city');
                    if ($rns[0] > 0) {
                        foreach ($rns as $rn) {
                            if ($rn == 0) {
                                continue;
                            }
                            if ($f_raion = geo_peer::instance()->get_city($rn)) {
                                ;
                            } ?>
                            <?= ($c4 == 0) ? '<p class="plm">'.t($function_title).'</p>' : '' ?>
                            <p class="plm">
                                <?= $f_raion['region_name_'.translate::get_lang()] ?> /
                                <?= $f_raion['name_'.translate::get_lang()] ?>
                            </p>
                            <? $c4++;
                        }
                    }
                }
                ?>

            <?php }
        } ?>
        <?php if ($user_auth_data['offline'] || $user_auth_data['del']) { ?>
            <br/><span class="fs11">*<?= t('Офф-лайн') ?></span>
            <?php if ($user_auth_data['del']) { ?>
                <?php if ($user_auth_data['del'] == $id) { ?>
                    <span class="fs11">&nbsp;(<?= t('Самоудаление') ?>)</span>
                <?php } else { ?>
                    <span class="fs11">&nbsp;(<?= t('Удален') ?>)</span>
                <?php } ?>
            <?php } ?>
        <?php } else { ?>
            <p class="plm bold"><?= $user_auth_data['id'] == 5 ? t('Глава (Лидер) Партии') : user_auth_peer::get_status(
                                $user_auth_data['status'],
                                $user_auth_data['ban']
                        ).user_auth_peer::get_hidden_type($user_auth_data['hidden_type'], $user_auth_data['type']); ?>
            </p>
            <?php
        }
    } else { ?>
        <?= user_helper::full_name($id) ?>
        <?php if (request::get_int('map') && $map_data[$id] > 0) {
            echo '<p class="plm bord pt5 m0"><b>';
            echo user_helper::convert_distance($map_data[$id]);
            echo '</b></p>';
        } ?>
        <p class="plm bold"><?= $user_auth_data['id'] == 5 ? t('Глава (Лидер) Партии') : user_auth_peer::get_status(
                            $user_auth_data['status'],
                            $user_auth_data['ban']
                    ).user_auth_peer::get_hidden_type($user_auth_data['hidden_type'], $user_auth_data['type']); ?>
        </p>
        <?php
        #повтор
        foreach (user_auth_peer::get_functions() as $function_id => $function_title) { ?>
            <?php if (in_array($function_id, $user_functions) && !request::get_int('map')) { ?>
                <?php
                if (in_array($function_id, [5, 9])) {
                    $rgs = user_desktop_peer::instance()->is_regional_coordinator($user_desktop['user_id']);
                    $c1  = 0;
                    if (is_array($rgs)) {
                        foreach ($rgs as $rs) {
                            ?>
                            <? if ($f_region = geo_peer::instance()->get_region($rs)) {
                                ?>
                                <?= ($c1 == 0) ? '<p class="plm">'.t($function_title).'</p>' : '' ?>
                                <p class="plm">
                                    <?= $f_region['name_'.translate::get_lang()] ?>
                                </p>
                                <? $c1++;
                            }
                        }
                    }
                }
                if (in_array($function_id, [6, 10])) {
                    $rns = user_desktop_peer::instance()->is_raion_coordinator($user_desktop['user_id']);
                    $c2  = 0;
                    if (is_array($rns)) {
                        foreach ($rns as $rn) {
                            if ($f_raion = geo_peer::instance()->get_city($rn)) {
                                ?>
                                <?= ($c2 == 0) ? '<p class="plm">'.t($function_title).'</p>' : '' ?>
                                <p class="plm">
                                    <?= $f_raion['region_name_'.translate::get_lang()] ?> /
                                    <?= $f_raion['name_'.translate::get_lang()] ?>
                                </p>
                                <? $c2++;
                            }
                        }
                    }
                }
                if ($function_id == 18) {
                    $rns = user_desktop_peer::instance()->is_logistic_coordinator($user_desktop['user_id'], 'city');
                    $c3  = 0;
                    if (is_array($rns)) {
                        foreach ($rns as $rn) {
                            if ($f_raion = geo_peer::instance()->get_city($rn)) {
                                ?>
                                <?= ($c3 == 0) ? '<p class="plm">'.t($function_title).'</p>' : '' ?>
                                <p class="plm">
                                    <?= $f_raion['region_name_'.translate::get_lang()] ?> /
                                    <?= $f_raion['name_'.translate::get_lang()] ?>
                                </p>
                                <?php $c3++;
                            }
                        }
                    }
                } ?>

            <?php }
        } ?>
    <?php } ?>
</div>
