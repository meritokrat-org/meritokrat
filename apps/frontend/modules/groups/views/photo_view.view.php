<h1 class="mt10 mr10 column_head">
	<a href="/group<?=$group['id']?>"><?=htmlspecialchars($group['title'])?></a> &rarr <?=t('Фото')?>
</h1>

<div class="form_bg mr10 fs12 p10 mb10">
	<div class="left">
		<?=t('Фотоальбом')?>
		<a href="/groups/photo?id=<?=$group['id']?>&album_id=<?=$photo['album_id']?>"><?= $photo['album_id'] ? htmlspecialchars($album['title']) : t('Основной альбом') ?></a>
	</div>
	<a href="/groups/photo_add?id=<?=$group['id']?>" class="right"><?=t('Загрузить фото')?></a>
	<div class="clear"></div>
</div>

<div class="acenter mr10">

	<? if ( $photo['title'] ) { ?>
		<h1><?=htmlspecialchars($photo['title'])?></h1>
	<? } ?>

	<?=group_helper::media_photo($photo['id'], 'f')?>

	<br />
	<?=t('Автор')?>: <?=user_helper::full_name($photo['user_id']);?>

	<? if ( ($photo['user_id'] == session::get_user_id()) || groups_peer::instance()->is_moderator($photo['group_id'], session::get_user_id()) ) { ?>
		<a class="maroon ml10 fs11" href="/groups/photo_delete?id=<?=$photo['id']?>" onclick="return confirm('<?=t('Вы уверены?')?>');"><?=t('Удалить')?></a>
	<? } ?>
	<br /><br />

	<div class="box_content p10 fs12">
		<? if ( $previous_id ) { ?>
			<a class="left" href="/groups/photo_view?id=<?=$previous_id?>"><?=t('Предыдущая')?></a>
		<? } ?>
		<? if ( $next_id ) { ?>
			<a class="right" href="/groups/photo_view?id=<?=$next_id?>"><?=t('Следующая')?></a>
		<? } ?>
		<div style="margin: 0px auto;"><?=t('Фото ')?> <?=$current?> <?=t('из')?> <b><?=$total?></b></div>
		<div class="clear"></div>
	</div>
</div>

<h3 class="mt10 mr10 column_head"><?=tag_helper::image('common/comments.png', array('class' => 'vcenter'))?> <?=t('Комментарии')?></h3>
<div class="mt10 mb10" id="comments">
	<? if ( !$comments ) { ?>
		<div id="no_comments" class="acenter fs11 quiet"><?=t('Нет комментариев')?></div>
	<? } else { ?>
		<? foreach ( $comments as $id ) { include 'partials/comment.php'; } ?>
	<? } ?>
</div>
<? if ( session::is_authenticated()  ) { ?>
	<form class="form_bg" id="comment_form" action="/groups/photo_comment">
		<h3 class="column_head_small mb5"><?=t('Добавить комментарий')?></h3>
		<div class="ml10 mr10 mb10">
			<input type="hidden" name="photo_id" value="<?=$photo['id']?>"/>
			<textarea rel="<?=t('Напишите текст')?>" style="width: 99%; height: 75px;" name="text"></textarea>
			<input type="submit" name="submit" class="mt5 mb5 button" value=" <?=t('Отправить')?> " />
			<?=tag_helper::wait_panel()?>
		</div>
	</form>

	<form id="comment_reply_form" class="hidden" action="/groups/photo_comment">
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
