<h1 class="mt10 mr10 column_head"><?=user_helper::full_name(request::get_int('id')?request::get_int('id'):session::get_user_id())?> &rarr; <?=t('Библиотека')?></h1>

<?   $max=db::get_row("SELECT max(position) FROM files_dirs WHERE type=1 AND parent_id=0 AND object_id=$user_id"); ?>
	<div class="form_bg mr10 fs12 p10 mb10">
		<div class="left" style="color:#333333; text-align: justify;">
                    <a id="showall" class="pointer"><?=t('Развернуть все')?></a>
                    <a id="hideall" class="hidden pointer"><?=t('Свернуть все')?></a>
		</div>
            <?  if (request::get_int('id')==session::get_user_id() || !request::get_int('id')) { ?>
            <div class="right" style="width: 185px;">
                <a onclick="$('#add_photoalbum').show(50);" href="javascript:;"><?=t('Добавить папку')?></a> |
		<a onclick="$('#add_stuff').show(50);$('#add_file').show(50);" href="javascript:;" class="right">&nbsp;<?=t('Добавить материал')?></a>
            </div>
		<div class="clear"></div>
                <div id="add_photoalbum" class="hidden" style="display: none;">
                    <form class="form_bg mr10 fs12 p10 mb10" id="photoalbum_form" method="get">
                        <input type="hidden" name="id" value="<?=request::get_int('id')?>"/>
			<table width="100%" class="mt10">
			<tbody><tr>
					<td width="18%" class="aright"><?=t('Название')?></td>
					<td><input type="text" rel="Введите название папки" name="title" style="width: 500px;" class="text" id="title"></td>
				</tr>
                                <tr>
                                        <td class="aright" width="20%"><?=t('Батько')?></td>
                                        <td><?=tag_helper::select('parent_id', $dirs_lists, array('id' => 'parent_id'))?></td>
                                </tr>
				<tr>
					<td width="18%" class="aright">Позиція</td>
					<td><input type="text" name="position" style="width: 50px;" class="text" id="title" value="<?=($max['max']+1)?>"></td>
				</tr>
				<tr>
					<td></td>
					<td>
						<input type="submit" class="button" value="<?=t('Добавить')?>" name="submit" id="submit">
						<input type="button" onclick="$('#add_photoalbum').hide();" value="<?=t('Отмена')?>" class="button_gray" id="">
						<?=tag_helper::wait_panel() ?>
                                                <div class="success hidden mr10 mt10"><?=t('Папка добавлена')?></div></td>
				</tr>
			</tbody>
                        </table>
                </form>
		</div>
                 <div id="add_stuff" class="<?=request::get('add')==1 ? 'hidden' : ''?>" style="display: <?=request::get_int('add')!=1 ? 'none' : 'block'?>;">
		<table width="100%" class="fs12">
			<tr>
                            <td></td>
                            <td class="aright"><input type="radio" name="radio" checked  onchange="$('#add_link').hide();$('#add_file').show(50);" value="1"> <?=t('Файл')?> &nbsp; &nbsp; &nbsp; &nbsp;<input type="radio" name="radio"  onchange="$('#add_link').show(50);$('#add_file').hide();" value="2"><?=t('Сайт')?> </td>
			</tr>
		</table>
                </div>

                <div id="add_file" class="<?=request::get('add')==1 ? 'hidden' : ''?>" style="display: <?=request::get_int('add')!=1 ? 'none' : 'block'?>;">
                    <form action="/profile/file_add" id="photo_form" class="form" method="post" enctype="multipart/form-data">
		<table width="100%" class="fs12">
			<tr>
				<td class="aright"><?=t('Файл')?></td>
				<td>
					<div class="mb5">
                                            <input type="file" name="file[]"/><br/>
					</div>
				</td>
			</tr>
			<tr>
				<td class="aright"><?=t('Название')?></td>
				<td>
					<div class="mb5">
						<input type="text" name="title" value=""/>
					</div>
				</td>
			</tr>
			<tr>
				<td class="aright"><?=t('Автор')?></td>
				<td>
					<div class="mb5">
						<input type="text" name="author" value=""/>
					</div>
				</td>
			</tr>
			<tr>
				<td class="aright"><?=t('Описание')?></td>
				<td>
					<div class="mb5">
						<textarea name="describe" rows="1" cols="1" style="width:180px"></textarea>
					</div>
				</td>
			</tr>
			<tr>
				<td class="aright"><?=t('Язык')?></td>
				<td>
					<input checked id="lang_no" type="radio" name="lang" value=""/>
					<label for="lang_no">-</label>

					<input id="lang_ua" type="radio" name="lang" value="ua"/>
					<label for="lang_ua"><?=tag_helper::image('/icons/ua.png', array('alt'=>"ua"))?></label>

					<input id="lang_ru" type="radio" name="lang" value="ru"/>
					<label for="lang_ru"><?=tag_helper::image('/icons/ru.png', array('alt'=>"ru"))?></label>

					<input id="lang_en" type="radio" name="lang" value="en"/>
					<label for="lang_en"><?=tag_helper::image('/icons/en.png', array('alt'=>"en"))?></label>
				</td>
			</tr>
                        <?if(is_array($dirs_lists) and count($dirs_lists)>1){?><tr>
				<td class="aright" width="20%"><?=t('Папка')?></td>
                                <?# unset($dirs_lists['0']); ?>
                                <td><?=tag_helper::select('dir_id', $dirs_lists, array('id' => 'dir_id'))?>
                                </td>
                                
			</tr><?}?>
			<tr class="hidden" id="album_name_pane">
				<td class="aright"><?=t('Название папки')?></td>
				<td><input type="text" id="album_name" name="album_name" class="text" /></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" name="submit" onclick="$('#wait_panel').show()" class="button" value=" <?=t('Сохранить')?> ">
					<input type="button" onclick="$('#add_file').hide();$('#add_stuff').hide();" value="<?=t('Отмена')?>" class="button_gray" id="">
					<?=tag_helper::wait_panel() ?>
				</td>

			</tr>
		</table>
                </form>
                </div>


                <div id="add_link" class="hidden" style="display: none;">
                    <form action="/profile/file_add" id="link_form" class="form" method="post">
		<table width="100%" class="fs12">
			<tr>
				<td class="aright"><?=t('Название')?></td>
				<td>
					<div class="mb5">
						<input type="text" name="title" value=""/>
					</div>
				</td>
			</tr>
			<tr>
				<td class="aright"><?=t('Автор')?></td>
				<td>
					<div class="mb5">
						<input type="text" name="author" value=""/>
					</div>
				</td>
			</tr>
			<tr>
				<td class="aright"><?=t('Адрес сайта')?></td>
				<td>
					<div class="mb5">
						<input type="text" name="url"/>
					</div>
				</td>
			</tr>
			<tr>
				<td class="aright"><?=t('Описание')?></td>
				<td>
					<div class="mb5">
						<textarea name="describe" rows="1" cols="1" style="width:180px"></textarea>
					</div>
				</td>
			</tr>
			<tr>
				<td class="aright"><?=t('Мова')?></td>
				<td>
					<input checked id="lang_no" type="radio" name="lang" value=""/>
					<label for="lang_no">-</label>

					<input id="lang_ua" type="radio" name="lang" value="ua"/>
					<label for="lang_ua"><?=tag_helper::image('/icons/ua.png', array('alt'=>"ua"))?></label>

					<input id="lang_ru" type="radio" name="lang" value="ru"/>
					<label for="lang_ru"><?=tag_helper::image('/icons/ru.png', array('alt'=>"ru"))?></label>

					<input id="lang_en" type="radio" name="lang" value="en"/>
					<label for="lang_en"><?=tag_helper::image('/icons/en.png', array('alt'=>"en"))?></label>
				</td>
			</tr>
			<tr>
				<td class="aright" width="20%"><?=t('Папка')?></td>
				<td><?=tag_helper::select('dir_id', $dirs_lists, array('id' => 'dir_id'))?></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" name="submit" onclick="$('#wait_panel').show()" class="button" value=" <?=t('Сохранить')?> ">
					<input type="button" onclick="$('#add_link').hide();$('#add_stuff').hide();" value="<?=t('Отмена')?>" class="button_gray" id="">
					<?=tag_helper::wait_panel() ?>
				</td>
			</tr>
		</table>
                </form>
                </div>
<? } ?>

            </div>
