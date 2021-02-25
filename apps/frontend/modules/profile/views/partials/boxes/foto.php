<div id="pane_foto" class="content_pane hidden">
	<? if ($photos) { ?>
		<div class="mt10 p10 mb15 fs12 gallery" style="text-align:center">
			<? foreach ($photos as $photo_id) { ?>
				<? /** ?>
				 * <a href="<?=context::get('image_server').photo_helper::photo_path($photo_id, 'o')?>" rel="prettyPhoto[gallery]">
				 * <?=photo_helper::photo($photo_id, 'h', array())?>
				 * </a>
				 * <? */ ?>
				<a href="/photo?type=1&oid=<?= request::get('id') ?>" class="ml10" rel="prettyPhoto[gallery]">
					<?= photo_helper::photo($photo_id, 'h', array()) ?>
				</a>
			<? } ?>
			<br>
			<a class="right fs12" href="/photo?type=1&oid=<?= request::get('id') ?>"><?= t('Все фото') ?> &rarr;</a><br>
		</div>
	<? } else { ?>
		<div class="m5 acenter fs12">
			<?= t('Фотографий еще нет') ?>
			<? if ($user_data['user_id'] == session::get_user_id() or session::has_credential('admin')) { ?>
				<br/>
				<a href="/photo/add?type=1&oid=<?= request::get('id') ?>"><?= t('Добавить фото') ?></a>
			<? } ?>
		</div>
	<? } ?>
</div>