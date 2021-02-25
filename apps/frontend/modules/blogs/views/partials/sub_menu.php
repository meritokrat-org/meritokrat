<div class="sub_menu mt5 mb10">
	<a href="/blogs?type=populars" <?=($sub_menu == '/blogs?type=populars' && !request::get_int('type')) ? 'class="bold"' : ''?>><?=t('Популярные публикации')?></a>
	<a href="/blogs" <?=($sub_menu == '/blogs' && !request::get_int('type')) ? 'class="bold"' : ''?>><?=t('Все публикации')?></a>
        <? if (user_auth_peer::instance()->get_rights(session::get_user_id(),20)) { ?><a href="/blogs?type=1" <?=($sub_menu == '/blogs' && request::get_int('type') == 1) ? 'class="bold"' : ''?>><?=t('Блоги')?></a><? } ?>
	<!--a href="/blogs/new" <?=$sub_menu == '/blogs/new' ? 'class="bold"' : ''?>><?=t('Свежие записи')?></a-->
	<a href="/bookmarks?type=1"><?=t('Закладки')?></a>
        <a href="/blogs/favorites" <?=$sub_menu == '/blogs/favorites' ? 'class="bold"' : ''?>><?=t('Любимые авторы')?></a>
	<!--a href="/blogs/discussed" <?=$sub_menu == '/blogs/discussed' ? 'class="bold"' : ''?>><?=t('Популярные')?></a-->
	<a href="/blogs/comments" <?=$sub_menu == '/blogs/comments' ? 'class="bold"' : ''?>><?=t('Комментарии')?></a>

	<? if ( session::is_authenticated()) { ?>
		<a href="/blog-<?=session::get_user_id()?>" <?=$sub_menu == '/blog-mine' ? 'class="bold"' : ''?>><?=t('Мои записи')?></a>

	<? if ( user_auth_peer::instance()->get_rights(session::get_user_id(),10) or session::has_credential('editor')) { ?>
                <a href="/blogs/edit" <?=$sub_menu == '/blogs/edit' ? 'class="bold"' : ''?>><span class="bold"><?=t('Добавить запись')?></span></a>
	<? }
           } ?>
</div>