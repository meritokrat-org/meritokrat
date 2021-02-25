<? $sub_menu = request::get_int('status') ?>
<? $action = context::get_controller()->get_action() ?>
<div class="sub_menu mt5 mb10"> 
        <a href="/eventreport" <?=(!$sub_menu && $action!='search' && $action!='statistics' && !request::get_int('id') ) ? 'class="bold"' : ''?>><?=t('Новые отчеты')?></a>
	<a href="/eventreport&status=1" <?=$sub_menu == 1 ? 'class="bold"' : ''?>><?=t('Отчеты на утверждение')?></a>
        <a href="/eventreport&status=2" <?=$sub_menu == 2 ? 'class="bold"' : ''?>><?=t('Отчеты на доработке')?></a>
        <a href="/eventreport&status=3" <?=$sub_menu == 3 ? 'class="bold"' : ''?>><?=t('Утвержденные')?></a>
        <a href="/eventreport&status=4" <?=$sub_menu == 4 ? 'class="bold"' : ''?>><?=t('Не состоявшиеся')?></a>
        <? if(session::has_credential('admin')){ ?>
        <a href="/eventreport/search" <?=($action=='search') ? 'class="bold"' : ''?>>*<?=t('Поиск')?></a>
        <a href="/eventreport/statistics" <?=($action=='statistics') ? 'class="bold"' : ''?>>*<?=t('Статистика')?></a>
        <? } ?>
</div>