<?php
declare(strict_types=1);

namespace TestClasses;

class SingletonWithNonStaticMethod
{
    private int $num;

    public function make(): static
    {
        return new self(123);
    }

    public function __construct(int $num)
    {
        $this->num = $num;
    }

    public function getNum(): int
    {
        return $this->num;
    }
}
