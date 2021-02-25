<div>
<? $num=0; ?>
<? foreach($list_4 as $item){ ?>
<? $num++; ?>
<? $idea = ideas_peer::instance()->get_item($item['oid']) ?>
<? if($big){ ?>

<div id="bookmark_item_<?=$item['id']?>" class="mb10 p10 box_content">
    <a class="right fs12 ml10 cgray" style="display:block;width:65px;text-align: center;line-height:1;" href="javascript:;" onclick="bookmarksController.deleteItem(<?=$item['id']?>);"><?=t('удалить из закладок')?></a>
    <div style="width:285px">
        <a class="fs16" href="/idea<?=$item['oid']?>"><?=stripslashes(htmlspecialchars($idea['title']))?></a>
    </div>
</div>

<? /** ?>
<div id="bookmark_item_<?=$item['id']?>">
<? if (context::get_controller()->get_module()!='home') { ?>

<!--div class="left"><?=user_helper::photo($idea['user_id'], 's', array('class' => 'border1'))?></div-->
<div class="left ml10 mt10" style="width: 97%;">
    <a class="right fs12 ml10 cgray" style="display:block;width:65px;text-align: center;line-height:1;" href="javascript:;" onclick="bookmarksController.deleteItem(<?=$item['id']?>);"><?=t('удалить из закладок')?></a>
	<a class="fs18" href="/idea<?=$item['oid']?>"><?=stripslashes(htmlspecialchars($idea['title']))?></a>
	<div class="fs11 quiet mb5 mt5">
            <div class="left mb5" style="margin-top:-5px;"><?=user_helper::full_name($idea['user_id'])?> &nbsp;<br/></div>
                <div class="mb5" style="color:black;font-size: 12px;"><br/><?=stripslashes(htmlspecialchars($idea['anounces']))?></div>
                    <div class="p5" style="background: #F7F7F7;">
<?=tag_helper::image('common/up.png', array('class' => 'vcenter'))?> <?=t('Идею поддерживают')?>: <b><?=$idea['rate']?></b>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="/idea<?=$id?>" class="fs11 cgray"><?=t('Читать далее')?> &rarr;</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="/idea<?=$id?>#comments" class="fs11 cgray"><?=t('Комментариев')?>: <?=ideas_comments_peer::instance()->get_count_by_idea($idea['id'])?></a>
		<?//=date_helper::human($idea['created_ts'], ', ')?>

		</div>
	</div>

</div>

<div class="clear"></div>
<? } else { ?>
        <!--div class="item fl">
     <div class="fl"-->
    <!--div class="left"><?=user_helper::photo($idea['user_id'], 's', array('class' => 'border1'))?></div-->
      <div class="left ml10 mt10" style="width: 97%;">
	<a class="fs18" href="/idea<?=$id?>"><?=stripslashes(htmlspecialchars($idea['title']))?></a>
	<div class="fs11 quiet mb5 mt5">
            <div class="left mb5" style="margin-top:-5px;"><?=user_helper::full_name($idea['user_id'])?> &nbsp;<br/></div>
                <div class="mb5" style="color:black;font-size: 12px;"><br/><?=stripslashes(htmlspecialchars($idea['anounces']))?></div>
                    <div class="mb5">
<?=tag_helper::image('common/up.png', array('class' => 'vcenter','width'=>'16', 'height'=>'16','style'=>'margin-bottom:2px;'))?> <?=t('Идею поддерживают')?>: <b><?=$idea['rate']?></b>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="/idea<?=$id?>" class="fs11 cgray"><?=t('Читать далее')?> &rarr;</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="/idea<?=$id?>#comments" class="fs11 cgray"><?=t('Комментариев')?>: <?=ideas_comments_peer::instance()->get_count_by_idea($idea['id'])?></a>
		<?//=date_helper::human($idea['created_ts'], ', ')?>
		</div>
                <div style="background-color: #CCCCCC;height:1px;"></div>
	</div>
	</div>	<!--/div>

</div-->
<? } ?>
</div>
<? */ ?>

<? }else{ ?>
    <? if($num>2)break; ?>
    <div class="p5 fs12 left" style="padding-bottom:0;">
        <?=user_helper::photo($idea['user_id'], 'sm', array('class' => 'left mr10 border1'))?>
        <div class="right" style="width:185px">
            <div style="line-height:1.2;" class="mb5"><a href="/idea<?=$item['oid']?>"><?=stripslashes(htmlspecialchars($idea['title']))?></a></div>

            <?=user_helper::full_name($idea['user_id'],true,array('style'=>'color:grey'))?>
        </div>    
    </div>    
<? }} ?>
</div>