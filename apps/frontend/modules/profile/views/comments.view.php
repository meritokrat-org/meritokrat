<div class="left ml10 mt10" style="width: 97%;">

        <? if($user_id==session::get_user_id()){ ?>
            <h1 class="column_head mb10"> <?=t('Мои комментарии')?></h1>
        <? }else{ ?>
            <h1 class="column_head mb10"><?=user_helper::full_name($user_id)?> &rarr; <?=t('Комментарии')?></h1>
        <? } ?>

	<? if ( !$list ) { ?>
		<div class="screen_message quiet acenter"><?=t('Тут еще нет записей')?></div>
	<? }else{ ?>
            <? foreach ( $list as $id ) { ?>
                    <? include 'partials/comment.php'; ?>
            <? } ?>
        <? } ?>

        <? if($pager){ ?>
	<div class="right pager"><?=pager_helper::get_full($pager)?></div>
        <? } ?>

</div>