<?php
declare(strict_types=1);

namespace CommunityHub\Components\Factory;

use Throwable;

/**
 * @throws Exception
 *     if the make method name is invalid.
 */
function makeFactory(string $method = 'make'): Factory
{
    require_once __DIR__ . '/classes/autoload/Factory.php';

    return new Factory($method);
}

/**
 * @throws Exception
 *     if there was an error calling the factory method.
 * @throws Throwable
 *     from inside the factory method up the stack.
 */
function makeSingleton(string $classPath, string $method = 'make'): mixed
{
    static $instances = [];

    if (isset($instances[$method])) {
        $instances[$method] = makeFactory($method);
    }

    return $instances[$method]($classPath);
}
