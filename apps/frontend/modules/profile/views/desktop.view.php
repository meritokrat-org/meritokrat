`<? $people_recomended = user_auth_peer::instance()->get_all_recomended_by_user($user_desktop['user_id']);
$people_attracted      = user_auth_peer::instance()->get_all_recomended_by_user($user_desktop['user_id'], true);
$user_desktop['user_id'] ? $user_desktop['user_id'] = $user_desktop['user_id'] : $user_desktop['user_id'] = 0;
$recommend_m      = db::get_cols(
    'SELECT user_id FROM user_recommend WHERE recommending_user_id='.$user_desktop['user_id'].' and status=10'
);
$recommend_member = db::get_cols(
    'SELECT user_id FROM user_recommend WHERE recommending_user_id='.$user_desktop['user_id'].' and status=20'
);

?>

<style type="text/css">
    .desktop_panel a {
        -moz-border-radius: 12px 12px 0 0;
        -webkit-border-radius: 12px 12px 0 0;
        border-radius: 12px 12px 0 0;
        background: url("/static/images/common/spritebuttons.png") repeat-x scroll 0 -246px transparent;
        text-decoration: none;
        padding: 6px 12px 2px 12px;
        margin-left: 1px;
        margin-bottom: -4px;

    }

    .desktop_panel_election a {
        background: url("/static/images/common/spritebuttons.png") repeat-x scroll 0 -246px transparent;
    }

    .desktop_panel a.selected {
        color: #FFCC66;
        display: block;
        float: left;
        font-weight: bold;
        padding: 6px 12px 2px 12px;
    }

    .tab_pane {
        margin-bottom: 15px;
        margin-top: 25px;
        background: none;
    }

    .content_recomended {
        height: <?=((count($people_recomended)+1)*20)>280 ? 280 : ((count($people_recomended)+1)*20)?>px;
        margin: 10px;
        overflow: auto;
    }

    .content_recommend_m {
        height: <?=((count($recommend_m)+1)*20)>280 ? 280 : ((count($recommend_m)+1)*20)?>px;
        margin: 10px;
        overflow: auto;
    }

    .content_recommend_member {
        height: <?=((count($recommend_member)+1)*20)>280 ? 280 : ((count($recommend_member)+1)*20)?>px;
        margin: 10px;
        overflow: auto;
    }

    .content_attr {
        height: <?=((count($people_attracted)+1)*20)>280 ? 280 : ((count($people_attracted)+1)*20)?>px;
        margin: 10px;
        overflow: auto;
    }

    #ui-datepicker-div {
        display: none;
    }
</style>
<div class="profile row" style="color: black">
    <div class="col-3">
        <? include __DIR__.'/partials/profile.nav.php' ?>
    </div>

    <div class="col ml-0 pl-0">
        <h1 class="mb5 fs28" style="color:#660000;">
            <a href="/profile-<?= $user_data['user_id'] ?>" style="text-decoration:none;
           "><?= stripslashes(htmlspecialchars($user_data['first_name'].' '.$user_data['last_name'])) ?></a> - <?= t(
                'Рабочий стол'
            ) ?>
            <? load::model('user/user_sessions');
            $user_functions = explode(',', str_replace(array('{', '}'), array('', ''), $user_desktop['functions']));
            ?>
        </h1>
        <div style="color: gray; margin-top: -15px;" class="right fs11 mr5 mb10"><?= user_sessions_peer::instance(
            )->last_visit($user_data['user_id']) ?></div>

        <? // include 'partials/profile.head.php' ?>
        <? if (user_auth_peer::instance()->get_rights(
            $this->user['id'],
            1
        )) { //user_auth_peer::instance()->get_rights($this->user['id'], 10) && $user['desktop']!=1?>
            <div class="fs11 border1 p10" style="color: #333333; font-weight: normal;margin-top: 25px;"><?= t(
                    'У вас пока нет рабочего стола. <br>Чтобы завести рабочий стол и начать в нем работать'
                ) ?> <a href="/profile/edit"><?= t('измените свой статус на "Меритократ"') ?></a></div>
        <? } else { ?>
            <div id="desktop_info_text" class="fs11 border1 hide p10"
                 style="color: #333333; font-weight: normal;margin-top: 25px;"><?= t(
                    'Информация, отраженная в рабочем столе, является объективным комплексным показателем Вашей активности и эффективности как члена команды. От эффективности вашей работы будет зависеть развитие Вашей партийной карьеры, поэтому оперативно вносите соответствующую информацию в свой рабочий стол.'
                ) ?></div>

            <div id="desktop_panels_box" style="<?= (session::has_credential(
                'admin'
            )) ? 'margin-bottom:52px;' : 'margin-bottom:26px;' ?>margin-top: 25px;" class="tab_pane">
                <div style="margin-top: 0px;">
                    <!-- <div class="desktop_panel desktop_panel_election">
                    <a href="javascript:;" id="tab_election" class="tab_menu <?= (request::get_string(
                            'tab'
                        ) and request::get_string('tab') != 'election') ? '' : ' selected' ?>" rel="election">
                        <?= t('Выборы') ?>
                    </a>
                </div> -->

                    <div class="desktop_panel">
                        <a href="javascript:;" id="tab_information"
                           class="tab_menu<?= (request::get_string('tab') == 'information') ? ' selected' : '' ?>"
                           rel="information">
                            <?= t('Основное') ?>
                        </a>
                    </div>

                    <div class="desktop_panel">
                        <a href="javascript:;" id="tab_info"
                           class="tab_menu<?= request::get_string('tab') == 'info' ? ' selected' : '' ?>" rel="info">
                            <?= t('Агитация') ?>
                        </a>
                    </div>

                    <div class="desktop_panel">
                        <a href="javascript:;" id="tab_naglyadka"
                           class="tab_menu<?= request::get_string('tab') == 'naglyadka' ? ' selected' : '' ?>"
                           rel="naglyadka">
                            <?= t('Наглядная агитация') ?>
                        </a>
                    </div>

                    <div class="desktop_panel">
                        <a href="javascript:;" id="tab_people"
                           class="tab_menu<?= request::get_string('tab') == 'people' ? ' selected' : '' ?>"
                           rel="people">
                            <?= t('Люди') ?>
                        </a>
                    </div>

                    <div class="desktop_panel">
                        <a href="javascript:;" id="tab_tasks"
                           class="tab_menu<?= request::get_string('tab') == 'tasks' ? ' selected' : '' ?>" rel="tasks">
                            <?= t('Задания') ?>
                        </a>
                    </div>

                    <!--  <div class="desktop_panel"><a href="javascript:;" id="tab_help" class="tab_menu<?= request::get_string(
                        'tab'
                    ) == 'help' ? ' selected' : '' ?>" rel="help"><?= t('Помощь') ?></a></div>-->

                </div>
                <div style="margin-top: 26px; margin-bottom: 0px;">

                    <? //if (count($user_desktop_meeting)) { ?>
                    <div class="desktop_panel">
                        <a href="javascript:;" id="tab_meetings"
                           class="tab_menu<?= request::get_string('tab') == 'meetings' ? ' selected' : '' ?>"
                           rel="meetings">
                            <?= t('Мероприятия Игоря Шевченка') ?>
                        </a>
                    </div>
                    <? // } ?>

                    <? //if (count($user_desktop_education)) { ?>
                    <div class="desktop_panel">
                        <a href="javascript:;" id="tab_educations"
                           class="tab_menu<?= request::get_string('tab') == 'educations' ? ' selected' : '' ?>"
                           rel="educations">
                            <?= t('Обучение') ?>
                        </a>
                    </div>
                    <? //} ?>

                    <? //if (count($user_desktop_event)) { ?>
                    <div class="desktop_panel"><a href="javascript:;" id="tab_events"
                                                  class="tab_menu<?= request::get_string(
                                                      'tab'
                                                  ) == 'event' ? ' selected' : '' ?>"
                                                  rel="events"><?= t('Другие мероприятия') ?></a></div><? //} ?>

                    <div class="desktop_panel"><a href="javascript:;" id="tab_groups"
                                                  class="tab_menu<?= request::get_string(
                                                      'tab'
                                                  ) == 'groups' ? ' selected' : '' ?>"
                                                  rel="groups"><?= t('В сообществах') ?></a></div>

                    <? //if ($user_desktop['other']) { ?>
                    <div class="desktop_panel"><a href="javascript:;" id="tab_other"
                                                  class="tab_menu<?= request::get_string(
                                                      'tab'
                                                  ) == 'other' ? ' selected' : '' ?>"
                                                  rel="other"><?= t('Другое') ?></a></div><? //} ?>


                </div>

                <? if (session::has_credential('admin') || $coordinator) { ?>
                    <div class="desktop_panel" style="margin-top: 52px; margin-bottom: 0px;">
                        <a href="javascript:;" id="tab_contacts"
                           class="tab_menu<?= request::get_string('tab') == 'contacts' ? ' selected' : '' ?>"
                           rel="contacts"><?= t('Контакты') ?></a>
                    </div>
                <? } ?>
                <? if (session::has_credential('admin') || $has_access || $has_confidence) { ?>
                    <div class="desktop_panel" style="margin-top: 52px; margin-bottom: 0px;">
                        <a href="javascript:;" id="tab_payments"
                           class="tab_menu<?= request::get_string('tab') == 'payments' ? ' selected' : '' ?>"
                           rel="payments"><?= t('Взносы') ?></a>
                    </div>
                <? } ?>
                <? if (session::has_credential('admin')) { ?>
                    <div class="desktop_panel" style="margin-top: 52px; margin-bottom: 0px;">
                        <a href="javascript:;" id="tab_ban"
                           class="tab_menu<?= request::get_string('tab') == 'ban' ? ' selected' : '' ?>"
                           rel="ban">*<?= t('Санкции') ?></a>
                    </div>
                <? } ?>
                <? if (session::has_credential('admin') || $rigts = user_auth_peer::instance()->get_rights(
                        session::get_user_id(),
                        20
                    )) { ?>
                    <div class="desktop_panel" style="margin-top: 52px; margin-bottom: 0px;">
                        <a href="javascript:;" id="tab_membership"
                           class="tab_menu<?= request::get_string('tab') == 'membership' ? ' selected' : '' ?>"
                           rel="membership"><?= t('Членство') ?></a>
                    </div>
                <? } ?>

                <div class="clear"></div>
            </div>
            <div class="clear"></div>

        <? // if(in_array(request::get_int("id"), array(11752, 2))){ ?>
        <? include "partials/desktop/election.php" ?>
        <? // } ?>


            <script type="text/javascript">
                $(document).ready(function () {
                    <? // if( ! in_array(request::get_int("id"), array(11752, 2))){ ?>
//			$("#tab_information").addClass("selected");
//			$("#pane_information").show();
                    <? // } ?>
                });
            </script>


            <div class="content_pane mt10" id="pane_information"
                 style="line-height:110%;<?= (request::get_string('tab') != 'information') ? 'display: none;' : '' ?>">
                <table width="100%" class="fs12 " style="color:black">
                    <? if (is_array($user_functions) or $user_desktop['user_id'] == 5) { ?>
                        <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                            <td class="bold cbrown" width="35%"><?= t('Функции') ?></td>
                            <td class="fs11 aright">
                                <? if (session::has_credential('admin')) { ?>
                                    <a class="cbrown"
                                       href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>"><?= t(
                                            'Редактировать'
                                        ) ?> &rarr;</a>
                                <? } ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                        </tr>
                        <? if ($user_desktop['user_id'] == 5) { ?>
                            <tr>
                                <td class="cblack" colspan="2"><?= t('Глава (Лидер) Партии') ?></td>
                            </tr>
                        <? } ?>
                        <? $skip = array(); ?>
                        <? foreach (user_auth_peer::get_functions() as $function_id => $function_title) { ?>
                            <? unset($level); ?>
                            <? if (in_array($function_id, $user_functions) && !in_array($function_id, $skip)) { ?>
                                <tr>
                                    <td><? //$function_id." :: "?><?= $function_title ?></td>
                                    <td>
                                        <? switch ($function_id) {
                                            case 5:
                                            case 9:
                                                array_push($skip, 6, 10, 18);
                                                $tmp_arr = user_desktop_peer::instance()
                                                    ->is_regional_coordinator($user_desktop['user_id']);
                                                if (is_array($tmp_arr)) {
                                                    foreach ($tmp_arr as $item) {
                                                        if ($rg = geo_peer::instance()->get_region($item)) { ?>
                                                            <div>
                                                                <a href="/search?submit=1&country=1&region=<?= $rg['id'] ?>"><?= $rg['name_'.translate::get_lang(
                                                                    )] ?></a>
                                                            </div>
                                                        <? }
                                                    }
                                                }
                                                break;

                                            case 6:
                                            case 10:
                                                array_push($skip, 18);
                                                $tmp_arr = user_desktop_peer::instance()
                                                    ->is_raion_coordinator($user_desktop['user_id']);
                                                if (is_array($tmp_arr)) {
                                                    foreach ($tmp_arr as $item) {
                                                        if ($rn = geo_peer::instance()->get_city($item)) { ?>
                                                            <div>
                                                                <a href="/search?submit=1&country=1&region=<?= $rn['region_id'] ?>"><?= $rn['region_name_'.translate::get_lang(
                                                                    )] ?></a>
                                                                /
                                                                <a href="/search?submit=1&country=1&region=<?= $rn['region_id'] ?>&city=<?= $rn['id'] ?>"><?= $rn['name_'.translate::get_lang(
                                                                    )] ?></a>
                                                            </div>
                                                        <? }
                                                    }
                                                }
                                                break;

                                            case 18:
                                                $tmp_arr = user_desktop_peer::instance()
                                                    ->is_logistic_coordinator($user_desktop['user_id']);
                                                if (is_array($tmp_arr)) {
                                                    foreach ($tmp_arr as $item) {
                                                        if ($rn = geo_peer::instance()->get_region($item)) { ?>
                                                            <div>
                                                                <a href="/search?submit=1&country=1&region=<?= $item ?>"><?= $rn['name_'.translate::get_lang(
                                                                    )] ?></a>
                                                            </div>
                                                        <? }
                                                    }
                                                }
                                                $tmp_arr = user_desktop_peer::instance()
                                                    ->is_logistic_coordinator($user_desktop['user_id'], 'city');
                                                if (is_array($tmp_arr) && $tmp_arr[0] > 0) {
                                                    foreach ($tmp_arr as $item) {
                                                        if ($item == 0) {
                                                            continue;
                                                        }
                                                        if ($rn = geo_peer::instance()->get_city($item)) { ?>
                                                            <div>
                                                                <a href="/search?submit=1&country=1&region=<?= $rn['region_id'] ?>"><?= $rn['region_name_'.translate::get_lang(
                                                                    )] ?></a>
                                                                /
                                                                <a href="/search?submit=1&country=1&region=<?= $rn['region_id'] ?>&city=<?= $rn['id'] ?>"><?= $f_raion['name_'.translate::get_lang(
                                                                    )] ?></a>
                                                            </div>
                                                        <? }
                                                    }
                                                }
                                                break;

                                            case 111:
                                            case 121:
                                                $level = 1;
                                            case 112:
                                            case 122:
                                                if (!$level) {
                                                    $level = 2;
                                                }
                                            case 113:
                                            case 123:
                                                if (!$level) {
                                                    $level = 3;
                                                }
                                                load::model("ppo/ppo");
                                                $ppo = ppo_peer::get_user_ppo($user_desktop['user_id'], $level);
                                                ?>
                                                <div>
                                                    <a href="/ppo<?= $ppo["id"] ?>/"><?= $ppo["title"] ?></a>
                                                </div>
                                                <? break;

                                            default:
                                                break;
                                        } ?>
                                    </td>
                                </tr>
                            <? } ?>
                        <? } ?>
                    <? } ?>
                    <? // if ( $user_desktop['information_people_count']) { ?>
                    <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                        <td class="bold cbrown" width="80%"><a
                                    href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=people"><?= t(
                                    'Люди'
                                ) ?></a></td>
                        <td class="fs11 aright"><? if (session::has_credential('admin') or session::get_user_id(
                                ) == $user_desktop['user_id']) { ?>
                                <a class="cbrown"
                                   href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=peoples"><?= t(
                                        'Редактировать'
                                    ) ?> &rarr;</a> <? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <? //if ( $user_desktop['people_recommended']) { ?>
                    <tr>
                        <td class="" width="80%"><?= t('Пригласил в сеть') ?></td>
                        <td class="cbrown">
                            <a class="cbrown"
                               href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=people&recomended=1"><?= count(
                                    user_auth_peer::instance()->get_all_recomended_by_user($user_desktop['user_id'])
                                )//$user_desktop['people_recommended']     ?></a>
                        </td>
                    </tr>
                    <? // } ?>

                    <? //if ( $user_desktop['people_attracted']) { ?>
                    <tr>
                        <td class="" width="80%"><?= t('Присоединились к сети') ?></td>
                        <td class="cbrown">
                            <a href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=people&attracted=1"
                               class="cbrown"><?= count(
                                    user_auth_peer::instance()->get_all_recomended_by_user(
                                        $user_desktop['user_id'],
                                        true
                                    )
                                )//$user_desktop['people_attracted']     ?></a>
                        </td>
                    </tr>
                    <? if (user_auth_peer::get_rights(session::get_user_id(), 10)) { ?>
                        <tr>
                            <td class="" width="80%"><?= t('Рекомендовал в "Меритократы"') ?></td>
                            <td class="cbrown">
                                <a class="cbrown"
                                   href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=people&recommend_m=1"><?= count(
                                        $recommend_m
                                    ) ?></a>
                            </td>
                        </tr>

                        <tr>
                            <td class="" width="80%"><?= t('Рекомендовал в члены команды Игоря Шевченка') ?></td>
                            <td class="cbrown">
                                <a href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=people&recommend_member=1"
                                   class="cbrown"><?= count($recommend_member) ?></a>
                            </td>
                        </tr>
                    <? } ?>

                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                        <td class="bold cbrown" width="35%" rel="info"><a
                                    href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=information"><?= t(
                                    'Агитация'
                                ) ?></a>
                        </td>
                        <td class="fs11 aright"><? if (session::has_credential('admin') or session::get_user_id(
                                ) == $user_desktop['user_id']) { ?>
                                <a class="cbrown"
                                   href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=information"><?= t(
                                        'Редактировать'
                                    ) ?> &rarr;</a> <? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class=""><b><?= t('Проинформировано людей') ?></b></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td width="35%"><?= t('Во время личных встреч') ?></td>
                        <td class="cbrown">
                            <a class="cbrown"><?= intval($user_desktop['information_people_private_count']) ?></a>
                        </td>
                    </tr>
                    <tr>
                        <td width="35%"><?= t('Телефонными звонками') ?></td>
                        <td class="cbrown">
                            <a class="cbrown"><?= intval($user_desktop['information_people_phone_count']) ?></a>
                        </td>
                    </tr>
                    <tr>
                        <td width="35%"><?= t('Электронными письмами') ?></td>
                        <td class="cbrown">
                            <a class="cbrown"><?= intval($user_desktop['information_people_email_count']) ?></a>
                        </td>
                    </tr>
                    <tr>
                        <td width="35%"><?= t('В социальных сетях') ?></td>
                        <td>
                            <?= intval($user_desktop['information_people_social_count']) ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="35%"><?= t('Всего') ?></td>
                        <td class="cbrown">
                            <a class="cbrown bold"><?= ($user_desktop['information_people_private_count'] + $user_desktop['information_people_phone_count'] + $user_desktop['information_people_email_count'] + $user_desktop['information_people_social_count']) ?></a>
                        </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="bold" width="35%" rel="info"><?= t('Агитационные материалы Игоря Шевченка') ?></td>
                        <td class="fs11 aright"></td>
                    </tr>

                    <tr>
                        <td class="" width="35%"><?= t('Получил') ?></td>
                        <td class="cbrown">
                            <a class="cbrown"><?= $agitation['receive'] ?></a>
                        </td>
                    </tr>
                    <? if (@in_array(18, $user_functions)) { ?>
                        <tr>
                            <td class="" width="35%"><?= t('Передал') ?></td>
                            <td class="cbrown">
                                <a class="cbrown"><?= $agitation['given'] ?></a>
                            </td>
                        </tr>
                    <? } ?>
                    <tr>
                        <td class="" width="35%"><?= t('Вручил') ?></td>
                        <td class="cbrown">
                            <a class="cbrown"><?= $agitation['presented'] ?></a>
                        </td>
                    </tr>
                    <tr>
                        <td class="" width="35%"><?= t('Осталось') ?></td>
                        <td class="cbrown">
                            <? $agittotal = $agitation['receive'] - $agitation['given'] - $agitation['presented'] ?>
                            <a class="cbrown">
                                <?= ($agittotal > 0) ? $agittotal : 0 ?>
                            </a>
                        </td>
                    </tr>

                    <? /* if (count(unserialize($user_desktop['information_banners']))>0 && if_array(unserialize($user_desktop['information_banners']))) { ?>
            <tr>
                    <td class=" bold"><?=t('Баннеры')?></td>
                    <td class="cbrown bold"><a href="/profile/desktop?id=<?=$user_desktop['user_id']?>&tab=info"><?=count(unserialize($user_desktop['information_banners']))?></a></td>
		<?/*<tr>
				<td class="bold"></td>
                                <td>
            <? foreach (unserialize($user_desktop['information_banners']) as $banner) {     ?>

                                   <a href="<?=$banner['url']?>"><?=stripslashes(htmlspecialchars($banner['title']))?></a><br>
                                   <span class="cgray fs11"><?=stripslashes(htmlspecialchars($banner['description']))?></span>
            <?  } ?>
                    </td>
                    </tr>
            <? } */ ?>

                    <? /* if (count(unserialize($user_desktop['information_publications']))>0) {?>

                        <tr>
                                <td class="bold" width="35%" rel="info"><?=t('Публикации')?></td>
                                <td class="cbrown bold"><a href="/profile/desktop?id=<?=$user_desktop['user_id']?>&tab=info"><?=count(unserialize($user_desktop['information_publications']))?></a></td>
                        </tr>
                        <?/*<tr>
				<td class="bold"></td>
                                <td>
                         <?
                         $temp_publications=unserialize($user_desktop['information_publications']);
                         foreach ($temp_publications as $temp_publication) $publications[strtotime($temp_publication['date'])]=$temp_publication;
                         asort($publications);
                         foreach ($publications as $key=>$publication) { ?>
			    <div class="mb5">
                                    <b style="font-size:100%" class="cbrown"><?=$publication['url'] ? '<a href="'.$publication['url'].'">' : ''?> <?=$publication['title']?><?=$publication['url'] ? '</a>' : ''?></b><?=($publication['type'] and $publication['type']!=16) ? ', <span class="cgray fs12">'.user_desktop_peer::get_publication_type($publication['type']).'</span>, ' : ''?>
                            <span class="cgray fs12"><?=$publication['media_name'] ? $publication['media_name'].', ' : ''?><?=$publication['date']?></span><br/>
                                    <span class="cgray fs11"><?=$publication['description'] ? stripslashes(htmlspecialchars($publication['description'])).'<br/>' : ''?></span>
			    </div>
            <?   } ?>
                                </td>
			</tr>
            <? }*/ ?>

                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                        <td class="bold cbrown" width="35%" rel="info"><a
                                    href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=naglyadka"><?= t(
                                    'Наглядная агитация'
                                ) ?></a>
                        </td>
                        <td class="fs11 aright"><? if (session::has_credential('admin') or session::get_user_id(
                                ) == $user_desktop['user_id']) { ?>
                                <a class="cbrown"
                                   href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=photo"><?= t(
                                        'Редактировать'
                                    ) ?> &rarr;</a> <? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <? $user_desktop_avtonumbers = unserialize($user_desktop['information_avtonumbers_photos']); ?>
                    <? if (count($user_desktop_avtonumbers)) { ?>
                        <tr>
                            <td class="bold" width="35%"><?= t('Авторамки') ?></td>
                            <td class="cbrown bold">
                                <a href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=naglyadka"><?= count(
                                        $user_desktop_avtonumbers
                                    ) ?></a>
                            </td>
                        </tr>
                    <? } ?>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                        <td class="bold cbrown" width="35%" rel="info"><a
                                    href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=tasks"><?= t(
                                    'Задания'
                                ) ?></a>
                        </td>
                        <td class="fs11 aright"><? if (session::has_credential('admin') or session::get_user_id(
                                ) == $user_desktop['user_id']) { ?>
                                <a class="cbrown"
                                   href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=tasks"><?= t(
                                        'Редактировать'
                                    ) ?> &rarr;</a> <? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <? if (count($user_desktop_signature_fact) > 0) {
                        foreach ($user_desktop_signature_fact as $signature) {
                            $plan += $signature['fact'];
                        }
                        ?>
                        <tr>
                            <td class="bold" width="35%"><?= t('Сбор подписей') ?></td>
                            <td class="cbrown bold">
                                <a href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=tasks"><?= $plan ?></a>
                            </td>
                        </tr>
                    <? } ?>

                    <? if (count($user_desktop_meeting)) { ?>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                            <td class="bold cbrown" width="35%"><a
                                        href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=meetings"><?= t(
                                        'Мероприятия Игоря Шевченка'
                                    ) ?></a>
                            </td>
                            <td class="fs11 aright"><? if (session::has_credential('admin') or session::get_user_id(
                                    ) == $user_desktop['user_id']) { ?>
                                    <a class="cbrown"
                                       href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=meetings"><?= t(
                                            'Редактировать'
                                        ) ?> &rarr;</a> <? } ?>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="bold"><?= t('Организовал') ?></td>
                            <td class="bold">
                                <? $i = 0;
                                foreach ($user_desktop_meeting as $meeting) {
                                    if ($meeting['part'] == 0) {
                                        $i++;
                                    }
                                }
                                ?>
                                <a href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=meetings"><?= $i ?></a>
                            </td>
                        </tr>
                        <? /* foreach ($user_desktop_meeting as $meeting) {
                      if ($meeting['part']==0) {?>
                    	<tr>
                            <td></td>
                            <td>
                                <a href="javascript:;" id="meeting_<?=$meeting_i?>" class="meeting_title bold cbrown"><?=stripslashes(htmlspecialchars($meeting['title']))?></a>, <?=$meeting['meeting_date']?> <br/>
                                <span id="meeting_<?=$meeting_i?>_description" class="cgray hide fs11"><?=stripslashes(htmlspecialchars($meeting['description']))?></span>
                            </td>
			</tr>
                        <? $meeting_i++; } } */ ?>

                        <? if (is_array($user_desktop_meeting_confirm)) { ?>
                            <tr>
                                <td class="bold"><?= t('Посетил') ?></td>
                                <td class="bold">
                                    <a href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=meetings"><?= count(
                                            $user_desktop_meeting_confirm
                                        ) ?></a>
                                </td>
                            </tr>
                            <? /*foreach ($user_desktop_meeting_confirm as $meeting) { ?>
                            <tr>
                                <td></td>
                                <td>
                                    <a href="javascript:;" id="meeting_<?=$meeting_i?>" class="meeting_title bold cbrown"><?=stripslashes(htmlspecialchars($meeting['title']))?></a>, <?=$meeting['meeting_date']?><br/>
                                    <span id="meeting_<?=$meeting_i?>_description" class="cgray hide fs11"><?=stripslashes(htmlspecialchars($meeting['description']))?></span>
                                </td>
                            </tr>
                            <? $meeting_i++; } */ ?>
                        <? } ?>

                        <? if (session::has_credential('admin') && is_array($user_desktop_meeting_decline)) { ?>
                            <tr>
                                <td class="bold">*<?= t('Не посетил') ?></td>
                                <td class="bold">
                                    <a href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=meetings"><?= count(
                                            $user_desktop_meeting_decline
                                        ) ?></a>
                                </td>
                            </tr>
                            <? /*foreach ($user_desktop_meeting_decline as $meeting) { ?>
                            <tr>
                                <td></td>
                                <td>
                                    <a href="javascript:;" id="meeting_<?=$meeting_i?>" class="meeting_title bold cbrown"><?=stripslashes(htmlspecialchars($meeting['title']))?></a>, <?=$meeting['meeting_date']?><br/>
                                    <span id="meeting_<?=$meeting_i?>_description" class="cgray hide fs11"><?=stripslashes(htmlspecialchars($meeting['description']))?></span>
                                </td>
                            </tr>
                            <? $meeting_i++; } */ ?>
                        <? } ?>

                    <? } ?>
                    <? if (count($user_desktop_event)) { ?>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                            <td class="bold cbrown" width="35%"><a
                                        href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=events"><?= t(
                                        'Другие мероприятия'
                                    ) ?></a>
                            </td>
                            <td class="fs11 aright"><? if (session::has_credential('admin') or session::get_user_id(
                                    ) == $user_desktop['user_id']) { ?>
                                    <a class="cbrown"
                                       href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=events"><?= t(
                                            'Редактировать'
                                        ) ?> &rarr;</a> <? } ?>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="bold"><?= t('Организовал') ?></td>
                            <td class="bold">
                                <? $i = 0;
                                foreach ($user_desktop_event as $meeting) {
                                    if ($meeting['part'] == 0) {
                                        $i++;
                                    }
                                }
                                ?> <a href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=event"><?= $i ?></a>
                            </td>
                        </tr>
                        <? /*foreach ($user_desktop_event as $meeting) {
                      if ($meeting['part']==0) {?>
                    	<tr>
                            <td></td>
                            <td>
                                <a href="javascript:;" id="meeting_<?=$meeting_i?>" class="meeting_title bold cbrown"><?=stripslashes(htmlspecialchars($meeting['title']))?></a>, <?=$meeting['event_date']?> <br/>
                                <span id="meeting_<?=$meeting_i?>_description" class="cgray hide fs11"><?=stripslashes(htmlspecialchars($meeting['description']))?></span>
                            </td>
			</tr>
                        <? $meeting_i++; } }*/ ?>


                        <tr>
                            <td class="bold"><?= t('Выступил') ?></td>
                            <td class="bold">
                                <? $i = 0;
                                foreach ($user_desktop_event as $meeting) {
                                    if ($meeting['part'] == 2) {
                                        $i++;
                                    }
                                }
                                ?> <a href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=event"><?= $i ?></a>
                            </td>
                        </tr>
                        <? foreach ($user_desktop_event as $meeting) {
                            if ($meeting['part'] == 2) { ?>
                                <tr>
                                    <td></td>
                                    <td>
                                        <a href="javascript:;" id="meeting_<?= $meeting_i ?>"
                                           class="meeting_title bold cbrown"><?= stripslashes(
                                                htmlspecialchars($meeting['title'])
                                            ) ?></a>, <?= $meeting['event_date'] ?>
                                        <br/>
                                        <span id="meeting_<?= $meeting_i ?>_description"
                                              class="cgray hide fs11"><?= stripslashes(
                                                htmlspecialchars($meeting['description'])
                                            ) ?></span>
                                    </td>
                                </tr>
                                <? $meeting_i++;
                            }
                        } ?>


                        <tr>
                            <td class="bold"><?= t('Посетил') ?></td>
                            <td class="bold">
                                <? $i = 0;
                                foreach ($user_desktop_event as $meeting) {
                                    if ($meeting['part'] == 1) {
                                        $i++;
                                    }
                                }
                                ?> <a href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=event"><?= $i ?></a>
                            </td>
                        </tr>
                        <? /* foreach ($user_desktop_event as $meeting) {
                      if ($meeting['part']==1) {  ?>
                    	<tr>
                            <td></td>
                            <td>
                                <a href="javascript:;" id="meeting_<?=$meeting_i?>" class="meeting_title bold cbrown"><?=stripslashes(htmlspecialchars($meeting['title']))?></a>, <?=$meeting['event_date']?> <br/>
                                <span id="meeting_<?=$meeting_i?>_description" class="cgray hide fs11"><?=stripslashes(htmlspecialchars($meeting['description']))?></span>
                            </td>
			</tr>
                        <? $meeting_i++; } } */ ?>
                    <? } ?>

                    <? if (count($user_desktop_education)) { ?>

                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                            <td class="bold cbrown" width="35%"><a
                                        href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=educations"><?= t(
                                        'Обучение'
                                    ) ?></a>
                            </td>
                            <td class="fs11 aright"><? if (session::has_credential('admin') or session::get_user_id(
                                    ) == $user_desktop['user_id']) { ?>
                                    <a class="cbrown"
                                       href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=educations"><?= t(
                                            'Редактировать'
                                        ) ?> &rarr;</a> <? } ?>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="bold"><?= t('Провел') ?></td>
                            <td class="bold">
                                <? $i = 0;
                                foreach ($user_desktop_education as $meeting) {
                                    if ($meeting['part'] == 0) {
                                        $i++;
                                    }
                                }
                                ?>
                                <a href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=educations"><?= $i ?></a>
                            </td>
                        </tr>
                        <? /*foreach ($user_desktop_education as $meeting) {
                      if ($meeting['part']==0) { ?>
                    	<tr>
                            <td></td>
                            <td>
                                <a href="javascript:;" id="meeting_<?=$meeting_i?>" class="meeting_title bold cbrown"><?=stripslashes(htmlspecialchars($meeting['title']))?></a>, <?=$meeting['education_date']?> <br/>
                                <span id="meeting_<?=$meeting_i?>_description" class="cgray hide fs11"><?=stripslashes(htmlspecialchars($meeting['description']))?></span>
                            </td>
			</tr>
                        <? $meeting_i++; } } */ ?>


                        <tr>
                            <td class="bold"><?= t('Принял участие') ?></td>
                            <td class="bold">
                                <? $i = 0;
                                foreach ($user_desktop_education as $meeting) {
                                    if ($meeting['part'] == 1) {
                                        $i++;
                                    }
                                }
                                ?>
                                <a href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=educations"><?= $i ?></a>
                            </td>
                        </tr>
                        <? /*foreach ($user_desktop_education as $meeting) {
                      if ($meeting['part']==1) {  ?>
                    	<tr>
                            <td></td>
                            <td>
                                <a href="javascript:;" id="meeting_<?=$meeting_i?>" class="meeting_title bold cbrown"><?=stripslashes(htmlspecialchars($meeting['title']))?></a>, <?=$meeting['education_date']?> <br/>
                                <span id="meeting_<?=$meeting_i?>_description" class="cgray hide fs11"><?=stripslashes(htmlspecialchars($meeting['description']))?></span>
                            </td>
			</tr>
                        <? $meeting_i++; } }*/ ?>
                    <? } ?>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="pl5 pt5 mb5 pb5 fs11" style="background: #F7F7F7">
                            <? if (session::has_credential('admin') or session::get_user_id(
                                ) == $user_desktop['user_id']) { ?>
                                <a style="color:gray;"
                                   href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=information"><?= t(
                                        'Редактировать'
                                    ) ?> &rarr;</a> <? } ?>
                        </td>
                    </tr>


                    <!--           HELP            -->
                    <? if (session::has_credential('admin')) { ?>
                    <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                        <td class="bold cbrown" width="35%" rel="info"><a
                                    href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=help"><?= t(
                                    'Допомога'
                                ) ?></a>
                        </td>
                        <td class="fs11 aright"><? if (session::has_credential('admin') or session::get_user_id(
                                ) == $user_desktop['user_id']) { ?>
                                <a class="cbrown"
                                   href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=help"><?= t(
                                        'Редактировать'
                                    ) ?> &rarr;</a> <? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <? if (!empty($user_need_help)) { ?>
                        <tr>
                            <td><b><?= t('Нуждаюсь'); ?></b></td>
                            <td><?
                                foreach ($user_need_help as $key => $val) {
                                    echo sprintf('<a href=\'/profile/desktop?tab=help\'>%s</a>&nbsp;', $val);
                                }

                                ?></td>
                        </tr>
                    <? } ?>
                    <tr>
                        <td></td>
                    </tr>
                    <? if (!empty($user_provide_help)) { ?>
                        <tr>
                            <td><b><?= t("Предоставляю"); ?></b></td>
                            <td>
                                <? foreach ($user_provide_help as $key => $val) {
                                    echo "<a href='/profile/desktop?tab=help'>".$val."</a>&nbsp;";
                                }
                                ?>
                            </td>
                        </tr>
                    <? } ?>

                </table>
            </div>
        <? } ?>
            <!--       END HELP         -->


            <div class="content_pane <?= (request::get_string('tab') == 'naglyadka' || request::get_string(
                    'tab'
                ) == 'autonumbers' || request::get_string('tab') == 'avatarm' || request::get_string(
                    'tab'
                ) == 'magnet') ? '' : 'hide ' ?>mt10" id="pane_naglyadka" style="line-height:110%">
                <?
                if (count($user_desktop_avtonumbers) > 0) {
                    foreach ($user_desktop_avtonumbers as $number) {
                        if (!$number['type']) {
                            $number['type'] = 0;
                        }
                        $numbers[$number['type']][] = $number;
                    }
                }
                ?>
                <table width="100%" class="fs12" style="color:black">
                    <? $ucontacts = unserialize($user_desktop['avatarm']) ?>
                    <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                        <td class="bold cbrown" width="45%" rel="info"><?= t('Аватарки с "М" в соцсетях') ?></td>
                        <td class="fs11 aright"></td>
                    </tr>
                    <? if (is_array($ucontacts) && count($ucontacts) > 0) { ?>
                        <tr>
                            <td colspan="2" id="all_avtonumbers" style="padding: 10px 5px;">
                                <? @$check = array_values($ucontacts) ?>
                                <? foreach ($check as $item) {
                                    if ($item != '') {
                                        $flag = 1;
                                        break;
                                    }
                                } ?>
                                <? if ($flag == 1) { ?>
                                    <? foreach ($ucontacts as $type => $contact) {
                                        if ($contact) { ?>
                                            <? $contact = user_data_peer::i()->prepare_contact($contact, $type) ?>
                                            <a href="http://<?= conf::get('server') ?>/ooops/leave?href=<?= urlencode(
                                                stripslashes(htmlspecialchars($contact))
                                            ) ?>" onclick="Application.checkLink(this);return false;"
                                               rel="<?= stripslashes(
                                                   htmlspecialchars($contact)
                                               ) ?>"><?= tag_helper::image(
                                                    '/logos/'.$type.'.png',
                                                    array(
                                                        'class' => 'vcenter mr5',
                                                        'title' => user_data_peer::get_contact_type($type),
                                                    )
                                                ) ?></a>
                                        <? }
                                    }
                                } ?>
                            </td>
                        </tr>
                    <? } ?>
                    <? if (count($numbers[0]) > 0) { ?>
                        <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                            <td class="bold cbrown" width="35%" rel="info"><?= t('Авторамки') ?></td>
                            <td class="fs11 aright"></td>
                        </tr>
                        <tr>
                            <td colspan="2" id="all_avtonumbers">
                                <div id="photo_box">
                                    <? foreach ($numbers[0] as $avtonumber) { ?>
                                        <div style="width: 225px;"
                                             class="left thread box_empty p10 mb10 mr10 box_content">
                                            <?= tag_helper::image(
                                                'p/avtonumber/'.$user_desktop['user_id'].$avtonumber['salt'].'.jpg',
                                                array(
                                                    'alt'   => $avtonumber['description'],
                                                    'style' => 'cursor: pointer;',
                                                ),
                                                context::get('image_server')
                                            ); ?>
                                            <div class="fs11 cgray mr15">
                                                <?= $avtonumber['description'] ?>
                                            </div>
                                        </div>
                                    <? } ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                    <? } ?>

                    <? if (count($numbers[1]) > 0) { ?>
                        <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                            <td class="bold cbrown" width="35%" rel="info"><?= t('Магнит на машину') ?></td>
                            <td class="fs11 aright"></td>
                        </tr>
                        <tr>
                            <td colspan="2" id="all_avtonumbers">
                                <div id="photo_box">
                                    <? foreach ($numbers[1] as $avtonumber) { ?>
                                        <div style="width: 225px;"
                                             class="left thread box_empty p10 mb10 mr10 box_content">
                                            <?= tag_helper::image(
                                                'p/avtonumber/'.$user_desktop['user_id'].$avtonumber['salt'].'.jpg',
                                                array(
                                                    'alt'   => $avtonumber['description'],
                                                    'style' => 'cursor: pointer;',
                                                ),
                                                context::get('image_server')
                                            ); ?>
                                            <div class="fs11 cgray mr15">
                                                <?= $avtonumber['description'] ?>
                                            </div>
                                        </div>
                                    <? } ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                    <? } ?>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>

                    <? if (count($numbers[2]) > 0) { ?>
                        <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                            <td class="bold cbrown" width="35%" rel="info"><?= t('Другое') ?></td>
                            <td class="fs11 aright"></td>
                        </tr>
                        <tr>
                            <td colspan="2" id="all_avtonumbers">
                                <div id="photo_box">
                                    <? foreach ($numbers[2] as $avtonumber) { ?>
                                        <div style="width: 225px;"
                                             class="left thread box_empty p10 mb10 mr10 box_content">
                                            <?= tag_helper::image(
                                                'p/avtonumber/'.$user_desktop['user_id'].$avtonumber['salt'].'.jpg',
                                                array(
                                                    'alt'   => $avtonumber['description'],
                                                    'style' => 'cursor: pointer;',
                                                ),
                                                context::get('image_server')
                                            ); ?>
                                            <div class="fs11 cgray mr15">
                                                <?= $avtonumber['description'] ?>
                                            </div>
                                        </div>
                                    <? } ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                    <? } ?>

                    <tr>
                        <td colspan="2" class="pl5 pt5 mb5 pb5 fs11" style="background: #F7F7F7">
                            <? if (session::has_credential('admin') or session::get_user_id(
                                ) == $user_desktop['user_id']) { ?><a style="color:gray;"
                                                                      href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=photo"><?= t(
                                'Редактировать'
                            ) ?> &rarr;</a> <? } ?>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="content_pane <?= request::get_string('tab') == 'other' ? '' : 'hide ' ?>mt10" id="pane_other"
                 style="line-height:110%">
                <table width="100%" class="fs12" style="color:black">
                    <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                        <td class="bold cbrown" rel="info"><?= t('Другое') ?></td>
                    </tr>
                    <tr>
                        <td class="ml15"><?= $user_desktop['other'] ?></td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="pl5 pt5 mb5 pb5 fs11" style="background: #F7F7F7">
                            <? if (session::has_credential('admin') or session::get_user_id(
                                ) == $user_desktop['user_id']) { ?><a style="color:gray;"
                                                                      href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=other"><?= t(
                                'Редактировать'
                            ) ?> &rarr;</a> <? } ?>
                        </td>
                    </tr>
                </table>
            </div>

        <? if (session::has_credential('admin') || $coordinator){ ?>
            <div class="content_pane <?= request::get_string('tab') == 'contacts' ? '' : 'hide ' ?>mt10"
                 id="pane_contacts" style="line-height:110%">
                <table width="100%" class="fs12" style="color:black">
                    <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                        <td class="bold cbrown" rel="info"><?= t('Контакти') ?></td>
                    </tr>
                    <tr>
                        <td class="ml15">
                            <? if (is_array($user_contacts)) { ?>
                                <table style="color:black">
                                    <tr>
                                        <td class="cbrown"><?= t('Имя') ?></td>
                                        <td class="cbrown"><?= t('Тип') ?></td>
                                        <td class="cbrown"><?= t('Дата') ?></td>
                                        <td class="cbrown"><?= t('Содержание') ?></td>
                                    </tr>
                                    <? foreach ($user_contacts as $c) { ?>
                                        <tr>
                                            <td><?= user_helper::full_name($c['user_id'], true, array(), false) ?></td>
                                            <td><?= user_helper::get_contact_type($c['type']) ?></td>
                                            <td><?= date("d.m.Y", $c['date']) ?></td>
                                            <td>
                                                <a onclick="$('#description_<?= $c['user_id'] ?>').show();$(this).hide();">показати</a>
                                                <div class="hide"
                                                     id="description_<?= $c['user_id'] ?>"><?= $c['description'] ?></div>
                                            </td>
                                        </tr>
                                    <? } ?>
                                </table>
                            <? } else { ?>
                                <?= t('Контактiв не зроблено') ?>
                            <? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                </table>
            </div>
        <? } ?>

            <div class="<?= (request::get_string('tab') == 'people' || in_array(
                    request::get_string('tab'),
                    array('guest', 'supporters', 'meritokrats', 'mpu_members')
                )) ? '' : 'hide ' ?>content_pane mt10" id="pane_people" style="line-height:110%;">
                <table width="100%" class="fs12" style="color: black;">
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <? // if ( $user_desktop['people_recommended']) { ?>
                    <tr>
                        <td class="" width="35%"><?= t('Пригласил в сеть') ?></td>
                        <td class="cbrown">
                            <? if ($people_recomended) { ?><a id="show_recomended"><? } ?><?= count(
                                    $people_recomended
                                )//$user_desktop['people_recommended']    ?><? if ($people_recomended) { ?></a><? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div id="recomended" class="content_recomended <?= request::get_string(
                                'recomended'
                            ) ? '' : 'hide' ?> ml10 mr15">
                                <?
                                foreach ($people_recomended as $recomended_id) {
                                    $count_recomended++; ?>
                                    <div style="width:225px;height:30px;"
                                         class="<?= ($i == 1) ? 'right' : 'left' ?> p5">
                                        <?= user_helper::photo(
                                            $recomended_id,
                                            'sm',
                                            array('class' => 'border1 left mr10', 'style' => 'width:30px;'),
                                            true
                                        ) ?>
                                        <div class="ml10 fs12">
                                            <div>
                                                <? $member  = user_auth_peer::instance()->get_item($recomended_id);
                                                $memberdata = user_data_peer::instance()->get_item($recomended_id); ?>
                                                <?= $member['active'] == true ? '<b>' : '' ?><?= user_helper::full_name(
                                                    $recomended_id
                                                ) ?><?= $member['active'] ? '</b>' : '' ?><br>
                                                <? $city = geo_peer::instance()->get_region($memberdata['region_id']);
                                                echo $city['name_'.translate::get_lang()] ?>
                                            </div>
                                        </div>
                                    </div>
                                    <? ($i == 1) ? $i = 0 : $i++;
                                } ?>
                                <div class="clear"></div>
                            </div>

                        </td>
                    </tr>

                    <? // } ?>
                    <? /*  if ( $user_desktop['people_attracted']) { */ ?>
                    <tr>
                        <td class="" width="35%"><?= t('Присоединились к сети') ?></td>
                        <td class="cbrown">
                            <? if ($people_attracted) { ?><a class="pointer" id="show_attracted"><? } ?><?= count(
                                    $people_attracted
                                )//$user_desktop['people_attracted']    ?><? if ($people_attracted) { ?></a><? } ?>
                            <? /* <div id="attracted" class="<?=request::get_string('attracted') ? '' : 'hide '?>">
                        <?/* foreach ($people_attracted as $attracted_id)
                        { $count_attracted++; ?>
                                <div style="width:330px;height:32px;" class="left p5">
                                        <?=user_helper::photo($attracted_id, 'sm', array('class' => 'border1 left mr10','style'=>'width:30px;'), true)?>
                                        <div class="ml10 fs12">
                                                <div>
                                                        <b><?=user_helper::full_name($attracted_id)?></b>,<br>
                                                        <? $member = user_auth_peer::instance()->get_item($attracted_id);
                                                        $memberdata = user_data_peer::instance()->get_item($attracted_id);?>
                                                        <? $city = geo_peer::instance()->get_region($memberdata['region_id']);
                                                        echo $city['name_' . translate::get_lang()]?>
                                                </div>
                                        </div>
                                </div>
                        <? if ($count_attracted==19 && count($people_attracted)>20) { ?>
                            <a id="show_all_attracted" class="cgray fs12"><?=t('Остальные')?> <?=(count($people_attracted)-20)?></a>
                        </div>
                        <div id="all_attracted" class="hide">
                     <? } ?>
                        <? } ?>
                        </div><? */ ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div id="attracted" class="content_attr ml10 mr15 <?= (request::get_string(
                                    'attracted'
                                ) || request::get_string('guest')) ? '' : 'hide ' ?>">
                                <? foreach ($people_attracted as $attracted_id) {
                                    $count_attracted++; ?>
                                    <div style="width:225px;height:30px;"
                                         class="<?= ($i == 1) ? 'right' : 'left' ?> p5">
                                        <?= user_helper::photo(
                                            $attracted_id,
                                            'sm',
                                            array('class' => 'border1 left mr10', 'style' => 'width:30px;'),
                                            true
                                        ) ?>
                                        <div class="ml10 fs12">
                                            <div>
                                                <b><?= user_helper::full_name($attracted_id) ?></b>,<br>
                                                <? $member  = user_auth_peer::instance()->get_item($attracted_id);
                                                $memberdata = user_data_peer::instance()->get_item($attracted_id); ?>
                                                <? $city = geo_peer::instance()->get_region($memberdata['region_id']);
                                                echo $city['name_'.translate::get_lang()] ?>
                                            </div>
                                        </div>
                                    </div>
                                    <? ($i == 1) ? $i = 0 : $i++;
                                } ?>
                                <div class="clear"></div>
                            </div>
                        </td>
                    </tr>
                    <? // } ?>
                    <tr>
                        <td class="" width="35%"><?= t('Стали сторонниками') ?></td>
                        <?
                        $supporters = db::get_cols(
                            "SELECT id FROM user_auth WHERE active=TRUE AND status>=5 AND status<10 AND (recomended_by=:uid OR invited_by=:uid)",
                            array('uid' => $user_desktop['user_id'])
                        );
                        ?>
                        <td class="cbrown">
                            <? if ($supporters) { ?><a id="show_supporters"
                                                       onClick="showPeople('supporters')"><? } ?><?= count(
                                    $supporters
                                )//$user_desktop['people_recommended']    ?><? if ($supporters) { ?></a><? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div id="supporters" class="content_recomended <?= request::get_string(
                                'supporters'
                            ) ? '' : 'hide' ?> ml10 mr15">
                                <?
                                foreach ($supporters as $recomended_id) {
                                    $count_recomended++; ?>
                                    <div style="width:225px;height:30px;"
                                         class="<?= ($i == 1) ? 'right' : 'left' ?> p5">
                                        <?= user_helper::photo(
                                            $recomended_id,
                                            'sm',
                                            array('class' => 'border1 left mr10', 'style' => 'width:30px;'),
                                            true
                                        ) ?>
                                        <div class="ml10 fs12">
                                            <div>
                                                <? $member  = user_auth_peer::instance()->get_item($recomended_id);
                                                $memberdata = user_data_peer::instance()->get_item($recomended_id); ?>
                                                <?= $member['active'] == true ? '<b>' : '' ?><?= user_helper::full_name(
                                                    $recomended_id
                                                ) ?><?= $member['active'] ? '</b>' : '' ?><br>
                                                <? $city = geo_peer::instance()->get_region($memberdata['region_id']);
                                                echo $city['name_'.translate::get_lang()] ?>
                                            </div>
                                        </div>
                                    </div>
                                    <? ($i == 1) ? $i = 0 : $i++;
                                } ?>
                                <div class="clear"></div>
                            </div>

                        </td>
                    </tr>

                    <tr>
                        <td class="" width="35%"><?= t('Стали меритократами') ?></td>
                        <?
                        $meritokrats = db::get_cols(
                            "SELECT id FROM user_auth WHERE active=TRUE AND status>=10 AND status<20 AND (recomended_by=:uid OR invited_by=:uid)",
                            array('uid' => $user_desktop['user_id'])
                        );
                        ?>
                        <td class="cbrown">
                            <? if ($meritokrats) { ?><a id="show_meritokrats"
                                                        onClick="showPeople('meritokrats')"><? } ?><?= count(
                                    $meritokrats
                                )//$user_desktop['people_recommended']    ?><? if ($meritokrats) { ?></a><? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div id="meritokrats" class="content_recomended <?= request::get_string(
                                'meritokrats'
                            ) ? '' : 'hide' ?> ml10 mr15">
                                <?
                                foreach ($meritokrats as $recomended_id) {
                                    $count_recomended++; ?>
                                    <div style="width:225px;height:30px;"
                                         class="<?= ($i == 1) ? 'right' : 'left' ?> p5">
                                        <?= user_helper::photo(
                                            $recomended_id,
                                            'sm',
                                            array('class' => 'border1 left mr10', 'style' => 'width:30px;'),
                                            true
                                        ) ?>
                                        <div class="ml10 fs12">
                                            <div>
                                                <? $member  = user_auth_peer::instance()->get_item($recomended_id);
                                                $memberdata = user_data_peer::instance()->get_item($recomended_id); ?>
                                                <?= $member['active'] == true ? '<b>' : '' ?><?= user_helper::full_name(
                                                    $recomended_id
                                                ) ?><?= $member['active'] ? '</b>' : '' ?><br>
                                                <? $city = geo_peer::instance()->get_region($memberdata['region_id']);
                                                echo $city['name_'.translate::get_lang()] ?>
                                            </div>
                                        </div>
                                    </div>
                                    <? ($i == 1) ? $i = 0 : $i++;
                                } ?>
                                <div class="clear"></div>
                            </div>

                        </td>
                    </tr>


                    <tr>
                        <td class="" width="35%"><?= t('Стали членамы команды Игоря Шевченка') ?></td>
                        <?
                        $mpu_mem = db::get_cols(
                            "SELECT id FROM user_auth WHERE active=TRUE AND status>=20 AND (recomended_by=:uid OR invited_by=:uid)",
                            array('uid' => $user_desktop['user_id'])
                        );
                        ?>
                        <td class="cbrown">
                            <? if ($mpu_mem) { ?><a id="show_members" onClick="showPeople('members')"><? } ?><?= count(
                                    $mpu_mem
                                )//$user_desktop['people_recommended']    ?><? if ($mpu_mem) { ?></a><? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div id="members" class="content_recomended <?= request::get_string(
                                'mpu_members'
                            ) ? '' : 'hide' ?> ml10 mr15">
                                <?
                                foreach ($mpu_mem as $recomended_id) {
                                    $count_recomended++; ?>
                                    <div style="width:225px;height:30px;"
                                         class="<?= ($i == 1) ? 'right' : 'left' ?> p5">
                                        <?= user_helper::photo(
                                            $recomended_id,
                                            'sm',
                                            array('class' => 'border1 left mr10', 'style' => 'width:30px;'),
                                            true
                                        ) ?>
                                        <div class="ml10 fs12">
                                            <div>
                                                <? $member  = user_auth_peer::instance()->get_item($recomended_id);
                                                $memberdata = user_data_peer::instance()->get_item($recomended_id); ?>
                                                <?= $member['active'] == true ? '<b>' : '' ?><?= user_helper::full_name(
                                                    $recomended_id
                                                ) ?><?= $member['active'] ? '</b>' : '' ?><br>
                                                <? $city = geo_peer::instance()->get_region($memberdata['region_id']);
                                                echo $city['name_'.translate::get_lang()] ?>
                                            </div>
                                        </div>
                                    </div>
                                    <? ($i == 1) ? $i = 0 : $i++;
                                } ?>
                                <div class="clear"></div>
                            </div>

                        </td>
                    </tr>


                    <? if (user_auth_peer::get_rights(session::get_user_id(), 10)) {
                        $i = 0; ?>
                        <tr>
                            <td class="" width="35%"><?= t('Рекомендовал в "Меритократы"') ?></td>
                            <td class="cbrown">
                                <a id="show_recommend_m"><?= count($recommend_m) ?></a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div id="recommend_m" class="content_recommend_m ml10 mr15 <?= request::get_string(
                                    'recommend_m'
                                ) ? '' : 'hide ' ?>">
                                    <? foreach ($recommend_m as $attracted_id) { ?>
                                        <div style="width:225px;height:30px;"
                                             class="<?= ($i == 1) ? 'right' : 'left' ?> p5">
                                            <?= user_helper::photo(
                                                $attracted_id,
                                                'sm',
                                                array('class' => 'border1 left mr10', 'style' => 'width:30px;'),
                                                true
                                            ) ?>
                                            <div class="ml10 fs12">
                                                <div>
                                                    <b><?= user_helper::full_name($attracted_id) ?></b>,<br>
                                                    <? $member  = user_auth_peer::instance()->get_item($attracted_id);
                                                    $memberdata = user_data_peer::instance()->get_item(
                                                        $attracted_id
                                                    ); ?>
                                                    <? $city = geo_peer::instance()->get_region(
                                                        $memberdata['region_id']
                                                    );
                                                    echo $city['name_'.translate::get_lang()] ?>
                                                </div>
                                            </div>
                                        </div>
                                        <? ($i == 1) ? $i = 0 : $i++;
                                    }
                                    $i = 0; ?>
                                    <div class="clear"></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="" width="35%"><?= t('Рекомендовал в члены команды Игоря Шевченка') ?></td>
                            <td class="cbrown">
                                <a id="show_recommend_member"><?= count($recommend_member) ?></a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div id="recommend_member"
                                     class="content_recommend_member ml10 mr15 <?= request::get_string(
                                         'recommend_member'
                                     ) ? '' : 'hide ' ?>">
                                    <? foreach ($recommend_member as $attracted_id) { ?>
                                        <div style="width:225px;height:30px;"
                                             class="<?= ($i == 1) ? 'right' : 'left' ?> p5">
                                            <?= user_helper::photo(
                                                $attracted_id,
                                                'sm',
                                                array('class' => 'border1 left mr10', 'style' => 'width:30px;'),
                                                true
                                            ) ?>
                                            <div class="ml10 fs12">
                                                <div>
                                                    <b><?= user_helper::full_name($attracted_id) ?></b>,<br>
                                                    <? $member  = user_auth_peer::instance()->get_item($attracted_id);
                                                    $memberdata = user_data_peer::instance()->get_item(
                                                        $attracted_id
                                                    ); ?>
                                                    <? $city = geo_peer::instance()->get_region(
                                                        $memberdata['region_id']
                                                    );
                                                    echo $city['name_'.translate::get_lang()] ?>
                                                </div>
                                            </div>
                                        </div>
                                        <? ($i == 1) ? $i = 0 : $i++;
                                    } ?>
                                    <div class="clear"></div>
                                </div>
                            </td>
                        </tr>
                    <? } ?>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="pl5 pt5 mb5 pb5 fs11" style="background: #F7F7F7">
                            <? if (session::has_credential('admin') or session::get_user_id(
                                ) == $user_desktop['user_id']) { ?><a style="color:gray;"
                                                                      href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=information"><?= t(
                                'Редактировать'
                            ) ?> &rarr;</a> <? } ?>
                        </td>
                    </tr>

                </table>
            </div>

            <div class="<?= request::get_string('tab') == 'groups' ? '' : 'hide ' ?>content_pane mt10" id="pane_groups"
                 style="line-height:110%; display:none;">
                <table width="100%" class="fs12" style="color: black;">
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <? load::model('groups/groups');
                    $owned = groups_peer::instance()->get_list(
                        array('user_id' => $user_desktop['user_id'], 'active' => 1, 'hidden' => 0)
                    );
                    if (count($owned)) {
                        ?>
                        <tr>
                            <td class="bold" width="35%"><?= t('Керівник спільнот') ?></td>
                            <td class="cbrown">
                                <? foreach ($owned as $groupid) {
                                    $titlegroup = groups_peer::instance()->get_item($groupid); ?>
                                    <a class="bold" href="/group<?= $groupid ?>"><?= stripslashes(
                                            htmlspecialchars($titlegroup['title'])
                                        ) ?></a><br>
                                <? } ?>
                            </td>
                        </tr>
                    <? }
                    $moder = groups_peer::instance()->get_list(array('active' => 1, 'hidden' => 0));
                    if (count($moder)) {
                        ?>
                        <tr>
                            <td class="bold" width="35%"><?= t('Модератор спільнот') ?></td>
                            <td class="cbrown">
                                <? foreach ($moder as $group_id) {

                                    if (groups_peer::instance()->is_moderator(
                                        $group_id,
                                        $user_desktop['user_id'],
                                        false
                                    )) {
                                        $title_group = groups_peer::instance()->get_item($group_id); ?>
                                        <a class="bold" href="/group<?= $group_id ?>"><?= stripslashes(
                                                htmlspecialchars($title_group['title'])
                                            ) ?></a><br>
                                    <? }
                                } ?>
                            </td>
                        </tr>
                    <? } ?>
                    <tr>
                        <td class="" width="35%"><?= t('Кількість публікацій в спільнотах') ?></td>
                        <td class="cbrown bold">
                            <?= db::get_scalar(
                                "SELECT count(*) FROM groups_topics_messages WHERE user_id=:user_id",
                                array('user_id' => $user_desktop['user_id'])
                            ) ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="pl5 pt5 mb5 pb5 fs11" style="background: #F7F7F7">
                            <? if (session::has_credential('admin') or session::get_user_id(
                                ) == $user_desktop['user_id']) { ?><a style="color:gray;"
                                                                      href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=information"><?= t(
                                'Редактировать'
                            ) ?> &rarr;</a> <? } ?>
                        </td>
                    </tr>

                </table>
            </div>

            <div class="<?= (request::get_string('tab') == 'info' || in_array(
                    request::get_string('tab'),
                    array('banners', 'publications', 'tent')
                )) ? '' : 'hide ' ?>content_pane mt10" id="pane_info" style="line-height:110%">
                <table width="100%" class="fs12" style="color:black;">

                    <?
                    $information_tent = unserialize($user_desktop['information_tent']);
                    if ($information_tent) { ?>
                        <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                            <td class="bold cbrown" width="35%" rel="info"><b><?= t('Уличная агитация') ?></b></td>
                            <td class="aright"><? if (session::has_credential('admin') or session::get_user_id(
                                    ) == $user_desktop['user_id']) { ?><a class="cbrown"
                                                                          href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=information"><?= t(
                                    'Редактировать'
                                ) ?> &rarr;</a><? } ?></td>
                        </tr>
                        <? foreach ($information_tent as $k => $v) { ?>
                            <tr>
                                <td width="35%"><?= t('Количество часов') ?></td>
                                <td class="cbrown">
                                    <a class="cbrown"><?= intval($v['hours']) ?></a>
                                </td>
                            </tr>
                            <tr>
                                <td width="35%"><?= t('Дата') ?></td>
                                <td class="cbrown">
                                    <a class="cbrown"><?= ($v['date']) ? date('d.m.Y', $v['date']) : ' - ' ?></a>
                                </td>
                            </tr>
                            <tr>
                                <td width="35%"><?= t('Детали') ?>&nbsp;(<?= t('название, место проведения и т.д.') ?>
                                    )
                                </td>
                                <td class="cbrown">
                                    <a class="cbrown"><?= ($v['description']) ? str_replace(
                                            "\\",
                                            "",
                                            $v['description']
                                        ) : ' - ' ?></a>
                                </td>
                            </tr>
                            <? if (isset($information_tent[$k + 1])) { ?>
                                <tr>
                                    <td colspan="2">
                                        <hr style="margin:0;">
                                    </td>
                                </tr><? } ?>
                        <? } ?>
                    <? } ?>


                    <?
                    $information_tent = unserialize($user_desktop['information_inet']);
                    if ($information_tent) { ?>
                        <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                            <td class="bold cbrown" width="35%" rel="info"><b><?= t('Агитация в интернете') ?></b></td>
                            <td class="aright"><? if (session::has_credential('admin') or session::get_user_id(
                                    ) == $user_desktop['user_id']) { ?><a class="cbrown"
                                                                          href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=information"><?= t(
                                    'Редактировать'
                                ) ?> &rarr;</a><? } ?></td>
                        </tr>
                        <? foreach ($information_tent as $k => $v) { ?>
                            <tr>
                                <td width="35%"><?= t('Количество часов') ?></td>
                                <td class="cbrown">
                                    <a class="cbrown"><?= intval($v['hours']) ?></a>
                                </td>
                            </tr>
                            <tr>
                                <td width="35%"><?= t('Дата') ?></td>
                                <td class="cbrown">
                                    <a class="cbrown"><?= ($v['date']) ? date('d.m.Y', $v['date']) : ' - ' ?></a>
                                </td>
                            </tr>
                            <tr>
                                <td width="35%"><?= t('Детали') ?>&nbsp;(<?= t('название, место проведения и т.д.') ?>
                                    )
                                </td>
                                <td class="cbrown">
                                    <a class="cbrown"><?= ($v['description']) ? str_replace(
                                            "\\",
                                            "",
                                            $v['description']
                                        ) : ' - ' ?></a>
                                </td>
                            </tr>
                            <? if (isset($information_tent[$k + 1])) { ?>
                                <tr>
                                    <td colspan="2">
                                        <hr style="margin:0;">
                                    </td>
                                </tr><? } ?>
                        <? } ?>
                    <? } ?>


                    <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                        <td class="bold cbrown" width="35%" rel="info"><b><?= t('Проинформировано людей') ?></b></td>
                        <td class="aright"><? if (session::has_credential('admin') or session::get_user_id(
                                ) == $user_desktop['user_id']) { ?><a class="cbrown"
                                                                      href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=information"><?= t(
                                'Редактировать'
                            ) ?> &rarr;</a><? } ?></td>
                    </tr>
                    <tr>
                        <td width="35%"><?= t('Во время личных встреч') ?></td>
                        <td class="cbrown">
                            <a class="cbrown"><?= intval($user_desktop['information_people_private_count']) ?></a>
                        </td>
                    </tr>
                    <tr>
                        <td width="35%"><?= t('Телефонными звонками') ?></td>
                        <td class="cbrown">
                            <a class="cbrown"><?= intval($user_desktop['information_people_phone_count']) ?></a>
                        </td>
                    </tr>
                    <tr>
                        <td width="35%"><?= t('Электронными письмами') ?></td>
                        <td class="cbrown">
                            <a class="cbrown"><?= intval($user_desktop['information_people_email_count']) ?></a>
                        </td>
                    </tr>
                    <tr>
                        <td width="35%"><?= t('В социальных сетях') ?></td>
                        <td>
                            <?= intval($user_desktop['information_people_social_count']) ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="35%"><?= t('Всего') ?></td>
                        <td class="cbrown">
                            <a class="cbrown bold"><?= ($user_desktop['information_people_private_count'] + $user_desktop['information_people_phone_count'] + $user_desktop['information_people_email_count'] + $user_desktop['information_people_social_count']) ?></a>
                        </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td></td>
                        <td></td>
                    </tr>

                    <? foreach (user_helper::get_agimaterials() as $k => $v) { ?>
                        <? $user_agitation = user_agitmaterials_peer::instance()->get_user(
                            $user_desktop['user_id'],
                            $k
                        ) ?>
                        <? if ($user_agitation['receive'] > 0 or $user_agitation['given'] > 0 or $user_agitation['presented'] > 0) { ?>
                            <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                                <td class="bold cbrown" width="35%" rel="info"><b><?= t($v) ?></b></td>
                                <td class="aright"><? if (session::has_credential('admin') or session::get_user_id(
                                        ) == $user_desktop['user_id']) { ?><a class="cbrown"
                                                                              href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=information"><?= t(
                                        'Редактировать'
                                    ) ?> &rarr;</a><? } ?></td>
                            </tr>

                            <tr>
                                <td class="" width="35%"><?= t('Получил') ?></td>
                                <td class="cbrown">
                                    <?= $user_agitation['receive'] ?>
                                </td>
                            </tr>
                            <? if (@in_array(18, $user_functions)) { ?>
                                <tr>
                                    <td class="" width="35%"><?= t('Передал') ?></td>
                                    <td class="cbrown">
                                        <?= $user_agitation['given'] ?>
                                    </td>
                                </tr>
                            <? } ?>
                            <tr>
                                <td class="" width="35%"><?= t('Вручил') ?></td>
                                <td class="cbrown">
                                    <?= $user_agitation['presented'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="" width="35%"><?= t('Осталось') ?></td>
                                <td class="cbrown">
                                    <? $agittotal = $user_agitation['receive'] - $user_agitation['given'] - $user_agitation['presented'] ?>
                                    <?= ($agittotal > 0) ? $agittotal : 0 ?>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                        <? }
                    } ?>

                    <? /* if ( $user_desktop['information_avtonumbers']) { ?>
            <tr>
                <td class="bold " width="35%"><?=t('Распространил авторамок')?></td>
                    <td class="cbrown">
                        <?=$user_desktop['information_avtonumbers']?>
                    </td>
            </tr>
            <?  } */ ?>
                    <? if (count(unserialize($user_desktop['information_banners'])) > 0 && is_array(
                            unserialize($user_desktop['information_banners'])
                        )) { ?>
                        <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                            <td class="bold cbrown" width="35%" rel="info"><b><?= t('Баннеры') ?></b></td>
                            <td class="aright"><? if (session::has_credential('admin') or session::get_user_id(
                                    ) == $user_desktop['user_id']) { ?><a class="cbrown"
                                                                          href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=information"><?= t(
                                    'Редактировать'
                                ) ?> &rarr;</a><? } ?></td>
                        </tr>
                        <tr>
                            <td class=" bold"></td>
                            <td>
                                <? foreach (unserialize($user_desktop['information_banners']) as $banner) { ?>

                                    <a href="<?= $banner['url'] ?>"><?= stripslashes(
                                            htmlspecialchars($banner['title'])
                                        ) ?></a><br>
                                    <span class="cgray fs11"><?= stripslashes(
                                            htmlspecialchars($banner['description'])
                                        ) ?></span>
                                <? } ?>
                            </td>
                        </tr>
                    <? }
                    ?>

                    <? if (count(unserialize($user_desktop['information_publications'])) > 0 && is_array(
                            unserialize($user_desktop['information_publications'])
                        )) { ?>
                        <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                            <td class="bold cbrown" width="35%" rel="info"><b><?= t('Публикации') ?></b></td>
                            <td class="aright"><? if (session::has_credential('admin') or session::get_user_id(
                                    ) == $user_desktop['user_id']) { ?><a class="cbrown"
                                                                          href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=information"><?= t(
                                    'Редактировать'
                                ) ?> &rarr;</a><? } ?></td>
                        </tr>
                        <tr>
                            <td class=" bold"></td>
                            <td>
                                <? foreach ($publications as $publication) { ?>
                                    <b style="font-size:100%"><?= $publication['url'] ? '<a href="'.$publication['url'].'">' : '' ?> <?= $publication['title'] ?><?= $publication['url'] ? '</a>' : '' ?></b>
                                    <?= ($publication['type'] and $publication['type'] != 16) ? ', <span class="cgray fs12">'.user_desktop_peer::get_publication_type(
                                        $publication['type']
                                    ).'</span>,' : '' ?>
                                    <span class="cgray fs12"><?= $publication['media_name'] ? str_replace(
                                                "\\",
                                                "",
                                                $publication['media_name']
                                            ).', ' : '' ?><?= $publication['date'] ?></span><br/>
                                    <span class="cgray fs11"><?= stripslashes(
                                            htmlspecialchars($publication['description'])
                                        ) ?></span><br/>

                                <? } ?>
                            </td>
                        </tr>
                    <? } ?>

                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="pl5 pt5 mb5 pb5 fs11" style="background: #F7F7F7">
                            <? if (session::has_credential('admin') or session::get_user_id(
                                ) == $user_desktop['user_id']) { ?><a style="color:gray;"
                                                                      href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=information"><?= t(
                                'Редактировать'
                            ) ?> &rarr;</a> <? } ?>
                        </td>
                    </tr>

                </table>
            </div>

            <div class="content_pane <?= request::get_string('tab') == 'meetings' ? '' : 'hide ' ?>mt10"
                 id="pane_meetings" style="line-height:110%">
                <table width="100%" class="fs12" style="color:black">
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <? if (count($user_desktop_meeting)) { ?>

                        <tr>
                            <td class="bold"><?= t('Организовал') ?></td>
                            <td>
                                <? $i = 0;
                                foreach ($user_desktop_meeting as $meeting) {
                                    if ($meeting['part'] == 0) {
                                        $i++;
                                    }
                                }
                                ?>
                                <a href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=meetings"><?= $i ?></a>
                            </td>
                        </tr>
                        <? foreach ($user_desktop_meeting as $meeting) {
                            if ($meeting['part'] == 0) { ?>
                                <tr>
                                    <td></td>
                                    <td>
                                        <a href="javascript:;" id="meeting_<?= $meeting_i ?>"
                                           class="meeting_title bold cbrown"><?= stripslashes(
                                                htmlspecialchars($meeting['title'])
                                            ) ?></a>, <?= $meeting['meeting_date'] ?> <br/>
                                        <span id="meeting_<?= $meeting_i ?>_description" class="cgray hide fs11">
                                    <?= stripslashes(strip_tags($meeting['description'])) ?>
                                            <? if ($meeting['event_id']) { ?><a
                                                href="/event<?= $meeting['event_id'] ?>"><?= t(
                                                'Перейти к событию'
                                            ) ?></a><? } ?>
                                </span>
                                    </td>
                                </tr>
                                <? $meeting_i++;
                            }
                        } ?>

                        <? if (is_array($user_desktop_meeting_confirm)) { ?>
                            <tr>
                                <td class="bold"><?= t('Посетил') ?></td>
                                <td class="bold">
                                    <a href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=meetings"><?= count(
                                            $user_desktop_meeting_confirm
                                        ) ?></a>
                                </td>
                            </tr>
                            <? foreach ($user_desktop_meeting_confirm as $meeting) { ?>
                                <tr>
                                    <td></td>
                                    <td>
                                        <a href="javascript:;" id="meeting_<?= $meeting_i ?>"
                                           class="meeting_title bold cbrown"><?= stripslashes(
                                                htmlspecialchars($meeting['title'])
                                            ) ?></a>, <?= $meeting['meeting_date'] ?><br/>
                                        <span id="meeting_<?= $meeting_i ?>_description" class="cgray hide fs11">
                                        <?= stripslashes(strip_tags($meeting['description'])) ?>
                                            <? if ($meeting['event_id']) { ?><a
                                                href="/event<?= $meeting['event_id'] ?>"><?= t(
                                                'Перейти к событию'
                                            ) ?></a><? } ?>
                                    </span>
                                    </td>
                                </tr>
                                <? $meeting_i++;
                            } ?>
                        <? } ?>

                        <? if (session::has_credential('admin') && is_array($user_desktop_meeting_decline)) { ?>
                            <tr>
                                <td class="bold">*<?= t('Не посетил') ?></td>
                                <td class="bold">
                                    <a href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=meetings"><?= count(
                                            $user_desktop_meeting_decline
                                        ) ?></a>
                                </td>
                            </tr>
                            <? foreach ($user_desktop_meeting_decline as $meeting) { ?>
                                <tr>
                                    <td></td>
                                    <td>
                                        <a href="javascript:;" id="meeting_<?= $meeting_i ?>"
                                           class="meeting_title bold cbrown"><?= stripslashes(
                                                htmlspecialchars($meeting['title'])
                                            ) ?></a>, <?= $meeting['meeting_date'] ?><br/>
                                        <span id="meeting_<?= $meeting_i ?>_description" class="cgray hide fs11">
                                        <?= stripslashes(strip_tags($meeting['description'])) ?>
                                            <? if ($meeting['event_id']) { ?><a
                                                href="/event<?= $meeting['event_id'] ?>"><?= t(
                                                'Перейти к событию'
                                            ) ?></a><? } ?>
                                    </span>
                                    </td>
                                </tr>
                                <? $meeting_i++;
                            } ?>
                        <? } ?>

                    <? } ?>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="pl5 pt5 mb5 pb5 fs11" style="background: #F7F7F7">
                            <? if (session::has_credential('admin') or session::get_user_id(
                                ) == $user_desktop['user_id']) { ?><a style="color:gray;"
                                                                      href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=meetings"><?= t(
                                'Редактировать'
                            ) ?> &rarr;</a> <? } ?>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="content_pane <?= (request::get_string('tab') == 'events' || in_array(
                    request::get_string('tab'),
                    array('speach')
                )) ? '' : 'hide ' ?>mt10" id="pane_events">
                <table width="100%" class="fs12" style="color:black">
                    <? if (count($user_desktop_event)) { ?>
                        <tr>
                            <td class="bold"><?= t('Организовал') ?></td>
                            <td>
                                <? $i = 0;
                                foreach ($user_desktop_event as $meeting) {
                                    if ($meeting['part'] == 0) {
                                        $i++;
                                    }
                                }
                                ?><a href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=events"><?= $i ?></a>
                            </td>
                        </tr>
                        <? foreach ($user_desktop_event as $meeting) {
                            if ($meeting['part'] == 0) { ?>
                                <tr>
                                    <td></td>
                                    <td>
                                        <a href="javascript:;" id="meeting_<?= $meeting_i ?>"
                                           class="meeting_title bold cbrown"><?= stripslashes(
                                                htmlspecialchars($meeting['title'])
                                            ) ?></a>, <?= $meeting['event_date'] ?> <br/>
                                        <span id="meeting_<?= $meeting_i ?>_description"
                                              class="cgray hide fs11"><?= stripslashes(
                                                htmlspecialchars($meeting['description'])
                                            ) ?></span>
                                    </td>
                                </tr>
                                <? $meeting_i++;
                            }
                        } ?>


                        <tr>
                            <td class="bold"><?= t('Выступил') ?></td>
                            <td>
                                <? $i = 0;
                                foreach ($user_desktop_event as $meeting) {
                                    if ($meeting['part'] == 2) {
                                        $i++;
                                    }
                                }
                                ?><a href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=events"><?= $i ?></a>
                            </td>
                        </tr>
                        <? foreach ($user_desktop_event as $meeting) {
                            if ($meeting['part'] == 2) { ?>
                                <tr>
                                    <td></td>
                                    <td>
                                        <a href="javascript:;" id="meeting_<?= $meeting_i ?>"
                                           class="meeting_title bold cbrown"><?= stripslashes(
                                                htmlspecialchars($meeting['title'])
                                            ) ?></a>, <?= $meeting['event_date'] ?> <br/>
                                        <span id="meeting_<?= $meeting_i ?>_description"
                                              class="cgray hide fs11"><?= stripslashes(
                                                htmlspecialchars($meeting['description'])
                                            ) ?></span>
                                    </td>
                                </tr>
                                <? $meeting_i++;
                            }
                        } ?>

                        <tr>
                            <td class="bold"><?= t('Посетил') ?></td>
                            <td>
                                <? $i = 0;
                                foreach ($user_desktop_event as $meeting) {
                                    if ($meeting['part'] == 1) {
                                        $i++;
                                    }
                                }
                                ?><a href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=events"><?= $i ?></a>
                            </td>
                        </tr>
                        <? foreach ($user_desktop_event as $meeting) {
                            if ($meeting['part'] == 1) { ?>
                                <tr>
                                    <td></td>
                                    <td>
                                        <a href="javascript:;" id="meeting_<?= $meeting_i ?>"
                                           class="meeting_title bold cbrown"><?= stripslashes(
                                                htmlspecialchars($meeting['title'])
                                            ) ?></a>, <?= $meeting['event_date'] ?> <br/>
                                        <span id="meeting_<?= $meeting_i ?>_description"
                                              class="cgray hide fs11"><?= stripslashes(
                                                htmlspecialchars($meeting['description'])
                                            ) ?></span>
                                    </td>
                                </tr>
                                <? $meeting_i++;
                            }
                        } ?>
                    <? } ?>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="pl5 pt5 mb5 pb5 fs11" style="background: #F7F7F7">
                            <? if (session::has_credential('admin') or session::get_user_id(
                                ) == $user_desktop['user_id']) { ?><a style="color:gray;"
                                                                      href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=events"><?= t(
                                'Редактировать'
                            ) ?> &rarr;</a> <? } ?>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="content_pane <?= (request::get_string('tab') == 'tasks' || request::get_string(
                    'tab'
                ) == 'signatures') ? '' : 'hide ' ?>mt10" id="pane_tasks" style="line-height:110%">
                <table width="100%" class="fs12" style="color:black">
                    <tr style="background: transparent url(/static/images/common/desktop_line.png);">
                        <td class="bold cbrown" width="35%" rel="info"><?= t('Сбор подписей') ?></td>
                        <td class="fs11 aright"><? if (session::has_credential('admin') or session::get_user_id(
                                ) == $user_desktop['user_id']) { ?><a class="cbrown"
                                                                      href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=tasks"><?= t(
                                'Редактировать'
                            ) ?> &rarr;</a><? } ?></td>
                    </tr>
                    <tr>
                        <td class="bold"><?= t('Планирую собрать') ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <? if (count($user_desktop_signature) > 0) {
                                $plan = 0;
                                foreach ($user_desktop_signature as $signature) {
                                    $plan     += $signature['plan'];
                                    $city     = geo_peer::instance()->get_city($signature['city_id']);
                                    $cityname = db::get_row(
                                        "SELECT * FROM districts WHERE id=:id",
                                        array('id' => $signature['city_id'])
                                    ); ?>
                                    <?= $city['region_name_'.session::get(
                                        'language'
                                    )] ?>, <?= $cityname['name_'.session::get(
                                        'language'
                                    )] ?> - <?= $signature['plan'] ?><br>
                                <? } ?>
                            <? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="bold"><?= t('Всего') ?>:</td>
                        <td><b><?= $plan ?></b></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <?
                    load::model('geo');
                    $regions = geo_peer::instance()->get_regions(1); ?>
                    <tr>
                        <td style="border-top: solid 1px;">Фактично</td>
                        <td style="border-top: solid 1px"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <? if (count($user_desktop_signature_fact) > 0) {
                                $fact = 0;
                                foreach ($user_desktop_signature_fact as $signature) {
                                    $fact        += $signature['fact'];
                                    $region_name = db::get_row(
                                        "SELECT name_".session::get('language')." as name FROM regions WHERE id=:id",
                                        array('id' => $signature['region_id'])
                                    );
                                    $city_name   = db::get_row(
                                        "SELECT name_".session::get('language')." as name FROM districts WHERE id=:id",
                                        array('id' => $signature['city_id'])
                                    ); ?>
                                    <div id="div_<?= $signature['id'] ?>">
                                        <div class="left" style="width:300px">
                                            <?= $region_name['name'] ?>, <?= $city_name['name'] ?>
                                            - <?= $signature['fact'] ?>
                                        </div>
                                        <div class="right">
                                            <a href="javascript:;" class="signature_delete"
                                               id="<?= $signature['id'] ?>"><img class="ml5" alt="Видалити"
                                                                                 src="/static/images/icons/3.3.png"></a>
                                        </div>

                                    </div>
                                <? } ?>
                            <? } ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="bold"><?= t('Всего') ?> фактично:</td>
                        <td><b><?= $fact ?></b></td>
                    </tr>
                    <? if (session::has_credential('admin')) { ?>
                        <tr id="show_add_signature">
                            <td></td>
                            <td>
                                <input type="submit" class="button" id="submit" name="submit"
                                       value="<?= t('Добавить') ?>">
                            </td>
                        </tr>
                        <form action="/profile/signatures_add">
                            <tr width="30%" class="add_signature hide">
                                <td class="aright"><?= t('Регион') ?></td>
                                <td>
                                    <input name="user_id" type="hidden" value="<?= $user_desktop['user_id'] ?>"/>
                                    <? $regions[''] = '&mdash;';
                                    ksort($regions); ?>
                                    <?= tag_helper::select(
                                        'region_id',
                                        $regions,
                                        array(
                                            'use_values' => false,
                                            'style'      => 'width:200px;',
                                            'id'         => $signaturecount,
                                            'rel'        => t('Выберите регион'),
                                            'class'      => 'regions',
                                        )
                                    ); ?>
                                </td>
                            </tr>

                            <tr class="add_signature hide">
                                <td class="aright"><?= t('Город/Район') ?></td>
                                <td>
                                    <? $cities = array(); ?>
                                    <?= tag_helper::select(
                                        'city_id',
                                        $cities,
                                        array(
                                            'use_values' => false,
                                            'id'         => 'city_'.$signaturecount,
                                            'rel'        => t('Выберите город/район'),
                                        )
                                    ); ?>
                                </td>
                            </tr>
                            <tr class="add_signature hide">
                                <td class="aright"><?= t('Количество') ?></td>
                                <td>
                                    <input name="fact" rel="<?= t('') ?>" class="text" type="text" value=""/>
                                </td>
                            </tr>
                            <tr class="add_signature hide">
                                <td></td>
                                <td>
                                    <input type="submit" class="button" id="submit" name="submit"
                                           value="<?= t('Добавить') ?>">
                                </td>
                            </tr>
                        </form>
                    <? } ?>
                    <tr>
                        <td colspan="2" class="pl5 pt5 mb5 pb5 fs11" style="background: #F7F7F7">
                            <? if (session::has_credential('admin') or session::get_user_id(
                                ) == $user_desktop['user_id']) { ?><a style="color:gray;"
                                                                      href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=tasks"><?= t(
                                'Редактировать'
                            ) ?> &rarr;</a> <? } ?>
                        </td>
                    </tr>
                </table>
                <table width="100%" class="fs12" id="table_signature">

                </table>
            </div>

            <div class="content_pane <?= request::get_string('tab') == 'help' ? '' : 'hide ' ?>mt10" id="pane_help"
                 style="line-height:110%">
                <? include 'partials/desktop/help.php'; ?>
            </div>

            <div class="content_pane <?= request::get_string('tab') == 'educations' ? '' : 'hide ' ?>mt10"
                 id="pane_educations" style="line-height:110%">
                <table width="100%" class="fs12">
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <? if (count($user_desktop_education)) { ?>
                        <td class="bold"><?= t('Организовал') ?></td>
                        <td>
                            <? $i = 0;
                            foreach ($user_desktop_education as $meeting) {
                                if ($meeting['part'] == '0') {
                                    $i++;
                                }
                            }
                            ?><a href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=educations"><?= $i ?></a>
                        </td>
                    </tr>
                    <? foreach ($user_desktop_education as $meeting) {
                        if ($meeting['part'] == 0) { ?>
                            <tr>
                                <td></td>
                                <td>
                                    <a href="javascript:;" id="meeting_<?= $meeting_i ?>"
                                       class="meeting_title bold cbrown"><?= stripslashes(
                                            htmlspecialchars($meeting['title'])
                                        ) ?></a>, <?= $meeting['education_date'] ?> <br/>
                                    <span id="meeting_<?= $meeting_i ?>_description"
                                          class="cgray hide fs11"><?= stripslashes(
                                            htmlspecialchars($meeting['description'])
                                        ) ?></span>
                                </td>
                            </tr>
                            <? $meeting_i++;
                        }
                    } ?>


                    <tr>
                        <td class="bold"><?= t('Посетил') ?></td>
                        <td>
                            <? $i = 0;
                            foreach ($user_desktop_education as $meeting) {
                                if ($meeting['part'] == 1) {
                                    $i++;
                                }
                            }
                            ?><a href="/profile/desktop?id=<?= $user_desktop['user_id'] ?>&tab=educations"><?= $i ?></a>
                        </td>
                    </tr>
                    <? foreach ($user_desktop_education as $meeting) {
                        if ($meeting['part'] == 1) { ?>
                            <tr>
                                <td></td>
                                <td>
                                    <a href="javascript:;" id="meeting_<?= $meeting_i ?>"
                                       class="meeting_title bold cbrown"><?= stripslashes(
                                            htmlspecialchars($meeting['title'])
                                        ) ?></a>, <?= $meeting['education_date'] ?> <br/>
                                    <span id="meeting_<?= $meeting_i ?>_description"
                                          class="cgray hide fs11"><?= stripslashes(
                                            htmlspecialchars($meeting['description'])
                                        ) ?></span>
                                </td>
                            </tr>
                            <? $meeting_i++;
                        }
                    } ?>
                    <!--tr>
                                <td class=""><?= t('Участие') ?></td>
                                <td>
                                    <?= $meeting['part'] == 0 ? t('Организовал') : t('Посетил') ?>
                                </td>
                        </tr>
			<tr>
				<td class=""><?= t('Название') ?></td>
				<td>
					<?= stripslashes(htmlspecialchars($meeting['title'])) ?>
				</td>
			</tr>
                        <tr>
				<td class=""><?= t('Дата') ?></td>
				<td>
                                        <?= $meeting['education_date'] ?>
                                </td>
                        </tr>
			<tr>
				<td class=""><?= t('Комментарий') ?></td>
				<td>
                                        <?= stripslashes(htmlspecialchars($meeting['description'])) ?>
                                </td>
			</tr-->
                    <? } ?>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="pl5 pt5 mb5 pb5 fs11" style="background: #F7F7F7">
                            <? if (session::has_credential('admin') or session::get_user_id(
                                ) == $user_desktop['user_id']) { ?><a style="color:gray;"
                                                                      href="/profile/desktop_edit?id=<?= $user_desktop['user_id'] ?>&tab=educations"><?= t(
                                'Редактировать'
                            ) ?> &rarr;</a> <? } ?>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="content_pane <?= request::get_string('tab') == 'contacts' ? '' : 'hide ' ?>mt10"
                 id="pane_contacts" style="line-height:110%">
                <table width="100%" style="color: black;" class="fs12">
                    <tbody>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td>

                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

        <? if (session::has_credential('admin') || $has_access || $has_confidence){ ?>
            <div class="content_pane <?= (request::get_string('tab') == 'payments' || in_array(
                    request::get_string('tab'),
                    array('charitable', 'regular', 'membership')
                )) ? '' : 'hide ' ?>mt10" id="pane_payments" style="line-height:110%">
                <? include 'partials/desktop/payments.php'; ?>
            </div>
        <? } ?>

        <? if (session::has_credential('admin')){ ?>
            <div class="content_pane <?= request::get_string('tab') == 'ban' ? '' : 'hide ' ?>mt10" id="pane_ban"
                 style="line-height:110%">
                <? include 'partials/desktop/ban.php'; ?>
            </div>
        <? } ?>

        <? if (session::has_credential('admin') || $rigts){ ?>
            <div class="content_pane <?= request::get_string('tab') == 'membership' ? '' : 'hide ' ?>mt10"
                 id="pane_membership" style="line-height:110%">
                <? include 'partials/desktop/membership.php'; ?>
            </div>
        <? } ?>

        <? } ?>
    </div>
</div>
<div id="overlay_photo_box" style="display:none;">
    <div id="lbOverlay" style="visibility: visible; opacity: 0.8;"></div>
    <div id="lbCenter">
        <div id="lbImage" style="visibility: visible; opacity: 1;">
            <img src=" " style=" position: absolute; top: 10px; left: 10px;max-height: 500px;">

        </div>
    </div>
    <div id="lbBottomContainer">
        <div id="lbBottom">
            <div id="lbCaption"></div>
            <a id="lbCloseLink" href="javascript: void(0);"></a>
        </div>
    </div>
</div>
<script>
    function showPeople(id) {
        $('#' + id).slideToggle(300);
    }

    jQuery(document).ready(function () {
        $('#lbCloseLink').click(function () {
            $('#overlay_photo_box').fadeOut(250);
        });
        $('#photo_box').find('img').each(function () {
            $(this).click(function () {
                $image = $('#overlay_photo_box').find('img');
                $small_src = $(this).attr('src');

                $image.attr('src', $small_src.replace('/p/', '/o/'));
                $image = $('#overlay_photo_box').find('img');

                $('#lbCaption').html($(this).attr('alt'));

                window.setTimeout(display, 350);


            })
        });
    });

    function display() {
        $('#overlay_photo_box').show('slow');
        $('#lbCenter').css('width', $image.width() + 'px');
        $('#lbCenter').css('height', $image.height() + 'px');
        $('#lbCenter').css('top', ($(window).height() - $image.height()) / 2 + 'px');
        $('#lbCenter').css('left', ($(window).width() - $image.width()) / 2 + 'px');

        $('#lbBottomContainer').css('width', $image.width() + 'px');
        $('#lbBottomContainer').css('top', ($(window).height() + $image.height()) / 2 + 'px');
        $('#lbBottomContainer').css('left', ($(window).width() - $image.width()) / 2 + 'px');


    }
</script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        $('.tab_pane > a').bind('click', function () {
            $('.tab_pane > a').removeClass('selected');
            $(this).addClass('selected');
            $('.content_pane').hide();
            $('#pane_' + $(this).attr('rel')).show();
            $(this).blur();
        });

        $('.regions').change(function () {
            var region_id = $(this).val();
            var region_attr_id = $(this).attr('id');
            if (region_id == '0') {
                $('#city_' + region_attr_id).html('');
                $('#city_' + region_attr_id).attr('disabled', true);
                return (false);
            }
            $('#city_' + region_attr_id).attr('disabled', true);
            $('#city_' + region_attr_id).html('<option>завантаження...</option>');

            var url = '/profile/get_select';
            $.post(url, {"region": region_id},
                function (result) {
                    if (result.type == 'error') {
                        alert('error');
                        return (false);
                    } else {
                        var options = '<option value="">- оберіть місто/район -</option>';
                        $(result.cities).each(function () {
                            options += '<option value="' + $(this).attr('id') + '">' + $(this).attr('title') + '</option>';
                        });
                        $('#city_' + region_attr_id).html(options);
                        $('#city_' + region_attr_id).attr('disabled', false);
                    }
                },
                "json"
            );
        });

        $('a.signature_delete').click(function () {
            if (confirm('Точно?')) {
                var id = $(this).attr('id');
                $("#div_" + id).hide();
                $.get('/profile/signatures_del', {id: id});
            }

        });

        $('#show_add_signature').click(function () {
            $('tr.add_signature').show();
            $('#show_add_signature').hide();
        });

        $('.desktop_panel > a').bind('click', function () {
            $('.desktop_panel > a').removeClass('selected');
            $(this).addClass('selected');
            $('.content_pane').hide();
            $('#pane_' + $(this).attr('rel')).show();
            $(this).blur();
        });


        $('#show_all_recomended').click(function () {
            $("#all_recomended").slideDown(70);
            $("#show_all_recomended").hide();
        });

        $('#show_all_attracted').click(function () {
            $("#all_attracted").slideDown(70);
            $('#show_all_attracted').hide();
        });

        $('#show_attracted').click(function () {
            if (!$("#attracted").is(":visible")) {
                $("#attracted").slideDown(70);

            } else {
                $("#attracted").slideUp(70);
                $("#all_attracted").slideUp(70);
            }
        });
        $('#show_recomended').click(function () {
            if (!$("#recomended").is(":visible")) {
                $("#recomended").slideDown(70);

            } else {
                $("#recomended").slideUp(70);
                $("#all_recomended").slideUp(70);
            }
        });
        $('#show_recommend_m').click(function () {
            if (!$("#recommend_m").is(":visible")) {
                $("#recommend_m").slideDown(70);

            } else {
                $("#recommend_m").slideUp(70);
            }
        });
        $('#show_recommend_member').click(function () {
            if (!$("#recommend_member").is(":visible")) {
                $("#recommend_member").slideDown(70);

            } else {
                $("#recommend_member").slideUp(70);
            }
        });

        $('.meeting_title').click(function () {
            if (!$("#" + this.id + "_description").is(":visible")) {
                $("#" + this.id + "_description").slideDown(70);

            } else {
                $("#" + this.id + "_description").slideUp(70);
            }
        });
    });

</script>


<style>
    /* SLIMBOX */

    #lbOverlay {
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: #000;
        cursor: pointer;
    }

    #lbCenter, #lbBottomContainer {
        position: absolute;
        z-index: 9999;
        overflow: hidden;
        background-color: #fff;
        padding: 10px;
    }

    .lbLoading {
        background: #fff url(loading.gif) no-repeat center;
    }

    #lbImage {
        position: absolute;
        left: 0;
        top: 0;
        background-repeat: no-repeat;
    }

    #lbPrevLink, #lbNextLink {
        display: block;
        position: absolute;
        top: 0;
        width: 50%;
        outline: none;
    }

    #lbPrevLink {
        left: 0;
    }

    #lbPrevLink:hover {
        background: transparent url(prevlabel.gif) no-repeat 0 15%;
    }

    #lbNextLink {
        right: 0;
    }

    #lbNextLink:hover {
        background: transparent url(nextlabel.gif) no-repeat 100% 15%;
    }

    #lbBottom {
        font-family: Verdana, Arial, Geneva, Helvetica, sans-serif;
        font-size: 10px;
        color: #666;
        line-height: 1.4em;
        text-align: left;
        border-top-style: none;
    }

    #lbCloseLink {
        display: block;
        float: right;
        width: 66px;
        height: 22px;
        background: transparent url('/static/images/common/closelabel.gif') no-repeat center;
        margin: 5px 0;
        outline: none;
    }

    #lbCaption, #lbNumber {
        float: left;
        clear: none;
    }

    #lbCaption {
        font-weight: bold;
    }
</style>
