<?php

namespace WebUI\Layout;

use App\Component\ComponentInterface;
use App\Traits\CreatableTrait;

class Footer implements ComponentInterface
{
    use CreatableTrait;

    public function __construct()
    {
    }


    public function render()
    {
        return <<<HTML
<footer class="container-fluid bg-dark px-0 pt-3 pb-5" style="min-height: 5rem">
    <div class="container-fluid px-0">
        <div class="container p-0">
            <div class="row">
                <div class="col-1 offset-11">
                    <script src="https://apps.elfsight.com/p/platform.js" defer></script>
                    <div class="elfsight-app-6ea67601-bec1-4755-95ca-dac7c266c4b9"></div>
                </div>
            </div>
        </div>
    </div>
</footer>
HTML;
    }
}

return static function () {
    return Footer::create()->render();
};