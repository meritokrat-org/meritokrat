        <div class="left">
         <?
                if($event['type']==1 && !$event['photo'])
               echo group_helper::photo($event['content_id'], 't', array());
        else
               echo image_helper::photo($event['photo'], 't', 'events', array());
        ?>
        </div>
        <div class="right fs12 ml10 cgray" style="display:block;width:120px;text-align: center;line-height:1;">
            <?=t('Мероприятие посещают')?>: <?=$event['users_count']+$event['users_leads_count']?><br/><br/>
            <? if(request::get('bookmark')){ ?>
                <? $bkm = bookmarks_peer::instance()->is_bookmarked(session::get_user_id(),7,$event['id']); ?>
                <a class="bookmark mb10 ml5 right" style="<?=($bkm)?'display:none;':'display:block;'?>" href="#add_bookmark" onclick="Application.bookmarkThisItem(this,'7','<?=$event['id']?>');return false;"><b></b><span><?=t('В закладки')?></span></a>
                <a class="unbkmrk mb10 ml5 right" style="<?=($bkm)?'display:block;':'display:none;'?>" href="#del_bookmark" onclick="Application.unbookmarkThisItem(this,'7','<?=$event['id']?>');return false;"><b></b><span><?=t('Удалить из закладок')?></span></a>
            <? } ?>
        </div>
	<div style="margin-left: 85px;width:220px;">
            
            <a href="/event<?=$event['id']?>"><?=stripslashes(htmlspecialchars($event['name']))?></a>
            <div class="fs11"><?=date_helper::get_date_range($event['start'],$event['end'])?></div>
        <div class="fs11">
            <?=t('Организатор').": "?>
        <?
        switch($event['type'])
        {
            case 1:
                load::model('groups/groups');
                if($event['content_id']>0)$group=groups_peer::instance()->get_item($event['content_id']);
                 ?>
                 <a href="/group<?=$group['id']?>" style="color:black"><?=$group['title']?></a> (<?=user_helper::full_name($event['user_id'],true,array('style'=>'color:black'),false);?>)
                 <?
                break;
            case 3:
                ?>
                <a href="/profile-31" style="color:black"><?=t("Секретариат МПУ")?></a>
                <?
            break;
            case 4:
                load::model('ppo/ppo');
                if($event['content_id']>0)$group=ppo_peer::instance()->get_item($event['content_id']);
                 ?>
                 <a href="/ppo<?=$group['id']?>" style="color:black"><?=$group['title']?></a> (<?=user_helper::full_name($event['user_id'],true,array('style'=>'color:black'),false);?>)
                 <?
            break;
            default:
                ?>
                <?=user_helper::full_name($event['user_id'],true,array('style'=>'color:black'),false);?>
                <?
        }
        ?>
            <br/>
            <?//$sections[$event['section']]?>
            <?=t('Место проведения').": "?>
            <? $region = geo_peer::instance()->get_region($event['region_id']) ?><?=$region['name_' . translate::get_lang()]?>,
            <? $city = geo_peer::instance()->get_city($event['city_id']) ?><?=$city['name_' . translate::get_lang()]?>
        </div>
            
           
        <?if($event['status']==1 || $event['status']==3){?><div class="fs11 green left"><?=($event['status']==1)?t('Вы посещаете это мероприятие'):t('Вы возможно посещаете это мероприятие')?></div><?}?>
        </div><div class="clear"></div>