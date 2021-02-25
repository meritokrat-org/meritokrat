<div>
<? $num=0; ?>
<? foreach($list_3 as $item){ ?>
<? $num++; ?>
<? $group = groups_peer::instance()->get_item($item['oid']) ?>
<? load::view_helper('group'); ?>
<? if($big){ ?>

<div id="bookmark_item_<?=$item['id']?>" class="mb10 p10 box_content">
    <a class="right fs12 ml10 cgray" style="display:block;width:65px;text-align: center;line-height:1;" href="javascript:;" onclick="bookmarksController.deleteItem(<?=$item['id']?>);"><?=t('удалить из закладок')?></a>
    <div style="width:285px">
        <a class="fs16" href="/group<?=$item['oid']?>"><?=stripslashes(htmlspecialchars($group['title']))?></a> &nbsp;&nbsp;&nbsp;&nbsp; <? if (session::has_credential('admin')) { ?> <?=$group['active'] ? '' : '*не схвалена'?> <? } ?>
    </div>
</div>
<? /** ?>
<div id="bookmark_item_<?=$item['id']?>" class="mb10 p10 box_content">
	<div class="left"><?=group_helper::photo($group['id'], 't', true, array())?></div>
    <a class="right fs12 ml10 cgray" style="display:block;width:65px;text-align: center;line-height:1;" href="javascript:;" onclick="bookmarksController.deleteItem(<?=$item['id']?>);"><?=t('удалить из закладок')?></a>
	<div style="margin-left: 85px;" class="ml10">
        <? if (session::has_credential('admin')) { ?>             
                <div class="right fs11">
                    *<?=$group['hidden']==1 ? 'скрита' : 'публічна'?><br>
                    <?=$group['privacy']==2 ? 'закрита' : 'відкрита'?>
		</div>
        <? } ?>
            <a href="/group<?=$item['oid']?>"><?=stripslashes(htmlspecialchars($group['title']))?></a> &nbsp;&nbsp;&nbsp;&nbsp; <? if (session::has_credential('admin')) { ?> <?=$group['active'] ? '' : '*не схвалена'?> <? } ?>
		<div class="mt5 quiet fs11">
			<?=groups_peer::get_type($group['type'])?>
			<? if ( groups_peer::instance()->is_moderator($group['id'], session::get_user_id()) ) { ?>
				<a class="ml10 bold" href="/groups/edit?id=<?=$group['id']?>"><?=t('Редактировать')?></a>
			<? } ?>
		</div>
                <div class="mt5 quiet fs11"><?=t('Участников')?>
                    <b><a href="/groups/members?id=<?=$group['id']?>"><?=db::get_scalar('SELECT count(user_id) FROM groups_members WHERE group_id=:group_id',array('group_id'=>$group['id']))?></a></b>
 &nbsp;
<?=t('Мыслей')?>    <b><a href="/group<?=$group['id']?>"><?=db::get_scalar('SELECT count(id) FROM groups_topics WHERE group_id=:group_id',array('group_id'=>$group['id']))?></a></b> &nbsp;
<?=t('Комментариев')?>    <b><a href="/group<?=$group['id']?>"><?=db::get_scalar('SELECT count(id) FROM groups_topics_messages WHERE topic_id in (SELECT id FROM groups_topics WHERE group_id=:group_id)',array('group_id'=>$group['id']))?></a></b>
		</div>

		<? /* $rate_offset = ceil( $group['rate'] ) + 2 ?>
			<div class="rate mt10"><div style="background-position: <?=$rate_offset?>px 0px"><?=t('Рейтинг')?>: <?=number_format($group['rate'], 2)?></div></div> * ?>
	</div>
	<div class="clear"></div>
</div>
<? */ ?>

<? }else{ ?>
    <? if($num>2)break; ?>
    <div class="p5 fs12 left" style="padding-bottom:0;">
        <?=group_helper::photo($group['id'], 's', true, array('class'=>'left mr10'))?>
        <div class="right" style="width:185px">
            <div style="line-height:1.2;" class="mb5"><a href="/group<?=$group['id']?>"><?=stripslashes(htmlspecialchars($group['title']))?></a></div>

            <span style="color:grey">
            <?=groups_peer::get_type($group['type'])?>
            </span>
        </div>
    </div>    
<? }} ?>
</div>