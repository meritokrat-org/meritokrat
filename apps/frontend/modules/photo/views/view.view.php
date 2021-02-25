<style>.paimg img{border: 2px solid #82ABC1;}</style>
<h1 class="mt10 ml10 column_head column_center">
	<a class="left" href="/<?=$links[$type][0]?>"><?=$links[$type][1]?></a>
        <span class="right"><?=t('Фото')?></span>
</h1>

<div class="form_bg ml10 fs12 p10 mb10">
	<div class="left">
		<a href="/photo?album_id=<?=$photo['album_id']?>"><?=htmlspecialchars(stripslashes($album['title']))?></a>
	</div>
        <? if($access){ ?>
	<a href="/photo/add?album_id=<?=$photo['album_id']?>" class="right"><?=t('Загрузить фото')?></a>
        <? } ?>
	<div class="clear"></div>
</div>

<div class="acenter ml10 paimg">

	<? if ( $photo['title'] ) { ?>
		<h1><?=htmlspecialchars(stripslashes($photo['title']))?></h1>
	<? } ?>

	<?=photo_helper::photo($photo['id'], 'f')?>

	<br />
	<?=t('Автор')?>: <?=strip_tags(user_helper::full_name($photo['user_id']),'<a>');?>

	<? if($access){ ?>
        <a class="maroon ml10 fs11" href="/photo/delete?id=<?=$photo['id']?>" onclick="return confirm('<?=t('Вы уверены?')?>');"><?=t('Удалить')?></a>
	<? } ?>
	<br /><br />

	<div class="box_content p10 fs12">
		<? if ( $previous_id ) { ?>
			<a class="left" href="/photo/view?album_id=<?=$photo['album_id']?>&id=<?=$previous_id?>"><?=t('Предыдущая')?></a>
		<? } ?>
		<? if ( $next_id ) { ?>
			<a class="right" href="/photo/view?album_id=<?=$photo['album_id']?>&id=<?=$next_id?>"><?=t('Следующая')?></a>
		<? } ?>
		<div style="margin: 0px auto;"><?=t('Фото')?> <?=$current?> <?=t('из')?> <b><?=$ptotal?></b></div>
		<div class="clear"></div>
	</div>
</div>

<h3 class="mt10 ml10 column_head"><?=tag_helper::image('common/comments.png', array('class' => 'vcenter'))?> <?=t('Комментарии')?></h3>
<div class="mt10 mb10 ml10" id="comments">
	<? if ( !$comments ) { ?>
		<div id="no_comments" class="acenter fs11 quiet"><?=t('Нет комментариев')?></div>
	<? } else { ?>
		<? foreach ( $comments as $id ) { include 'partials/comment.php'; } ?>
	<? } ?>
</div>
<? if ( session::is_authenticated()  ) { ?>
	<form class="form_bg ml10" id="comment_form" action="/photo/comment">
		<h3 class="column_head_small mb5"><?=t('Добавить комментарий')?></h3>
		<div class="ml10 mr10 mb10">
			<input type="hidden" name="photo_id" value="<?=$photo['id']?>"/>
			<textarea rel="<?=t('Напишите текст')?>" style="width: 99%; height: 75px;" name="text"></textarea>
			<input type="submit" name="submit" class="mt5 mb5 button" value=" <?=t('Отправить')?> " />
			<?=tag_helper::wait_panel()?>
		</div>
	</form>

	<form id="comment_reply_form" class="hidden" action="/photo/comment">
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
