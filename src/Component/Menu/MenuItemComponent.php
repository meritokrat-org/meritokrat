<?php

namespace App\Component\Menu;

use App\Component\ComponentInterface;
use App\Component\Context;
use App\Traits\CreatableInterface;
use App\Traits\CreatableTrait;

class MenuItemComponent implements ComponentInterface, CreatableInterface
{
    use CreatableTrait;

    /** @var Context */
    private $context;

    public function __construct($context)
    {
        $this->context = Context::create($context);
    }

    public function render()
    {
        return <<<HTML
<li class="nav-item">
    <a class="nav-link" href="{$this->context->get('href')}">{$this->context->get('icon')}{$this->getText()}</a>
</li>
HTML;
    }

    private function getText()
    {
        $text = $this->context->get('text');

        return (null !== $text ? sprintf('<span>%s</span>', t($text)) : '');
    }
}