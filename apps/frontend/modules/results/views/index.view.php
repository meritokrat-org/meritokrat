<?php
/**
 *
 */
?>

<div class="display-6">
    <span>Результати</span>
</div>

<div class="row m-0 mt-3" style="font-size: 125% ; ">
    <div class="col p-0">
        <div class="card">
            <h5 class="card-header d-flex justify-content-between align-items-center">
                Кількість учасників загальнонаціональної команди
                <span class="badge bg-danger rounded-pill" style="font-size: 11pt; background-color: black !important;">
                    <a href="/people" class="text-white"><?= db::get_scalar(
                                'select count(*) from user_auth where status not in (-1, 1, 3)'
                        ) ?></a>
                </span>
            </h5>
            <div class="card-body p-1">
                <ul class="list-group list-group-flush m-0">
                    <?php $statuses = user_auth_peer::get_statuses(); ?>
                    <?php foreach (array_reverse(array_keys($statuses)) as $k) { ?>
                        <?php $v = $statuses[$k] ?>
                        <?php if (in_array($k, [-1, 3], true)) {
                            continue;
                        } ?>
                        <?php if (1 === $k && session::has_credential('admin')) {
                            $v = sprintf('* %s', $v);
                        } ?>
                        <li class="list-group-item d-flex align-items-center">
                            <div class="flex-grow-1 ps-3"><?= $v ?></div>
                            <span class="badge bg-danger rounded-pill"
                                  style="font-size: 11pt; background-color: black !important;">
                        <?= db::get_scalar('select count(*) from user_auth where status = :status', ['status' => $k]) ?>
                    </span>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="row m-0 mt-3" style="font-size: 125% ; ">
    <div class="col p-0">
        <div class="card">
            <h5 class="card-header d-flex justify-content-between align-items-center">
                Кількість партійних організацій
                <span class="badge bg-danger rounded-pill" style="font-size: 11pt;background-color: black !important;">
                <a href="/ppo/index?status=2" class="text-white"><?= db::get_scalar(
                            'select count(*) from ppo where active = 1'
                    ) ?></a>
                </span>
            </h5>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush m-0">
                    <?php foreach (ppo_peer::get_levels(false) as $k => $v) { ?>
                        <li class="list-group-item d-flex align-items-center">
                            <div class="flex-grow-1 ps-3"><?= $v ?></div>
                            <span class="badge bg-danger rounded-pill"
                                  style="font-size: 11pt;background-color: black !important;">
                        <?= db::get_scalar('select count(*) from ppo where category = :category', ['category' => $k]) ?>
                    </span>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

    </div>

    <!--<div style="font-size: 14pt">-->
    <!--    <div class="row">-->
    <!--        <div class="col-3">Кол-во учасников</div>-->
    <!--        <div class="col">-->
    <!--            <a href="/people">-->
    <? //= db::get_scalar('select count(*) from user_auth where status not in (-1, 1, 3)') ?><!--</a>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--    <div class="row">-->
    <!--        <div class="col-3">Кол-во организаций</div>-->
    <!--        <div class="col">-->
    <!--            <a href="/ppo/index?status=2">-->
    <? //= db::get_scalar('select count(*) from ppo where active = 1') ?><!--</a>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->

</div>

