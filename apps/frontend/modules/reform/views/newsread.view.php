<h1 class="column_head mt10 mr10">
	<a href="/project<?=$group['id']?>/<?=$group['number']?>"><?=htmlspecialchars($group['title'])?></a>
	&rarr;
	<a href="/projects/news?id=<?=$group['id']?>"><?=t('Новости')?></a>
</h1>

<div class="left bold fs16 ml10 cbrown mt10 mb5">
    <?=stripslashes(nl2br(htmlspecialchars($item['title'])))?></div>
<div class="right fs12 mt10 mb5">
<?if(reform_peer::instance()->is_moderator(request::get_int('id'), session::get_user_id())){?>
    <a href="javascript:;" onclick="if ( confirm('<?=t('Удалить новость?')?>') ) reformController.deleteNews(<?=$item['id']?>,'/projects/news?id=<?=$group['id']?>');" class="dotted ml10"><?=t('Удалить')?></a>
    <a class="ml10" href="/projects/edit_news?id=<?=request::get_int('id')?>" id="newsedit"><?=t('Редактировать')?></a>
<?}?>
</div>
<div class="clear"></div>

<div class="left ml5 p5 fs11 quiet mb10" style="width:70px">
    <div class="left acenter">
         <?=user_helper::ppo_photo(user_helper::ppo_photo_path($group['id'],'s',$group['photo_salt']))?>
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
</div>
    <div class="box_content p5 mt15 aright fs12"><a href="/projects/news?id=<?=$group['id']?>"><?=t('Все новости сообщества')?> &rarr;</a></div>
</div>
<div class="clear"></div>
