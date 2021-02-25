<h1 class="column_head mt10 mr10">
	<a href="/group<?=$group['id']?>"><?=htmlspecialchars($group['title'])?></a>
	&rarr;
	<a href="/groups/news?id=<?=$group['id']?>"><?=t('Новости')?></a>
</h1>

<div class="left bold fs16 ml10 cbrown mt10 mb5">
    <?=stripslashes(nl2br(htmlspecialchars($item['title'])))?></div>
<div class="right fs12 mt10 mb5">
<?if(groups_peer::instance()->is_moderator(request::get_int('id'), session::get_user_id())){?>
    <a href="javascript:;" onclick="if ( confirm('<?=t('Удалить новость?')?>') ) groupsController.deleteNews(<?=$item['id']?>,'/groups/news?id=<?=$group['id']?>');" class="dotted ml10"><?=t('Удалить')?></a>
    <a class="ml10" href="/groups/edit_news?id=<?=request::get_int('id')?>" id="newsedit"><?=t('Редактировать')?></a>
<?}?>
</div>
<div class="clear"></div>

<div class="left ml5 p5 fs11 quiet mb10" style="width:70px">
    <div class="left acenter">
        <? load::view_helper('image');
        if(!$item['photo']){
               echo group_helper::photo($group['id'], 's', true, array('class' => 'border1'));
                }
        else
               echo image_helper::photo($item['photo'], 's', 'groupnews', array('class' => 'border1'));?>
    </div>
    <div class="left ml5 acenter"><?=date_helper::human($item['created_ts'])?>
    <div class="fs11 mt10 quiet"><?=t('Просмотров')?></div>
    <div class="acenter fs16 bold"><?=(int)$item['views']?></div>    
    </div>
    <div class="clear"></div>
</div>

<div class="left" style="width:650px">
<div class="mr15">
    <?=stripslashes(nl2br($item['text']))?>
	<?//load::view_helper('comments'); ?>
	<?//=comments_helper::render($item['id'], comments_helper::TYPE_GROUP_NEWS); ?>
</div>
    <div class="box_content p5 mt15 aright fs12"><a href="/groups/news?id=<?=$group['id']?>"><?=t('Все новости сообщества')?> &rarr;</a></div>
</div>
<div class="clear"></div>
