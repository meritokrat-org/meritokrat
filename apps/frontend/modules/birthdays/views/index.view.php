<div class="left" style="width: 760px;margin-top:15px;">

    <h1 class="column_head"><?=t('Ближайшие дни рождения')?></h1>

    <div>
        <? include 'partials/birthday.php'; ?>
    </div>

    <div class="right pager"><?=($pager)?pager_helper::get_short($pager):''?></div>

</div>