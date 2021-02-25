<div class="content_pane" id="pane_blog">
<? /*	<div class="pl5 pt5 mb5 pb5 fs11" style="background: #F7F7F7">
                <!--a id="blogs_posts" href="javascript:;" class="cgray ml10"><?=t('Общие')?></a> <?//=$count_blog_posts?>
                <a id="groups_posts" href="javascript:;" class="cgray ml10"><?=t('В сообществах')?></a--> <?//=$count_groups_post?>
		<?// if ( session::get_user_id() == $user['id'] ) { ?><!--a class="ml10" style="color:gray;" href="/blogs/edit"><?=t('Написать')?></a--><?// } ?>
                <!--a class="cgray right mr10" href="/blog-<?=$user_data['user_id']?>"><?=t('Читать все мысли')?> &rarr;</a-->
	</div> */ ?>
    
	<div id="blogs_posts_div" class="pl5 pt5 mb5 pb5 fs11" style="background: #F7F7F7">
	<? if ( !$blog_list ) { ?>
		<div class="screen_message quiet acenter"><?=t('Тут еще нет записей')?></div>
	<? }else ?>
	<? foreach ( $blog_list as $id ) { ?>
		<? $post_data = blogs_posts_peer::instance()->get_item($id);
                ?>
		<? include dirname(__FILE__) . '/../../../../blogs/views/partials/post.php'; ?>
	<? } ?>
        </div>
    
	<!--div id="groups_posts_div" class="pl5 pt5 mb5 pb5 fs11 hide" style="background: #F7F7F7">-->
	<? if ( !$groups_post_list ) { ?>		
        <div class="screen_message quiet acenter"><?=t('Тут еще нет записей')?></div>
	<? }else ?>
	<? foreach ( $groups_post_list as $id ) { 
            ?>
                                                                                    
		<? $post_data = blogs_posts_peer::instance()->get_item($id);?>
		<? include dirname(__FILE__) . '/../../../../blogs/views/partials/post.php'; ?>
	<? } ?>
	<div class="pl5 pt5 mb5 pb5 fs11" style="background: #F7F7F7">
		<a style="color:gray;" href="/blog-<?=$user["id"]?>"><?=t('Все публикации')?> &rarr;</a>
	</div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#blogs_posts').click(function(){
                $('#blogs_posts').addClass('bold');
                $('#groups_posts').removeClass('bold');
                $('#groups_posts_div').hide();
                $('#blogs_posts_div').show();
            });
        $('#groups_posts').click(function(){
                $('#groups_posts').addClass('bold');
                $('#blogs_posts').removeClass('bold');
                $('#blogs_posts_div').hide();
                $('#groups_posts_div').show();
            });
    });
</script>