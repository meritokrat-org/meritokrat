<h1 class="mt10 mr10 column_head"><?=t("Редактирование")?></h1>

	<div class="form_bg mr10 fs12 p10 mb10">
<? if (request::get_int('dir_id')) {
    load::model('library/files_dirs');
    $dir = library_files_dirs_peer::instance()->get_item(request::get_int('dir_id'));
    ?>
                    <form action="/profile/dir_edit" class="form" method="post">
			 <input type="hidden" name="dir_id" value="<?=request::get_int('dir_id')?>">
			<table width="100%" class="mt10">
			<tbody>
                                <tr>
					<td width="18%" class="aright"><?=t('Название')?></td>
					<td><input type="text" rel="Введите название папки" name="title" style="width: 500px;" class="text" value="<?=$dir['title']?>"></td>
				</tr>
                                <tr>
                                        <td class="aright" width="20%"><?=t('Батько')?></td>
                                        <? $dirs[0]='Немає';                                        ?>
                                        <td><?=tag_helper::select('parent_id', $dirs, array('id' => 'parent_id','value'=>$dir['parent_id']))?></td>
                                </tr>
				<tr>
					<td width="18%" class="aright">Позиція</td>
					<td><input type="text" name="position" style="width: 50px;" class="text" id="title" value="<?=$dir['position']?>"></td>
				</tr>
				<tr>
					<td></td>
					<td>
						<input type="submit" class="button" value="<?=t('Сохранить')?>" name="submit">
					</td>
				</tr>
			</tbody>
                        </table>
                </form>

<? } else {
    load::model('library/files');
    $file = library_files_peer::instance()->get_item(request::get_int('id'))
            ?>
                    <form action="/profile/admin_file_edit" class="form" method="post">
			 <input type="hidden" name="id" value="<?=$file['id']?>">
                         <input type="hidden" name="user_id" value="<?=$file['object_id']?>">
                        <table width="100%" class="fs12">
			<tr>
				<td class="aright"><?=t('Название')?></td>
				<td>
					<div class="mb5">
                                            <input type="text" name="title" value="<?=stripslashes(htmlspecialchars($file['title']))?>"  style="width:380px">
					</div>
				</td>
			</tr>
                        <? if(!isset($file['size'])) { ?>
                        <tr>
				<td class="aright"><?=t('Адрес сайта')?></td>
				<td>
					<div class="mb5">
						<input type="text" name="url" value="<?=$file['url']?>" style="width:380px">
					</div>
				</td>
			</tr>
                        <? } else { ?> <input type="hidden" name="type" value="file" style="width:380px"> <? } ?>
			<tr>
				<td class="aright"><?=t('Описание')?></td>
				<td>
					<div class="mb5">
						<textarea name="describe" rows="1" cols="1" style="width:380px"><?=stripslashes(htmlspecialchars($file['describe']))?></textarea>
					</div>
				</td>
			</tr>
			<tr>
				<td class="aright" width="20%"><?=t('Папка')?></td>
				<td><?=tag_helper::select('dir_id', $dirs, array('id' => 'dir_id','value'=>$file['dir_id']))?></td>
			</tr>
                        <tr>
                                <td width="18%" class="aright">Позиція</td>
                                <td><input type="text" name="position" style="width: 50px;" class="text" id="title" value="<?=$file['position']?>"></td>
                        </tr>
			<tr class="hidden" id="album_name_pane">
				<td class="aright"><?=t('Название папки')?></td>
				<td><input type="text" id="album_name" name="album_name" class="text" /></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
				</td>

			</tr>
		</table>
                </form>
<? } ?>

            </div>
