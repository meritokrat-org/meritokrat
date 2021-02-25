<style>
.textcontainer img{margin:0 10px 5px 0}
</style>

<div class="mt10 mr10">
	<h1 class="column_head">
		<div class="left">
				<a href="/group<?=$post_data['group_id']?>"><?=stripslashes(htmlspecialchars($group['title']))?></a> &rarr; 
				<a href="/groups/posts?group_id=<?=$post_data['group_id']?>"><?=t('Мысли')?></a>
			&rarr; <?=t('Просмотр')?>
		</div>
		<div class="right fs11">
		</div>
		<div class="clear"></div>
	</h1>

</div>


<div class="mr10">
	<div class="left acenter mt5" style="width: 80px;">
		<?=user_helper::photo($post_data['user_id'], 't', array('class' => 'border1'))?>
		<div class="fs11 mb10">
			<?=user_helper::full_name($post_data['user_id'],true,array("style"=>"font-weight:bold;"))?>
			<br /><br />
			<a href="/blog-<?=$post_data['user_id']?>"><?=t('Мысли пользователя')?></a>
		</div>
		
        <? if ( session::is_authenticated() ){ ?>
        <? load::model('bookmarks/bookmarks'); ?>
        <? $bkm = bookmarks_peer::instance()->is_bookmarked(session::get_user_id(),6,$post_data['user_id']); ?>
        <div class="fs11 mb10">
            <a href="#add_bookmark" class="b6 <?=($bkm)?'hide':''?>" onclick="Application.bookmarkItem('6','<?=$post_data['user_id']?>');return false;"><?=t('Добавить в любимые авторы')?></a>
		    <a href="#del_bookmark" class="b6 <?=($bkm)?'':'hide'?>" onclick="Application.unbookmarkItem('6','<?=$post_data['user_id']?>');return false;"><?=t('Удалить из любимых авторов')?></a>  
        </div>
        <? } ?>
        
        <br />
		<div class="fs11 mb5 quiet"><?=t('Оценка')?></div>

		<div class="mb5 acenter" id="vote_value">
			<span class="green"><?=$post_data['for']?></span>
			<span class="red ml5"><?=$post_data['against']?></span>
		</div>

		<? if ( session::has_credential('admin') ) { ?>
			<a class="fs10" href="/blogs/rate_history?id=<?=$post_data['id']?>"><?=t('История')?></a>
			<br /><br />
		<? } ?>

		<? if ( session::is_authenticated() && !$is_blacklisted && !blogs_posts_peer::instance()->has_rated($post_data['id'], session::get_user_id()) ) { ?>
			<div id="vote_pane">
				<a title="<?=t('Голосовать за')?>" class="fs11"onclick="groupsController.vote( true );" href="javascript:;"><?=tag_helper::image('common/up.gif')?></a>
				<a title="<?=t('Голосовать против')?>" class="fs11"onclick="groupsController.vote( false );" href="#comment_form"><?=tag_helper::image('common/down.gif')?></a>
			</div>
		<? } ?>

		<div class="fs11 mb5 quiet"><?=t('Просмотров')?></div>
		<div class="acenter bold"><?=(int)$post_data['views']?></div>
	</div>

	<div class="left ml10 mt5" style="width: 645px; margin-left:25px;">
	<h1 class="mb10 mt10 fs18 cbrown"><?=stripslashes(htmlspecialchars($post_data['title']))?></h1>

        <div class="fs12 mb5">
                        <?=($post_data['edit'] && !in_array($post_data['user_id'], user_auth_peer::get_admins()))?t('Отредактировано').': '.strip_tags(user_helper::full_name($post_data['edit']),'<a>').(($post_data['edit_ts'])?' '.user_helper::com_date(date($post_data['edit_ts'])):'').'<br/>':''?>
			<? if ( $post_data['tags_text'] ) { ?>
				<b class="quiet mr5"><?=t('Метки')?>:</b>
				<? foreach ( explode(', ', $post_data['tags_text']) as $tag ) echo "<a href=\"/blogs/index?tag=" . htmlspecialchars($tag) . "\" class=\"mr5\">".stripslashes($tag)."</a>" ?>
			<? } ?>
	</div>
	<div class="fs11 left  mb10">
        <?=date_helper::human($post_data['created_ts'], ', ')?>
	<? if ( ( $post_data['user_id'] == session::get_user_id() ) || ( session::has_credential('moderator') ) || groups_peer::instance()->is_moderator($post_data['group_id'], session::get_user_id()) ) { ?>
	<div class="right mr10 actionpanel">
                
                <a id="blogedit<?#(session::get_user_id()==5 or session::get_user_id()==$post_data['user_id']) ? '' : 'blogedit'?>" href="<?=session::get_user_id()==5 ? '/groups/post_edit?id='.$post_data['id'] : 'javascript:;'?>" class="ml10"><?=t('Редактировать')?></a>
		<a <? if (session::get_user_id()!=$post_data['user_id'] && session::get_user_id()!=5) { ?>
                    onclick="Application.delItem('<?=$post_data['id']?>','blogs/delete')" href="javascript:;"
                    <? } else { ?>
                    href="/blogs/delete?id=<?=$post_data['id']?>"
                    <? } ?>
                    class="ml10"><?=t('Удалить')?></a>
                
		<? if ( session::has_credential('moderator') ) { ?>
			<!--a href="/blogs/hide?id=<?=$post_data['id']?>" class="ml10"><?= $post_data['visible']==true ? t('Скрыть') : t('Показать')?></a>
			<a href="/blogs/valuable?id=<?=$post_data['id']?>" class="ml10"><?=t('Сделать важным')?></a-->
<!--
			<? if ( !$post_data['favorite'] ) { ?>
				<a href="/blogs/favorite?id=<?=$post_data['id']?>" class="ml10"><?=t('В закладки')?></a>
			<? } else { ?>
				<a href="/blogs/favorite?id=<?=$post_data['id']?>" class="ml10"><?=t('Убрать из закладок')?></a>
			<? } ?>
-->                      
		<? } ?>
        
        <? if ( session::is_authenticated() ){ ?>
        <? $bkm = bookmarks_peer::instance()->is_bookmarked(session::get_user_id(),1,$post_data['id']); ?>
            <a class="ml10 b1 <?=($bkm)?'hide':''?>" href="#add_bookmark" onclick="Application.bookmarkItem('1','<?=$post_data['id']?>');return false;"><?=t('В закладки')?></a>
            <a class="ml10 b1 <?=($bkm)?'':'hide'?>" href="#del_bookmark" onclick="Application.unbookmarkItem('1','<?=$post_data['id']?>');return false;"><?=t('Удалить из закладок')?></a>
        <? } ?>
        

        <? if(session::get_user_id()==5){ ?> 
            <? if($post_data['group_id']>0 && $post_data['type']==0){ ?>
                <a href="javascript:;" class="typechanger ml10" onclick="blogsController.changeType(this,<?=$post_data['id']?>,<?=blogs_posts_peer::TYPE_GROUP_POST?>)">Прибрати з публікацій</a>
            <? } else { ?>
                <a href="javascript:;" class="typechanger ml10" onclick="blogsController.changeType(this,<?=$post_data['id']?>,<?=blogs_posts_peer::TYPE_MIND_POST?>)"><?=t('Перенести в публикации')?></a>
            <? } ?>
        <? } ?>
                
                
        </div>
	<? } ?>
        </div>
		<div class="clear"></div>
                <div class="mt5 textcontainer">
		<?=user_helper::get_links(stripslashes($post_data['body']),false)?>
                </div>
                <div class="clear"></div>
		<div id="additional" style="border-top: 1px solid #EEE;">
			<div class="left mt5">

