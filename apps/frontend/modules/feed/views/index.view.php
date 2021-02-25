<h1 class="column_head mt10 mr10">
	<span class="left"><?=t('Лента активности')?></span>
	<? if ( $list ) { ?>
		<a class="right fs11 maroon" onclick="return confirm('<?=t('Очистить все обновления?')?>')" href="/feed/clear"><?=t('Очистить')?></a>
	<? } ?>
	<div class="clear"></div>
</h1>

<? if ( !$list ) { ?>
	<div class="acenter screen_message fs11 quiet"><?=t('Обновлений еще нет')?></div>
<? } else { ?>
	<? foreach ( $list as $id ) { include 'partials/item.php'; } ?>
	<div class="bottom_line_d mb10 mr10"></div>
	<div class="right mr10 pager"><?=pager_helper::get_full($pager)?></div>
<? } ?>
<br/><br /> 