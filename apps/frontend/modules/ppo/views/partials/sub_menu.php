<div class="sub_menu mt5 mb15">
    <a href="/ppo" <?= ($sub_menu == '/ppo' && !request::get_string('req')) ? 'class="bold"' : '' ?>><?= t('Все') ?></a>
    <? if ($allow_create) { ?>
        <a href="/ppo/create" <?= $sub_menu == '/ppo/create' ? 'class="bold"' : '' ?>><b><?= t('Создать') ?> ПО</b></a>
    <? } ?>
    <?php
    $ppo = db::get_row(
        "SELECT id,number FROM ppo WHERE id in(SELECT group_id FROM ppo_members WHERE user_id = :user_id)",
        [
            'user_id' => session::get_user_id(),
        ]
    );
    ?>
    <a href="/ppo<?= $ppo['id'] ?>/<?= $ppo['number'] ?>"><?= t('Моя партийная организация') ?></a>
    <div class="right mr15">
        <form action="/ppo/search" method="GET">
            <input name="req" type="text" class="text" value="<?= request::get_string('req') ?>"/>
            <input name="submit" type="submit" class="btn btn-sm btn-primary" value="<?= t('Поиск') ?>"/>
        </form>
    </div>
</div>