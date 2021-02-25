<? $comment = blogs_comments_peer::instance()->get_item($id) ?>
<? $post_data = blogs_posts_peer::instance()->get_item($comment['post_id']) ?>
<div class="box_content mb15" style="padding:0 10px">
    <h5 class="mb5"><a href="/blogpost<?=$post_data['id']?>"><?=stripslashes(htmlspecialchars($post_data['title']))?></a>
    <? if ($post_data['group_id']) { 
                $group=groups_peer::instance()->get_item($post_data['group_id']);?>
                &rarr; <a href="/group<?=$post_data['group_id']?>"><?=stripslashes(htmlspecialchars($group['title']))?></a>
    <? } ?>
    </h5>
    <div class="fs12">
            <span class="quiet"><?=user_helper::com_date(date($comment['created_ts']))?></span>
            <br/>
            <?=user_helper::get_links($comment['text'])?>
    </div>
</div>