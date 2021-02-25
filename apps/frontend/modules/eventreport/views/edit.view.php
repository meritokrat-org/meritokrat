<style>
.long{width:390px}
.short{width:50px}
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
</style>

<form id="edit_form" method="post">
<input type="hidden" name="id" value="<?=request::get_int('id')?>"/>
<input type="hidden" name="message" id="message" value=""/>

<? include 'partials/sub_menu.php'; ?>
<div class="profile">

    <div>
    <h1 style="height: 60px; overflow: hidden; color: rgb(102, 0, 0);line-height:1;" class="mb10 ml10 left fs28"><?=stripslashes(htmlspecialchars($item['name']))?></h1>
            
    <div class="clear"></div>


    <div class="tab_pane p5" style="font-size:90%;padding-left:10px"><?=t('Описание события')?></div>
    <?load::view_helper('date')?>
    <table class="fs12 form_bg" style="margin-right:0">
    <tbody>
    <tr>
        <td class="bold aright"><?=t('Описание мероприятия')?>:</td>
        <td style="color:black">
            <textarea name="description"><?=strip_tags(stripslashes($item['description']))?></textarea>
        </td>
    </tr>
    <tr>
        <td width="150px" class="bold aright"><?=t('Формат')?>:</td>
        <td style="color:black">
            <? $item["format"] = unserialize($item["format"]) ?>
            <? $f = events_peer::get_formats(); ?>
            <div>
                <input type="checkbox" id="campaign" name="campaign" <?= $item["format"]["campaign"] ? "checked" : "" ?> />
                <label for="campaign"><?=$f["campaign"]?></label>
            </div>
            <div>
                <input type="checkbox" id="propaganda" name="propaganda" <?= $item["format"]["propaganda"] ? "checked" : "" ?> />
                <label for="propaganda"><?=$f["propaganda"]?></label>
            </div>
            <div>
                <input type="checkbox" id="other" name="other" <?= $item["format"]["other"] ? "checked" : "" ?> />
                <label for="other"><?=$f["other"]?></label>
                <input type="text" id="other_text" name="other_text" value="<?=$item["format"]["other_text"]?>" />
            </div>
        </td>
    </tr>
    <tr>
        <td class="bold aright"><?=t('Время')?>:</td>
        <td style="color:black">
            <?=t('С')?>
            <input rel="<?=t('Введите дату начала')?>" style="width: 150px;" value="<?=date("d.m.Y H:i",$item['start'])?>" name="start" id="start" type="text" class="start text" />
            <?=t('по')?>
            <input rel="<?=t('Введите дату окончания')?>" style="width: 150px;" value="<?=date("d.m.Y H:i",$item['end'])?>" name="end" id="end" type="text" class="end text" />
        </td>
    </tr>
    <tr>
        <td class="bold aright"><?=t('Количество проинформированных людей')?>:</td>
        <td style="color:black">
            <input class="short" value="<?=intval($item['informed'])?>" name="informed" id="informed" type="text" />
        </td>
    </tr>
    <tr>
        <td class="bold aright"><?=t('Количество розданных материалов')?>:</td>
        <td style="color:black">
            <? $agitation = user_helper::get_agimaterials() ?>
            <? $item['agitation'] = unserialize($item['agitation']) ?>
            <? foreach($agitation as $k=>$v){ ?>
                <? if($v){ ?>
                <?=$v?> <input class="short" type="text" name="agitation[<?=$k?>]" value="<?=intval($item['agitation'][$k])?>"/><br class="mb5"/>
                <? } ?>
            <? } ?>
            <?=t('Другое')?> <input type="text" name="agitation[99]" value="<?=$item['agitation'][99]?>"/><br class="mb5"/>
        </td>
    </tr>
    </tbody>
    </table>


    <div class="tab_pane p5" style="font-size:90%;padding-left:10px"><?=t('Фото')?></div>
    <div class="p5 form_bg mb15" style="margin-right:0">
        <? if ( $photos ) { ?>
        <div class="mt10 p10 mb15 fs12 gallery" style="text-align:center">
            <? foreach ( $photos as $photo_id ) { ?>
                <a href="/photo&album_id=<?=$item['photo']?>" class="ml10" rel="prettyPhoto[gallery]">
                        <?=photo_helper::photo($photo_id, 'h', array())?>
                </a>
            <? } ?>
            <br>
            <a class="right fs12" target="_blank" href="/photo&album_id=<?=$item['photo']?>"><?=t('Добавить фото')?> &rarr;</a><br>
        </div>
        <? } else { ?>
        <div class="m5 acenter fs12">
            <?=t('Фотографий еще нет')?>
            <br />
            <a target="_blank" href="/photo&album_id=<?=$item['photo']?>"><?=t('Добавить фото')?></a>
        </div>
        <? } ?>
    </div>
    <div class="clear"></div>

    <div class="tab_pane p5" style="font-size:90%;padding-left:10px"><?=t('Видео')?></div>
    <table class="fs12 form_bg">
    <tbody>
        <tr>
            <td width="150px" class="bold aright"><?=t('Видео')?>:</td>
            <td style="color:black">
                <input type="text" class="long" name="video" value="<?=strip_tags(stripslashes($item['video']))?>" />
                <br/>
                * <?=t('Укажите адрес страницы на youtube.com')?>
            </td>
        </tr>
    </tbody>
    </table>
    <div class="clear"></div>

    
    
    </div>
    <div class="clear"></div>
