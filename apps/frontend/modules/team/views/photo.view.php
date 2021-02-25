<h1 class="mt10 mr10 column_head">
	<a href="/group<?=$group['id']?>"><?=htmlspecialchars($group['title'])?></a> &rarr; <?=t('Фото')?>
</h1>

<? if ( (ppo_members_peer::instance()->is_member($group['id'], session::get_user_id()) ) or session::has_credential('admin')) { ?>
	<div class="form_bg mr10 fs12 p10 mb10">
		<div class="left">
			<? if ( $album_id !== null ) { ?>
				<a href="/ppo/photo?id=<?=$group['id']?>"><?=t('Фотоальбомы сообщества')?></a> &rarr;
				<?= $album ? htmlspecialchars($album['title']) : t('Основной альбом') ?>
			<? } else { ?>
				<?=t('Фотоальбомы сообщества')?>
			<? } ?>
		</div>
            <div class="right">
                <a onclick="$('#add_photoalbum').show(50);" href="javascript:;"><?=t('Добавить альбом')?></a> | 
		<a href="/ppo/photo_add?id=<?=$group['id']?>&album_id=<?=$album_id?>" class="right">&nbsp;<?=t('Загрузить фото')?></a>
            </div>
		<div class="clear"></div>
                <div id="add_photoalbum" class="hidden" style="display: none;">
                    <form class="form_bg mr10 fs12 p10 mb10" id="photoalbum_form" method="get">
			<input type="hidden" value="<?=$group['id']?>" name="id" id="id">
			<table width="100%" class="mt10">
				<tbody><tr>
					<td width="18%" class="aright">Назва</td>
					<td><input type="text" rel="Введите название альбома" name="title" style="width: 500px;" class="text" id="title"></td>
				</tr>
				<!--tr>
					<td width="18%" class="aright">Текст</td>
					<td><textarea rel="Введіть текст" name="text" id="text" style="width: 500px; height: 50px; display: none;"></textarea></td>
				</tr-->
				<tr>
					<td></td>
					<td>
						<input type="submit" class="button" value="<?=t('Добавить')?>" name="submit" id="submit">
						<input type="button" onclick="$('#add_photoalbum').hide();" value="<?=t('Отмена')?>" class="button_gray" id="">
						<?=tag_helper::wait_panel() ?>
                                                <div class="success hidden mr10 mt10"><?=t('Фотоальбом добавлен')?></div></td>
				</tr>
			</tbody></table>
                </form>
		</div>
	</div>
<? } ?>

<? if ( $album_id !== null ) { ?>
	<? if ( $photos ) { ?>
		<? foreach ( $photos as $id ) { include 'partials/photo.php'; } ?>
		<div class="clear"></div><br />
		<div class="bottom_line_d mb10 mr10"></div>
		<div class="right pager mr10"><?=pager_helper::get_full($pager)?></div>
	<? } else { ?>
		<div class="acenter fs12 p5 ml10"><?=t('Фотографий еще нет')?></div>
	<? } ?>
<? } else { ?>
	<? foreach ( $albums as $album_id ) { include 'partials/album.php'; } ?>
<? } ?>