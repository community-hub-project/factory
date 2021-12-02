<?php
declare(strict_types=1);

namespace TestClasses;

use CommunityHub\Components\Factory\Factory;

class SingletonInsideSingleton
{
    private Singleton $singleton;

    public static function make(Factory $factory): mixed
    {
        $singleton = $factory(Singleton::class);

        return new static($singleton);
    }

    public function __construct(Singleton $singleton)
    {
        $this->singleton = $singleton;
    }

    public function getSingleton(): Singleton
    {
        return $this->singleton;
    }
}