</div>

<? if (session::is_authenticated()) { ?>

<div id="pans">
<div class="tab_pane" style="font-size:90%">
    <a href="javascript:;" class="selected" rel="users1">
         <?=t('Пойдут')?> <?=$event['users1count']+$event['users1sum']?><?=($event['users1sum'])?'('.$event['users1count'].'+'.$event['users1sum'].')':''?>
    </a>
    <a href="javascript:;" rel="users2"><?=t('Не пойдут')?> <?=$event['users2count']+$event['users2sum']?></a>
    <a href="javascript:;" rel="users3"><?=t('Возможно пойдут')?> <?=$event['users3count']+$event['users3sum']?></a>
    <? if(count($whos_invited)>0) { ?>
    <a href="javascript:;" rel="users4"><?=t('Вы уже пригласили')?> (<?=count($whos_invited)?>)</a>
    <? } ?>
    <? if(session::has_credential('admin')){ ?>
    <a href="/events?action=view&id=<?=$event['id']?>&print=1" id="print_users1" class="printlink">*<?=t('Печатать')?></a>
    <a href="/events?action=view&id=<?=$event['id']?>&print=2" id="print_users2" class="printlink" style="display:none">*<?=t('Печатать')?></a>
    <a href="/events?action=view&id=<?=$event['id']?>&print=3" id="print_users3" class="printlink" style="display:none">*<?=t('Печатать')?></a>
    <a href="/events?action=view&id=<?=$event['id']?>&print=1&confirm=2" id="print_users5" class="printlink" style="display:none">*<?=t('Печатать')?></a>
    <a href="/events?action=view&id=<?=$event['id']?>&print=1&confirm=1" id="print_users6" class="printlink" style="display:none">*<?=t('Печатать')?></a>
    <? } ?>
    <? if($event['start']<time() && session::has_credential('admin')){ ?>
    <a href="javascript:;" style="float:right" class="mr10" rel="users5">*<?=t('Не были')?></a>
    <a href="javascript:;" style="float:right" rel="users6">*<?=t('Были')?></a>
    <? } ?>
    <div class="clear"></div>
</div>
<div class="content_pane" id="pane_users1">
    <?$i=0; foreach(events_members_peer::instance()->get_members($event['id'], 1) as $user){?>
    <? include 'partials/member_short.php'; ?>
    <?($i==1)?$i=0:$i++;}?>
    <div class="clear"></div>
</div>
<div class="content_pane hidden" id="pane_users2">
    <?$i=0; foreach(events_members_peer::instance()->get_members($event['id'], 2) as $user){?>
    <? include 'partials/member_short.php'; ?>
    <?($i==1)?$i=0:$i++;}?>
</div>
<div class="content_pane hidden" id="pane_users3">
    <?$i=0; foreach(events_members_peer::instance()->get_members($event['id'], 3) as $user){?>
    <? include 'partials/member_short.php'; ?>
    <?($i==1)?$i=0:$i++;}?>
</div>

<? if($event['start']<time()){ ?>
<div class="content_pane hidden" id="pane_users6" style="min-height:10px">
    <?$i=0; foreach(events_members_peer::instance()->get_members($event['id'], 0, 1) as $user){?>
    <? include 'partials/member_short.php'; ?>
    <?($i==1)?$i=0:$i++;}?>
</div>
<div class="content_pane hidden" id="pane_users5" style="min-height:10px">
    <?$i=0; foreach(events_members_peer::instance()->get_members($event['id'], 0, 2) as $user){?>
    <? include 'partials/member_short.php'; ?>
    <?($i==1)?$i=0:$i++;}?>
</div>
<? } ?>

</div>

<? } ?>

