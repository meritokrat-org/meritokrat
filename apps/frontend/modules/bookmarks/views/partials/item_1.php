<div class="box_content">
<? $num=0; ?>
<? foreach($list_1 as $item){ ?>
<? $num++; ?>
<? $data = blogs_posts_peer::instance()->get_item($item['oid']) ?>
<? if($big){ ?>

<div id="bookmark_item_<?=$item['id']?>" class="mb10 p10 box_content">
    <a class="right fs12 ml10 cgray" style="display:block;width:65px;text-align: center;line-height:1;" href="javascript:;" onclick="bookmarksController.deleteItem(<?=$item['id']?>);"><?=t('удалить из закладок')?></a>
    <div style="width:285px">
            <a class="fs16" style="font-weight:normal;" href="/blogpost<?=$item['oid']?>"><?=htmlspecialchars(stripslashes($data['title']))?></a>
    </div>
</div>
<? /** ?>
<div id="bookmark_item_<?=$item['id']?>">
    <table><tr><td width="54" class="pr5">
        <?=user_helper::photo($data['user_id'], 's', array('class' => 'left mr10 border1'))?>
        </td><td class="pr5">
        <!--<a class="delete right" href="javascript:;" onclick="bookmarksController.deleteItem(<?=$item['id']?>);"></a>-->
        <a class="right fs12 ml10 cgray" style="display:block;width:65px;text-align: center;line-height:1;" href="javascript:;" onclick="bookmarksController.deleteItem(<?=$item['id']?>);"><?=t('удалить из закладок')?></a>
        <div style="width: 90%;">
            <h5 class="mb5">
                <a class="fs18" style="font-weight:normal;" href="/blogpost<?=$item['oid']?>"><?=htmlspecialchars(stripslashes($data['title']))?></a>
            </h5>
            <div class="mb5 fs12" style="color:black;">
                <?=stripslashes(htmlspecialchars($data['anounces']))?>
            </div>
            <div class="mt10 fs11 quiet">
                <?=user_helper::full_name($data['user_id'])?>,
                <?=date_helper::human($data['created_ts'], ', ')?>
            </div>
        </div>
    </td></tr></table>
</div>   
<? */ ?>
 
<? }else{ ?>
    <? if($num>2)break; ?>
    <div class="p5 fs12 left" style="padding-bottom:0;">
        <?=user_helper::photo($data['user_id'], 'sm', array('class' => 'left mr10 border1'))?>
        <div class="right" style="width:185px">
            <div style="line-height:1.2;" class="mb5"><a href="/blogpost<?=$item['oid']?>"><?=htmlspecialchars(stripslashes($data['title']))?></a></div>

            <?=user_helper::full_name($data['user_id'],true,array('style' => 'color:grey'))?>
        </div>
    </div>
<? } ?>
<? } ?>
</div>