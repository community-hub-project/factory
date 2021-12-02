<?php
declare(strict_types=1);

namespace TestClasses;

class Singleton
{
    private int $num;

    public static function make(): static
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
