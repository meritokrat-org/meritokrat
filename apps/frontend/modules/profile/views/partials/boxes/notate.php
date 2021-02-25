<div class="content_pane hidden" id="pane_notate">    
	<div id="notate_posts_div" class="pl5 pt5 mb5 pb5 fs11" style="background: #F7F7F7">
	<? if ( !$notate_list ) { ?>
		<div class="screen_message quiet acenter"><?=t('Тут еще нет записей')?></div>
	<? } ?>
	<? foreach ( $notate_list as $id ) { ?>
		<? $post_data = blogs_posts_peer::instance()->get_item($id) ?>
		<? include dirname(__FILE__) . '/../../../../blogs/views/partials/post.php'; ?>
	<? } ?>
        </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#notate_posts').click(function(){
                $('#blogs_posts').addClass('bold');
                $('#groups_posts').removeClass('bold');
                $('#groups_posts_div').hide();
                $('#blogs_posts_div').show();
            });
    });
</script>