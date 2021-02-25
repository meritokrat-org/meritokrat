<div class="form_bg ml10 mt10">

	<div class="column_head">
            <a class="left" href="/photo?type=<?=$type?>&oid=<?=$oid?>"><?=$names[$type]?></a>
            <span class="right"><?=t('Загрузка')?></span>
        </div>

	<? if(count($albums)>0 && !request::get('new')){ ?>
        <form id="photo_form" class="form" method="post" enctype="multipart/form-data">
                <input type="hidden" value="<?=$oid?>" name="oid" id="oid">
                <input type="hidden" value="<?=$type?>" name="type" id="type">
		<table width="100%" class="fs12">
			<tr>
				<td class="aright" width="20%"><?=t('Фотоальбом')?></td>
				<td>
                                    <?=tag_helper::select('album_id', $albums, array('id' => 'album_id', 'value' => request::get_int('album_id')))?>
                                    &nbsp;
                                    <a class="button" style="padding:2px 5px;font-size:12px" href="/photo/add?new=1&type=<?=$type?>&oid=<?=$oid?>"><?=t('Добавить')?></a>
                                </td>
			</tr>
			<tr class="hidden" id="album_name_pane">
				<td class="aright"><?=t('Название альбома')?></td>
				<td><input type="text" id="album_name" name="album_name" class="text" /></td>
			</tr>
			<tr>
				<td class="aright"><?=t('Файлы')?></td>
				<td>
					<div class="mb5">
						<input type="file" onchange="$(this).parent().parent().append('<div class=mb5>' + $(this).parent().html() + '</div>')" name="file[]"/>
						<span class="quiet ml10">Текст</span>
						<input type="text" name="title[]" value=""/>
					</div>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" name="submit" onclick="$('#wait_panel').show()" class="button" value=" <?=t('Сохранить')?> ">
					<input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
					<?=tag_helper::wait_panel() ?>
				</td>
			</tr>
		</table>
	</form>
        <? }else{ ?>
        <form class="form_bg mr10 fs12 mb10" method="get">
                <input type="hidden" value="<?=$oid?>" name="oid" id="oid">
                <input type="hidden" value="<?=$type?>" name="type" id="type">
                <table width="100%" class="mt10"><tbody>
                        <tr>
                                <td width="25%" class="aright"><?=t('Название альбома')?></td>
                                <td><input type="text" rel="Введите название альбома" name="album_name" style="width: 450px;" class="text" id="album_name"></td>
                        </tr>
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
        <? } ?>
</div>
