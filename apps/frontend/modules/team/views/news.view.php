<h1 class="column_head mt10 mr10">
	<a href="/team<?= $group['id'] ?>/<?= $group['number'] ?>"><?= stripslashes(htmlspecialchars($group['title'])) ?></a>
	&rarr;
	<?= t('Новости') ?>
</h1>
<? load::view_helper('image') ?>
<div class="mr10">

	<? if (!$list) { ?>
		<div class="screen_message acenter"><?= t('Новостей еще нет') ?></div>
	<? } ?>

	<? foreach ($list as $id) { ?>
		<? $news_item = team_news_peer::instance()->get_item($id); ?>

		<div class="left  p5 fs12 bold box_content " id="news_title_<?= $id ?>"
		     class="fs14"><?= stripslashes(htmlspecialchars($news_item['title'])) ?> </div>
		<div id="news_head_<?= $id ?>" class="mb5 quiet fs11 box_content p5">&nbsp;&nbsp;&nbsp;<br><br>
			<?= date_helper::human($news_item['created_ts'], ', ') ?>
			<? if (team_peer::instance()->is_moderator($group['id'], session::get_user_id())) { ?>
				<a href="javascript:;"
				   onclick="if ( confirm('<?= t('Удалить новость?') ?>') ) teamController.deleteNews(<?= $news_item['id'] ?>,'/team/news?id=<?= $group['id'] ?>');"
				   class="dotted ml10"><?= t('Удалить') ?></a>
				<a class="ml10" href="/team/edit_news?id=<?= $news_item['id'] ?>"
				   id="newsedit"><?= t('Редактировать') ?></a>
			<? } ?>
		</div>
		<div class="fs12" id="news_body_<?= $id ?>">
			<div style="width: 60px;" class="left mr10">
				<?= user_helper::team_photo(user_helper::team_photo_path($group['id'], 's', $group['photo_salt'])) ?>
			</div>
			<? $news_item['text'] = html_entity_decode(text_helper::smart_trim(strip_tags($news_item['text'], '<br><br />'), 320)) ?>
			<p><?= stripslashes($news_item['text']) ?></p>
			<a class="right" href="/team/newsread?id=<?= $news_item['id'] ?>"><?= t('Читать дальше') ?> &rarr;</a>
		</div>
		<div class="clear"></div>
	<? } ?>

	<div class="bottom_line_d mb10"></div>
	<div class="right pager"><?= pager_helper::get_full($pager) ?></div>
</div>

<form id="edit_news_form" class="hidden" action="/team/save_news">
	<input type="hidden" name="news_id" id="news_id" value="">
	<textarea name="text" rel="<?= t('Введите текст') ?>" id="text"></textarea>

	<div class="mt10">
		<input type="submit" class="button" name="submit" value="<?= t('Сохранить') ?>">
		<input type="button" class="button_gray"
		       onclick="$('#edit_news_form').hide(); $('#news_body_' + $('#news_id').val() + ' > p').show();"
		       value="<?= t('Отмена') ?>">
		<?= tag_helper::wait_panel('news_wait') ?>
	</div>
</form>