<div class="tab_pane p5" style="font-size:90%;padding-left:10px"><?=t('Выводы')?></div>
<? $item['findings'] = unserialize($item['findings']) ?>
<table class="fs12 form_bg">
<tbody>
    <tr>
        <td width="150px" class="bold aright"><?=t('Что было хорошо?')?>:</td>
        <td style="color:black">
            <textarea name="findings[]"><?=strip_tags(stripslashes($item['findings'][0]))?></textarea>
        </td>
    </tr>
    <tr>
        <td class="bold aright"><?=t('Что можно улучшить?')?>:</td>
        <td style="color:black">
            <textarea name="findings[]"><?=strip_tags(stripslashes($item['findings'][1]))?></textarea>
        </td>
    </tr>
    <tr>
        <td class="bold aright"><?=t('Что можно улучшить на национальном уровне?')?>:</td>
        <td style="color:black">
            <textarea name="findings[]"><?=strip_tags(stripslashes($item['findings'][2]))?></textarea>
        </td>
    </tr>
</tbody>
</table>

<div id="messageholder" class="hide">
    <div class="popup_header">
	<h2><?=t('Укажите причину')?></h2>
    </div>
    <div class="p5">
        <textarea name="messagetext" id="messagetext"></textarea>
        <br/>
        <input type="button" class="button" onclick="send()" value="<?=t('Отправить')?>" />
        <input type="button" class="button_gray" onclick="$('#popup_box').hide()" value="<?=t('Отмена')?>" />
    </div>
</div>

<? if(session::has_credential('admin')){ ?>
    <input type="submit" name="save" class="button" value="<?=t('Утвердить')?>" />
    <? if($item['status'] && session::has_credential('admin')){ ?>
        <input type="button" class="button_gray popup" value="<?=t('Отправить на доработку')?>" />
        <input type="submit" id="send" name="send" class="button_gray hide" value="<?=t('Отправить на доработку')?>" />
    <? } ?>
<? }else{ ?>
    <input type="submit" name="save" class="button" value="<?=t('Сохранить')?>" />
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

<? include 'partials/datepicker.php' ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('.confirmation').click(function(){
        var confirm = 0;
        if($(this).hasClass('button_gray')){
            confirm = 2;
        }
        $.post('/events/confirm',{"event_id":'<?=request::get_int('id')?>','user_id':$(this).attr('rel'),'confirm':confirm});
        $(this).parent().find('.confresult').html($(this).html()).show();
        $(this).parent().find('.confirmation').hide();
    });

    $('.confresult').click(function(){
        $(this).parent().find('.confirmation').show();
        $(this).hide();
    });

    $('input#start').datetimepicker({
        onClose:function(){
            $.post('/events/datarange',{'start':$('input#start').val(),'id':<?=$item['event_id']?>},function(data){$('input#end').val(data);});
        },
        dateFormat: 'dd.mm.yy'
    });
    $('input#end').datetimepicker({ dateFormat: 'dd.mm.yy' });
    $('#ui-datepicker-div').css('background','#f0f0f0');

    tabclick();
    function tabclick(){
        $('.tab_pane > a').click(function() {
            if($(this).attr('rel')){
                $('.tab_pane > a').removeClass('selected');
                $(this).addClass('selected');
                $('.content_pane').hide();
                $('#pane_' + $(this).attr('rel')).show();
                $(this).blur();
                $('.printlink').hide();
                $('#print_' + $(this).attr('rel')).show();
            }
        });
    }
});
function send(){
    $('#message').val($('#popup_box').find('textarea').val());
    $('.clicked').click();
}
</script>