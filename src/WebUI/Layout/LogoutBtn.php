<?php

namespace App\WebUI\Layout;

use App\Component\ComponentInterface;
use App\Traits\CreatableTrait;

class LogoutBtn implements ComponentInterface
{
    use CreatableTrait;

    public function render()
    {
        $t = static function ($phrase, $vars = [], $lang = null) {
            return t($phrase, $vars, $lang);
        };

        return <<<HTML
<a class="nav-link" href="/sign/out">
    {$t('Выход')}
    <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
</a>
HTML;
    }
}