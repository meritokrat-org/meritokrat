<?
$applicant=db::get_row("SELECT * FROM ppo_applicants WHERE user_id=:user_id AND group_id=:group_id AND reason IS NOT NULL",
        array("user_id"=>$applicant_id,"group_id"=>  request::get_int('id')));
?>
<div class="mb10">
	<?=user_helper::full_name($applicant_id)?>
    <?if($applicant['reasonerwr']){?><a class="ml10 fs10 quiet maroon info" id="<?=$applicant_id?>" href="javascript:;"><?=t('Причина')?></a><?}?>
	<a class="ml10 fs10 quiet green" href="javascript:;" rel="<?=$applicant_id?>" onclick="ppoController.applicantApprove(<?=$applicant_id?>, this);"><?=t('Одобрить')?></a>
	<a class="ml10 fs10 quiet maroon" href="javascript:;" onclick="ppoController.applicantCancel(<?=$applicant_id?>, this);"><?=t('Отказать')?></a>
    <div id="applicant_info_<?=$applicant_id?>" class="hidden">
    <?=$applicant['reasonsdsd']?>
    </div>
</div>