<style>.paimg img{border: 2px solid #82ABC1;}</style>
<h1 class="mt10 ml10 column_head column_center">
	<a class="left" href="/photocompetition"><?=t('Фотоконкурс')?></a>
        <span class="right"><?=$photo['title']?></span>
</h1>
<div class="acenter ml10 paimg">

	<? if ( $photo['title'] ) { ?>
		<div class="m5 fs16 bold cbrown">
                        <?=htmlspecialchars(stripslashes($photo['title']))?>
                        <? if(session::has_credential('admin') || $photo['user_id']==session::get_user_id()){ ?>
                                <a class="maroon ml10 mr10 right fs11" href="/photocompetition/delete?photo_id=<?=$photo['id']?>" onclick="return confirm('<?=t('Вы уверены?')?>');"><?=t('Удалить')?></a>
                        <? } ?>
                </div>
	<? } ?>

	<?=photo_competition_peer::photo($photo['id'], 'f')?>

	<br />
	<?=t('Автор')?>: <?=strip_tags(user_helper::full_name($photo['user_id']),'<a>');?>
	<br />
        <? if ($photo['user_id']!=session::get_user_id() && !photo_competition_peer::instance()->has_voted($photo['id'], session::get_user_id()) && session::is_authenticated()) { ?>
            <!--<input type="button" class="button vote mt5" id="<?=$photo['id']?>" class="vote" value="<?=t('Голосовать')?>">-->
        <? } ?>
            <span class="bold ml15 mr5" id="votes">
                <?=$photo['votes']?>
            </span>
            <span class="fs11 cgray">
                <?=session::has_credential('admin') ? '<a href="/photocompetition/voters?id='.$photo["id"].'">*голосів</a>' : 'голосів' ?>
            </span>
	<br /><br />

	<div class="box_content p10 fs12">
			<a class="left" href="/photocompetition/<?=db::get_scalar("SELECT id FROM photo_competition WHERE id>".$photo['id']." ORDER BY id ASC LIMIT 1")?>"><?=t('Предыдущая')?></a>
                        <a class="right" href="/photocompetition/<?=db::get_scalar("SELECT id FROM photo_competition WHERE id<".$photo['id']." ORDER BY id DESC LIMIT 1")?>"><?=t('Следующая')?></a>
		<div class="clear"></div>
	</div>
</div>

<h3 class="mt10 ml10 column_head"><?=tag_helper::image('common/comments.png', array('class' => 'vcenter'))?> <?=t('Комментарии')?></h3>
<div class="mt10 mb10 ml10" id="comments">
	<? if ( !$photo_comments ) { ?>
		<div id="no_comments" class="acenter fs11 quiet"><?=t('Нет комментариев')?></div>
	<? } else { ?>
		<? foreach ( $photo_comments as $id ) { include 'partials/comment.php'; } ?>
	<? } ?>
</div>
<? if ( session::is_authenticated()  ) { ?>
	<form class="form_bg ml10" id="comment_form" action="/photocompetition/comment">
		<h3 class="column_head_small mb5"><?=t('Добавить комментарий')?></h3>
		<div class="ml10 mr10 mb10">
			<input type="hidden" name="photo_id" value="<?=$photo['id']?>"/>
			<textarea rel="<?=t('Напишите текст')?>" style="width: 99%; height: 75px;" name="text"></textarea>
			<input type="submit" name="submit" class="mt5 mb5 button" value=" <?=t('Отправить')?> " />
			<?=tag_helper::wait_panel()?>
		</div>
	</form>

	<form id="comment_reply_form" class="hidden" action="/photocompetition/comment">
		<input type="hidden" name="photo_id" value="<?=$photo['id']?>"/>
		<input type="hidden" name="parent_id"/>
		<textarea rel="<?=t('Напишите текст')?>" style="width: 99%; height: 50px;" name="text"></textarea>
		<input type="submit" name="submit" class="mt5 mb5 button" value=" <?=t('Отправить')?> " />
		<?=tag_helper::wait_panel()?>
		<input type="button" class="button_gray" value="<?=t('Отмена')?>" onclick="$('#comment_reply_form').hide();">
	</form>
<? } else { ?>
	<?=user_helper::login_require( t('Войдите на сайт, что-бы оставить комментарий') )?>
<? } ?>
<script type="text/javascript">
jQuery(document).ready(function(){
        var votes = parseInt($('#votes').html());
        $('.vote').click(function(){
        $(this).fadeOut(100);
        $.post(
                '/photocompetition/vote',
                {id: $(this).attr('id')},
                function () {
                        $('#votes').html(votes+1);
                }
        );
                    
    });
});

</script>