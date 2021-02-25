<h1 class="column_head mt10 mr10 mb5">
	<a href="/help?party"><?=t('Партия')?></a>
		<? /*<div class="right fs11">
			<? if ( $idea['tags_text'] ) { ?>
				<b class="quiet mr5"><?=t('Метки')?></b>
				<? foreach ( explode(', ', $idea['tags_text']) as $tag ) echo "<a href=\"/ideas/index?tag=" . stripslashes(htmlspecialchars($tag)) . "\" class=\"mr10\">{$tag}</a>" ?>
			<? } ?>
		</div>*/?>
</h1>
	<div class="left acenter mt5" style="width: 80px;">
<?=user_helper::photo($idea['user_id'], 't', array('class' => 'border1 mr10', 'align' => 'left'))?>
		<div class="fs11 mb10">
			<?=user_helper::full_name($idea['user_id'],true,array("style"=>"font-weight:bold;"))?>
		</div>
            <div class="fs11 mb5 quiet"><?=t('Просмотров')?></div>
        <div class="acenter bold"><?=$idea['views']?></div>
        <? load::model('bookmarks/bookmarks'); ?>
        <? $bkm = bookmarks_peer::instance()->is_bookmarked(session::get_user_id(),4,$idea['id']); ?>

            <a class="fs12 mt5 b4 <?=($bkm)?'hide':''?>" href="#add_bookmark" onclick="Application.bookmarkItem('4','<?=$idea['id']?>');return false;"><?=t('В закладки')?></a>
            <a class="fs12 mt5 b4 <?=($bkm)?'':'hide'?>" href="#del_bookmark" onclick="Application.unbookmarkItem('4','<?=$idea['id']?>');return false;"><?=t('Удалить из закладок')?></a>

    </div>
<div class="left ml10" style="width: 660px;margin-top: 1px;">

	<? if ( session::is_authenticated() ) { ?>
    <div class="right" style="width:100px">
		<?=user_helper::share_item('idea', $idea['id'], array('class' => 'right'))?>
    </div>
	<? } ?>
	<h1 class="mb10 fs18 cbrown">
            <?=session::get('language')=='ru' ? stripslashes(htmlspecialchars($idea['title_ru'])) : stripslashes(htmlspecialchars($idea['title']))?>
        </h1>
<? /*        <div class="fs12 mb5">
		<?=t('Категория')?>: <a href="/ideas/index?segment=<?=urlencode(ideas_peer::get_segment_name($idea['segment']))?>"><?=ideas_peer::get_segment_name($idea['segment'])?></a>
	</div>
*/ ?>
    <div id="rate_pane" class="right fs12 p5 mb15 aright">
           	<?=tag_helper::image('common/up.gif', array('class'=>'left','style'=>'margin-top:-3px;'))?>
		<? if (session::is_authenticated() && !ideas_peer::instance()->has_voted($idea['id'], session::get_user_id()) ) { ?>
			<a class="ml10 bold dotted" id="rate_pane" href="javascript:;" onclick="ideasController.rateIdea(<?=$idea['id']?>);"><?=t('Поддержать')?></a>
		<? } ?>
                 &nbsp; <?=t('Идею поддерживают')?>: <b id="rate"><?=$idea['rate']?></b>
    </div>
	<div class="fs11 mt10 mb15 left">
		<span class="quiet"><?=date_helper::human($idea['created_ts'], ', ')?></span>
                <!--a class="ml10" href="mailto:info@meritokrat.org?subject=<?=t('Предложение по идеологии')?>"><?=t('Отправить предложение')?></a-->
	<? /* if ( session::has_credential('moderator') ) { ?>
		&nbsp;&nbsp;&nbsp;&nbsp; <a href="/ideas/hide?id=<?=$idea['id']?>" class="fs11"><?=t('Скрыть')?></a>
	<? } */ ?>
        <? if ( session::has_credential('moderator') or session::has_credential('admin') or session::get_user_id()==$idea['user_id'] ) { ?>
		&nbsp;&nbsp;&nbsp;&nbsp; <a href="/ideas/edit?id=<?=$idea['id']?>" class="fs11"><?=t('Редактировать')?></a>
                &nbsp;&nbsp;&nbsp;&nbsp; <a href="/ideas/delete?id=<?=$idea['id']?>" class="fs11" onclick="return confirm('Удалить эту запись?');"><?=t('Удалить')?></a>
	<? } ?>
               
	</div>
	<div class="clear"></div>
