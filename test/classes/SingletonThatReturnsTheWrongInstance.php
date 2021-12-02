<?php
declare(strict_types=1);

namespace TestClasses;

class SingletonThatReturnsTheWrongInstance
{
    public static function make(): Singleton
    {
        return Singleton::make();
    }
}
