in<h1 class="mt10 mr10 column_head"><?=user_helper::full_name(request::get_int('id')?request::get_int('id'):session::get_user_id())?> &rarr; Адмiн файли</h1>
	<div class="form_bg mr10 fs12 p10 mb10">
		<div class="left" style="color:#333333; text-align: justify;">
                    <a id="showall" class="pointer"><?=t('Развернуть все')?></a>
                    <a id="hideall" class="hidden pointer"><?=t('Свернуть все')?></a>
		</div>
            <?  if (session::has_credential('admin')) { ?>
            <div class="right" style="width: 185px;">
		<a onclick="$('#add_stuff').show(50);$('#add_file').show(50);" href="javascript:;" class="right">&nbsp;<?=t('Добавить материал')?></a>
            </div>
		<div class="clear"></div>
                 <div id="add_stuff" class="<?=request::get('add')==1 ? 'hidden' : ''?>" style="display: <?=request::get_int('add')!=1 ? 'none' : 'block'?>;">
                </div>

                <div id="add_file" class="<?=request::get('add')==1 ? 'hidden' : ''?>" style="display: <?=request::get_int('add')!=1 ? 'none' : 'block'?>;">
                    <form action="/profile/admin_file_add" id="photo_form" class="form" method="post" enctype="multipart/form-data">
                        <input type="hidden" value="<?=request::get_int('id')?>" name="user_id"/>
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
				<td class="aright"><?=t('Описание')?></td>
				<td>
					<div class="mb5">
						<textarea name="describe" rows="1" cols="1" style="width:180px"></textarea>
					</div>
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
<? } ?>

            </div>
<div class="clear"></div>

    <?
    $count_dirs=count($dirs)-1;
  
    if(is_array($dirs_tree))
    foreach ( $dirs_tree as $dir_id=>$array ) 
    {
            $step=0;
            include 'partials/file/admin_listing.php'; 
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