<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style">
<?=user_helper::get_adds($post_data['title'],$post_data['preview'],$post_data['body'])?>
</div>
<!-- AddThis Button END -->

			</div>

			<? /* <div class="fs11 right ml10" style="margin-top:4px;"><a href="mailto:secretariat@shevchenko.ua?subject=<?=t('Предложение по идеологии')?>" class="promote right mb10"><?=t('Отправить предложение')?></a></div> */ ?>

			<? if ( session::is_authenticated() ) { ?>
                <?=user_helper::share_item('blog_post', $post_data['id'], array('class' => 'mb10 ml5 right'))?>
                <? if ( session::get_user_id() != $post_data['user_id'] ) { ?>
                    <a class="bookmark mb10 ml5 right b1" style="<?=($bkm)?'display:none':''?>" href="#add_bookmark" onclick="Application.bookmarkItem('1','<?=$post_data['id']?>');return false;"><b></b><span><?=t('В закладки')?></span></a>
                    <a class="unbkmrk mb10 ml5 right b1" style="<?=($bkm)?'':'display:none'?>" href="#del_bookmark" onclick="Application.unbookmarkItem('1','<?=$post_data['id']?>');return false;"><b></b><span><?=t('Удалить из закладок')?></span></a>
                <? } ?>
			<? } ?>
			<div class="clear"></div> 
		</div>

		<div class="acenter p10">
		</div>
		<? if(!$post_data['nocomments']){ ?>
		<h3 class="column_head"><?=tag_helper::image('common/comments.png', array('class' => 'vcenter'))?> <?=t('Комментарии')?></h3>
                <? if ( session::is_authenticated()  && user_auth_peer::instance()->get_rights(session::get_user_id(),5) ) { ?>
		<div class="mt10 mb10" id="comments">
			<? if ( !$comments ) { ?>
				<div id="no_comments" class="acenter fs11 quiet"><?=t('Нет комментариев')?></div>
			<? } else { ?>
				<? foreach ( $comments as $id ) { include 'partials/post_comment.php'; } ?>
			<? } ?>
		</div>
		<? } ?>
			<? if ( !$is_blacklisted && !$post_data['nocomments']) { ?>
				<form class="form_bg" id="comment_form" action="/blogs/comment">
					<h3 class="column_head_small mb5"><?=t('Добавить комментарий')?></h3>
					<div class="ml10 mr10 mb10">
                                                <input type="hidden" name="neg_msg" value="0"/>
						<input type="hidden" name="post_id" value="<?=$post_data['id']?>"/>
						<textarea rel="<?=t('Напишите текст')?>" style="width: 99%; height: 75px;" name="text"></textarea>
						<input type="submit" name="submit" class="mt5 mb5 button left" value=" <?=t('Отправить')?> " />
                                                <input type="button" name="cancel_v" style="display: none;" class="mt5 ml5 mb5 left button_gray" value=" <?=t('Отменить')?> " />
						<?=tag_helper::wait_panel()?>
					</div>
				</form>

				<form id="comment_reply_form" class="hidden" action="/blogs/comment">
					<input type="hidden" name="post_id" value="<?=$post_data['id']?>"/>
					<input type="hidden" name="parent_id"/>
					<textarea rel="<?=t('Напишите текст')?>" style="width: 99%; height: 50px;" name="text"></textarea>
					<input type="submit" name="submit" class="mt5 mb5 button" value=" <?=t('Добавить')?> " />
					<?=tag_helper::wait_panel()?>
					<input type="button" class="button_gray" value="<?=t('Отмена')?>" onclick="$('#comment_reply_form').hide();">
				</form>

                                <form id="comment_update_form" class="hidden" action="/blogs/comment">
					<input type="hidden" name="upd_id" id="upd_id"/>
                                        <input type="hidden" name="why" id="why"/>
                                        <input type="hidden" name="post_id" value="<?=$post_data['id']?>"/>
					<textarea rel="<?=t('Напишите текст')?>" style="width: 99%; height: 100px;" name="text"></textarea>
					<input type="submit" name="submit" class="mt5 mb5 button" value=" <?=t('Сохранить')?> " />
					<?=tag_helper::wait_panel()?>
					<input type="button" class="button_gray" value="<?=t('Отмена')?>" onclick="Application.cancelComUpd()">
				</form>
			<? } ?>
		<? } else { ?>
                <?if (!session::is_authenticated()  ){?>
                        <div class="mt10 p5 acenter fs12" style="border: 1px solid #E4E4E4; background: #F7F7F7;">
                            <a href="/sign/up"><?=t('Зарегистрируйтесь')?></a>&nbsp;<?=t('или')?>&nbsp;<a href="/sign"><?=t('войдите')?></a>&nbsp;<?=t('на сайт чтобы просматривать и оставлять комментарии')?>
                    </div>
                <?}elseif(!$post_data['nocomments']){?>
			<?=user_helper::login_require( t('Недостаточно прав').', '.t('чтобы оставлять комментарии') )?>
		<? }} ?>
	</div>
