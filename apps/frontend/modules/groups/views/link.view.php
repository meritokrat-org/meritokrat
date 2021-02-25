<h1 class="mt10 mr10 column_head">
	<a href="/group<?=$group['id']?>"><?=htmlspecialchars($group['title'])?></a> &rarr; <?=t('Ссылки')?>
</h1>

<? if ( (groups_members_peer::instance()->is_member($group['id'], session::get_user_id()) ) or session::has_credential('admin')) { ?>
	<div class="form_bg mr10 fs12 p10 mb10">
		<div class="left">
			<? if ( $dir_id !== null ) { ?>
				<a href="/groups/link?id=<?=$group['id']?>"><?=t('Ссылки сообщества')?></a> &rarr;
				<?= $album ? htmlspecialchars($album['title']) : t('Разное') ?>
			<? } else { ?>
				<?=t('Ссылки сообщества')?>
			<? } ?>
		</div>
<? if (groups_peer::instance()->is_moderator($group['id'], session::get_user_id()))   { ?>
            <div class="right">
                <a onclick="$('#add_photoalbum').show(50);" href="javascript:;"><?=t('Добавить категорию')?></a> |
		<a onclick="$('#add_link').show(50);" href="javascript:;" class="right">&nbsp;<?=t('Добавить ссылку')?></a>
            </div>
<? }  ?>
		<div class="clear"></div>
                <div id="add_photoalbum" class="hidden" style="display: none;">
                    <form class="form_bg mr10 fs12 p10 mb10" id="photoalbum_form" method="get">
			<input type="hidden" value="<?=$group['id']?>" name="id" id="id">
			<table width="100%" class="mt10">
			<tbody><tr>
					<td width="18%" class="aright">Назва</td>
					<td><input type="text" rel="Введите название категории" name="title" style="width: 500px;" class="text" id="title"></td>
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
                                                <div class="success hidden mr10 mt10"><?=t('Категория добавлена')?></div></td>
				</tr>
			</tbody>
                        </table>
                </form>
		</div>
                <div id="add_link" class="<?=request::get_int('add')!=1 ? 'hidden' : ''?>" style="display: <?=request::get_int('add')!=1 ? 'none' : 'block'?>;">
                    <form action="/groups/link_add?id=<?=$group['id']?>" id="link_form" class="form" method="post">
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
				<td class="aright"><?=t('Адрес сайта')?></td>
				<td>
					<div class="mb5">
						<input type="text" name="url"/>
					</div>
				</td>
			</tr>
			<tr>
				<td class="aright" width="20%"><?=t('Категория')?></td>
				<td><?=tag_helper::select('dir_id', $dirs_lists, array('id' => 'dir_id'))?></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" name="submit" onclick="$('#wait_panel').show()" class="button" value=" <?=t('Сохранить')?> ">
					<input type="button" onclick="$('#add_link').hide();" value="<?=t('Отмена')?>" class="button_gray" id="">
					<?=tag_helper::wait_panel() ?>
				</td>
			</tr>
		</table>
                </form>
                </div>
	</div>
<? } ?>


    <? foreach ( $dirs as $dir_id ) { ?>
      <? $dir=groups_links_dirs_peer::instance()->get_item($dir_id);
      if ($dir_id==0) $dir['title']=t('Разное'); ?>
            <div id="s_<?=$dir_id?>" class="pointer folder <?//=$dir_id==0 ? 'folder_open' : 'folder_closed'?> left">
            </div>
            <div id="<?=$dir_id?>" class="pointer ml10 folder_title left mt15 cbrown bold ">
                  <?=$dir['title']?> <span class="ml15 fs10"> [ <?=count($links[$dir_id])?> ]</span>
            </div>
<? if (groups_peer::instance()->is_moderator($group['id'], session::get_user_id()) and $dir_id!=0)   { ?>
                        <div class="left mt15 ml15"><a class="fs10" onclick="return confirm('Ви впевнені?');" href="/groups/linkdir_delete?id=<?=$dir_id?>">[X]</a></div>
                    <? } ?>
            <div class="clear"></div>
            <div id="files_<?=$dir_id?>" class="<?//=$dir_id==0 ? '' : 'hidden'?>">
            <? if ( $links[$dir_id] ) { ?>
                 <!--div class="left fs10" style="margin-left: 110px; width:240px"><?=t('Ссылка')?></div>
                 <div class="left mr15 fs10" style="width:200px;"><?=t('Пользователь')?></div>
<? if (groups_peer::instance()->is_moderator($group['id'], session::get_user_id()))   { ?>
                 <div class="left ml15 fs10"><?=t('Удалить')?></div-->
                 <? } ?>
            <div class="clear"></div>
                <? foreach ( $links[$dir_id] as $id ) {
                       $link=groups_links_peer::instance()->get_item($id); ?>
                        <div class="left" style="margin-left:20px;width: 230px;"><a href="<?=$link['url']?>" class="">
                       <?=$link['title']?></a><!--br/><span class="fs10"><?=$link['title']?></span--><? if (groups_peer::instance()->is_moderator($group['id'], session::get_user_id()))   { ?>
                        <a onclick="return confirm('Ви впевнені?');" href="/groups/link_delete?id=<?=$id?>">[X]</a><br> 
                    <? } ?>
                        <span class="fs11 cgray"><?=user_helper::full_name($link['user_id'], true)?></span>
                        </div>
            <? } ?>
            <? } else { ?>
                        <div class="acenter"><?=t('Категория пуста')?></div>
            <? } ?>
            </div>
            <div class="clear"></div>
        <? } ?>
<script type="text/javascript">
jQuery(document).ready(function(){
   $(".file").hover(function() {
       $(this).addClass("folder_selected");
   }, function() {
       $(this).removeClass("folder_selected");
   });
   $(".folder").click(function() {
                if(!$("#file"+this.id).is(":visible"))
                {
                       $("#file"+this.id).slideDown(300);
                       //$(this).removeClass('folder_closed');
                       //$(this).addClass('folder_open');

                }
                else
                {
                       $("#file"+this.id).slideUp(300);
                       //$(this).addClass('folder_closed');
                       //$(this).removeClass('folder_open');
                }
});
   $(".folder_title").click(function() {
                if(!$("#files_"+this.id).is(":visible"))
                {
                        $("#files_"+this.id).slideDown(300);
                        //$("#s_"+this.id).removeClass('folder_closed');
                        //$("#s_"+this.id).addClass('folder_open');

                }
                else
                {
                       $("#files_"+this.id).slideUp(300);
                       //$("#s_"+this.id).addClass('folder_closed');
                       //$("#s_"+this.id).removeClass('folder_open');
                }
});
});

</script>