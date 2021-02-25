<? $group = groups_peer::instance()->get_item($id) ?>

<div class="mb10 p10 box_content">
	<div class="left"><?=group_helper::photo($group['id'], 't', true, array())?></div>
	<div style="margin-left: 85px;" class="ml10">
        <? if (session::has_credential('admin')) { ?>             
                <div class="right fs11">
                    *<?=$group['hidden']==1 ? 'скрита' : 'публічна'?><br>
                    <?=$group['privacy']==2 ? 'закрита' : 'відкрита'?>
		</div>
        <? } ?>
    <? if(request::get('bookmark')){ ?>
        <? $bkm = bookmarks_peer::instance()->is_bookmarked(session::get_user_id(),3,$group['id']); ?>    
        <a class="bookmark mb10 ml5 right" style="<?=($bkm)?'display:none;':'display:block;'?>" href="#add_bookmark" onclick="Application.bookmarkThisItem(this,'3','<?=$group['id']?>');return false;"><b></b><span><?=t('В закладки')?></span></a>
        <a class="unbkmrk mb10 ml5 right" style="<?=($bkm)?'display:block;':'display:none;'?>" href="#del_bookmark" onclick="Application.unbookmarkThisItem(this,'3','<?=$group['id']?>');return false;"><b></b><span><?=t('Удалить из закладок')?></span></a>
    <? } ?>
            <a href="/group<?=$group['id']?>" class="bold"><?=stripslashes(htmlspecialchars($group['title']))?></a> &nbsp;&nbsp;&nbsp;&nbsp; 
    <? if (session::has_credential('admin')) { ?> 
                <? if (!$group['active']) { ?><span class="fs11"> <a class="ml10 fs11" href="/groups/approve_group?group_id=<?=$group['id']?>">Схвалити</a>
                                <a class="ml10 fs11" href="/groups/delete_group?group_id=<?=$group['id']?>" onclick="return confirm('Видалити цю спільноту?');">Відмовити</a><br>
                                <?=user_helper::full_name($group['creator_id'])?>
                    <? } ?>
 <? } ?>
		<div class="mt5 quiet fs11">
			<?=($group['category']!=2)?groups_peer::get_type($group['type']):t('Рабочая группа')?><?=$group['level']>0 ? ', '.groups_peer::get_level($group['level']).' '.t('уровень') : ''?>
			<? if ( groups_peer::instance()->is_moderator($group['id'], session::get_user_id()) ) { ?>
				<a class="ml10 bold" href="/groups/edit?id=<?=$group['id']?>"><?=t('Редактировать')?></a>
			<? } ?>
		</div>
                <div class="mt5 quiet fs11"><?=t('Участников')?>
                    <b><a href="/groups/members?id=<?=$group['id']?>"><?=db::get_scalar('SELECT count(user_id) FROM groups_members WHERE group_id=:group_id AND user_id not IN(SELECT id FROM user_auth WHERE del>0)',array('group_id'=>$group['id']))?></a></b>
 &nbsp;
<?=t('Мыслей')?>    <b><a href="/groups/posts?group_id=<?=$group['id']?>"><?=db::get_scalar('SELECT count(id) FROM blogs_posts WHERE group_id=:group_id',array('group_id'=>$group['id']))?></a></b> &nbsp;
<?=t('Комментариев')?>    <b><a href="/groups/posts?group_id=<?=$group['id']?>"><?=db::get_scalar('SELECT count(id) FROM blogs_comments WHERE post_id in (SELECT id FROM blogs_posts WHERE group_id=:group_id)',array('group_id'=>$group['id']))?></a></b>
<?
		if ( $group['privacy'] == groups_peer::PRIVACY_PRIVATE )
		{load::model('groups/applicants');
                $applicants=groups_applicants_peer::instance()->get_by_group($group['id']);
if (session::has_credential('admin') || groups_peer::instance()->is_moderator($group['id'], session::get_user_id())){?><?=t('Заявок')?>    <b><a href="/groups/edit?id=<?=$group['id']?>"><span id="new_applicants" class="green fs10"><?=$applicants ? '+' . count($applicants) : ''?></span></a></b><?}}?>
		</div>

		<? /* $rate_offset = ceil( $group['rate'] ) + 2 ?>
			<div class="rate mt10"><div style="background-position: <?=$rate_offset?>px 0px"><?=t('Рейтинг')?>: <?=number_format($group['rate'], 2)?></div></div> */ ?>
	</div>
	<div class="clear"></div>
</div>