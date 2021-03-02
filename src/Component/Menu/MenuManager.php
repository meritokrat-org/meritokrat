<?php

namespace App\Component\Menu;

use App\Traits\CreatableTrait;
use Doctrine\Common\Collections\ArrayCollection;

class MenuManager
{
    use CreatableTrait;

    private $menu;

    public function __construct()
    {
        $this->menu = new ArrayCollection();
    }

    public static function create()
    {
        return new static();
    }

    public function set($key, $value)
    {
        $this->menu->set($key, MenuComponent::create($value));

        return $this;
    }

    /**
     * @param $key
     * @return MenuComponent
     */
    public function get($key)
    {
        return $this->menu->get($key);
    }
}