<div>
	<h1 class="mt10 mr10 column_head">
		<a href="/team<?= $group['id'] ?>/<?= $group['number'] ?>"><?= htmlspecialchars($group['title']) ?></a> &rarr; <?= t('Файлы') ?>
	</h1>

	<? $max = db::get_row("SELECT max(position) FROM team_files_dirs WHERE group_id=:group_id", array('group_id' => $group['id'])); ?>
	<div class="form_bg mr10 fs12 p10 mb10">
		<div class="left" style="color:#333333; text-align: justify;">
		</div>
		<div class="left" style="color:#333333; text-align: justify;">
			<a id="showall"><?= t('Развернуть все') ?></a>
			<a id="hideall" class="hidden"><?= t('Свернуть все') ?></a>
		</div>
		<div class="right">
			<? if (team_peer::instance()->is_moderator($group['id'], session::get_user_id())) { ?><a
				onclick="$('#add_photoalbum').show(50);"
				href="javascript:;"><?= t('Добавить папку') ?></a>&nbsp;|&nbsp;<? } ?><? if (team_members_peer::instance()->is_member($group['id'], session::get_user_id()) || session::has_credential('admin')) { ?>
				<a onclick="$('#add_stuff').show(50);$('#add_file').show(50);" href="javascript:;" class="right">
				&nbsp;<?= t('Добавить материал') ?></a><? } ?>
		</div>
		<? if (team_peer::instance()->is_moderator($group['id'], session::get_user_id())) { ?>
			<div class="clear"></div>
			<div id="add_photoalbum" class="hidden" style="display: none;">
				<form class="form_bg mr10 fs12 p10 mb10" id="photoalbum_form" method="get">
					<input type="hidden" value="<?= $group['id'] ?>" name="id" id="id">
					<table width="100%" class="mt10">
						<tbody>
						<tr>
							<td width="18%" class="aright"><?= t('Название') ?></td>
							<td><input type="text" rel="Введите название папки" name="title" style="width: 500px;"
							           class="text" id="title"></td>
						</tr>
						<tr>
							<td class="aright" width="20%"><?= t('Батько') ?></td>
							<td><?= (is_array($dirs_lists) and count($dirs_lists) > 0) ? tag_helper::select('parent_id', $dirs_lists, array('id' => 'parent_id')) : t('створіть спочатку папку') ?></td>
						</tr>
						<tr>
							<td width="18%" class="aright">Позиція</td>
							<td><input type="text" name="position" style="width: 50px;" class="text" id="title"
							           value="<?= ($max['max'] + 1) ?>"></td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="submit" class="button" value="<?= t('Добавить') ?>" name="submit"
								       id="submit">
								<input type="button" onclick="$('#add_photoalbum').hide();" value="<?= t('Отмена') ?>"
								       class="button_gray" id="">
								<?= tag_helper::wait_panel() ?>
								<div class="success hidden mr10 mt10"><?= t('Папка добавлена') ?></div>
							</td>
						</tr>
						</tbody>
					</table>
				</form>
			</div>

		<? } ?>
		<? if (team_members_peer::instance()->is_member($group['id'], session::get_user_id()) || session::has_credential('admin')) { ?>
			<div id="add_stuff" class="<?= request::get('add') == 1 ? 'hidden' : '' ?>"
			     style="display: <?= request::get_int('add') != 1 ? 'none' : 'block' ?>;">
				<table width="100%" class="fs12">
					<tr>
						<td></td>
						<td class="aright"><input type="radio" name="radio" checked
						                          onchange="$('#add_link').hide();$('#add_file').show(50);"
						                          value="1"> <?= t('Файл') ?> &nbsp; &nbsp; &nbsp; &nbsp;<input
								type="radio" name="radio" onchange="$('#add_link').show(50);$('#add_file').hide();"
								value="2"><?= t('Сайт') ?> </td>
					</tr>
				</table>
			</div>

			<div id="add_file" class="<?= request::get('add') == 1 ? 'hidden' : '' ?>"
			     style="display: <?= request::get_int('add') != 1 ? 'none' : 'block' ?>;">
				<form action="/team/file_add" id="photo_form" class="form" method="post" enctype="multipart/form-data">
					<input type="hidden" value="<?= $group['id'] ?>" name="id" id="id">
					<table width="100%" class="fs12">
						<tr>
							<td class="aright"><?= t('Файл') ?></td>
							<td>
								<div class="mb5">
									<input type="file" name="file[]"/><br/>
								</div>
							</td>
						</tr>
						<tr>
							<td class="aright"><?= t('Название') ?></td>
							<td>
								<div class="mb5">
									<input type="text" name="title" value=""/>
								</div>
							</td>
						</tr>
						<tr>
							<td class="aright"><?= t('Автор') ?></td>
							<td>
								<div class="mb5">
									<input type="text" name="author" value=""/>
								</div>
							</td>
						</tr>
						<tr>
							<td class="aright"><?= t('Описание') ?></td>
							<td>
								<div class="mb5">
									<textarea name="describe" rows="1" cols="1" style="width:180px"></textarea>
								</div>
							</td>
						</tr>
						<tr>
							<td class="aright"><?= t('Мова') ?></td>
							<td>
								<input checked id="lang_no" type="radio" name="lang" value=""/>
								<label for="lang_no">-</label>

								<input id="lang_ua" type="radio" name="lang" value="ua"/>
								<label
									for="lang_ua"><?= tag_helper::image('/icons/ua.png', array('alt' => "ua")) ?></label>

								<input id="lang_ru" type="radio" name="lang" value="ru"/>
								<label
									for="lang_ru"><?= tag_helper::image('/icons/ru.png', array('alt' => "ru")) ?></label>

								<input id="lang_en" type="radio" name="lang" value="en"/>
								<label
									for="lang_en"><?= tag_helper::image('/icons/en.png', array('alt' => "en")) ?></label>
							</td>
						</tr>
						<tr>
							<td class="aright" width="20%"><?= t('Папка') ?></td>
							<? unset($dirs_lists['0']); ?>
							<td><?= count($dirs_lists) ? tag_helper::select('dir_id', $dirs_lists, array('id' => 'dir_id')) : t('сначала создайте папку') ?></td>
						</tr>
						<tr class="hidden" id="album_name_pane">
							<td class="aright"><?= t('Название папки') ?></td>
							<td><input type="text" id="album_name" name="album_name" class="text"/></td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="submit" name="submit" onclick="$('#wait_panel').show()" class="button"
								       value=" <?= t('Сохранить') ?> ">
								<input type="button" onclick="$('#add_file').hide();$('#add_stuff').hide();"
								       value="<?= t('Отмена') ?>" class="button_gray" id="">
								<?= tag_helper::wait_panel() ?>
							</td>

						</tr>
					</table>
				</form>
			</div>

			<div id="add_link" class="hidden" style="display: none;">
				<form action="/team/file_add" id="link_form" class="form" method="post">
					<input type="hidden" value="<?= $group['id'] ?>" name="id" id="id">
					<table width="100%" class="fs12">
						<tr>
							<td class="aright"><?= t('Название') ?></td>
							<td>
								<div class="mb5">
									<input type="text" name="title" value=""/>
								</div>
							</td>
						</tr>
						<tr>
							<td class="aright"><?= t('Автор') ?></td>
							<td>
								<div class="mb5">
									<input type="text" name="author" value=""/>
								</div>
							</td>
						</tr>
						<tr>
							<td class="aright"><?= t('Адрес сайта') ?></td>
							<td>
								<div class="mb5">
									<input type="text" name="url"/>
								</div>
							</td>
						</tr>
						<tr>
							<td class="aright"><?= t('Описание') ?></td>
							<td>
								<div class="mb5">
									<textarea name="describe" rows="1" cols="1" style="width:180px"></textarea>
								</div>
							</td>
						</tr>
						<tr>
							<td class="aright"><?= t('Мова') ?></td>
							<td>
								<input checked id="lang_no" type="radio" name="lang" value=""/>
								<label for="lang_no">-</label>

								<input id="lang_ua" type="radio" name="lang" value="ua"/>
								<label
									for="lang_ua"><?= tag_helper::image('/icons/ua.png', array('alt' => "ua")) ?></label>

								<input id="lang_ru" type="radio" name="lang" value="ru"/>
								<label
									for="lang_ru"><?= tag_helper::image('/icons/ru.png', array('alt' => "ru")) ?></label>

								<input id="lang_en" type="radio" name="lang" value="en"/>
								<label
									for="lang_en"><?= tag_helper::image('/icons/en.png', array('alt' => "en")) ?></label>
							</td>
						</tr>
						<tr>
							<td class="aright" width="20%"><?= t('Папка') ?></td>
							<td><?= (is_array($dirs_lists) and count($dirs_lists) > 0) ? tag_helper::select('dir_id', $dirs_lists, array('id' => 'dir_id')) : t('сначала создайте папку') ?></td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="submit" name="submit" onclick="$('#wait_panel').show()" class="button"
								       value=" <?= t('Сохранить') ?> ">
								<input type="button" onclick="$('#add_link').hide();$('#add_stuff').hide();"
								       value="<?= t('Отмена') ?>" class="button_gray" id="">
								<?= tag_helper::wait_panel() ?>
							</td>
						</tr>
					</table>
				</form>
			</div>
		<? } ?>

	</div>


	<?
	$count_dirs = count($dirs) - 1;
	if (is_array($dirs_tree) and count($dirs_tree) > 0) {
		foreach ($dirs_tree as $dir_id => $array) {
			$step = 0;
			include 'partials/listing.php';
		} ?>
	<? } ?>
	<div class="left" style="margin-left:20px;width:730px;">
		<? //if (session::get_user_id()==29) var_dump(ppo_files_peer::instance()->get_list(array('dir_id'=>0,'group_id'=>$group['id']),array(),array('position ASC')));     ?>
		<? foreach (team_files_peer::instance()->get_list(array('dir_id' => 0, 'group_id' => $group['id']), array(), array('position ASC')) as $id) {
			$counter++;
			$file = team_files_peer::instance()->get_item($id);
			if (isset($file['files']))
				$arr = unserialize($file['files']);
			?>
			<div class="mt5" style="border-bottom: 1px solid #f7f7f7;">
				<div class="left">
					<div class="ml5"><a
							href="<?= (isset($file['files'])) ? context::get('file_server') . $file['id'] . '/' . $arr[0]['salt'] . "/" . $arr[0]['name'] : $file['url'] ?>"><?= stripslashes(htmlspecialchars($file['title'])) ?></a>
					</div>
					<div class="left ml5 fs12"><?= $file['author'] ?></div>
				</div>
				<? if (isset($file['files'])) {
					foreach ($arr as $f) {
						$ext = end(explode('.', $f['name']));
						?>
						<div class="left ml5 <?//=$file['author'] ? 'mt15' : ''?>">
							<a href="<?= context::get('file_server') . $file['id'] . '/' . $f['salt'] . "/" . $f['name'] ?>">
								<img src="/static/images/files/<?= team_files_peer::instance()->get_icon($ext) ?>">
							</a></div>
					<? }
				} else { ?>
					<div class="left ml5 <? //=$file['author'] ? 'mt15' : ''?>"><img src="/static/images/files/IE.png">
					</div> <? } ?>
				<? if ($file['lang'] == 'ua' or $file['lang'] == 'en') { ?>
				<div class="left ml5" style="margin-top:  1<? //=$file['author'] ? '17' : '2'?>px;"><img
						src="https://s1.meritokrat.org/icons/<?= $file['lang'] ?>.png"></div><? } ?>
				<div class="right aright mr5"
				     style="border-bottom: 1px solid #d7d7d7; color:#565656;"> <?= $file['size'] ? $file['size'] : '' //$file['exts'] ? team_files_peer::formatBytes(filesize($file['url'])) : ''  ?>
					<img id="<?= $id ?>" class="info ml5 <?= !isset($file['describe']) ? ' hidden' : '' ?>"
					     alt="Інформація" src="/static/images/icons/1.png">
					<? if (team_peer::instance()->is_moderator($group['id'], session::get_user_id())) { ?>
						<? if ($counter != 1) { ?>
							<a href="/team/up_file?id=<?= $id ?>" style="color:#565656;">
								&#9650;</a> <?= $file['position'] ?>
						<? }
						if ($counter != count($files[$dir_id])) { ?>
						<a href="/team/down_file?id=<?= $id ?>" style="color:#565656;">
								&#9660;</a><? if ($counter == 1) { ?><span class="cgray fs11"
								                                           style="font-weight:normal"><?= $file['position'] ?></span><? } ?>&nbsp;
						<? } ?>
					<? } ?>
					<? if (team_peer::instance()->is_moderator($group['id'], session::get_user_id()) || $file['user_id'] == session::get_user_id()) { ?>
						<a href="/team/file_edit?id=<?= $id ?>"><img class="ml5" alt="Редагування"
						                                            src="/static/images/icons/2.png"></a>
						<a onclick="return confirm('Ви впевнені?');" href="/team/file_delete?id=<?= $id ?>"><img
								class="ml5" alt="видалення" src="/static/images/icons/3.png"></a>
					<? } ?>
				</div>
				<div class="clear"></div>
				<div id="file_describe_<?= $id ?>"
				     class="ml10 fs11 hidden"><?= stripslashes(htmlspecialchars($file['describe'])) ?></div>
			</div>
			<div class="clear"></div>
		<? } ?>
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function () {
		$("#showall").click(function () {
			<? if (is_array($dirs) and count($dirs)>0) foreach ( $dirs as $dir_id ) { ?>
			$("#files_<?=$dir_id?>").show();
			<? } ?>
			$("#showall").hide();
			$("#hideall").show();
		});

		$("#hideall").click(function () {
			<? if (is_array($dirs) and count($dirs)>0)  foreach ( $dirs as $dir_id ) { ?>
			$("#files_<?=$dir_id?>").hide();
			<? } ?>
			$("#hideall").hide();
			$("#showall").show();
		});

		$(".file").hover(function () {
			$(this).addClass("folder_selected");
		}, function () {
			$(this).removeClass("folder_selected");
		});
		$(".folder").click(function () {
			if (!$("#file" + this.id).is(":visible")) {
				$("#file" + this.id).slideDown(300);
				//$(this).removeClass('folder_closed');
				//$(this).addClass('folder_open');

			}
			else {
				$("#file" + this.id).slideUp(300);
				//$(this).addClass('folder_closed');
				//$(this).removeClass('folder_open');
			}
		});
		$(".folder_title").click(function () {
			if (!$("#files_" + this.id).is(":visible")) {
				$("#files_" + this.id).slideDown(300);
				//$("#s_"+this.id).removeClass('folder_closed');
				//$("#s_"+this.id).addClass('folder_open');

			}
			else {
				$("#files_" + this.id).slideUp(300);
				// $("#s_"+this.id).addClass('folder_closed');
				//   $("#s_"+this.id).removeClass('folder_open');
			}
		});
		$(".info").click(function () {
			if (!$("#file_describe_" + this.id).is(":visible")) {
				$("#file_describe_" + this.id).slideDown(100);

			}
			else {
				$("#file_describe_" + this.id).slideUp(100);
			}
		});
	});

</script>