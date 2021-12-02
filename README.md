# Factory

A factory for creating singletons based on static methods.

## Usage

The most basic usage of a factory is this:

    <?php

    class Some
    {
        public static function make(): static
        {
            return new static;
        }
    }

    $factory = new \CommunityHub\Component\Factory\Factory;

    $someClass = $factory(Some::class);

When invoked, a factory will attempt to create an instance of the specified
class path and return it. It does this by calling the static method `make` on
any requested class and returning the result.

It will keep any instances it has created and return them if they are requested
again instead of recreating them.

### Helper functions

There are also 2 helper functions available:

    <?php

    // to get a new factory instance:
    $factory = \CommunityHub\Component\Factory\makeFactory();

    // to get a singleton instance:

    class Some
    {
        public static function make(): static
        {
            return new static;
        }
    }

    $someClass = \CommunityHub\Component\Factory\makeSingleton(Some::class);

The `makeSingleton` helper function will use an existing instance of the factory
if it has one, otherwise it will create one. It will use that instance to
generate and/or return the singleton.

### Creating a factory by static method.

If preferred, the factory can be created by a static method:

    <?php

    $factory = \CommunityHub\Component\Factory\Factory::make();

### Creating an instance with dependencies

If an instance has dependencies, then those dependencies can also be initialized
and injected. When a `make` method is called, The calling instance of
`\CommunityHub\Factory` is passed into the method. Meaning it can be used to
create and return other object instances:

    <?php

    class ClassA
    {
        public static function make(): self
        {
            return new self;
        }
    }

    class ClassB
    {
        private ClassA $classA;

        public static function make(\CommunityHub\Component\Factory\Factory $factory): self
        {
            return new static(
                $factory(ClassA::class),
            );
        }

        public function __construct(ClassA $classA)
        {
            $this->classA = $classA;
        }
    }

    $factory = new \CommunityHub\Component\Factory\Factory;

    $someClass = $factory(ClassB::class);

### Changing the method or constant name

By default, a factory will look for static methods named `make`. If a different
static method name is preferred, then it can be passed into the constructor:

    <?php

    class Some
    {
        public static function create(): static
        {
            return new static;
        }
    }

    $factory = new \CommunityHub\Component\Factory\Factory('create');

    $someClass = $factory(Some::class);

The static method name can also be passed into the factory `make` method.

    <?php

    class Some
    {
        public static function create(): static
        {
            return new static;
        }
    }

    $factory = \CommunityHub\Component\Factory\Factory::make('create');

    $someClass = $factory(Some::class);

Finally, it can also be passed into the `makeFactory` helper method:

    <?php

    class Some
    {
        public static function create(): static
        {
            return new static;
        }
    }

    $factory = \CommunityHub\Component\Factory\makeFactory('create');

    $someClass = $factory(Some::class);
