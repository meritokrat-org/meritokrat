<td colspan="3" class="m-0 p-0">
    <div class="shadow bg-body rounded mb-3 p-3" style="column-count: 3">
        <?= implode(
                PHP_EOL,
                array_map(
                        static function ($item) {
                            return sprintf(
                                    '<div><a href="/profile-%d">%s</a></div>',
                                    $item['id'],
                                    user_helper::full_name($item['id'])
                            );
                        },
                        $list
                )
        ) ?>
    </div>
</td>
