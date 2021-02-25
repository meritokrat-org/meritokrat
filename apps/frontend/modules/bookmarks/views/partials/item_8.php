<div>
<? $num=0; ?>
<? foreach($list_8 as $item){ ?>
<? $num++; ?>
<? $group = ppo_peer::instance()->get_item($item['oid']); ?>
<? load::view_helper('group'); ?>
<? if($big){ ?>

<div id="bookmark_item_<?=$item['id']?>" class="mb10 p10 box_content">
    <a class="right fs12 ml10 cgray" style="display:block;width:65px;text-align: center;line-height:1;" href="javascript:;" onclick="bookmarksController.deleteItem(<?=$item['id']?>);"><?=t('удалить из закладок')?></a>
    <div style="width:285px">
        <a class="fs16" href="/group<?=$item['oid']?>"><?=stripslashes(htmlspecialchars($group['title']))?></a>
    </div>
</div>

<? }else{ ?>
    <? if($num>2)break; ?>
    <div class="p5 fs12 left" style="padding-bottom:0;">
        <? if($group['photo_salt']){ ?>
            <?=user_helper::ppo_photo(user_helper::ppo_photo_path($group['id'],'s',$group['photo_salt']),array('class'=>'mr10'))?>
        <? }else{ ?>
            <?=group_helper::photo(0, 's', false, array('class'=>'mr10'))?>
        <? } ?>
        <div class="right" style="width:185px">
            <div style="line-height:1.2;" class="mb5"><a href="/group<?=$group['id']?>"><?=stripslashes(htmlspecialchars($group['title']))?></a></div>
        </div>
    </div>    
<? }} ?>
</div>