<? /*
	<div class="fs12 mb10 mt10 p5 aright" style="background: #FAFAFA; border: 1px solid #EEE;" id="rate_pane">
		<?=tag_helper::image('common/up.gif', array('class' => 'vcenter'))?> <?=t('Идею поддерживают')?>: <b id="rate"><?=$idea['rate']?></b>
		<? if (session::is_authenticated() && !ideas_peer::instance()->has_voted($idea['id'], session::get_user_id()) ) { ?>
			<a class="ml10 bold dotted" href="javascript:;" onclick="ideasController.rateIdea(<?=$idea['id']?>);"><?=t('Поддержать')?></a>
		<? } ?>
	</div>
*/ ?>
	<div><?=session::get('language')=='ru' ? stripslashes($idea['text_ru']) : stripslashes($idea['text'])?></div><br />

	<div class="column_head">
        <div class="left icocomments mr5" style="margin-top:3px"></div>
        <div class="left" style="margin-top:2px"><?=t('Комментарии')?></div>
        </div>

	<div class="mt10 mb10" id="comments">
		<? if ( !$comments ) { ?>
			<div id="no_comments" class="acenter fs11 quiet"><?=t('Нет комментариев')?></div>
		<? } else { ?>
			<? foreach ( $comments as $id ) { include 'partials/comment.php'; } ?>
		<? } ?>
	</div>
	<? if ( session::is_authenticated() ) { ?>
		<form class="form_bg" id="comment_form" action="/ideas/comment">
			<h3 class="column_head_small mb5"><?=t('Добавить комментарий')?></h3>
			<div class="ml10 mr10 mb10">
				<input type="hidden" name="idea_id" value="<?=$idea['id']?>"/>
				<textarea rel="<?=t('Напишите текст')?>" style="width: 99%; height: 75px;" name="text"></textarea>
				<input type="submit" name="submit" class="mt5 mb5 button" value=" <?=t('Отправить')?> " />
				<?=tag_helper::wait_panel()?>
			</div>
		</form>

		<form id="comment_reply_form" class="hidden" action="/ideas/comment">
			<input type="hidden" name="idea_id" value="<?=$idea['id']?>"/>
			<input type="hidden" name="parent_id"/>
			<textarea rel="<?=t('Напишите текст')?>" style="width: 99%; height: 50px;" name="text"></textarea>
			<input type="submit" name="submit" class="mt5 mb5 button" value=" <?=t('Отправить')?> " />
			<?=tag_helper::wait_panel()?>
			<input type="button" class="button_gray" value="<?=t('Отмена')?>" onclick="$('#comment_reply_form').hide();">
		</form>

                <form id="comment_update_form" class="hidden" action="/ideas/comment">
                        <input type="hidden" name="upd_id" id="upd_id"/>
                        <input type="hidden" name="why" id="why"/>
                        <input type="hidden" name="idea_id" value="<?=$idea['id']?>"/>
                        <textarea rel="<?=t('Напишите текст')?>" style="width: 99%; height: 100px;" name="text"></textarea>
                        <input type="submit" name="submit" class="mt5 mb5 button" value=" <?=t('Сохранить')?> " />
                        <?=tag_helper::wait_panel()?>
                        <input type="button" class="button_gray" value="<?=t('Отмена')?>" onclick="Application.cancelComUpd()">
                </form>
	<? } else { ?>
		<?=user_helper::login_require( t('Войдите на сайт, что-бы оставить комментарий') )?>
	<? } ?>
</div>