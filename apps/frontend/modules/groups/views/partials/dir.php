<div class="left acenter fs12" style="width: 170px; height: 200px;">
	<? $dir = groups_files_dirs_peer::instance()->get_item($dir_id) ?>
	<a href="/groups/file?id=<?=$group['id']?>&dir_id=<?=$dir_id?>"></a><br />
	<a href="/groups/file?id=<?=$group['id']?>&dir_id=<?=$dir_id?>"><?= $dir_id ? htmlspecialchars($dir['title']) : t('Основная папка') ?></a>
        <? if($dir_id and session::has_credential('admin')) { ?> &nbsp;&nbsp;[ <a class=""  onclick="return confirm('<?=t('Удалить папку?')?>');" href="/groups/filedir_delete?id=<?=$dir_id?>">X</a> ] <? } ?>
</div>