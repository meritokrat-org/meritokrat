<? $sub_menu = '/groups'; ?>
<? include 'partials/sub_menu.php' ?>

<div class="left" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10" style="width: 62%;">
	<h1 class="column_head">
		<? if ( $cur_type ) { ?>
			<a href="/groups"><?=t('Сообщества')?></a> &rarr;
			<?=groups_peer::get_type($cur_type)?>
		<? } elseif ( $cur_level ) { ?>
			<a href="/groups"><?=t('Сообщества')?></a> &rarr;
			<?=groups_peer::get_level($cur_level)." ".t('уровень')?>
                <? } elseif ( $cur_category ) { ?>
			<a href="/groups"><?=t('Сообщества')?></a> &rarr;
			<?=groups_peer::get_category($cur_category)?>
                <? } elseif ( request::get_string('req') ) { ?>
			<?=t('Поиск')?>
		<? } else { ?>
			<?=request::get_int('user_id')>0 ? user_helper::full_name(request::get_int('user_id'),false).' &mdash; ' : ''?> <?=t('Все сообщества')?>
		<? } ?>
	</h1>

        <? if(request::get_string('req')){ ?>
            <div class="mt5 fs11 mb10"><a href="/groups" class="cgray">&larr;  <?=t('Вернуться к общему списку') ?></a></div>
        <? } ?>

	<? if ($hot) {
        foreach ( $hot as $id ) { include 'partials/group.php'; }
        }
        else {
            ?>
    <div class="mb10 p10 box_content">
	<div class="left">
            <?=t('Cообществ пока нет. Вы можете')?> <a href=""><?=t('предложить свое сообщество')?></a>
	</div>
	<div class="clear"></div>
</div>
        <? } 
        ?>
	<div class="bottom_line_d mb10"></div>
	<div class="right pager"><?=pager_helper::get_full($pager)?></div>
</div>