<?php

namespace App\Traits;

use ReflectionClass;

trait CreatableTrait
{
    public static function create()
    {
        return (new ReflectionClass(static::class))
            ->newInstanceArgs(func_get_args());
    }
}