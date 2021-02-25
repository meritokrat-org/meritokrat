<?php

return static function ($uid) {
    $fullName = user_helper::full_name($uid, false, [], false);
    $imageSrc = sprintf('https://image.meritokrat.org/%s', user_helper::photo_path($uid, 'm'));

    $i18n = [
        'Написать' => t('Написать'),
    ];

    return <<<HTML
<div class="card pointer shadow-sm" onclick="window.location = '/profile-{$uid}'">
    <div style="width: 100%; object-fit: cover">
        <!--<source srcset="{$imageSrc}" type="image/jpeg">-->
        <img src="{$imageSrc}" style="height: 180px" class="" alt="...">
    </div>
    <!--<img alt="{$fullName}" title="{$fullName}" class="card-img-top img-fluid" src="{$imageSrc}"/>-->
    <div class="card-body py-1">
        <h6 class="card-title" style="font-size: 75%">
            <a href="/profile-{$uid}">{$fullName}</a>
        </h6>
        </div>
        <div class="card-footer">
        <a class="btn btn-danger btn-sm"
           href="/messages/compose?user_id={$uid}"
           role="button"
           style="font-size: 12px; background-color: #600; border-color: #600"
        >{$i18n['Написать']}</a>
    </div>
</div>
HTML;
};