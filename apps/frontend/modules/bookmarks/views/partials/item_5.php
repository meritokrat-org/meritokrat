<div>
<? $num=0; ?>
<? foreach($list_5 as $item){ ?>
<? $num++; ?>
<? $poll = polls_peer::instance()->get_item($item['oid']) ?>
<? if($big){ ?>

<div id="bookmark_item_<?=$item['id']?>" class="mb10 p10 box_content">
    <a class="right fs12 ml10 cgray" style="display:block;width:65px;text-align: center;line-height:1;" href="javascript:;" onclick="bookmarksController.deleteItem(<?=$item['id']?>);"><?=t('удалить из закладок')?></a>
    <div style="width:285px">
        <a href="/poll<?=$item['oid']?>" class="fs16"><?=stripslashes(htmlspecialchars($poll['question']))?></a>
    </div>
</div>
<? /** ?>
<div id="bookmark_item_<?=$item['id']?>">
<div class="ml10 mt10" style="width:97%">
    <?=user_helper::photo($poll['user_id'], 's', array('class' => 'left mr10 border1'))?>
    <a class="right fs12 ml10 cgray" style="display:block;width:65px;text-align: center;line-height:1;" href="javascript:;" onclick="bookmarksController.deleteItem(<?=$item['id']?>);"><?=t('удалить из закладок')?></a>
	<a href="/poll<?=$item['oid']?>" style="font-weight:normal;" class="fs18"><?=stripslashes(htmlspecialchars($poll['question']))?></a>
    <div class="fs11 quiet mb5">
		<?=date_helper::human($poll['created_ts'], ', ')?> &nbsp;&nbsp;
		<?=user_helper::full_name($poll['user_id'])?>
                <div class="mt5">
		<?=t('Количество проголосовавших')?>: <b><?=$poll['count']?></b> &nbsp;&nbsp;
                <? if ( !polls_votes_peer::instance()->has_voted($id, session::get_user_id()) ) { ?>
                        <a class="fs11" href="/poll<?=$id?>"><?=t('Голосовать')?>...</a>
                <? } else { ?>
                        <a class="fs11" href="/poll<?=$id?>"><?=t('Смотреть результаты')?></a>
                <? } ?>
                </div>
       </div>
</div>
<div class="clear"></div>
</div>
<? */ ?>

<? }else{ ?>
    <? if($num>2)break; ?>
    <div class="p5 fs12 left" style="padding-bottom:0;">
        <?=user_helper::photo($poll['user_id'], 'sm', array('class' => 'left mr10 border1'))?>
        <div class="right" style="width:185px">
            <div style="line-height:1.2;" class="mb5"><a href="/poll<?=$item['oid']?>"><?=stripslashes(htmlspecialchars($poll['question']))?></a></div>

            <?=user_helper::full_name($poll['user_id'],true,array('style'=>'color:grey'))?>
        </div>
    </div>    
<? }} ?>
</div>