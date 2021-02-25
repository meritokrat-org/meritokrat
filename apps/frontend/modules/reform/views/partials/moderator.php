<div class="mb10">
	<?=user_helper::full_name($moderator_id)?>
	<a class="ml10 fs10 quiet" href="javascript:;" rel="<?=$moderator_id?>" onclick="ppoController.deleteModerator(this);"><?=t('Удалить')?></a>
	<a class="ml10 fs10 quiet bold" href="javascript:;" rel="<?=t('Передать полномочия администратора этому пользователю?')?>" onclick="ppoController.changeOwner(<?=$moderator_id?>, this);"><?=t('Передать полномочия')?></a>
</div>