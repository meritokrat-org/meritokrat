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


    private $checkup;

    public function __construct($context)
    {
        if (array_key_exists('checkup', $context)) {
            $this->checkup = $context['checkup'];
            unset($context['checkup']);
        }

        $this->context = Context::create($context);
    }

    public function render()
    {
        if (null !== $this->checkup && !call_user_func($this->checkup)) {
            return null;
        }

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