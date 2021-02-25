<div class="sub_menu mt5 mb10">
	<a href="/polls" <?=$sub_menu == '/polls/new' ? 'class="bold"' : ''?>><?=t('Новые')?></a>
	<a href="/polls/hot" <?=$sub_menu == '/polls' ? 'class="bold"' : ''?>><?=t('Популярные')?></a>
	<? if ( session::is_authenticated() ) { ?>
		<a href="/polls/mine" <?=$sub_menu == '/polls/mine' ? 'class="bold"' : ''?>><?=t('Мои')?></a>
	<? } ?>
        <? if (user_auth_peer::get_rights(session::get_user_id(),20)) { ?>
	<a href="/polls/create" <?=$sub_menu == '/polls/create' ? 'class="bold"' : ''?>><?=t('Создать вопрос')?></a>
        <? } ?>
</div>