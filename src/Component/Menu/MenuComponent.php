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

    /** @var callable */
    private $checkup;

    public function __construct($context)
    {
        if (array_key_exists('checkup', $context)) {
            $this->checkup = $context['checkup'];
            unset($context['checkup']);
        }

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
        if (null !== $this->checkup && !call_user_func($this->checkup)) {
            return null;
        }
        
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