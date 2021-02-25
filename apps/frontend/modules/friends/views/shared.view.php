<? if(request::get_int('pending')){ ?>
    <h1 class="column_head mt10 mr10">
        <? if(session::get_user_id()==$user_id){ ?>
            <?=t('Мои друзья')?> &rarr; <?=t('Запросы на дружбу')?>
        <? }else{ ?>
            <?=user_helper::full_name($id)?> &rarr; <?=t('Друзья')?>  
        <? } ?>
    </h1>
	<? foreach ( $pending_friends as $id ) include 'partials/friend_pending.php'; ?>
<? }else{ ?>
<h1 class="column_head mt10 mr10">
    <? if(session::get_user_id()==$user_id){ ?>
        <a href="/friends"><?=t('Мои друзья')?></a>
        &nbsp;
        <?=$online?>
        <div class="right">
            <a href="/friends/shared?user=<?=$user_id?>&online=1" style="text-transform:none;">Онлайн</a>
            <?=$total?>
            <? if(count($pending_friends)>0){ ?>
            &nbsp;
            <a href="/friends?pending=1" style="text-transform:none;">
            <?=t('Запросы на дружбу')?>
            </a>
            <?=count($pending_friends)?>
            <? } ?>
        </div>
    <? }else{ ?>
        <?=user_helper::full_name($user_id)?> &rarr; <a href="/friends/shared?user=<?=$user_id?>"><?=t('Общие друзья')?></a>
        &nbsp;
        <?=$total?>
            <div class="right">
                <a href="/friends/shared?user=<?=$user_id?>&online=1" style="text-transform:none;">Онлайн</a>
                <?=$online?>
            </div>
    <? } ?>
</h1>
	<div class="left"><? foreach ( $friends as $id ) include 'partials/friend.php'; ?></div>
    <div class="right pager"><?=pager_helper::get_short($pager)?></div>
<? } ?>