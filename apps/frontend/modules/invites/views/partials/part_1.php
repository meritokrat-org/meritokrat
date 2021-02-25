<div>
<? $num=0; ?>
<? foreach($list_1 as $item){ ?>
<? $num++; ?>
<? $event = events_peer::instance()->get_item($item['obj_id']) ?>
<? load::view_helper('image') ?>
<? $sections=events_peer::get_types() ?>
<? if($big){ ?>

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

<? }else{ ?>
    <? if($num>2)break; ?>
    <div class="p5 fs12 left" style="padding-bottom:0;">
        <?=image_helper::photo($event['photo'], 'sm', 'events', array('class'=>'left mr10'))?>
        <div class="right" style="width:180px">
            <div style="line-height:1.2;"><a href="/event<?=$event['id']?>"><b><?=stripslashes(htmlspecialchars($event['name']))?></b></a></div>
            <span class="cgray">
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
                <?=date_helper::get_date_range($event['start'],$event['end'])?>
            </span>
            <br/>
            <span class="cgray">
                <? /*$region = geo_peer::instance()->get_region($event['region_id']) ?><?=$region['name_' . translate::get_lang()]?>,
                <? $city = geo_peer::instance()->get_city($event['city_id']) ?><?=$city['name_' . translate::get_lang()]*/?>

                <?=t('Вас приглашает').': '?><?=user_helper::get_inviters(session::get_user_id(),'1',$event['id'])?>
            </span>
        </div>
    </div>
<? }} ?>
</div>