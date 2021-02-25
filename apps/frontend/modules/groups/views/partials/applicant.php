<div class="mb10">
	<?=user_helper::full_name($applicant_id)?>
	<a class="ml10 fs10 quiet green" href="javascript:;" rel="<?=$applicant_id?>" onclick="groupsController.applicantApprove(<?=$applicant_id?>, this);"><?=t('Одобрить')?></a>
	<a class="ml10 fs10 quiet maroon" href="javascript:;" onclick="groupsController.applicantCancel(<?=$applicant_id?>, this);"><?=t('Отказать')?></a>
</div>