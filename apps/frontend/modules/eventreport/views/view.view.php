<? $messages = array('Сохранено','Отправлено на подтверждение','Отправлено на доработку','Утверждено','Мероприятие не состоялось') ?>
<style type="text/css">
.content_pane{
    height:160px;
    overflow:auto;
    margin:10px;
}
td{
    padding:2px 10px 2px 5px;
}
a.confirmation, a.confresult{
    height:13px;
    padding:5px;
}
a.editlnk{float:right;padding:0;cursor:pointer}
</style>
<? if($has_access){ ?>
<? include 'partials/sub_menu.php'; ?>
<? } ?>
<div class="profile <?=($has_access)?'':'mt10'?>">

    <div style="padding-top: 10px;width:760px" class="left">
    <h1 style="height: 60px; overflow: hidden; color: rgb(102, 0, 0);line-height:1;" class="mb5 ml10 left fs28"><?=stripslashes(htmlspecialchars($item['name']))?></h1>
            
    <div class="clear"></div>


    <div class="tab_pane p5" style="font-size:90%;padding-left:10px">
        <?=t('Описание события')?>
        <? if(session::has_credential('admin') || ($has_access && $editable)){ ?>
            <a class="editlnk mr5" href="/eventreport/edit&id=<?=$item['id']?>"><?=t('редактировать')?> &rarr;</a>
        <? } ?>
    </div>

    <?load::view_helper('date')?>
    <table class="fs12 mt10">
    <tbody>
    <tr>
        <td class="bold aright"><?=t('Описание мероприятия')?>:</td>
        <td style="color:black"><?=stripslashes($item['description'])?></td>
    </tr>
    <tr>
        <td class="bold aright"><?=t('Организатор')?>:</td>
        <td style="color:black">
            <? $ppo = ppo_peer::instance()->get_item($item['po_id']) ?>
            <a href="/ppo<?=$item['po_id']?>/"><?=$ppo['title']?></a>
            <? if(session::has_credential('admin')){ ?>
            (<?=user_helper::full_name($item['user_id'],true,array(),false)?>)
            <? } ?>
        </td>
    </tr>
    <tr>
        <td width="150px" class="bold aright"><?=t('Формат')?>:</td>
        <td style="color:black">
            <? $item["format"] = unserialize($item["format"]) ?>
            <? $f = events_peer::get_formats(); ?>
            <? if($item["format"]["campaign"]){ ?>
                    <div><?=$f["campaign"]?></div>
            <? } ?>
            <? if($item["format"]["propaganda"]){ ?>
                    <div><?=$f["propaganda"]?></div>
            <? } ?>
            <? if($item["format"]["other"]){ ?>
                    <div><?=$f["other"]?>: <?=$item["format"]["other_text"]?></div>
            <? } ?>
        </td>
    </tr>
    <tr>
        <td class="bold aright"><?=t('Время')?>:</td>
        <td style="color:black">
            <?=date_helper::get_date_range($item['start'], $item['end'])?>
        </td>
    </tr>
    <tr>
        <td class="bold aright"><?=t('Количество проинформированных людей')?>:</td>
        <td style="color:black">
            <?=intval($item['informed'])?>
        </td>
    </tr>
    <? if($has_access){ ?>
    <tr>
        <td class="bold aright"><?=t('Количество розданных материалов')?>:</td>
        <td style="color:black">
            <? $agitation = user_helper::get_agimaterials() ?>
            <? $agitation[99] = t('Другое') ?>
            <? $item['agitation'] = unserialize($item['agitation']) ?>
            <? if(is_array($item['agitation'])){ ?>
            <? foreach($item['agitation'] as $k=>$v){ ?>
                <? if($v){ ?>
                <?=$agitation[$k]?> - <?=$v?><br class="mb5"/>
                <? } ?>
            <? } ?>
            <? }else{ ?>
                <p><?=t('Информация отсутствует')?></p>
            <? } ?>
        </td>
    </tr>
    <? } ?>
    </tbody>
    </table>


    <div class="tab_pane p5" style="font-size:90%;padding-left:10px">
        <?=t('Фото')?>
        <? if(session::has_credential('admin') || ($has_access && $editable)){ ?>
            <a class="editlnk mr5" href="/photo&album_id=<?=$item['photo']?>"><?=t('редактировать')?> &rarr;</a>
        <? } ?>
    </div>
    <div class="p5 mb5">
        <? if ( $photos ) { ?>
        <div class="mt10 p10 mb15 fs12 gallery" style="text-align:center">
            <? foreach ( $photos as $photo_id ) { ?>
                <a href="/photo&album_id=<?=$item['photo']?>" class="ml10" rel="prettyPhoto[gallery]">
                        <?=photo_helper::photo($photo_id, 'h', array())?>
                </a>
            <? } ?>
        </div>
        <? } else { ?>
        <div class="m5 acenter fs12">
            <?=t('Фотографий еще нет')?>
        </div>
        <? } ?>
    </div>

    <? if($item['video']){ ?>
    <div class="tab_pane p5" style="font-size:90%;padding-left:10px">
        <?=t('Видео')?>
        <? if(session::has_credential('admin') || ($has_access && $editable)){ ?>
            <a class="editlnk mr5" href="/eventreport/edit&id=<?=$item['id']?>"><?=t('редактировать')?> &rarr;</a>
        <? } ?>
    </div>
    <? $item['video'] = explode('?v=',$item['video']) ?>
    <? $item['video'] = explode('&',$item['video'][1]) ?>
    <div class="p5 mb5">
        <? if($item['video'][0]){ ?>
        <iframe width="360" height="240" src="http://www.youtube.com/embed/<?=$item['video'][0]?>" frameborder="0" allowfullscreen></iframe>
        <? }else{ ?>
        <div class="m5 acenter fs12">
            <?=t('Видео еще нет')?>
        </div>
        <? } ?>
    </div>
    <? } ?>
    
    </div>
    <div class="clear"></div>
