<? $photo = photo_peer::instance()->get_item($id) ?>
<div id="photo_<?=$id?>" class="form_bg left p10 mr5 mb5" style="height:70px;width: 220px;">
	<?=photo_helper::photo($id, 'sm', array('class' => 'border1 left'), true)?>
	<div class="ml10" style="margin-left: 65px;">
		<div>
                        <input type="hidden" class="text" name="album_id" id="album_id" value="<?=$photo['album_id']?>"/>
			<input style="width:150px" type="text" class="text mb5 album_title" name="album_title" value="<?=htmlspecialchars(stripslashes($photo['title']))?>"/>
		</div>
                <div class="fs11">
                        <a class="left" onclick="photoController.save(<?=$id?>,<?=$photo['album_id']?>);" href="javascript:;"><?=t('Сохранить')?></a>
                        <a class="right maroon" onclick="photoController.del(<?=$id?>,<?=$photo['album_id']?>);" href="javascript:;"><?=t('Удалить')?></a>
                </div>
                <?=tag_helper::wait_panel() ?>
                <div class="success hidden p5 fs12"><?=t('Изменения сохранены')?></div>
	</div>
	<div class="clear"></div>
</div>