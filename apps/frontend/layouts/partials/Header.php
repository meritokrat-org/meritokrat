<?php

namespace WebUI\Layout;

use App\Component\Component;


class Header implements Component
{
    public function __invoke()
    {
        return $this->render();
    }

    public function render()
    {


        return <<<HTML
<div class="container-fluid" style="height: 125px; background-color: #377cea">
    <div class="container">
        <div class="position-relative" style="display: grid; grid-auto-flow: column; grid-template-columns: 330px 1px repeat(2, 1fr)">
            <div>
                <img src="/static/images/common/header/header_1.svg" alt="" style="height: 2.5rem"/>
            </div>
            <div class="bg-white"></div>
            <div class="text-end">
                <img src="/static/images/common/header/header_2.svg" alt="" style="height: 2.5rem"/>
            </div>
            <div>
                <img src="/static/images/common/header/header_3.svg" alt="" style="height: 2.5rem"/>
            </div>
        </div>
        <div class="menu">{$this->getMenu()}</div>
    <div class="sub_menu">{$this->getMenu(1)}</div>
    </div>
</div>
HTML;
    }


    private function getMenu($type = null)
    {

        ob_start();
        include __DIR__.'/'.(null !== $type ? 'submenu.php' : 'menu.php');

        return ob_get_clean();
    }
}

return new Header();