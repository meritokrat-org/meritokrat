<div class="form_bg mt10">

	<h1 class="column_head"><a href="/ppo/file?id=<?=$group['id']?>"><?=t('Файлы сообществ')?></a> &rarr <?=t('Загрузка')?></h1>

	<form id="photo_form" class="form" method="post" enctype="multipart/form-data">
		<table width="100%" class="fs12">
			<tr>
				<td class="aright" width="20%"><?=t('Папка')?></td>
				<td><?=tag_helper::select('dir_id', $dirs, array('id' => 'dir_id', 'value' => request::get_int('dir_id')))?></td>
			</tr>
			<tr class="hidden" id="album_name_pane">
				<td class="aright"><?=t('Название папки')?></td>
				<td><input type="text" id="album_name" name="album_name" class="text" /></td>
			</tr>
			<tr>
				<td class="aright"><?=t('Файл')?></td>
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
</div>