<div class="sub_menu mt5 mb10">
	<a href="/debates" <?=$sub_menu == '/debates' ? 'class="bold"' : ''?>><?=t('Новые')?></a>
	<a href="/debates/hot" <?=$sub_menu == '/debates/hot' ? 'class="bold"' : ''?>><?=t('Горячие')?></a>
	<? if ( session::is_authenticated() ) { ?>
		<a href="/debates/mine" <?=$sub_menu == '/debates/mine' ? 'class="bold"' : ''?>><?=t('Мои')?></a>
	<? } ?>
	<a href="/debates/create" <?=$sub_menu == '/debates/create' ? 'class="bold"' : ''?>><?=t('Начать дебаты')?></a>
</div>