</div>

<div id="pans">
<div class="tab_pane p5" style="font-size:90%;padding-left:10px">
    <?=t('Участники')?>
    <? if(session::has_credential('admin') || ($has_access && $editable)){ ?>
        <a class="editlnk mr5" href="/eventreport/edit&id=<?=$item['id']?>"><?=t('редактировать')?> &rarr;</a>
    <? } ?>
</div>
<div class="content_pane" id="pane_users6" style="min-height:10px">
    <?$i=0; foreach(events_members_peer::instance()->get_members($event['id'], 0, 1) as $user){?>
    <? include 'partials/member_short.php'; ?>
    <?($i==1)?$i=0:$i++;}?>
</div>
</div>

<? if($has_access){ ?>

<div class="tab_pane p5" style="font-size:90%;padding-left:10px">
    <?=t('Выводы')?>
    <? if(session::has_credential('admin') || ($has_access && $editable)){ ?>
        <a class="editlnk mr5" href="/eventreport/edit&id=<?=$item['id']?>"><?=t('редактировать')?> &rarr;</a>
    <? } ?>
</div>
<? $findings = unserialize($item['findings']) ?>
<table class="fs12 mt10">
<tbody>
    <tr>
        <td width="150px" class="bold aright"><?=t('Что было хорошо?')?>:</td>
        <td style="color:black">
           <?=($findings[0])?stripslashes($findings[0]):t('Информация отсутствует')?>
        </td>
    </tr>
    <tr>
        <td class="bold aright"><?=t('Что можно улучшить?')?>:</td>
        <td style="color:black">
           <?=($findings[1])?stripslashes($findings[1]):t('Информация отсутствует')?>
        </td>
    </tr>
    <tr>
        <td class="bold aright"><?=t('Что можно улучшить на национальном уровне?')?>:</td>
        <td style="color:black">
            <?=($findings[2])?stripslashes($findings[2]):t('Информация отсутствует')?>
        </td>
    </tr>
</tbody>
</table>

<div class="tab_pane p5" style="font-size:90%;padding-left:10px"><?=t('История изменений')?></div>
<? if($log){ ?>
<table class="fs12 mt10">
<tbody>
    <? foreach($log as $l){ ?>
    <? $info = eventreport_log_peer::instance()->get_item($l) ?>
    <tr>
        <td width="150px" class="bold aright"><?=date('d.m.Y H:i',$info['date'])?></td>
        <td style="color:black">
            <?=user_helper::full_name($info['user_id'],true,array(),false)?> - <?=t($messages[$info['status']])?>
            <? if($info['message']){ ?>(<?=stripslashes(strip_tags($info['message']))?>)<? } ?>
        </td>
    </tr>
    <? } ?>
</tbody>
</table>
<? }else{ ?>
<div class="p5 acenter fs12">
    <?=t('Изменений еще нет')?>
</div>
<? } ?>

<? if($has_access){ ?>
<div id="messageholder" class="hide">
    <div class="popup_header">
	<h2><?=t('Укажите причину')?></h2>
    </div>
    <div class="p5">
        <textarea name="messagetext" id="messagetext"></textarea>
        <br/>
        <input type="button" class="button" onclick="send()" value="<?=t('Отправить')?>" />
        <input type="button" class="button_gray"  onclick="$('#popup_box').hide()" value="<?=t('Отмена')?>" />
    </div>
</div>

<form id="edit_form" method="post">
<input type="hidden" name="id" value="<?=request::get_int('id')?>"/>
<input type="hidden" name="message" id="message" value=""/>
    <? if(session::has_credential('admin')){ ?>
        <input type="submit" name="save" class="button" value="<?=t('Утвердить')?>" />
        <? if($item['status'] && session::has_credential('admin')){ ?>
            <input type="button" class="button_gray popup" value="<?=t('Отправить на доработку')?>" />
            <input type="submit" id="send" name="send" class="button_gray hide" value="<?=t('Отправить на доработку')?>" />
        <? } ?>
    <? }elseif($editable){ ?>
        <input type="submit" name="send" class="button_gray" value="<?=t('Отправить на утверждение')?>" />
    <? } ?>

    <? if(session::has_credential('admin')){ ?>
        <input type="button" class="button_gray popup" value="<?=t('Мероприятие не состоялось')?>" />
        <input type="submit" id="reject" name="reject" class="button_gray hide" value="<?=t('Мероприятие не состоялось')?>" />
        <input type="submit" name="delete" class="button_gray" value="<?=t('Удалить')?>" />
        <? if(!$item['status']){ ?>
            <input type="submit" name="resend" class="button_gray" value="<?=t('Отправить напоминание')?>" />
        <? } ?>
    <? } ?>

</form>
<? } ?>

<? } ?>

<script type="text/javascript">
    function send(){
        $('#message').val($('#popup_box').find('textarea').val());
        $('.clicked').click();
    }
</script>
