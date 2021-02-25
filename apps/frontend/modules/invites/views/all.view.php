<?
$arr=array('',
    array('events','Приглашения на события','Посмотреть события'),
    array('groups','Приглашения в сообщества','Посмотреть сообщества'),
    array('polls','Приглашения на опрос','Посмотреть опросы'),
    array('ppo','Приглашения в парт. организации','Посмотреть партийные организации'),
    array('team','Приглашения в команду','Посмотреть команды'),
    array('projects', 'Приглашения в проекты', 'Запрошенння до проекту')
);
?>

<div class="left" style="width: 265px">

    <?
    $total = 0;
    foreach($types as $t)
    {
        $total++;
        $num = count($$items[$t]);
        if($t==3 && $num==0)
        {

        }
        else
        {
            ?>
            <div class="left" style="width:265px;">
            <div class="right mt15 mr10 <?=($total>3||$num==0)?'hide':''?>" style="cursor: pointer;" id="<?=$arr[$t][0]?>_on" onclick="Application.ShowHide('<?=$arr[$t][0]?>')"><img src="https://meritokrat.org/static/images/icons/up_icon.jpg" /></div>
            <div class="right mt15 mr10 <?=($total<=3&&$num!=0)?'hide':''?>" style="cursor: pointer;" id="<?=$arr[$t][0]?>_off" onclick="Application.ShowHide('<?=$arr[$t][0]?>')"><img src="https://meritokrat.org/static/images/icons/down_icon.jpg" /></div>
            <h1 class="column_head mt10">
                <a href="/invites?type=<?=$t?>"><?=t($arr[$t][1])?></a>
            </h1>
            <?
            if($num)
            {
                ?>
                <div id="<?=$arr[$t][0]?>" class="<?=($total>3)?'hide':''?>">
                    <? include 'partials/part_' . $t . '.php'; ?>
                </div>
                <?
            }
            else
            {
                ?>
                <div id="<?=$arr[$t][0]?>" class="fs12 p10 hide" style="text-align:center;color:grey;">
                    <?=t('Тут еще нет записей')?><br />
                    <a href="/<?=$arr[$t][0]?>"><?=t($arr[$t][2])?></a>
                </div>
                <?
            }
            ?>
            </div>
            <?
        }
    }
    ?>

