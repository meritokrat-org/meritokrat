<?php

namespace App\Component\Menu;

use App\Component\ComponentInterface;
use App\Traits\CreatableTrait;
use Doctrine\Common\Collections\ArrayCollection;

class MenuComponent implements ComponentInterface
{
    use CreatableTrait;

    /** @var ArrayCollection */
    private $context;

    public function __construct($context)
    {
        $this->setContext($context);
    }

    private function setContext($context)
    {
        $this->context = new ArrayCollection(
            array_map(
                static function ($context) {
                    return MenuItemComponent::create($context);
                },
                $context
            )
        );

        return $this;
    }

    public function render()
    {
        return <<<HTML
<ul class="nav justify-content-between w-auto">
    {$this->renderContext()}
</ul>
HTML;
    }

    private function renderContext()
    {
        return implode(
            PHP_EOL,
            array_map(
                static function ($item) {
                    /** @var MenuItemComponent $item */
                    return $item->render();
                },
                $this->context->toArray()
            )
        );
    }
}