</div>

<script type="text/javascript" src="https://s1.meritokrat.org/module_blogs.js?1" charset="utf-8"></script>
<script type="text/javascript">
    $('#blogedit').click(function(){
        <?$why_text=mem_cache::i()->get("group_".$post_data['id']."_".session::get_user_id());?>
        var why;
        var whytext='<?=$why_text?>';
        <? if (session::get_user_id()!=$post_data['user_id'] && !session::has_credential('admin')) { ?>
                var why = prompt("Вкажiть причину редагування:", whytext);
                if(!why){
                    alert("Ви не можете редагувати без поважної причини");
                    return false;
                }
        <? } ?>
        window.location = 'http://'+window.location.hostname+'/groups/post_edit?id=<?=$post_data['id']?>&why='+why;
    });
    $(document).ready(function($){
        $('.comhide').unbind('click').click(function(){
            $(this).hide().next().show();
            $('#child_comments_'+$(this).attr('rel')).hide();
        });
        $('.comshow').unbind('click').click(function(){
            $(this).hide().prev().show();
            $('#child_comments_'+$(this).attr('rel')).show();
        });
        $('.textcontainer').find('img').each(function(){
            var wdth = $(this).width();
            if(wdth<400){
                $(this).attr('align','left');
            }else{
                $(this).css({'margin-left':(620-wdth)/2+'px'});
            }
        });
    });
    function doprint(){
        /*document.getElementById('additional').style.display = 'none';
        document.getElementById('footer').style.display = 'none';
        document.getElementById('header').style.display = 'none';
        document.getElementById('left').style.display = 'none';
        document.getElementById('comment_form').style.display = 'none';*/
        $('#additional, #footer, #header, #left, #comment_form, #comments, .column_head, #vote_pane, .actionpanel').hide();
        $('.textcontainer').addClass('fs16').css({width:'800px'});
        print();
    }
</script>
