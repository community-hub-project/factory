<?php
declare(strict_types=1);

namespace TestClasses;

class SingletonThatDoesNotReturnAnInstance
{
    public static function make(): void
    {
    }
}
