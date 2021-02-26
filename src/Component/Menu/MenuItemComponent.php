<?php

namespace App\Component\Menu;

use App\Component\ComponentInterface;
use App\Component\CreatableTrait;

class MenuItemComponent implements ComponentInterface
{
    use CreatableTrait;

    private $href;
    private $text;
    private $icon;

    public function __construct($context)
    {
        $this->href = $context['href'];
        $this->text = t($context['text']);
        if (array_key_exists('icon', $context)) {
            $this->icon = $context['icon'];
        }
    }

    public function render()
    {
        return <<<HTML
<li class="nav-item">
    <a class="nav-link" href="{$this->href}">{$this->icon}{$this->text}</a>
</li>
HTML;
    }
}