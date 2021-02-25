<?$sub_menu=request::get('action'); ?>
<div class="sub_menu mt5 mb10"> 
        <a href="/events" <?=$sub_menu == '' ? 'class="bold"' : ''?>><?=t('Будущие')?></a>
	<a href="/events/mine" <?=$sub_menu == 'mine' ? 'class="bold"' : ''?>><?=t('Мои мероприятия')?></a>
        <? if(session::has_credential("admin")){ // || $coordinator ?>
					<a href="/events/create" <?=$sub_menu == 'create' ? 'class="bold"' : ''?>>*<?=t('Добавить мероприятие')?></a>
        <? } ?>
        <a href="/events/search" <?=$sub_menu == 'search' ? 'class="bold"' : ''?>><?=t('Искать мероприятие')?></a>
        <a href="/events/arhive" <?=$sub_menu == 'arhive' ? 'class="bold"' : ''?>><?=t('Архив мероприятий')?></a>
</div>