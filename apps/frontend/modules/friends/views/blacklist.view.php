<div class="popup_header">
	<?=user_helper::photo($user['id'], 's', array('class' => 'border1 vcenter'))?>
	<span class="ml10"><?=user_helper::full_name($user['id'], false)?></span>
</div>

<div class="m10">
	<div class="fs11 mb10" style="width: 300px;">
		<?=t('Вы уверены, что хотите добавить этого человека в черный список?')?>
	</div>
	<input type="button" class="button" onclick="Application.doBlacklist(<?=$user['id']?>);" value=" <?=t('Да, добавить')?> ">
	<input type="button" class="button_gray" onclick="Popup.close();" value=" <?=t('Нет')?> ">
</div>