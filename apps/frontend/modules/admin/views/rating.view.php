<div class="container-fluid">
    <div class="row">
        <div class="col-4">
            <div class="column_head pointer" onclick="Application.ShowHide('functions')">
                <div class="left"><?= t('Критерии') ?></div>
                <div class="right mt5 icoupicon <?= request::get_int('function') <= 0 ? '' : 'hide' ?>"
                     style="cursor: pointer;"
                     id="functions_on"></div>
                <div class="right mt5 icodownt <?= !request::get_int('function') ? '' : 'hide' ?>"
                     style="cursor: pointer;"
                     id="functions_off"></div>
            </div>
            <div class="box_content <?= request::get_int('function') <= 0 ? '' : 'hide' ?>" id="functions">
                <ul class="list-group m-0 p-2">
                    <?php $userStatuses = array_filter(
                            user_auth_peer::get_statuses(),
                            static function ($key) {
                                return user_auth_peer::SUPPORTER <= $key;
                            },
                            ARRAY_FILTER_USE_KEY
                    ) ?>
                    <?= implode(
                            PHP_EOL,
                            array_map(
                                    static function ($key, $value) use ($status) {
                                        $classes = [
                                                'li'     => [
                                                        'list-group-item',
                                                        'd-flex',
                                                        'justify-content-between',
                                                        'align-items-center',
                                                ],
                                                'li > a' => [
                                                        'btn-link',
                                                        'text-danger',
                                                ],
                                        ];

                                        if ($key === $status) {
                                            $classes['li']       = array_merge(
                                                    $classes['li'],
                                                    [
                                                            'list-group-item-danger',
                                                    ]
                                            );
                                            $classes['li > a'][] = 'text-danger';
                                        }

                                        array_walk(
                                                $classes,
                                                static function (&$value) {
                                                    $value = implode(' ', $value);
                                                }
                                        );

                                        $count = db::get_scalar(
                                                'select count(*) from user_auth where status = :status and invited_by > 0',
                                                [
                                                        'status'     => $key
                                                ]
                                        );

                                        return <<<HTML
<li class="{$classes['li']}">
    <a class="{$classes['li > a']}" href="/admin/rating?status={$key}">{$value}</a>
    <span class="badge bg-danger rounded-pill">{$count}</span>
</li>
HTML;
                                    },
                                    array_reverse(array_keys($userStatuses)),
                                    array_reverse(array_values($userStatuses))
                            )
                    ) ?>
                </ul>
                <hr class="m-0" style="border-style: none; border-top: 1px solid #ccc"/>
                <ul class="list-group m-0 p-2">
                    <?php foreach ($alias2names as $alias => $name) { ?>
                        <?php if (!in_array($alias, ['full_rating', 'regional_ratio', 'added_points'])) { ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a class="btn-link text-danger" href="/admin/rating&type=criteria_adv&direct=DESC&field=<?= $alias ?>"><?= $name ?></a>
                                <span class="badge bg-danger rounded-pill"><?= db::get_scalar(
                                            'select count(*) from user_auth where invited_by > 0'
                                    ) ?></span>
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="col">
            <h1 class="column_head mb-1"><?= t('Рейтинг привлечения в комманду') ?></h1>

            <?php
            switch ($type) {
                case 'criteria':
                    include 'partials/rating/criteria.php';
                    break;

                case 'criteria_adv':
                    include 'partials/rating/criteria_adv.php';
                    break;

                case 'people':
                default :
                    include 'partials/rating/people.php';
                    break;
            }
            ?>
        </div>
    </div>
</div>
