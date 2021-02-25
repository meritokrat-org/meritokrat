<?php

/**
 * @var array $list
 */

call_user_func(
    static function ($list) {
        echo sprintf('<h1 class="mt10 mr10 column_head">%s</h1>', t('Премодерация фотографий'));

        foreach ($list as $id) {
            include 'partials/person_photo.php';
        }

        echo <<<HTML
<script type="text/javascript">
    $(document).ready(function ($) {
        $('.phtapr').click(function () {
            $.post('/profile/approve_photo', {'id': $(this).attr('rel')});
            $('#ph' + $(this).attr('rel')).hide(500);
        });
        $('.phtapri').click(function () {
            $.post('/profile/approve_photo', {'id': $(this).attr('rel'), 'attention': 1});
            $('#ph' + $(this).attr('rel')).hide(500);
        });
        $('.phdel').click(function () {
            $.post('/profile/unapprove_photo', {'id': $(this).attr('rel')});
            $('#ph' + $(this).attr('rel')).hide(500);
        });
    });
</script>
HTML;
    },
    $list
);