</div>
<? $big = 1; ?>
<div class="left ml10" style="width: 470px;margin-top:10px;">

    <h1 class="column_head">
        <?=t('Все приглашения')?>
    </h1>
    <? load::view_helper('image') ?>
    <? load::view_helper('group'); ?>
    <?
    if(count($list_0) > 0)
    {
        foreach($list_0 as $item)
        {
            if($item['type']==1)
            {
                ?>
                <? $event = events_peer::instance()->get_item($item['obj_id']) ?>
                <div id="invite_item_<?=$item['id']?>" class="mb10 p10 box_content">
                <div class="left"><?=image_helper::photo($event['photo'], 't', 'events', array())?></div>
                <div style="margin-left: 85px;">
                    <a href="/event<?=$event['id']?>"><b><?=stripslashes(htmlspecialchars($event['name']))?></b></a>
                    <div class="fs11">
                        <?=date_helper::get_date_range($event['start'],$event['end'])?>
                    </div>
                    <div class="fs11">
                        <?=t('Организатор').": "?>
                        <?
                        switch($event['type'])
                        {
                            case 1:
                                if($event['content_id']>0)$group=groups_peer::instance()->get_item($event['content_id']);
                                ?>
                                <a href="/group<?=$group['id']?>"><?=stripslashes(htmlspecialchars($group['title']))?></a>
                                <?
                                break;

                            default:
                                ?>
                                <a href="/profile-31"><?=t("Оргкомитет")?></a>
                                <?
                            break;

                        }
                        ?>
                        <br/>
                        <?//$sections[$event['section']]?>
                        <?=t('Место проведения').": "?>
                        <? $region = geo_peer::instance()->get_region($event['region_id']) ?><?=$region['name_' . translate::get_lang()]?>,
                        <? $city = geo_peer::instance()->get_city($event['city_id']) ?><?=$city['name_' . translate::get_lang()]?>
                    </div>
                    <div class="fs11"><?=t('Мероприятие посещают')?>: <?=$event['users1sum']+$event['users3sum']+$event['users1count']+$event['users3count']?> <?=t('участников')?></div>
                <? if($event['status']==1 || $event['status']==3) { ?>
                    <span class="fs11 green"><?=($event['status']==1)?t('Вы посещаете это мероприятие'):t('Вы возможно посещаете это мероприятие')?></span>
                <? } ?>
                </div>
                <? if($event['status']==0) { ?>
                <div class="mt10" style="margin-left:85px;">
                    <a class="uline promt button p5" style="text-decoration:none;" href="javascript:;" onclick="invitesController.event(this,'<?=$item['id']?>','1');"><?=t('Да, пойду')?></a>&nbsp;&nbsp;
                    <a class="uline button p5" style="text-decoration:none;" href="javascript:;" onclick="invitesController.event(this,'<?=$item['id']?>','2');"><?=t('Нет, не пойду')?></a>&nbsp;&nbsp;
                    <a class="uline promt button p5" style="text-decoration:none;" href="javascript:;" onclick="invitesController.event(this,'<?=$item['id']?>','3');"><?=t('Возможно, пойду')?></a>
                </div>
                <? } ?>
            </div>
            <?
            }
            elseif($item['type']==2)
            {
                ?>
                <? $group = groups_peer::instance()->get_item($item['obj_id']) ?>
                <div id="invite_item_<?=$item['id']?>" class="mb10 p10 box_content">
                    <div class="left"><?=group_helper::photo($group['id'], 't', true, array())?></div>
                        <div style="margin-left: 85px;" class="ml10">
                            <a href="/group<?=$item['obj_id']?>"><b><?=stripslashes(htmlspecialchars($group['title']))?></b></a> &nbsp;&nbsp;&nbsp;&nbsp; <? if (session::has_credential('admin')) { ?> <?=$group['active'] ? 'схвалена' : 'не схвалена'?> <? } ?>
                                <div class="mt5 quiet fs11">
                                        <?=groups_peer::get_type($group['type'])?>
                                </div>
                                <div class="mt5 quiet fs11"><?=t('Участников')?>
                                    <b><a href="/groups/members?id=<?=$group['id']?>"><?=db::get_scalar('SELECT count(user_id) FROM groups_members WHERE group_id=:group_id',array('group_id'=>$group['id']))?></a></b>
                 &nbsp;
                <?=t('Мыслей')?>    <b><a href="/group<?=$group['id']?>"><?=db::get_scalar('SELECT count(id) FROM groups_topics WHERE group_id=:group_id',array('group_id'=>$group['id']))?></a></b> &nbsp;
                <?=t('Комментариев')?>    <b><a href="/group<?=$group['id']?>"><?=db::get_scalar('SELECT count(id) FROM groups_topics_messages WHERE topic_id in (SELECT id FROM groups_topics WHERE group_id=:group_id)',array('group_id'=>$group['id']))?></a></b>
                                </div>
                        </div>
                        <div class="mt10" style="margin-left:85px;">
                            <a class="uline button p5" style="text-decoration:none;" href="javascript:;" onclick="invitesController.group('<?=$item['id']?>','1');"><?=t('Вступить')?></a>&nbsp;&nbsp;
                            <a class="uline nopromt button p5" style="text-decoration:none;" href="javascript:;" onclick="invitesController.group('<?=$item['id']?>','2');"><?=t('Отказать')?></a>
                        </div>
                </div>
                <?
            }
            elseif($item['type']==4)
            {
                include 'partials/ppo.php';
            }
            elseif($item['type']==5)
            {
                include 'partials/team.php';
            }
            elseif($item['type']==6)
            {
                include 'partials/projects.php';
            }
            else
            {
                ?>
                <? $poll = polls_peer::instance()->get_item($item['obj_id']) ?>
                <div id="invite_item_<?=$item['id']?>" class="mb10 p10 box_content">
                <div class="left"><?=user_helper::photo($poll['user_id'], 't')?></div>
                    <div style="margin-left: 85px;" class="ml10">
                        <a href="/poll<?=$item['obj_id']?>" style="font-weight:normal;" class="fs18"><?=stripslashes(htmlspecialchars($poll['question']))?></a>
                        <div class="fs11 quiet mb5">
                                <?=date_helper::human($poll['created_ts'], ', ')?> &nbsp;&nbsp;
                                <?=user_helper::full_name($poll['user_id'])?>
                                <div class="mt5">
                                <?=t('Количество проголосовавших')?>: <b><?=$poll['count']?></b> &nbsp;&nbsp;
                                </div>
                       </div>
                    </div>
                    <div class="mt10" style="margin-left:85px;">
                        <a class="uline nopromt button p5" style="text-decoration:none;" href="/poll<?=$item['obj_id']?>"><?=t('Голосовать')?></a>&nbsp;&nbsp;
                        <a class="uline nopromt button p5" style="text-decoration:none;" href="javascript:;" onclick="invitesController.group('<?=$item['id']?>','2');"><?=t('Отказать')?></a>
                    </div>
                </div>
                <?
            }
        }
    }
    else
    {
        ?>
        <div class="fs12 mt15" style="text-align:center;color:grey;"><?=t('Тут еще нет записей')?></div>
        <?
    }
    ?>

    <div class="right pager"><?=pager_helper::get_short($pager)?></div>

</div>