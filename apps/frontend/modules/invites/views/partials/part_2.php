<div>
<? $num=0; ?>
<? foreach($list_2 as $item){ ?>
<? $num++; ?>
<? $group = groups_peer::instance()->get_item($item['obj_id']) ?>
<? load::view_helper('group'); ?>
<? if($big){ ?>

<div id="invite_item_<?=$item['id']?>" class="mb10 p10 box_content">
    <div class="left"><?=group_helper::photo($group['id'], 't', true, array())?></div>
	<div style="margin-left: 85px;" class="ml10">
        <? if (session::has_credential('admin')) { ?>             
                <div class="right fs11">
                    *<?=$group['hidden']==1 ? 'скрита' : 'публічна'?><br>
                    <?=$group['privacy']==2 ? 'закрита' : 'відкрита'?>
		</div>
        <? } ?>
            <a href="/group<?=$item['oid']?>"><b><?=stripslashes(htmlspecialchars($group['title']))?></b></a> &nbsp;&nbsp;&nbsp;&nbsp; <? if (session::has_credential('admin')) { ?> <?=$group['active'] ? '' : '*не схвалена'?> <? } ?>
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

<? }else{ ?>
    <? if($num>2)break; ?>
    <div class="p5 fs12 left" style="padding-bottom:0;">
        <?=group_helper::photo($group['id'], 's', true, array('class'=>'left mr10'))?>
        <div class="right" style="width:185px">
            <div style="line-height:1.2;"><a href="/group<?=$group['id']?>"><b><?=stripslashes(htmlspecialchars($group['title']))?></b></a></div>

            <span style="color:grey">
                <?//groups_peer::get_type($group['type'])?>
                <?=t('Вас приглашает').': '?><?=user_helper::get_inviters(session::get_user_id(),'2',$group['id'])?>
            </span>
        </div>
    </div>
<? }} ?>
</div>