<div class="clear"></div>

    <?
    $count_dirs=count($dirs)-1;
  
    if(is_array($dirs_tree))
    foreach ( $dirs_tree as $dir_id=>$array ) 
    {
            $step=0;
            include 'partials/file/listing.php'; 
    }
    ?>
<script type="text/javascript">
jQuery(document).ready(function(){
   $("#showall").click(function(){
   <? foreach ( $dirs as $dir_id ) { ?>
   $("#files_<?=$dir_id?>").show();
   <? } ?>
   $("#showall").hide();
   $("#hideall").show();
   });
   
   $("#hideall").click(function(){
   <? foreach ( $dirs as $dir_id ) { ?>
   $("#files_<?=$dir_id?>").hide();
   <? } ?>
   $("#hideall").hide();
   $("#showall").show();
   });
   
   $(".file").hover(function() {
       $(this).addClass("folder_selected");
   }, function() {
       $(this).removeClass("folder_selected");
   });
   $(".folder").click(function() {
                if(!$("#file"+this.id).is(":visible"))
                {
                       $("#file"+this.id).slideDown(300);

                }
                else
                {
                       $("#file"+this.id).slideUp(300);
                }
});
   $(".folder_title").click(function() {
                if(!$("#files_"+this.id).is(":visible"))
                {
                        $("#files_"+this.id).slideDown(300);

                }
                else
                {
                       $("#files_"+this.id).slideUp(300);
                }
});
   
});
$(".info").click(function() {
                if(!$("#file_describe_"+this.id).is(":visible"))
                {
                       $("#file_describe_"+this.id).slideDown(100);

                }
                else
                {
                       $("#file_describe_"+this.id).slideUp(100);
                }
});
</script>