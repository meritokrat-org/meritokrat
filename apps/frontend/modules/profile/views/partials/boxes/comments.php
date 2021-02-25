<div class="content_pane" id="pane_comments">
	<div class="pl5 pt5 mb5 pb5 fs11" style="background: #F7F7F7">
		<a style="color:gray;" href="/profile/comments?id=<?=$user['id']?>"><?=t('Читать все комментарии')?> &rarr;</a>
	</div>
	<? if ( !$com_list ) { ?>
		<div class="screen_message quiet acenter"><?=t('Тут еще нет записей')?></div>
	<? }else{ ?>
	<? foreach ( $com_list as $id ) { ?>
                <? $comment = blogs_comments_peer::instance()->get_item($id) ?>
                <? $post_data = blogs_posts_peer::instance()->get_item($comment['post_id']) ?>
                <div class="box_content mb10" style="padding:0 10px">
                    <h5 class="mb5">
                        <a href="/blogpost<?=$post_data['id']?>"><?=stripslashes(htmlspecialchars($post_data['title']))?></a>
                        <? if ($post_data['group_id']) { 
                        $group=groups_peer::instance()->get_item($post_data['group_id']);?>
                        &rarr; <a href="/group<?=$post_data['group_id']?>"><?=stripslashes(htmlspecialchars($group['title']))?></a>
                        <? } ?>
                    </h5>
                    <div class="fs12">
                            <span class="quiet"><?=user_helper::com_date(date($comment['created_ts']))?></span>
                            <br/>
                            <?=stripslashes(nl2br(htmlspecialchars($comment['text'])))?>
                    </div>
                </div>
	<? }} ?>
	<div class="pl5 pt5 mb5 pb5 fs11" style="background: #F7F7F7">
		<a style="color:gray;" href="/profile/comments?id=<?=$user["id"]?>"><?=t('Все комментарии')?> &rarr;</a>
	</div>
</div>