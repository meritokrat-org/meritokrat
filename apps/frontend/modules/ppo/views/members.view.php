<div class="row align-middle m-0 px-2 py-1 rounded" style="background-color: #600; font-size: 11px">
    <?php $titleParts = [
        t('Партийная организация'),
        sprintf('<a href="/ppo%d/%d">%s</a>', $group['id'], $group['number'], htmlspecialchars($group['title'])),
    ] ?>
    <div class="col m-0 p-0 white fw-bold text-uppercase"><?= implode(' ', $titleParts) ?> - <?=user_auth_peer::getStatus($status)?></div>
</div>

<?php
$styles = [
    'display'               => 'grid',
    'grid-gap'              => '.75rem',
    'grid-template-columns' => 'repeat(auto-fit, minmax(130px, 1fr))',
] ?>
<div class="mt-2" style="<?= implode(
    '; ',
    array_map(
        static function ($key, $value) {
            return sprintf('%s: %s', $key, $value);
        },
        array_keys($styles),
        array_values($styles)
    )
) ?>">
    <?php $person = require __DIR__.'/members/person.php' ?>
    <?php foreach ($list as $id) { ?>
        <?= $person($id) ?>
    <?php } ?>
</div>

<div>
    <?php if (!$list) { ?>
        <div class="acenter fs11 quiet m10 p10"><?= t('Тут еще никого нет') ?>...</div>
    <?php } else { ?>
        <div class="bottom_line_d my-1"></div>
        <div class="right pager"><?= pager_helper::get_full($pager) ?></div>
    <?php } ?>
</div>