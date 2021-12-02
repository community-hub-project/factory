<?php
declare(strict_types=1);

namespace TestClasses;

use InvalidArgumentException;

class SingletonWithErroneousMakeMethod
{
    public static function make(): static
    {
        throw new InvalidArgumentException('Some error.');
    }
}
