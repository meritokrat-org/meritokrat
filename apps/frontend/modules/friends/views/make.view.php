<div class="popup_header">
	<?=user_helper::photo($user['id'], 's', array('class' => 'border1 vcenter'))?>
	<span class="ml10"><?=user_helper::full_name($user['id'], false)?></span>
</div>

<div class="m10">
	<? if(!$error){ ?>
        <div class="fs11 mb10" style="width: 300px;">
		<?=t('Запрос на добавление отправлен.')?>
		<?=t('После того, как человек одобрит запрос, он появится у Вас в друзьях.')?>
	</div>
        <? }else{ ?>
        <div class="fs11 mb10" style="width: 300px;">
		<?=t('Ошибка. Вы можете подать не более 10 запросов на дружбу в день.')?>
	</div>
        <? } ?>
</div>