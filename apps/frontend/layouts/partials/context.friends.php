<? if ( !session::is_authenticated() ) return; ?>

<? $friends = friends_peer::instance()->get_by_user(session::get_user_id())?>

<!--div class="ml10 friends-box">
	<h2><a href="/friends"><?=t('Мои друзья')?></a></h2>
	<? if ( $friends ) { $friends = array_slice($friends, 0, 10); foreach ( $friends as $friend_id ) { ?>
		<?=user_helper::photo($friend_id, 's')?>
	<? } } else { ?>
		<p class="acenter quiet fs11"><?=t('У Вас еще нет друзей')?>. <a href="/search"><?=t('Найти друзей')?></a></p>
	<? } ?>
</div-->