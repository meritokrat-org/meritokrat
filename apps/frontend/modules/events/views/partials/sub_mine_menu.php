<div class="sub_menu mt5 mb10">
         <a href="/events" class="right mr5" style="padding-right:5px;"><?=t('Все')?></a>
         <a href="/events/mine" <?=$sub_menu == 'mine' ? 'class="bold"' : ''?>><?=t('Будущие')?></a>
         <a href="/invites?type=1"><?=t('Приглашения')?></a>
         <a href="/events/mine_past" <?=$sub_menu == 'mine_past' ? 'class="bold"' : ''?>><?=t('Прошедшие')?></a>
</div>