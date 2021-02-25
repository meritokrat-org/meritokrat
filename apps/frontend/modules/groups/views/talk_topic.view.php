<h2 class="mt10 mr10 column_head">
	<a href="/groups/talk?id=<?=$group['id']?>"><?=t('Обсуждения')?></a>
	&rarr;
	<?=t('Просмотр темы')?>
</h2>

<h1 class="mt5 mb10 bold cbrown" style="margin-left:65px;">
	<?=stripslashes(htmlspecialchars($topic['topic']))?>
	<? if ( ( $topic['user_id'] == session::get_user_id() ) || groups_peer::instance()->is_moderator($group['id'], session::get_user_id()) ) { ?>
		<a href="javascript:;" class="fs11 maroon" onclick="groupsController.deleteTalkTopic(<?=$topic['id']?>);"><?=t('Удалить тему')?></a>
	<? } ?>
</h1>
<?  $counter=0;
    foreach ( $list as $id ) {
    $counter++;
    include 'partials/topic_message.php';
    } ?>

<div class="bottom_line_d mb10 mr10"></div>
<div class="right pager mr10"><?=pager_helper::get_full($pager)?></div>

<div class="clear mb10"></div>

<? if ( session::is_authenticated() ) { ?>
	<form class="form_bg mr10 fs12 mb10" action="/groups/talk_message?id=<?=$topic['id']?>" id="message_form">
		<h3 class="column_head_small"><?=t('Добавить сообщение')?></h3>

		<div class="ml10 mr10">
			<input type="hidden" name="id" value="<?=$topic['id']?>">
			<div class="mb10">
				<textarea style="width: 99%; height: 50px;" name="text" rel="<?=t('Введите текст сообщения')?>"></textarea>
			</div>

			<input name="submit" type="submit" value=" <?=t('Создать')?> " class="button">
			<?=tag_helper::wait_panel('message_wait')?><br /><br />
		</div>
	</form>
<? } else { ?>
	<div class="mr10"><?=user_helper::login_require( t('Войдите в систему, что-бы вести обсуждения') )?></div><br />
<? } ?>