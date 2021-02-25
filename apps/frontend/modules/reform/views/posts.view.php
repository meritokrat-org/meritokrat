<div class="mt10 mr10">
	<h1 class="column_head">
		<div class="left">
				<a href="/ppo<?=$group['id']?>/<?=$group['number']?>"><?=$group['title']?></a> &rarr; 
				<a href="/ppo/posts?group_id=<?=$group['id']?>"><?=t('Мысли')?></a>
			&rarr; <?=t('Список')?>
		</div>
		<div class="clear"></div>
	</h1>
</div>
<div class="box_content p5 mb10 fs11 mr10"><a href="/ppo/post_edit?group_id=<?=$group['id']?>&add=1"><?=t('Добавить мысль')?> &rarr;</a></div>
    <? if ( !$posts ) { ?>
            <div class="m5 acenter fs12"><?=t('Мыслей еще нет')?></div>
    <? } else {?>
            <? foreach ( $posts as $id ) { ?>
                    <? $post = blogs_posts_peer::instance()->get_item($id) ?>
            <div class="ml10 mb10 box_content p5" style="width: 730px;">
            <div class="left" style="width:60px"><?=user_helper::photo($post['user_id'], 's', array('class' => 'border1'))?>
            </div>
            <div class="left ml10" style="width: 525px;">
                            <div class="mb5 bold fs12">
                                    <a href="/ppo/post?group_id=<?=$group['id']?>&id=<?=$id?>"><?=stripslashes(htmlspecialchars($post['title']))?></a>
                            </div>
                            <div class="fs11 pb5">
                                    <div class="left quiet">
                                            <?=t('Комментариев')?>:
                                            <b class="mr5"><?=blogs_comments_peer::instance()->get_count_by_post($id)?></b><br>
                                            <?=t('Просмотров')?>: <b><?=$post['views']?></b><br>
                                            <?=user_helper::full_name($post['user_id'],false,array('class'=>'mr10 fs11'),false)?> <a href="/ppo/post?group_id=<?=$group['id']?>&id=<?=$id?>"><?=t("Читать дальше")?>  &rarr;</a>
                                    </div>
                            </div>
            </div>

            <div class="clear"></div>
            </div>
            <? } ?>
    <? } ?>
	<div class="bottom_line_d mb10 mr10"></div>
	<div class="right pager mr10"><?=$posts?pager_helper::get_full($pager):''?></div>
<div class="clear"></div>