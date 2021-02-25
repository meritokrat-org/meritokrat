<h1 class="mt10 mr10 column_head">
	<a href="/group<?=$group['id']?>"><?=htmlspecialchars($group['title'])?></a> &rarr; <?=t('Ссылки')?>
</h1>

<? if ( (ppo_members_peer::instance()->is_member($group['id'], session::get_user_id()) ) or session::has_credential('admin')) { ?>
	<div class="form_bg mr10 fs12 p10 mb10">
		<div class="left">
			<? if ( $dir_id !== null ) { ?>
				<a href="/ppo/link?id=<?=$group['id']?>"><?=t('Папки сообщества')?></a> &rarr;
				<?= $album ? htmlspecialchars($album['title']) : t('Основная папка') ?>
			<? } else { ?>
				<?=t('Папки сообщества')?>
			<? } ?>
		</div>
<? if (ppo_peer::instance()->is_moderator($group['id'], session::get_user_id()))   { ?>
            <div class="right">
                <a onclick="$('#add_photoalbum').show(50);" href="javascript:;"><?=t('Добавить папку')?></a> |
		<a onclick="$('#add_file').show(50);" href="javascript:;" class="right">&nbsp;<?=t('Добавить ссылку')?></a>
            </div>
<? }  ?>
		<div class="clear"></div>
                <div id="add_photoalbum" class="hidden" style="display: none;">
                    <form class="form_bg mr10 fs12 p10 mb10" id="photoalbum_form" method="get">
			<input type="hidden" value="<?=$group['id']?>" name="id" id="id">
			<table width="100%" class="mt10">
			<tbody><tr>
					<td width="18%" class="aright">Назва</td>
					<td><input type="text" rel="Введите название папки" name="title" style="width: 500px;" class="text" id="title"></td>
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
                                                <div class="success hidden mr10 mt10"><?=t('Папка добавлена')?></div></td>
				</tr>
			</tbody>
                        </table>
                </form>
		</div>
                <div id="add_file" class="<?=request::get('add')==1 ? 'hidden' : ''?>" style="display: none;">
                    <form action="/ppo/link_add?id=<?=$group['id']?>" id="photo_form" class="form" method="post" enctype="multipart/form-data">
		<table width="100%" class="fs12">
			<tr>
				<td class="aright" width="20%"><?=t('Папка')?></td>
				<td><?=tag_helper::select('dir_id', $dirs_lists, array('id' => 'dir_id'))?></td>
			</tr>
			<tr class="hidden" id="album_name_pane">
				<td class="aright"><?=t('Название папки')?></td>
				<td><input type="text" id="album_name" name="album_name" class="text" /></td>
			</tr>
			<tr>
				<td class="aright"><?=t('URL-адрес')?></td>
				<td>
					<div class="mb5">
						<input type="text" onchange="$(this).parent().parent().append('<div class=mb5>' + $(this).parent().html() + '</div>')" name="url[]"/>
						<span class="quiet ml10">Текст</span>
						<input type="text" name="title[]" value=""/>
					</div>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" name="submit" onclick="$('#wait_panel').show()" class="button" value=" <?=t('Сохранить')?> ">
					<input type="button" onclick="$('#add_file').hide();" value="<?=t('Отмена')?>" class="button_gray" id="">
					<?=tag_helper::wait_panel() ?>
				</td>
			</tr>
		</table>
                </form>
                </div>
	</div>
<? } ?>


    <? foreach ( $dirs as $dir_id ) { ?>
      <? $dir=ppo_links_dirs_peer::instance()->get_item($dir_id);
      if ($dir_id==0) $dir['title']=t('Основная папка'); ?>
            <div id="s_<?=$dir_id?>" class="pointer folder <?=$dir_id==0 ? 'folder_open' : 'folder_closed'?> left">
            </div>
            <div id="<?=$dir_id?>" class="pointer folder_title left mt15 cbrown bold " style="width:300px">
                  <?=$dir['title']?> <span class="ml15 fs10"> [ <?=count($links[$dir_id])?> ]</span>
            </div>
<? if (ppo_peer::instance()->is_moderator($group['id'], session::get_user_id()) and $dir_id!=0)   { ?>
                        <div class="left ml15"><a class="fs10" onclick="return confirm('Ви впевнені?');" href="/ppo/linkdir_delete?id=<?=$dir_id?>">[X]</a></div>
                    <? } ?>
            <div class="clear"></div>
            <div id="files_<?=$dir_id?>" class="<?=$dir_id==0 ? '' : 'hidden'?>">
            <? if ( $links[$dir_id] ) { ?>
                 <div class="left fs10" style="margin-left: 110px; width:240px"><?=t('Название')?></div>
                 <div class="left mr15 fs10" style="width:200px;"><?=t('Пользователь')?></div>
<? if (ppo_peer::instance()->is_moderator($group['id'], session::get_user_id()))   { ?>
                 <div class="left ml15 fs10"><?=t('Удалить')?></div>
                 <? } ?>
            <div class="clear"></div>
                <? foreach ( $links[$dir_id] as $id ) {
                       $link=ppo_links_peer::instance()->get_item($id); ?>
                        <div class="left" style="width:60px; margin-left: 50px;"><img src="/static/images/icons/file.png" alt=""></div>
                        <div class="left" style="width:240px"><a href="<?=$link['url']?>"><?=$link['title']?></a><!--br/><span class="fs10"><?=$link['title']?></span--></div>
                        <div class="left mr15" style="width:200px;"><?=user_helper::full_name($link['user_id'], true)?></div>
                        <? if (ppo_peer::instance()->is_moderator($group['id'], session::get_user_id()))   { ?>
                        <div class="left ml15"><a onclick="return confirm('Ви впевнені?');" href="/ppo/link_delete?id=<?=$id?>">[X]</a></div>
                    <? } ?>

            <div class="clear"></div>
            <? } ?>
            <? } else { ?>
                        <div class="acenter"><?=t('Папка пуста')?></div>
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
                       $(this).removeClass('folder_closed');
                       $(this).addClass('folder_open');

                }
                else
                {
                       $("#file"+this.id).slideUp(300);
                       $(this).addClass('folder_closed');
                       $(this).removeClass('folder_open');
                }
});
   $(".folder_title").click(function() {
                if(!$("#files_"+this.id).is(":visible"))
                {
                        $("#files_"+this.id).slideDown(300);
                        $("#s_"+this.id).removeClass('folder_closed');
                        $("#s_"+this.id).addClass('folder_open');

                }
                else
                {
                       $("#files_"+this.id).slideUp(300);
                       $("#s_"+this.id).addClass('folder_closed');
                       $("#s_"+this.id).removeClass('folder_open');
                }
});
});

</script>