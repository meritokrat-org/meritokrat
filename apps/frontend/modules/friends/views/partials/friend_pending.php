<div id="friend_<?=$id?>" class="border1" style="padding: 10px; margin: 10px 10px 0px 0px;">
	<?=user_helper::photo($id, 't', array('class' => 'border1 left'))?>
	<div class="left fs12 ml10">
		<?=user_helper::full_name($id)?> <?=t('хочет добавить Вас в друзья')?><br /><br />
		<input type="button" onclick="friendsController.accept(<?=$id?>);" class="button" value=" <?=t('Одобрить')?> ">
		<input type="button" onclick="friendsController.decline(<?=$id?>);" class="button_gray" value=" <?=t('Отказать')?> ">
	</div>
	<div class="clear"></div>
</div>