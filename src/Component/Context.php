<?php

namespace App\Component;

use App\Traits\CreatableInterface;
use App\Traits\CreatableTrait;
use Doctrine\Common\Collections\ArrayCollection;

class Context implements CreatableInterface
{
    use CreatableTrait;

    /** @var ArrayCollection */
    private $context;

    public function __construct($context = null)
    {
        if (null === $context) {
            $context = [];
        }

        $this->context = new ArrayCollection($context);
    }

    public function get($key)
    {
        if (!$this->context->containsKey($key)) {
            return null;
        }

        return $this->context->get($key);
    }

    public function set($key, $value)
    {
        $this->context->set($key, $value);

        return $this;
    }
}